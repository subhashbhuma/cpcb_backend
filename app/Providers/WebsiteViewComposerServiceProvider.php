<?php

namespace App\Providers;

use App\Models\AdditionalLogo;
use App\Models\ContactDetail;
use App\Models\Menu;
use App\Models\SocialMedia;
use Illuminate\Support\ServiceProvider;

class WebsiteViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the 'frontend.menu' data to the frontend layout
        view()->composer('layouts.website_layout', function ($view) {
            $menus = Menu::getHeaderParentMenus();
            $view->with('menus', $menus);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
