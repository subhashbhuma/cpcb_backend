<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;

class EmployeeDynamicPageController extends Controller
{
    /**
     * Display the dynamic page for the employee module.
     *
     * @param string $url
     * @return \Illuminate\View\View
     */
    public function show($url)
    {
        // The $url parameter does not include the 'secure/' prefix since this is inside the secure group.
        // We will check possible URL permutations that might be saved in the menus table.
        $possibleUrls = [
            $url,
            '/' . $url,
            'secure/' . $url,
            '/secure/' . $url,
        ];

        // Find the employee menu that matches the URL
        $menu = Menu::where('location', 'employee')
            ->whereIn('url', $possibleUrls)
            ->first();

        if (!$menu) {
            abort(404);
        }

        // Find the associated page for this menu
        // We only want pages of type 'employee'
        $page = Page::where('menu_id', $menu->id)
            ->where('type', 'employee')
            ->first();

        if (!$page) {
            abort(404);
        }

        return view('secure.employee_pages.show', compact('page', 'menu'));
    }
}
