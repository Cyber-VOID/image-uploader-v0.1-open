<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\ImagesCollection;
use App\Models\PublicUrls;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UrlGeneratorController extends Controller
{
    private object $imagesCollection;

    private object $publicUrls;

    private $model;

    private $collectionImages;

    private $type;

    private object $images;

    public function __construct()
    {
        $this->images = new Images();
        $this->imagesCollection = new ImagesCollection();
        $this->publicUrls = new PublicUrls();
        $this->model = [];
        $this->type = false;
    }

    public function index()
    {
        try {
            $urls = $this->publicUrls::where('owner_id', auth()->user()->id)->with('urlable')->paginate(6);
        } catch (\Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return view('users.urls.get-all-urls', compact('urls'));
    }

    public function newUrlIndex(string $type, int $id, string $token)
    {

        try {
            if (! in_array($type, $this->publicUrls::TYPE)) {
                return redirect()->back()
                    ->withErrors(['error' => 'You Cant Manipulate The System']);
            } elseif ($type === 'image') {
                $this->model = $this->images::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->where('ispublic', '1')->with('collection')->firstOrFail();
                $this->type = true;
            } elseif ($type === 'collection') {
                $this->model = $this->imagesCollection::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->where('ispublic', '1')->with('images')->firstOrFail();
                $this->type = false;
            }
            if (! $this->model->ispublic) {
                if ($type === 'collection') {
                    return redirect()->route('collections.show', [$id, $token])->withErrors(['error' => 'The Collection Must Be Public']);
                } else {
                    return redirect()->route('images.get', [$id, $token])->withErrors(['error' => 'The Image Must Be Public']);
                }
            }
            if (! isset($this->model->url)) {
                if ($type === 'collection') {
                    return redirect()->route('collections.index')->withErrors(['error' => 'You Already have a url']);
                } else {
                    return redirect()->route('images.index')->withErrors(['error' => 'You Already have a url']);
                }
            }
            if (! $this->type) {
                $this->collectionImages = $this->model->images()->where('ispublic', '1')->paginate(3);
                if ($this->model->count() <= 0) {
                    return redirect()->route('collections.show', [$id, $token])->withErrors(['error' => 'You Have No Images Or Its Private Please Upload Images To Proceed']);
                }
            }
        } catch (\Exception|\Throwable $e) {
            $pattern = '/No query results for model \[.*?\]\./';
            $replacement = 'No query results';

            $outputString = preg_replace($pattern, $replacement, $e->getMessage());

            return redirect()->back()->withErrors(['error' => $outputString]);
        }

        if (! $this->type) {
            $collection = $this->model;
            $images = $this->collectionImages;

            return view('users.urls.generate-url', compact('collection', 'images'));
        } else {
            $image = $this->model;

            return view('users.urls.image-generator', compact('image'));

        }
    }

    public function newUrl(Request $request, string $type, int $id, string $token): RedirectResponse
    {

        try {
            if (! in_array($type, $this->publicUrls::TYPE)) {
                return redirect()->back()
                    ->withErrors(['error' => 'You Cant Manipulate The System']);
            } elseif ($type === 'image') {
                $this->model = $this->images::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->where('ispublic', '1')->with('collection')->firstOrFail();
                $this->type = 'images';
                $ispicutre = ['isimage' => '1'];
            } elseif ($type === 'collection') {
                $this->model = $this->imagesCollection::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->where('ispublic', '1')->with('images')->firstOrFail();
                $this->type = 'images_collection';
                $ispicutre = ['isimage' => '0'];
            }

            $data = $request->validate([
                'name' => 'required|string|max:256',
                'ispublic' => 'required|in:0,1',
            ]) + ['owner_id' => str(auth()->user()->id)] + $ispicutre;
            $url = $this->publicUrls::where('urlable_id', $id)->where('urlable_type', $this->type)->first();
            if ($url) {
                return redirect()->back()->withErrors(['error' => 'You Cant Make More Than One URL For The Same Image Or Collection']);
            }
            $url = $this->model->url()->create($data);

        } catch (\Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with(['message' => 'Url Has Been Generated Successfully', 'infos' => route('short-url', $url->short_url)]);
    }

    public function patch(Request $request, $id, $token)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'ispublic' => 'required|in:0,1']);
        try {
            $url = $this->publicUrls::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
            $url->updateOrFail([
                'name' => $data['name'],
                'ispublic' => strval($data['ispublic']),
            ]);
        } catch (\Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('url-generator.index', [$url->id, $url->token])
            ->with(['message' => 'Url Has Been Updated Successfully']);
    }

    public function deleteUrl($id, $token)
    {
        try {
            $url = $this->publicUrls::where('id', $id)->where('token', $token)->where('owner_id', auth()->user()->id)->firstOrFail();
            $url->delete();
        } catch (\Exception|\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with(['message' => 'Url Has Been Deleted Successfully']);
    }
}
