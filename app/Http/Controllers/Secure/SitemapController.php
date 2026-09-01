<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Exports\SitemapExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SitemapController extends Controller
{
    /**
     * Display the sitemap management page.
     */
    public function index()
    {
        $pageTitle = 'Export Sitemap';
        $frontendUrl = rtrim(env('FRONT_END_URL', config('app.url')), '/');

        $menus = self::getFlattenedMenus($frontendUrl);

        return view('secure.sitemap.index', compact('pageTitle', 'menus', 'frontendUrl'));
    }

    /**
     * Export sitemap as Excel file with Sl No, Title, URL.
     */
    public function export()
    {
        $filename = 'sitemap_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new SitemapExport, $filename);
    }

    /**
     * Get menus flattened in parent-child order, header location first.
     * Attaches full_url with frontend base URL for non-# URLs.
     */
    public static function getFlattenedMenus($frontendUrl = null)
    {
        if (!$frontendUrl) {
            $frontendUrl = rtrim(env('FRONT_END_URL', config('app.url')), '/');
        }

        $excludedLocations = ['sidebar', 'employee', 'useful-links', 'importantLink'];

        // Get root menus (no parent) with children loaded recursively
        $rootMenus = Menu::whereNull('parent_id')
            ->whereNotIn('location', $excludedLocations)
            ->with(['children' => function ($q) use ($excludedLocations) {
                $q->whereNotIn('location', $excludedLocations)
                  ->orderBy('order');
            }])
            ->orderByRaw("CASE WHEN location = 'header' THEN 0 ELSE 1 END")
            ->orderBy('order')
            ->get();

        // Flatten: parent, then children, then grandchildren etc.
        $flatList = collect();
        foreach ($rootMenus as $menu) {
            self::flattenMenu($menu, 0, $flatList, $frontendUrl);
        }

        return $flatList;
    }

    /**
     * Recursively flatten a menu and its children.
     */
    private static function flattenMenu($menu, $depth, &$flatList, $frontendUrl)
    {
        $menu->depth = $depth;

        // Build the full URL: skip if url is # or empty
        if ($menu->url && $menu->url !== '#' && !str_contains($menu->url, '#')) {
            $menuUrl = $menu->url;
            // If it's already an absolute URL, use as is
            if (str_starts_with($menuUrl, 'http://') || str_starts_with($menuUrl, 'https://')) {
                $menu->full_url = $menuUrl;
            } else {
                if (!str_starts_with($menuUrl, '/')) {
                    $menuUrl = '/' . $menuUrl;
                }
                $menu->full_url = $frontendUrl . $menuUrl;
            }
        } else {
            $menu->full_url = null;
        }

        $flatList->push($menu);

        if ($menu->children && $menu->children->isNotEmpty()) {
            foreach ($menu->children->sortBy('order') as $child) {
                // Load grandchildren if not already loaded
                if (!$child->relationLoaded('children')) {
                    $child->load(['children' => function ($q) {
                        $q->orderBy('order');
                    }]);
                }
                self::flattenMenu($child, $depth + 1, $flatList, $frontendUrl);
            }
        }
    }
}
