<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SlugInitController extends Controller
{
    protected $pageService;

    public function __construct()
    {
        $this->pageService = new PageService();
    }

    public function index(Request $request)
    {
        try {
            $url = $request->query('url');

            if (!$url) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL parameter is required',
                ], 400);
            }

            // $cacheKey = 'slug_init_' . md5($url);
            
            // return Cache::remember($cacheKey, 600, function() use ($url) {
                // 1. Fetch Page Data
                $pages = $this->pageService->findByMenuUrlForPublic($url);
                $pageData = $pages->first();

                // 2. Fetch Breadcrumbs
                $urls = [rtrim($url, '/'), rtrim($url, '/') . '/'];
                $menu = Menu::whereIn('url', $urls)->first();
                $breadcrumbs = [];
                
                if ($menu) {
                    $parents = $menu->getAllParents();
                    $breadcrumbs = $parents->map(function($parent) {
                        return [
                            'id' => $parent->id,
                            'title' => $parent->title,
                            'title_hi' => $parent->title_hi,
                            'url' => $parent->url
                        ];
                    })->toArray();
                    
                    $breadcrumbs[] = [
                        'id' => $menu->id,
                        'title' => $menu->title,
                        'title_hi' => $menu->title_hi,
                        'url' => $menu->url
                    ];
                }

                // 3. Fetch Sidebar Menu
                $useDefaultMenu = $pageData ? ($pageData->default_menu == 1) : false;
                $sidebarMenus = [];
                $sidebarTitle = '';

                if ($useDefaultMenu) {
                    $sidebarMenus = Menu::with('children')
                        ->where('location', 'default')
                        ->whereNull('parent_id')
                        ->orderBy('order', 'asc')
                        ->get();
                } else {
                    // Optimized fetching to prefer exact location match first
                    $menus = Menu::with('children')
                        ->where('location', 'inner-pages')
                        ->whereNull('parent_id')
                        ->whereHas('children', function($q) use ($url) {
                            $q->where('url', 'LIKE', $url . '%');
                        })
                        ->orderBy('order', 'asc')
                        ->get();
                    
                    if ($menus->isEmpty()) {
                        $menus = Menu::with('children')
                            ->where('url', $url)
                            ->where('location', 'header')
                            ->whereNull('parent_id')
                            ->orderBy('order', 'asc')
                            ->get();
                    }

                    if ($menus->isEmpty()) {
                        $menus = Menu::with('children')
                            ->where('location', 'header')
                            ->whereNull('parent_id')
                            ->whereHas('children', function($q) use ($url) {
                                $q->where('url', 'LIKE', $url . '%');
                            })
                            ->orderBy('order', 'asc')
                            ->get();
                    }

                    $root = $menus->first();
                    if ($root) {
                        $sidebarMenus = $root->children ?? [];
                        $sidebarTitle = [
                            'en' => $root->title,
                            'hi' => $root->title_hi
                        ];
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'page_data' => $pageData,
                        'breadcrumbs' => $breadcrumbs,
                        'sidebar' => [
                            'menus' => $sidebarMenus,
                            'title' => $sidebarTitle,
                            'use_default' => $useDefaultMenu
                        ]
                    ],
                ]);
            // });
        } catch (\Exception $e) {
            Log::error('Slug initialization failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize slug page data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
