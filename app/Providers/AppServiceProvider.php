<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use App\Models\Menu;
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

        URL::forceRootUrl(config('app.url'));

        if (str_contains(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Implicitly grant "ADMIN" and "SUPERADMIN" roles all permissions
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
                return true;
            }

            if ($user->hasRole('EMPLOYEE')) {
                // For EMPLOYEE role, check permission based on roles only, ignoring direct user permissions
                return $user->getPermissionsViaRoles()->pluck('name')->contains($ability) ? true : false;
            }

            return null;
        });

        View::share('siteSettings', Cache::rememberForever('site_settings', function () {
            return SiteSetting::first();
        }));


        // Dynamic Sidebar Menus View Composer
        View::composer('includes.secure.sidebar', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $cacheKey = 'sidebar_menus_' . $user->id . '_' . ($user->updated_at ? $user->updated_at->timestamp : 0);

                $filteredMenus = Cache::remember($cacheKey, 300, function () use ($user) {

                    $location = $user->hasRole('EMPLOYEE')
                        ? 'employee'
                        : 'sidebar';

                    $allRootMenus = Menu::where('location', $location)
                        ->whereNull('parent_id')
                        ->with('children')
                        ->orderBy('order')
                        ->get();

                    // SUPERADMIN, ADMIN and EMPLOYEE see all menus
                    if ($user->hasAnyRole(['ADMIN', 'SUPERADMIN', 'EMPLOYEE'])) {
                        return $allRootMenus;
                    }

                    return $this->filterMenus($allRootMenus);
                });

                $view->with('sidebarDynamicMenus', $filteredMenus);
            }
        });
    }

    /**
     * Filter menus based on user permissions and hierarchy.
     */
    private function filterMenus($menus)
    {
        $filtered = collect();
        $currentCaption = null;
        $captionItems = collect();

        foreach ($menus as $menu) {
            if ($menu->is_caption) {
                // If we already have a caption, check if it had items before starting a new one
                if ($currentCaption && $captionItems->isNotEmpty()) {
                    $filtered->push($currentCaption);
                    $filtered = $filtered->merge($captionItems);
                }
                $currentCaption = $menu;
                $captionItems = collect();
                continue;
            }

            // Recursive check for children
            $viewableChildren = collect();
            if ($menu->children && $menu->children->isNotEmpty()) {
                $viewableChildren = $this->filterMenus($menu->children);
            }

            // A menu is viewable if:
            // 1. It has viewable children (hierarchy requirement)
            // 2. OR it is a direct link (no children) and the user has view permissions
            // 3. OR it is a parent with its own direct link and the user has view permissions

            $hasViewableChildren = $viewableChildren->isNotEmpty();
            $hasOwnPermissions = $menu->userCanView();
            $isDirectLink = !empty($menu->url) && $menu->url !== '#';

            // If it has children in DB, it shows if:
            // - It has at least one viewable child
            // - OR it has its own direct link and user has permission
            if ($menu->children->isNotEmpty()) {
                $isViewable = $hasViewableChildren || ($hasOwnPermissions && $isDirectLink);
            } else {
                // Standalone menu item
                $isViewable = $hasOwnPermissions;
            }

            if ($isViewable) {
                // Temporarily override children with filtered ones
                $menu->setRelation('children', $viewableChildren);

                if ($currentCaption) {
                    $captionItems->push($menu);
                } else {
                    $filtered->push($menu);
                }
            }
        }

        // Push last caption if it has items
        if ($currentCaption && $captionItems->isNotEmpty()) {
            $filtered->push($currentCaption);
            $filtered = $filtered->merge($captionItems);
        }

        return $filtered;
    }
}
