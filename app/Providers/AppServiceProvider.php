<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $path = public_path('.well-known/assetlinks.json');

        if (File::exists($path)) {
            Response::macro('assetLinks', function () use ($path) {
                return Response::make(File::get($path), 200)
                    ->header('Content-Type', 'application/json');
            });

            // Route::get('/.well-known/assetlinks.json', function () use ($path) {
            //     return response()->assetLinks();
            // });
        }


        //
    }
}
