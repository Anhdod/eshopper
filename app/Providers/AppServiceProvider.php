<?php

namespace App\Providers;

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
    view()->composer('*', function ($view) {
        $view->with('menus', \App\Models\Menu::with('children')->whereNull('parent_id')->orderBy('order')->get());
        $view->with('categories', \App\Models\Category::with('children')->whereNull('parent_id')->get());
    });
}

}
