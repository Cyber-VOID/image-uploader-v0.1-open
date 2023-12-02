<?php

namespace App\Http\Controllers;

use App\Models\PublicUrls;

class UrlsController extends Controller
{
    public function index($shortUrl)
    {
        $url = PublicUrls::where('short_url', $shortUrl)->where('ispublic', '1')->with('urlable')->firstOrFail();

        if (! $url->ispublic) {
            abort(404);
        } else {
            if ($url->isimage) {
                $image = $url->urlable;

                return view('url', compact('image'));
            } else {
                $collections = $url->urlable->images;

                return view('url', compact('collections'));
            }
        }
    }
}
