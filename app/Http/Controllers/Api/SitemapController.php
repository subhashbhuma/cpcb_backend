<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class SitemapController extends Controller
{
    /**
     * Get all published pages for sitemap generation
     * 
     * @return JsonResponse
     */
    public function getPagesForSitemap(): JsonResponse
    {
        try {
            $pages = Page::where('is_published', true)
                ->where('is_approved', true)
                ->select('slug', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get();

            return response()->json($pages);
        } catch (\Exception $e) {
            \Log::error('Error fetching pages for sitemap: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}
