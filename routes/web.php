<?php

use App\Http\Controllers\ImagesCollectionController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlGeneratorController;
use App\Http\Controllers\UrlsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('collections')->group(function () {
        Route::get('/', [ImagesCollectionController::class, 'index'])->name('collections.index');
        Route::get('/new', [ImagesCollectionController::class, 'newCollectionIndex'])->name('collections.new.index');
        Route::post('/new', [ImagesCollectionController::class, 'createCollection'])->name('collections.new.post');
        Route::get('/get/{id}/{token}', [ImagesCollectionController::class, 'showCollection'])->name('collections.show');
        Route::delete('/delete/{id}/{token}', [ImagesCollectionController::class, 'deleteCollection'])->name('collections.delete');
        Route::prefix('patch')->group(function () {
            Route::patch('/publicity/{id}/{token}', [ImagesCollectionController::class, 'patchPublicity'])->name('collections.patch.publicity');
            Route::patch('/{id}/{token}', [ImagesCollectionController::class, 'patchCollection'])->name('collections.patch.collection');
        });
    });
    Route::prefix('images')->group(function () {
        Route::get('/show', [ImagesController::class, 'index'])->name('images.index');
        Route::get('/get/{id}/{token}', [ImagesController::class, 'getImage'])->name('images.get');
        Route::delete('/delete/{id}/{collectionID}/{token}', [ImagesController::class, 'deleteImage'])->name('images.delete');
        Route::prefix('/new')->group(function () {
            Route::get('/', [ImagesController::class, 'newImageIndex'])->name('images.new-index');
            Route::post('/', [ImagesController::class, 'createImage'])->name('images.new-post');

        });
        Route::prefix('patch')->group(function () {
            Route::patch('/publicity/{id}/{token}', [ImagesController::class, 'patchPublicity'])->name('images.patch.publicity');
            Route::patch('/{id}/{token}', [ImagesController::class, 'patchImage'])->name('images.patch');
        });
    });
    Route::prefix('url-generator')->group(function () {
        Route::get('/', [UrlGeneratorController::class, 'index'])->name('url-generator.index');
        Route::post('/new/{type}/{id}/{token}', [UrlGeneratorController::class, 'newUrl'])->name('url-generator.new-url');
        Route::get('/new/{type}/{id}/{token}', [UrlGeneratorController::class, 'newUrlIndex'])->name('url-generator.new-url');
        Route::delete('/delete/{id}/{token}', [UrlGeneratorController::class, 'deleteUrl'])->name('url-generator.delete-url');
        Route::prefix('/patch')->group(function () {
            Route::patch('/publicty/{id}/{token}', [UrlGeneratorController::class, 'patch'])->name('url-generator.patch');
        });

    });
});
Route::get('/urls/{shortUrl}', [UrlsController::class, 'index'])->name('short-url');

Route::get('/tests', function () {
    $collections = \App\Models\PublicUrls::with('urlable')->where('urlable_type', 'images_collection')->firstOrFail();
    dd($collections->urlable->collection_name);
});

require __DIR__.'/auth.php';
