<?php

namespace App\Http\Controllers\Secure;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use App\Traits\FileUploadTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeMenuController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $pageTitle = 'Employee Menu';

        // Fetch all employee menus with their children
        $menus = Menu::whereNull('parent_id')->where([
            'location' => 'employee',
        ])->with('children')->orderBy('order')->get();

        return view('secure.employee-menus.index', compact('menus', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Add New Employee Menu';
        $menus = $this->getEmployeeMenuTreeForDropdown();
        // Fetch unique permission groups
        $permissionGroups = \Spatie\Permission\Models\Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->groupBy('group')
            ->pluck('group');

        return view('secure.employee-menus.create', compact('menus', 'pageTitle', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        // Define validation rules (no 'location' rule — hardcoded to 'employee')
        $rules = [
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'url' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'icon_type' => 'required|string|in:ICON,IMAGE',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
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

            // Ensure employee menu URLs are prefixed with 'secure/' unless they are external
            if (!empty($url) && $url !== '#') {
                if (!\Illuminate\Support\Str::startsWith($url, 'http://') && !\Illuminate\Support\Str::startsWith($url, 'https://')) {
                    $trimmedUrl = ltrim($url, '/');
                    if (!\Illuminate\Support\Str::startsWith($trimmedUrl, 'secure/')) {
                        $url = 'secure/' . $trimmedUrl;
                    } else {
                        $url = $trimmedUrl;
                    }
                }
            }

            // Force location to 'employee'
            $location = 'employee';

            // Auto-generate order if not provided (next sibling order)
            $order = $request->input('order');
            if ($order === null || $order === '') {
                $parentId = $request->input('parent_id');
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
            $menu->location = $location; // Always 'employee'
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
                'message' => 'Employee Menu created successfully.',
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
                'message' => 'An error occurred while creating the employee menu.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    public function edit(Menu $menu)
    {
        $pageTitle = 'Edit Employee Menu';
        $menus = $this->getEmployeeMenuTreeForDropdown();
        // Fetch unique permission groups
        $permissionGroups = \Spatie\Permission\Models\Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->groupBy('group')
            ->pluck('group');

        return view('secure.employee-menus.edit', compact('menu', 'menus', 'pageTitle', 'permissionGroups'));
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

            // Ensure employee menu URLs are prefixed with 'secure/' unless they are external
            if (!empty($url) && $url !== '#') {
                if (!\Illuminate\Support\Str::startsWith($url, 'http://') && !\Illuminate\Support\Str::startsWith($url, 'https://')) {
                    $trimmedUrl = ltrim($url, '/');
                    if (!\Illuminate\Support\Str::startsWith($trimmedUrl, 'secure/')) {
                        $url = 'secure/' . $trimmedUrl;
                    } else {
                        $url = $trimmedUrl;
                    }
                }
            }

            // Force location to 'employee'
            $location = 'employee';

            // Auto-generate order if not provided (next sibling order)
            $order = $request->input('order');
            if ($order === null || $order === '') {
                $parentId = $request->input('parent_id');
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
            $menu->location = $location; // Always 'employee'
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
                'message' => 'Employee Menu updated successfully.',
                'data' => $menu,
                'redirect_url' => route('employee-menus.index'),
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
                'message' => 'An error occurred while updating the employee menu.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('employee-menus.index')->with('success', 'Employee Menu deleted successfully.');
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

    /**
     * Get full tree of employee menus for dropdown lists
     */
    private function getEmployeeMenuTreeForDropdown()
    {
        $flatMenus = [];

        $rootMenus = Menu::where('location', 'employee')
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
                $this->flattenMenuForDropdown($rootMenu, (string) $slNo, 0, $flatMenus);
                $slNo++;
            }
        }

        return $flatMenus;
    }

    private function flattenMenuForDropdown($menu, $prefix, $depth, &$rows)
    {
        $dashes = str_repeat('-', $depth);

        $rows[] = (object) [
            'id' => $menu->id,
            'dashes' => $dashes,
            'title' => $menu->title,
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
                $this->flattenMenuForDropdown($child, $prefix . '.' . $childIndex, $depth + 1, $rows);
                $childIndex++;
            }
        }
    }
}
