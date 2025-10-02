<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\StoreLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        //
        View::composer('layouts.app', function ($view) {

            if (Auth::check()) {
                $view->with(
                    'messagesByUserID',
                    Message::where('user_from_id', Auth::user()->id)->OrWhere('user_to_id', Auth::user()->id)->get()
                );
            }

            $view->with('allStoreLocation', StoreLocation::all());
        });
    }
}
