<?php

namespace App\Http\Controllers;

use App\Http\Requests\imagesRequest;
use App\Models\Images;
use App\Models\ImagesCollection;
use App\Services\FilesHandlerController;
use App\Services\ImageServicesController;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImagesController extends Controller
{
    private object $imagesModel;

    private string $oldName;

    private object $filesHandler;

    private string $oldPath;

    private $collectionData;

    private ImageServicesController $imageServices;

    public function __construct()
    {
        $this->imagesModel = new Images();
        $this->filesHandler = new FilesHandlerController();
        $this->imageServices = new ImageServicesController();
    }

    public function index()
    {
        try {
            $images = $this->imagesModel::with('collection')->where('owner_id', auth()->user()->id)->paginate(6);
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return view('users.images.images', compact('images'));
    }

    public function getImage(int $id, string $token): View
    {
        $image = $this->imagesModel->with('collection')->where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();

        return view('users.images.get-image', compact('image'));

    }

    public function newImageIndex(): View
    {
        return view('users.images.new-image');
    }

    public function createImage(imagesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->extracted($data);
        $img = $this->imageServices->createImage($data['img'], $this->collectionData['path'], $this->collectionData['name']);
        if (! $img) {
            return redirect()->back()->withErrors(['error' => 'An Error Occurred While Uploading Images To The Database']);
        }

        return redirect()->back()
            ->with(['message' => 'Image Has Been Uploaded Successfully', 'infos' => route('images.get', [$img->id, $img->token])]);
    }

    public function patchImage(Request $request, int $id, string $token): RedirectResponse
    {
        try {
            $image = $this->imagesModel::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
            $this->oldName = $image->name;
            $this->oldPath = $image->path;
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        $data = $request->validate(['name' => 'required|string|max:255', 'ispublic' => 'required|in:0,1']);
        try {
            $image->update($data);
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with(['message' => 'Image Has Been Updated Successfully']);
    }

    public function patchPublicity(Request $request, int $id, string $token): RedirectResponse
    {
        $image = $this->imagesModel->where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
        $request->validate(['ispublic' => 'required|in:0,1']);

        try {
            $image->updateOrFail(['ispublic' => $request->ispublic]);
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with(['message' => 'Image Has Been Updated Successfully']);
    }

    public function deleteImage(int $id, string $collectionID, string $token): RedirectResponse
    {
        try {
            $image = $this->imagesModel->where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
            if (isset($image->collection_id) && $collectionID != $image->collection_id) {
                return redirect()->back()->withErrors(['error' => 'You Cant Manipulate The System']);
            }
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        if ($collectionID != 'null' && isset($image->collection_id)) {
            try {
                $collection = ImagesCollection::where('id', $collectionID)->firstOrFail();
            } catch (Exception|\Throwable $e) {
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
            if ($collection->count() > 0) {
                return redirect()->route('collections.show', [$collectionID, $collection->token])
                    ->with(['message' => 'Image Has Been deleted Successfully']);
            }
        } elseif ($collectionID == 'null' && isset($image->collection_id)) {
            return redirect()->back()->withErrors(['error' => 'You Cant Manipulate The System']);
        } else {
            return redirect()->back()->withErrors(['error' => 'You Cant Manipulate The System']);
        }

        return redirect()->back()
            ->with(['message' => 'Image Has Been deleted Successfully']);

    }

    public function extracted(mixed $data): void
    {
        $this->collectionData['owner_id'] = auth()->user()->id;
        $this->collectionData['name'] = $data['name'];
        $this->collectionData['ispublic'] = $data['ispublic'];
        $this->collectionData['path'] = 'users/images/user-'.auth()->user()->id.'-'.auth()->user()->username;
    }
}
