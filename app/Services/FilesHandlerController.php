<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class FilesHandlerController extends Controller
{
    private $storagePath;

    private $imgStoragePath;

    public function __construct()
    {
        $this->storagePath = storage_path('app/public/');
        $this->imgStoragePath = storage_path('app/public/collections/');
    }

    public function createFolder($folder): void
    {
        //        $folderPath = $this->storagePath . $folder;
        if (! File::exists($folder)) {
            File::makeDirectory($folder, 0777, true, true);
        }
    }

    public function renameFolder(string $oldName, string $newName)
    {
        if (File::exists(collection_path($oldName))) {
            dd(File::move(collection_path($oldName), collection_path($newName)));
        }
    }
    /*public function createImageFolder($folder): void
    {
        $this->imgStoragePath = $this->imgStoragePath.$folder;
        if (! File::isDirectory($folder)) {
            File::makeDirectory($folder);
        }
    }*/
    /*public function deleteFolder($folder): void
    {
        $folderPath = $this->storagePath.'/'.$folder;
        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
        }
    }*/
}
