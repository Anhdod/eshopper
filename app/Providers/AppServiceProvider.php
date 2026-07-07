<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Menu;
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
        view()->composer('partials.header', function ($view) {
            $view->with('menus', Menu::with('children')->whereNull('parent_id')->orderBy('order')->get());
            $view->with('categories', Category::with('children')->whereNull('parent_id')->get());
        });

        view()->composer('home', function ($view) {
            $view->with('categories', Category::with('children')->withCount('products')->whereNull('parent_id')->get());
        });
    }

}
