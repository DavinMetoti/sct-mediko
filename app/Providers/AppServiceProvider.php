<?php

namespace App\Providers;

use App\Models\UserFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            Telescope::night();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

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
