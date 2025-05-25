<?php

namespace App\Providers;

use App\Models\UserFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
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
    public function boot()
    {
        // if (config('app.env') === 'production') {
        //     URL::forceScheme('https');
        // }

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $collections = UserFolder::where('user_id', Auth::id())->get();
                $view->with('collections', $collections);
            } else {
                $view->with('collections', collect());
            }
        });
    }

}
