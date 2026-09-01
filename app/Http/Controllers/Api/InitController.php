<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\SiteSettingService;
use App\Services\VisitorService;
use App\Http\Resources\SiteSettingResource;
use Illuminate\Support\Facades\Log;

class InitController extends Controller
{
    protected $siteSettingService;
    protected $visitorService;

    public function __construct()
    {
        $this->siteSettingService = new SiteSettingService();
        $this->visitorService = new VisitorService();
    }

    /**
     * GET /api/layout-init
     * Consolidates site settings, visitor stats, and common menus.
     */
    public function index()
    {
        try {
            // 1. Fetch Site Settings
            $siteSetting = $this->siteSettingService->findFirst();
            $settingsData = $siteSetting ? SiteSettingResource::make($siteSetting) : null;

            // 2. Fetch Visitor Stats
            $visitorStats = $this->visitorService->getStats();

            // 3. Fetch Menus (Top Header and Main Header)
            $topHeaderMenus = Menu::with('children')
                ->where('location', 'top-header')
                ->orderBy('order', 'asc')
                ->where('parent_id', null)
                ->get();

            $headerMenus = Menu::with('children')
                ->where('location', 'header')
                ->orderBy('order', 'asc')
                ->where('parent_id', null)
                ->get();

            $footerQuickLinks = Menu::with('children')
                ->where('location', 'footer-quickLink')
                ->orderBy('order', 'asc')
                ->where('parent_id', null)
                ->get();

            $sidebarDefaultMenus = Menu::with('children')
                ->where('location', 'default')
                ->orderBy('order', 'asc')
                ->where('parent_id', null)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'settings' => $settingsData,
                    'visitor_stats' => $visitorStats,
                    'menus' => [
                        'top_header' => $topHeaderMenus,
                        'header' => $headerMenus,
                        'footer_quick_links' => $footerQuickLinks,
                        'sidebar_default' => $sidebarDefaultMenus,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Layout initialization failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize layout data.',
            ], 500);
        }
    }
}
