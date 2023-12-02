<?php

namespace App\Http\Controllers;

use App\Http\Requests\newCollectionRequest;
use App\Models\ImagesCollection;
use App\Services\FilesHandlerController;
use App\Services\ImageServicesController;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImagesCollectionController extends Controller
{
    /* cid stands for collection id wich is the database row id*/
    private $cid;

    /* iid stands for image id wich is the database row id*/
    private $iid;

    /*uid stand for the user id*/
    private $uid;

    private $imagesCollectionModel;

    private array $collectionData;

    private $ImageServices;

    private $filesHandler;

    public function __construct()
    {
        $this->imagesCollectionModel = new ImagesCollection();
        $this->ImageServices = new ImageServicesController();
        $this->filesHandler = new FilesHandlerController();
    }

    public function index(): View
    {
        $allCollections = $this->imagesCollectionModel::with('images')->paginate(7);

        return view('users.collections.show-collections', compact('allCollections'));
    }

    public function showCollection(int $id, string $token): View
    {
        $this->imagesCollectionModel::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
        $collection = $this->imagesCollectionModel->with('images')->where('id', $id)->get()->first();
        $images = $collection->images()->paginate(6);

        return view('users.collections.show-collection', compact('collection', 'images'));
    }

    public function newCollectionIndex(): View
    {
        return view('users.collections.manage-collections');
    }

    public function createCollection(newCollectionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->extracted($data);
        try {
            $this->cid = $this->imagesCollectionModel->create($this->collectionData);
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        $imageUploaded = $this->ImageServices->storeImages($request->file('imgs'), $this->collectionData['path'], $data['collection_name'], $this->cid->id);

        if (! $imageUploaded) {
            $this->rollBack($this->cid->id);

            return redirect()->back()->withErrors(['error' => 'error occurred while uploading images']);
        }

        return redirect()->back()
            ->with(
                ['message' => 'Collection Has Been Created Successfully',
                    'infos' => route('collections.show', [$this->cid['id'], $this->cid['token']])]);
    }

    public function deleteCollection(int $id, string $token): RedirectResponse
    {
        try {
            $collection = ImagesCollection::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
            $collection->delete();
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with(['message' => 'Collection Has Been Deleted Successfully']);
    }

    public function patchCollection(Request $request, int $id, string $token): RedirectResponse
    {
        try {
            $collection = ImagesCollection::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        $data = $request->validate([
            'collection_name' => 'string|required',
            'ispublic' => 'in:1,0|required',
            'imgs' => ['bail', 'required'],
            'imgs.*' => ['bail', 'image', 'mimes:png,jpg,jpeg', 'max:10240'],
        ]);
        $this->extracted($data);

        if ($collection->collection_name != $this->collectionData['collection_name']) {
            $this->filesHandler->renameFolder($collection->path, $this->collectionData['path']);
        }
        try {
            $collection->updateOrFail($this->collectionData);
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        $imageUploaded = $this->ImageServices->storeImages($request->file('imgs'), $this->collectionData['path'], $data['collection_name'], auth()->user()->id, $collection->id);

        if (! $imageUploaded) {
            return redirect()->back()->withErrors(['error' => 'error occurred while uploading images']);
        }

        return redirect()->back()
            ->with(['message' => 'Collection Has Been Updated Successfully']);
    }

    public function patchPublicity(Request $request, int $id, string $token): RedirectResponse
    {
        $collection = $this->imagesCollectionModel->where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
        $request->only('ispublic');
        $request->validate(['ispublic' => 'required|in:0,1']);
        try {
            $collection->updateOrFail(['ispublic' => $request->ispublic]);
        } catch (Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with(['message' => 'Collection Has Been Updated Successfully']);
    }

    protected function rollBack(int $cid): bool
    {
        try {
            $this->imagesCollectionModel->findOrFail($cid)->delete();
        } catch (Exception|\Throwable $e) {
            return false;
        }

        return true;
    }

    public function extracted(mixed $data): void
    {
        $this->collectionData['owner_id'] = auth()->user()->id;
        $this->collectionData['collection_name'] = $data['collection_name'];
        $this->collectionData['ispublic'] = $data['ispublic'];
        $this->collectionData['path'] = 'users/collections/user-'.auth()->user()->id.'/collection-'.str()->slug($data['collection_name']).'-'.now()->toDateString();
    }
}
