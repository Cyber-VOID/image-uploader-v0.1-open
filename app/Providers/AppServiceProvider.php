<?php

namespace App\Providers;

use App\Models\Images;
use App\Models\ImagesCollection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'images' => Images::class,
            'images_collection' => ImagesCollection::class,
        ]);
    }
}
