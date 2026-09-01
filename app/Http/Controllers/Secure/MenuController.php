<?php

namespace App\Http\Controllers\Secure;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use App\Models\MenuLocation;
use App\Traits\FileUploadTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    use FileUploadTrait;
    protected $menuLocationService;

    public function __construct()
    {
        // Initialize the menu location service
        $this->menuLocationService = new \App\Services\MenuLocationService();
    }
    public function __destruct()
    {
        // Clean up resources if needed
        unset($this->menuLocationService);
    }

    public function index()
    {
        $pageTitle = 'Menu';
        // Fetch Locations
        $excludeCodes = ['sidebar'];
        $locations = $this->menuLocationService->findAll($excludeCodes);

        $filterLocation = request()->get('location') ?? 'header';
        if ($filterLocation) {
            $firstLocation = $locations->where('location_code', $filterLocation)->first();
            if (!$firstLocation) {
                return redirect()->route('menus.index')->with('error', 'Invalid location selected.');
            }
        } else {
            $firstLocation = $locations->first();
        }

        // Fetch all menus with their children
        $menus = Menu::whereNull('parent_id')->where([
            'location' => $firstLocation->location_code,
        ])->with('children')->orderBy('order')->get();

        return view('secure.menus.index', compact('menus', 'pageTitle', 'firstLocation', 'locations'));
    }

    public function create()
    {
        $pageTitle = 'Add New Menu';
        $menus = $this->getMenuTreeForDropdown(['sidebar']);
        // Fetch unique permission groups
        $permissionGroups = \Spatie\Permission\Models\Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->groupBy('group')
            ->pluck('group');
        $locations = $this->menuLocationService->findAll(['sidebar']);

        return view('secure.menus.create', compact('menus', 'pageTitle', 'locations', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'url' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'icon_type' => 'required|string|in:ICON,IMAGE',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'location' => 'required|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'permission_name' => 'nullable|string|exists:permissions,name',
            'permission_group' => 'nullable|string',
            'type' => 'required|string|in:URL,FILE',
            'file_name' => 'nullable|file|mimes:pdf|max:51200',
            'file_name_hi' => 'nullable|file|mimes:pdf|max:51200',
        ];

        // Conditional validation for icon_png based on icon_type
        if ($request->input('icon_type') === 'ICON') {
            $rules['icon_png'] = 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u';
        } else {
            $rules['icon_png'] = 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=512,max_height=512';
        }

        // Create a validator instance and validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Auto-generate URL slug from title if URL is not provided
            $url = $request->input('url');
            if (empty(trim((string)$url))) {
                $slug = '/' . Str::slug($request->input('title'));
                // Prefix with parent URL for nested menus
                $parentId = $request->input('parent_id');
                if ($parentId) {
                    $parentMenu = Menu::find($parentId);
                    if ($parentMenu && $parentMenu->url && $parentMenu->url !== '#') {
                        $slug = rtrim($parentMenu->url, '/') . '/' . Str::slug($request->input('title'));
                    }
                }
                $url = $slug;
            }

            // Auto-generate order if not provided (next sibling order)
            $order = $request->input('order');
            if ($order === null || $order === '') {
                $parentId = $request->input('parent_id');
                $location = $request->input('location');
                $maxOrder = Menu::where('location', $location)
                    ->where(function ($q) use ($parentId) {
                        if ($parentId) {
                            $q->where('parent_id', $parentId);
                        } else {
                            $q->whereNull('parent_id');
                        }
                    })
                    ->max('order');
                $order = ($maxOrder !== null ? (int)$maxOrder : -1) + 1;
            }

            // Create a new Menu instance
            $menu = new Menu();
            $menu->title = $request->input('title');
            $menu->title_hi = $request->input('title_hi');
            $menu->type = $request->input('type');
            $menu->url = $url;
            $menu->icon_type = $request->input('icon_type');
            $menu->parent_id = $request->input('parent_id');
            $menu->order = $order;
            $menu->location = $request->input('location');
            $menu->permission_name = $request->input('permission_name');
            $menu->permission_group = $request->input('permission_group');
            $menu->created_by = auth()->user()->id;
            $menu->updated_by = auth()->user()->id;

            // Handle icon_png based on icon_type
            if ($request->input('icon_type') === 'IMAGE' && $request->hasFile('icon_png')) {
                $iconFile = $this->uploadFile($request->file('icon_png'), Config::get('file_paths')['MENU_ICON_IMAGE_PATH']);
                $menu->icon_png = $iconFile['file_name'];
            } else {
                $menu->icon_png = $request->input('icon_png');
            }

            if ($request->file_name && $request->input('type') == "FILE") {
                $headerFile = $this->uploadFile($request->file_name, Config::get('file_paths')['MENU_FILE_EN_PATH']);
                $menu->file_name = $headerFile['file_name'];
            } else {
                $menu->file_name = null;
            }

            if ($request->file_name_hi && $request->input('type') == "FILE") {
                $headerFile = $this->uploadFile($request->file_name_hi, Config::get('file_paths')['MENU_FILE_HI_PATH']);
                $menu->file_name_hi = $headerFile['file_name'];
            } else {
                $menu->file_name_hi = null;
            }

            // Save the menu item to the database
            $menu->save();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Menu created successfully.',
                'data' => $menu,
            ], 201);
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the menu.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    public function edit(Menu $menu)
    {
        $pageTitle = 'Edit Menu';
        $menus = $this->getMenuTreeForDropdown(['sidebar']);
        // Fetch unique permission groups
        $permissionGroups = \Spatie\Permission\Models\Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->groupBy('group')
            ->pluck('group');
        $locations = $this->menuLocationService->findAll(['sidebar']);

        return view('secure.menus.edit', compact('menu', 'menus', 'pageTitle', 'locations', 'permissionGroups'));
    }

    public function update(Request $request, Menu $menu)
    {
        $rules = [
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'url' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'icon_type' => 'required|string|in:ICON,IMAGE',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'location' => 'required|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'permission_name' => 'nullable|string|exists:permissions,name',
            'type' => 'required|string|in:URL,FILE',
            'file_name' => 'nullable|file|mimes:pdf|max:51200',
            'file_name_hi' => 'nullable|file|mimes:pdf|max:51200',
        ];

        // Conditional validation for icon_png based on icon_type
        if ($request->input('icon_type') === 'ICON') {
            $rules['icon_png'] = 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u';
        } else {
            $rules['icon_png'] = 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=512,max_height=512';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Auto-generate URL slug from title if URL is not provided
            $url = $request->input('url');
            if (empty(trim((string)$url))) {
                $slug = '/' . Str::slug($request->input('title'));
                // Prefix with parent URL for nested menus
                $parentId = $request->input('parent_id');
                if ($parentId) {
                    $parentMenu = Menu::find($parentId);
                    if ($parentMenu && $parentMenu->url && $parentMenu->url !== '#') {
                        $slug = rtrim($parentMenu->url, '/') . '/' . Str::slug($request->input('title'));
                    }
                }
                $url = $slug;
            }

            // Auto-generate order if not provided (next sibling order)
            $order = $request->input('order');
            if ($order === null || $order === '') {
                $parentId = $request->input('parent_id');
                $location = $request->input('location');
                $maxOrder = Menu::where('location', $location)
                    ->where(function ($q) use ($parentId) {
                        if ($parentId) {
                            $q->where('parent_id', $parentId);
                        } else {
                            $q->whereNull('parent_id');
                        }
                    })
                    ->where('id', '!=', $menu->id)
                    ->max('order');
                $order = ($maxOrder !== null ? (int)$maxOrder : -1) + 1;
            }

            $menu->title = $request->input('title');
            $menu->title_hi = $request->input('title_hi');
            $menu->type = $request->input('type');
            $menu->url = $url;
            $menu->icon_type = $request->input('icon_type');
            $menu->parent_id = $request->input('parent_id');
            $menu->order = $order;
            $menu->location = $request->input('location');
            $menu->permission_name = $request->input('permission_name');
            $menu->permission_group = $request->input('permission_group');
            $menu->updated_by = auth()->user()->id;

            // Handle icon_png based on icon_type
            if ($request->input('icon_type') === 'IMAGE') {
                if ($request->hasFile('icon_png')) {
                    $iconFile = $this->uploadFile($request->file('icon_png'), Config::get('file_paths')['MENU_ICON_IMAGE_PATH']);
                    $menu->icon_png = $iconFile['file_name'];
                }
                // If no new file uploaded, keep the existing icon_png value
            } else {
                $menu->icon_png = $request->input('icon_png');
            }

            if ($request->file_name && $request->input('type') == "FILE") {
                $headerFile = $this->uploadFile($request->file_name, Config::get('file_paths')['MENU_FILE_EN_PATH']);
                $menu->file_name = $headerFile['file_name'];
            } else {
                $menu->file_name = null;
            }

            if ($request->file_name_hi && $request->input('type') == "FILE") {
                $headerFile = $this->uploadFile($request->file_name_hi, Config::get('file_paths')['MENU_FILE_HI_PATH']);
                $menu->file_name_hi = $headerFile['file_name'];
            } else {
                $menu->file_name_hi = null;
            }
            $menu->update();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Menu updated successfully.',
                'data' => $menu,
                'redirect_url' => route('menus.index'),
            ], 201);
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the menu.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');
        $this->updateMenuOrder($order, null);
        return response()->json(['success' => true]);
    }

    private function updateMenuOrder(array $items, $parentId)
    {
        foreach ($items as $index => $item) {
            $menu = Menu::find($item['id']);
            $menu->update([
                'parent_id' => $parentId,
                'order' => $index,
                'updated_by' => auth()->user()->id,
            ]);

            if (isset($item['children'])) {
                $this->updateMenuOrder($item['children'], $menu->id);
            }
        }
    }



    public function fetchAllMenusByLocation($location)
    {
        $cacheKey = 'menu_by_location_' . $location;

        $menus = Cache::remember($cacheKey, 3600, function () use ($location) {
            return Menu::with('children')
                ->where('location', $location)
                ->orderBy('order', 'asc')
                ->where('parent_id', null)
                ->get();
        });

        return response()->json([
            'success' => true,
            'data'    => $menus,
        ]);
    }

    /**
     * Get full tree of menus for dropdown lists
     */
    private function getMenuTreeForDropdown($excludeCodes = [])
    {
        $locations = MenuLocation::all()->keyBy('location_code');
        $locationOrder = ['sidebar', 'header', 'inner-pages', 'default', 'footer-quickLink', 'footer-importantLink', 'useful-links', 'top-header', 'importantLink'];
        $flatMenus = [];

        if (!empty($excludeCodes)) {
            $locationOrder = array_diff($locationOrder, $excludeCodes);
        }

        foreach ($locationOrder as $locCode) {
            $location = $locations->get($locCode);
            if (!$location)
                continue;

            $rootMenus = Menu::where('location', $locCode)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with([
                    'children' => function ($q) {
                        $q->orderBy('order')->with('children');
                    }
                ])
                ->get();

            if ($rootMenus->isNotEmpty()) {
                $slNo = 1;
                foreach ($rootMenus as $rootMenu) {
                    $this->flattenMenuForDropdown($rootMenu, (string) $slNo, 0, $location->location_name, $flatMenus);
                    $slNo++;
                }
            }
        }

        return $flatMenus;
    }

    private function flattenMenuForDropdown($menu, $prefix, $depth, $locationName, &$rows)
    {
        $dashes = str_repeat('-', $depth);

        $rows[] = (object) [
            'id' => $menu->id,
            'dashes' => $dashes,
            'title' => $menu->title,
            'location_name' => $locationName,
            'sl_no' => $prefix
        ];

        if ($menu->children && $menu->children->isNotEmpty()) {
            $childIndex = 1;
            foreach ($menu->children as $child) {
                if (!isset($child->children)) {
                    $child->load([
                        'children' => function ($q) {
                            $q->orderBy('order');
                        }
                    ]);
                }
                $this->flattenMenuForDropdown($child, $prefix . '.' . $childIndex, $depth + 1, $locationName, $rows);
                $childIndex++;
            }
        }
    }

    //         // For default location, return all parent menus
    //         $menus = Menu::with('children')
    //             ->where('location', $location)
    //             ->where('parent_id', null)
    //             ->orderBy('order', 'asc')
    //             ->get();
    //     } else if($location == 'header') {
    //         // $menus = Menu::with('children')
    //         // ->whereIn('location',['inner-pages','footer-quickLink'])
    //         //     ->where('parent_id', null)
    //         //         ->whereHas('children', function($q) use ($url) {
    //         //     $q->where('url', 'LIKE', $url . '%');
    //         // })
    //         //     ->orderBy('order', 'asc')
    //         //     ->get();

    //         // if ($menus->isEmpty()) {
    //         //      $menus = Menu::with('children')
    //         //       ->where('url', $url)
    //         //     ->where('location', $location)
    //         //     ->where('parent_id', null)
    //         //     ->orderBy('order', 'asc')
    //         //     ->get();
    //         // }

    //         // if ($menus->isEmpty()) {
    //         // $menus = Menu::with('children')
    //         // ->where('location', $location)
    //         // ->where('parent_id', null)
    //         // ->whereHas('children', function($q) use ($url) {
    //         //     $q->where('url', 'LIKE', $url . '%');
    //         // })
    //         // ->orderBy('order', 'asc')
    //         // ->get();
    //         // }



    //         $menus = Menu::with('children')
    //             ->whereIn('location', ['inner-pages', 'footer-quickLink','useful-links'])
    //             ->whereNull('parent_id')
    //             ->whereHas('children', function ($q) use ($url) {
    //                 $q->where('url', 'LIKE', $url . '%');
    //             })
    //             ->orderBy('order', 'asc')
    //             ->get();

    //         if ($menus->isEmpty()) {
    //             $menus = Menu::with('children')
    //                 ->where('url', $url)
    //                 ->where('location', $location)
    //                 ->whereNull('parent_id')
    //                 ->orderBy('order', 'asc')
    //                 ->get();
    //         }

    //         if ($menus->isEmpty()) {
    //             $menus = Menu::with('children')
    //                 ->where('location', $location)
    //                 ->whereNull('parent_id')
    //                 ->whereHas('children', function ($q) use ($url) {
    //                     $q->where('url', 'LIKE', $url . '%');
    //                 })
    //                 ->orderBy('order', 'asc')
    //                 ->get();
    //         }

    //     }
    //     return response()->json([
    //         'success' => true,
    //         'data' => $menus,
    //     ]);
    // }


    public function fetchAllMenusByUrl(Request $request)
    {
        $url      = rtrim($request->query('url', ''), '/');
        $location = $request->query('location', '');
        $cacheKey = 'menu_by_url_' . md5($url . '|' . $location);

        $menus = Cache::remember($cacheKey, 3600, function () use ($url, $location) {
            if ($location == 'default') {
                $trimUrl = trim($url, '/');
                if (in_array($trimUrl, ['feedback', 'complaint'])) {
                    $parentId = Menu::where('location', $location)
                        ->where('title', 'LIKE', '%Waste Management%')
                        ->value('id');

                    return Menu::where('parent_id', $parentId)
                        ->orderBy('order', 'asc')
                        ->get();
                } else {
                    return Menu::with('children')
                        ->where('location', $location)
                        ->whereNull('parent_id')
                        ->orderBy('order', 'asc')
                        ->get();
                }
            } elseif ($location == 'header') {
                $headerLocations = ['header', 'inner-pages', 'footer-quickLink', 'useful-links'];

                // 1. Normal header behavior: root header parent menu having a child match
                $menus = Menu::with('children')
                    ->whereIn('location', $headerLocations)
                    ->whereNull('parent_id')
                    ->whereHas('children', function ($q) use ($url) {
                        $q->where('url', 'LIKE', $url . '%');
                    })
                    ->orderBy('order', 'asc')
                    ->get();

                // 2. fallback: resolve url to a menu and use that menu (or its ancestor)
                if ($menus->isEmpty() && $url) {
                    $matchedMenu = Menu::with('children')
                        ->where('url', $url)
                        ->whereIn('location', $headerLocations)
                        ->first();

                    if (!$matchedMenu) {
                        // fallback to path segment matching as getBreadcrumb does
                        $segments    = array_filter(explode('/', $url));
                        $currentPath = '';
                        $lastMenu    = null;
                        foreach ($segments as $segment) {
                            $currentPath .= '/' . $segment;
                            $segmentMenu  = Menu::with('children')
                                ->where('url', $currentPath)
                                ->whereIn('location', $headerLocations)
                                ->first();
                            if ($segmentMenu) {
                                $lastMenu = $segmentMenu;
                            }
                        }
                        $matchedMenu = $lastMenu;
                    }

                    if (!$matchedMenu) {
                        // fallback to partial match
                        $matchedMenu = Menu::with('children')
                            ->where('url', 'LIKE', $url . '%')
                            ->whereIn('location', $headerLocations)
                            ->orderByRaw('LENGTH(url) desc')
                            ->first();
                    }

                    if ($matchedMenu) {
                        // if it has a parent, find top-most ancestor (for header scope)
                        $ancestor = $matchedMenu;
                        while ($ancestor && $ancestor->parent_id) {
                            $ancestor = Menu::with('children')->find($ancestor->parent_id);
                        }

                        if ($ancestor) {
                            $menus = Menu::with('children')->where('id', $ancestor->id)->get();
                        } else {
                            // if no ancestor, use matched directly
                            $menus = Menu::with('children')->where('id', $matchedMenu->id)->get();
                        }
                    }
                }

                // 3. final fallback: all root header sections
                if ($menus->isEmpty()) {
                    $menus = Menu::with('children')
                        ->whereIn('location', $headerLocations)
                        ->whereNull('parent_id')
                        ->orderBy('order', 'asc')
                        ->get();
                }

                return $menus;
            } else {
                return Menu::with('children')
                    ->where('location', $location)
                    ->whereNull('parent_id')
                    ->orderBy('order', 'asc')
                    ->get();
            }
        });

        return response()->json([
            'success' => true,
            'data'    => $menus,
        ]);
    }

    /**
     * Get breadcrumb trail for a given URL
     * Supports both exact URL match and nested URL paths
     */
    public function getBreadcrumb(Request $request)
    {
        $url = $request->query('url');

        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'URL parameter is required',
                'data'    => []
            ], 400);
        }

        $cacheKey = 'menu_breadcrumb_' . md5($url);

        $result = Cache::remember($cacheKey, 3600, function () use ($url) {
            // First, try exact URL match
            $urls = [rtrim($url, '/'), rtrim($url, '/') . '/'];
            $menu = Menu::whereIn('url', $urls)->first();

            if ($menu) {
                // Found exact match, use getAllParents method
                $parents = $menu->getAllParents();

                // Build breadcrumb array
                $breadcrumbs = $parents->map(function ($parent) {
                    return [
                        'id'       => $parent->id,
                        'title'    => $parent->title,
                        'title_hi' => $parent->title_hi,
                        'url'      => $parent->url
                    ];
                })->values()->toArray();

                // Add current menu as the last item
                $breadcrumbs[] = [
                    'id'       => $menu->id,
                    'title'    => $menu->title,
                    'title_hi' => $menu->title_hi,
                    'url'      => $menu->url
                ];

                return ['found' => true, 'data' => array_values($breadcrumbs)];
            }

            $segments    = array_filter(explode('/', $url));
            $breadcrumbs = [];
            $currentPath = '';

            foreach ($segments as $segment) {
                $currentPath .= '/' . $segment;

                // Try to find menu with this path
                $segmentMenu = Menu::where('url', $currentPath)->first();

                if ($segmentMenu) {
                    $breadcrumbs[] = [
                        'id'       => $segmentMenu->id,
                        'title'    => $segmentMenu->title,
                        'title_hi' => $segmentMenu->title_hi,
                        'url'      => $segmentMenu->url
                    ];
                }
            }

            if (count($breadcrumbs) > 0) {
                return ['found' => true, 'data' => array_values($breadcrumbs)];
            }

            return ['found' => false];
        });

        if ($result['found']) {
            return response()->json([
                'success' => true,
                'data'    => $result['data']
            ]);
        }

        // No breadcrumb found
        return response()->json([
            'success' => false,
            'message' => 'No breadcrumb trail found for the given URL',
            'data'    => []
        ]);
    }


    // public function fetchInnerMenusByParentTitle(Request $request)
    // {
    //     $url = $request->query('url');
    //     $location = $request->query('location');
    //     $parentMenu = Menu::where('title', 'ILIKE', '%' . $url . '%')
    //         ->where('location', $location)
    //         ->first();
    //     if (!$parentMenu) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Parent menu not found.',
    //         ], 404);
    //     }
    //     $innerMenus = Menu::with('children')
    //         ->where('parent_id', $parentMenu->id)
    //         ->orderBy('order', 'asc')
    //         ->get();
    //     return response()->json([
    //         'success' => true,
    //         'data' => $innerMenus,
    //     ]);
    // }


    // public function fetchInnerMenusByParentTitle(Request $request)
    // {
    //     $url = $request->query('url');
    //     $location = $request->query('location');
    //     $parentMenu =  Menu::with('children')->where(function ($q) use ($url) {
    //         $q->where('url', 'ILIKE', "%{$url}%")
    //             ->orWhere('title', 'ILIKE', "%{$url}%");
    //     })
    //         ->when($location, function ($q) use ($location) {
    //             $q->where('location', $location);
    //         })
    //         ->first();
    //     if (!$parentMenu) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Parent menu not found.',
    //         ], 404);
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'data' => $parentMenu,
    //     ]);
    // }



    public function fetchInnerMenusByParentTitle(Request $request)
    {
        $url      = $request->query('url');
        $location = $request->query('location');
        $isParent = $request->isParent;
        $cacheKey = 'menu_inner_by_title_' . md5($url . '|' . $location . '|' . ($isParent ? '1' : '0'));

        $parentMenu = Cache::remember($cacheKey, 3600, function () use ($url, $location, $isParent) {
            if ($isParent) {
                return Menu::with('children')->where(function ($q) use ($url) {
                    $q->where('title', 'ILIKE', "%{$url}%");
                })
                    ->when($location, function ($q) use ($location) {
                        $q->whereIn('location', [$location, 'inner-pages']);
                    })
                    ->first();
            } else {
                return Menu::with('children')->where(function ($q) use ($url) {
                    $q->where('url', 'ILIKE', "%{$url}%")
                        ->orWhere('title', 'ILIKE', "%{$url}%");
                })
                    ->when($location, function ($q) use ($location) {
                        $q->where('location', $location);
                    })
                    ->first();
            }
        });

        if (!$parentMenu) {
            return response()->json([
                'success' => false,
                'message' => 'Parent menu not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'data'    => $parentMenu,
        ]);
    }

    public function fetchInnerMenusByParentFullTitle(Request $request)
    {
        $url      = $request->query('url');
        $location = $request->query('location');
        $cacheKey = 'menu_inner_by_full_title_' . md5($url . '|' . $location);

        $parentMenu = Cache::remember($cacheKey, 3600, function () use ($url, $location) {
            return Menu::with('children')->where(function ($q) use ($url) {
                $q->whereRaw('LOWER(title) = ?', [strtolower($url)]);
            })
                ->when($location, function ($q) use ($location) {
                    $q->where('location', $location);
                })
                ->orderBy('id', 'desc')
                ->first();
        });

        if (!$parentMenu) {
            return response()->json([
                'success' => false,
                'message' => 'Parent menu not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'data'    => $parentMenu,
        ]);
    }


    public function fetchAllMenus()
    {
        $cacheKey = 'menu_fetch_all';

        [$menus, $lastUpdatedOn] = Cache::remember($cacheKey, 3600, function () {
            return [
                Menu::with(['children', 'location:location_code,location_name'])
                    ->orderBy('order', 'asc')
                    ->get(),
                Menu::getLastUpdatedOrCreatedAt(),
            ];
        });

        return response()->json([
            'success'       => true,
            'data'          => $menus,
            'lastUpdatedOn' => $lastUpdatedOn,
        ]);
    }
}
