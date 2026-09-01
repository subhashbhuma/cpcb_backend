<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Two-layer permission check for page operations:
     *
     * Layer 1: Check if the user has the menu-level permission
     *          (via userCan($action) which checks permission_name and permission_group)
     * Layer 2: The existing route middleware (can:view page, can:edit page, etc.)
     *          handles the generic page-module permission check.
     *
     * This middleware handles Layer 1 dynamically based on the request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
            return $next($request);
        }

        // Try to find the related menu from the page/request
        $menu = $this->resolveMenuFromRequest($request);

        // If we found a menu, check the appropriate action permission
        if ($menu) {
            $action = $this->determineAction($request);
            
            if (!$menu->userCan($action)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to ' . $action . ' content under this menu.'
                    ], 403);
                }
                abort(403, 'You do not have permission to ' . $action . ' content under this menu.');
            }
        }

        return $next($request);
    }

    /**
     * Determine the action (view, add, edit, delete, approve, publish) based on the request.
     */
    protected function determineAction(Request $request): string
    {
        if ($request->routeIs('pages.approve')) return 'approve';
        if ($request->routeIs('pages.publish')) return 'publish';
        
        if ($request->isMethod('POST')) return 'add';
        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) return 'edit';
        if ($request->isMethod('DELETE')) return 'delete';
        
        return 'view';
    }

    /**
     * Resolve the Menu model from the current request.
     * Supports route model binding ({page}) and request input (menu_id).
     */
    protected function resolveMenuFromRequest(Request $request): ?Menu
    {
        // 1. Try route model binding — when editing/viewing/deleting a specific page
        $page = $request->route('page');
        if ($page instanceof Page) {
            return $page->menu;
        }

        // If page is an ID string (not model-bound), load it
        if ($page && (is_numeric($page) || is_string($page))) {
            $pageModel = Page::find($page);
            if ($pageModel) {
                return $pageModel->menu;
            }
        }

        // 2. Try request input — when creating/storing a new page
        $menuId = $request->input('menu_id');
        if ($menuId) {
            return Menu::find($menuId);
        }

        return null;
    }
}
