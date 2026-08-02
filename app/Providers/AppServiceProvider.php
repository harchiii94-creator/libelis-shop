<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
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
        if (app()->isProduction()) {
        URL::forceScheme('https');
    }

    View::composer('*', function ($view) {
        $view->with('siteName', 'libellis-shop');
        $view->with('navbarCartCount', array_sum(session('cart', [])));
        $view->with('brandCategories', Category::pluck('name', 'slug')->toArray());
        });
    }
}
