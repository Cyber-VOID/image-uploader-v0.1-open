<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Images;
use App\Models\ImagesCollection;
use Exception;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageServicesController extends Controller
{
    private $thumbName;

    private $filesHandler;

    public function __construct()
    {
        $this->filesHandler = new FilesHandlerController();
    }

    public function storeImages($files, string $path, string $name, $cid): bool
    {
        try {
            ImagesCollection::findOrFail($cid);
        } catch (Exception|\Throwable $e) {
            return false;
        }

        $this->filesHandler->createFolder($path);
        foreach ($files as $key => $file) {
            $img = Image::make($file);

            if (! empty($img->exif()) || ! empty($img->iptc())) {
                $img->exif();
                $img->iptc();
            }

            $filename = Str::slug($name)."-{$key}-".now()->toDateString().'.'.$file->getClientOriginalExtension();

            $url = $path.'/';
            $img->save($path.'/'.$filename);
            try {
                Images::create([
                    'ikey' => strval($key),
                    'owner_id' => strval(auth()->user()->id),
                    'name' => $name,
                    'image_name' => $filename,
                    'path' => $url,
                    'ispublic' => '0',
                    'collection_id' => strval($cid),
                ]);
            } catch (Exception|\Throwable $e) {
                return false;
            }

        }

        return true;
    }

    public function createImage($file, string $path, string $name)
    {
        $dims = getimagesize($file);

        $img = Image::make($file);
        $this->filesHandler->createFolder($path);

        if (! empty($img->exif()) || ! empty($img->iptc())) {
            $img->exif();
            $img->iptc();
        }

        $filename = Str::slug($name).'-'.now()->toDateString().'.'.$file->getClientOriginalExtension();

        $url = $path.'/';
        $img->save($path.'/'.$filename)->resize($dims[0], $dims[1]);
        try {
            $image = Images::create([
                'ikey' => strval(0),
                'owner_id' => strval(auth()->user()->id),
                'name' => $name,
                'image_name' => $filename,
                'path' => $url,
                'ispublic' => '0',
            ]);
        } catch (Exception|\Throwable $e) {
            return false;
        }

        return $image;
    }
}
