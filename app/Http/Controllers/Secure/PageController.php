<?php

namespace App\Http\Controllers\Secure;

use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use App\DTO\PageDto;
use App\DTO\PageFileDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Page;
use App\Models\PageFile;
use Illuminate\Http\Request;
use App\Services\MenuService;
use App\Services\PageFileService;
use App\Services\PageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PageFilesExport;
use App\Exports\PageFilesFormatExport;
use App\Imports\PageFilesImport;

class PageController extends Controller
{
    protected $menuService;
    protected $pageService;
    protected $pageFileService;
    private $accessibilityCache = [];
    private $userPermissions = null;

    public function __construct()
    {
        $this->menuService = new MenuService();
        $this->pageService = new PageService();
        $this->pageFileService = new PageFileService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Pages';
        return view('secure.pages.index', compact('pageTitle'));
    }

    /**
     * Recursively flatten a menu tree in memory.
     */
    private function flattenMenuTreeMemory($menu, $groupedMenus, $allMenusById, $prefix, $depth, $locationName, &$rows)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN');

        // Check if this menu or any descendant has a page AND the user has permission to see it
        // For ADMIN/SUPERADMIN, we want to show all menus unconditionally
        if (!$isAdmin && !$this->isMenuAccessible($menu, $groupedMenus))
            return;

        // Build parent path by walking up the parent chain (ancestors only, excluding current)
        $parentParts = [];
        $current = $allMenusById->get($menu->parent_id);
        while ($current) {
            array_unshift($parentParts, $current->title);
            $current = $current->parent_id ? $allMenusById->get($current->parent_id) : null;
        }
        $parentPath = count($parentParts) > 0 ? implode(' → ', $parentParts) : '';

        if ($menu->pages && $menu->pages->count() > 0 && ($isAdmin || $this->userHasAnyPermission($menu))) {
            foreach ($menu->pages as $index => $page) {
                $rows[] = [
                    'id' => $menu->id,
                    'sl_no' => $prefix . ($menu->pages->count() > 1 ? '.' . ($index + 1) : ''),
                    'depth' => $depth,
                    'menu_title' => $menu->title . ($menu->pages->count() > 1 ? ' (Page ' . ($index + 1) . ')' : ''),
                    'parent_path' => $parentPath,
                    'page' => $page,
                    'menu_url' => $menu->url ?? '',
                    'location_name' => $locationName,
                    'menu_obj' => $menu,
                ];
            }
        } elseif ($isAdmin && (!$menu->pages || $menu->pages->count() == 0)) {
            // Only ADMIN/SUPERADMIN can see menus that don't have pages yet
            $rows[] = [
                'id' => $menu->id,
                'sl_no' => $prefix,
                'depth' => $depth,
                'menu_title' => $menu->title,
                'parent_path' => $parentPath,
                'page' => null,
                'menu_url' => $menu->url ?? '',
                'location_name' => $locationName,
                'menu_obj' => $menu, // Store object to avoid N+1 in actions
            ];
        }

        // Always recurse into children (even if current menu has no pages)
        $children = $groupedMenus->get($menu->id) ?? collect();
        $childIndex = 1;
        foreach ($children as $child) {
            $this->flattenMenuTreeMemory($child, $groupedMenus, $allMenusById, $prefix . '.' . $childIndex, $depth + 1, $locationName, $rows);
            $childIndex++;
        }
    }

    /**
     * Check if a menu is accessible (has a page AND permission) or has an accessible descendant.
     */
    private function isMenuAccessible($menu, $groupedMenus)
    {
        if (isset($this->accessibilityCache[$menu->id])) {
            return $this->accessibilityCache[$menu->id];
        }

        // If this menu has a page, check if the user has ANY permission for this menu
        if ($menu->pages && $menu->pages->count() > 0 && $this->userHasAnyPermission($menu)) {
            return $this->accessibilityCache[$menu->id] = true;
        }

        // Check if any child is accessible
        $children = $groupedMenus->get($menu->id) ?? collect();
        foreach ($children as $child) {
            if ($this->isMenuAccessible($child, $groupedMenus)) {
                return $this->accessibilityCache[$menu->id] = true;
            }
        }

        return $this->accessibilityCache[$menu->id] = false;
    }

    /**
     * Check if a user has any permission for a menu (direct check, no recursion).
     */
    private function userHasAnyPermission($menu)
    {
        if (auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SUPERADMIN'))
            return true;

        if ($this->userPermissions === null) {
            $this->userPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();
        }

        $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];
        foreach ($actions as $action) {
            // Check against pre-fetched permissions for performance
            $permName = strtolower($action . ' ' . $menu->permission_group);
            if (in_array($permName, $this->userPermissions))
                return true;

            // Also check old permission_name if exists
            if ($menu->permission_name) {
                $oldPermName = strtolower($action . ' ' . $menu->permission_name);
                if (in_array($oldPermName, $this->userPermissions))
                    return true;
            }
        }

        return false;
    }

    /**
     * Recursively flatten a menu tree into rows with sequence numbers and depth.
     */
    private function flattenMenuTree($menu, $prefix, $depth, $locationName, &$rows)
    {
        // Build dash prefix based on depth
        $dashes = str_repeat('-', $depth);

        if ($menu->pages && $menu->pages->count() > 0) {
            foreach ($menu->pages as $index => $page) {
                $rows[] = [
                    'sl_no' => $prefix . ($menu->pages->count() > 1 ? '.' . ($index + 1) : ''),
                    'dashes' => $dashes,
                    'depth' => $depth,
                    'menu_title' => $menu->title . ($menu->pages->count() > 1 ? ' (Page ' . ($index + 1) . ')' : ''),
                    'menu_title_hi' => $menu->title_hi,
                    'page' => $page,
                    'location_name' => $locationName,
                ];
            }
        } else {
            $rows[] = [
                'sl_no' => $prefix,
                'dashes' => $dashes,
                'depth' => $depth,
                'menu_title' => $menu->title,
                'menu_title_hi' => $menu->title_hi,
                'page' => null,
                'location_name' => $locationName,
            ];
        }

        // Process children
        if ($menu->children && $menu->children->isNotEmpty()) {
            $childIndex = 1;
            foreach ($menu->children as $child) {
                if ($child->userCanView() && $this->menuHasPages($child)) {
                    $this->flattenMenuTree($child, $prefix . '.' . $childIndex, $depth + 1, $locationName, $rows);
                    $childIndex++;
                }
            }
        }
    }

    /**
     * Recursively check if a menu or any of its children have pages.
     */
    private function menuHasPages($menu)
    {
        if ($menu->pages && $menu->pages->count() > 0) {
            return true;
        }

        if ($menu->children) {
            foreach ($menu->children as $child) {
                if ($this->menuHasPages($child)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if a menu branch is accessible for a specific action.
     */
    private function isBranchAccessible($menu, $action)
    {
        if ($menu->userCan($action)) {
            return true;
        }

        if ($menu->children) {
            foreach ($menu->children as $child) {
                if ($this->isBranchAccessible($child, $action)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get full tree of menus for dropdown lists without filtering out unlinked pages
     */
    private function getMenuTreeForDropdown($action = 'view')
    {
        $locations = \App\Models\MenuLocation::all()->keyBy('location_code');
        $locationOrder = ['header', 'inner-pages', 'default', 'footer-quickLink', 'footer-importantLink', 'useful-links', 'top-header', 'importantLink', 'employee'];
        $flatMenus = [];

        foreach ($locationOrder as $locCode) {
            $location = $locations->get($locCode);
            if (!$location)
                continue;

            $rootMenus = \App\Models\Menu::where('location', $locCode)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with([
                    'children' => function ($q) {
                        $q->orderBy('order')->with('children'); // Load up to 2 levels of children cleanly
                    }
                ])
                ->get();

            if ($rootMenus->isNotEmpty()) {
                $slNo = 1;
                foreach ($rootMenus as $rootMenu) {
                    if ($this->isBranchAccessible($rootMenu, $action)) {
                        $this->flattenMenuForDropdown($rootMenu, (string) $slNo, 0, $location->location_name, $flatMenus, $action);
                        $slNo++;
                    }
                }
            }
        }

        return $flatMenus;
    }

    private function flattenMenuForDropdown($menu, $prefix, $depth, $locationName, &$rows, $action = 'view')
    {
        $dashes = str_repeat('-', $depth);

        $rows[] = (object) [
            'id' => $menu->id,
            'dashes' => $dashes,
            'title' => $menu->title,
            'location_name' => $locationName,
            'location_code' => $menu->location,
            'sl_no' => $prefix
        ];

        if ($menu->children && $menu->children->isNotEmpty()) {
            $childIndex = 1;
            foreach ($menu->children as $child) {
                if ($this->isBranchAccessible($child, $action)) {
                    // Ensure deeper children are loaded to avoid missed tree elements
                    if (!isset($child->children)) {
                        $child->load([
                            'children' => function ($q) {
                                $q->orderBy('order');
                            }
                        ]);
                    }
                    $this->flattenMenuForDropdown($child, $prefix . '.' . $childIndex, $depth + 1, $locationName, $rows, $action);
                    $childIndex++;
                }
            }
        }
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $locations = \App\Models\MenuLocation::all()->keyBy('location_code');
            $locationOrder = ['header', 'inner-pages', 'default', 'footer-quickLink', 'footer-importantLink', 'useful-links', 'top-header', 'importantLink', 'employee'];
            $flatRows = [];

            // Fetch ALL menus for ALL target locations in one query to preserve hierarchies
            $allMenus = \App\Models\Menu::whereIn('location', $locationOrder)
                ->with('pages')
                ->orderBy('order')
                ->get();

            $groupedMenus = $allMenus->groupBy('parent_id');
            $allMenusById = $allMenus->keyBy('id');
            $allMenuIds = $allMenus->pluck('id')->toArray();

            foreach ($locationOrder as $locCode) {
                $location = $locations->get($locCode);
                if (!$location)
                    continue;

                // Find root menus for this location.
                $rootMenus = $allMenus->where('location', $locCode)->filter(function ($m) use ($allMenuIds) {
                    return $m->parent_id === null || !in_array($m->parent_id, $allMenuIds);
                });

                $slNo = 1;
                foreach ($rootMenus as $rootMenu) {
                    $this->flattenMenuTreeMemory($rootMenu, $groupedMenus, $allMenusById, (string) $slNo, 0, $location->location_name, $flatRows);
                    $slNo++;
                }
            }

            return DataTables::of(collect($flatRows))
                ->addColumn('type', function ($row) {
                    $page = $row['page'];
                    if ($page) {
                        return $page->type === 'employee' ? 'Employee' : 'Website';
                    }
                    // return '<span class="text-muted">—</span>';
                    return "—";
                })
                ->addColumn('status', function ($row) {
                      $page = $row['page'];

                        if (!$page) {
                            return '<span class="text-muted">—</span>';
                        }

                       return $page
                        ? statusBadge(
                            $page->is_approved,
                            $page->is_published
                        )
                        : '<span class="text-muted">—</span>';
                                })
                ->addColumn('page_url', function ($row) {
                    $menuObj = $row['menu_obj'];
                    return $menuObj->url && $menuObj->url != '#' && $menuObj->url != '0' && $menuObj->url != '' ? '<a href="' . config('app.frontend_url') . $menuObj->url . '" target="_blank">View</a>' : '—';
                })
                ->addColumn('actions', function ($row) {
                    $page = $row['page'];
                    if (!$page)
                        // return '<span class="text-muted">—</span>';
                    return "—";

                    // Use the menu object already present in the row to avoid N+1 queries
                    $menu = $row['menu_obj'];
                    $buttons = '';
                    $user = auth()->user();

                    if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN') || ($menu && $menu->userCanView())) {
                        $buttons .= '<a href="' . route('pages.show', $page->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN') || ($menu && $menu->userCan('edit'))) {
                        $buttons .= '<a href="' . route('pages.edit', $page->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN') || ($menu && $menu->userCan('delete'))) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-page" data-id="' . $page->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                    }

                    return $buttons;
                })
                ->editColumn('menu_title', function ($row) {
                    $hasPage = $row['page'] != null;
                    $class = $hasPage ? 'fw-medium d-block text-primary' : 'text-muted fst-italic';
                    $html = '<div>';
                    if (!empty($row['parent_path'])) {
                        $html .= '<small class="fw-medium">' . e($row['parent_path']) . '</small> ';
                    }
                    $html .= '<span class="' . $class . '">' . e($row['menu_title']) . '</span>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['status', 'page_url', 'actions', 'menu_title'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Page';
        $menus = $this->getMenuTreeForDropdown('add');
        return view('secure.pages.create', compact('pageTitle', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePageRequest $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('ADMIN') && !$user->hasRole('SUPERADMIN')) {
            $menu = \App\Models\Menu::find($request->input('menu_id'));
            if (!$menu || !$menu->userCan('add')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to add content to this menu.'
                ], 403);
            }
        }

        DB::beginTransaction();
        try {
            // Create a PageDto instance with validated request data
            $pageDto = new PageDto(
                $request->input('menu_id'),
                $request->input('type', 'website'),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                Purifier::clean(html_entity_decode($request->input('content'))) ?? null,
                Purifier::clean(html_entity_decode($request->input('content_hi'))) ?? null,
                $request->file('featured_image'),
                0,
                0,
                $request->input('default_menu', 0),
                null,
                $request->input('publish_remark'),
                auth()->user()->id,
                auth()->user()->id
            );

            // Use the PageService to create a new page
            $page = $this->pageService->create($pageDto);

            if (!$page) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                    'data' => $page
                ], 500);
            }

            // Add the page files
            $fileCountArray = json_decode($request->input('fileCountArray', '[]'), true);
            if (is_array($fileCountArray) && count($fileCountArray) > 0) {
                foreach ($fileCountArray as $key => $count) {
                    $pageFileDto = new PageFileDto(
                        $page->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        strip_tags($request->input('title_' . $count)) ?? null,
                        strip_tags($request->input('title_hi_' . $count)) ?? null,
                        auth()->user()->id,
                        auth()->user()->id,
                        $request->input('upload_date_' . $count) ?? date('Y-m-d'),
                        strip_tags($request->input('order_number_' . $count)) ?? 0
                    );
                    $result = $this->pageFileService->create($pageFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while saving page files.',
                            'data' => $result
                        ], 500);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'page_id' => $page->id,
                'redirect_url' => route('pages.index'),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Page creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            DB::rollBack();

            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Page';
        $page = $this->pageService->findById($id);
        return view('secure.pages.show', compact('page', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $user = auth()->user();
        if (!$user->hasRole('ADMIN') && !$user->hasRole('SUPERADMIN')) {
            if (!$page->menu || !$page->menu->userCan('edit')) {
                abort(403, 'You do not have permission to edit this page.');
            }
        }

        $pageTitle = 'Edit Page';
        $menus = $this->getMenuTreeForDropdown('edit');
        $page = $this->pageService->findById($page->id);
        return view('secure.pages.edit', compact('pageTitle', 'menus', 'page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $user = auth()->user();
        if (!$user->hasRole('ADMIN') && !$user->hasRole('SUPERADMIN')) {
            if (!$page->menu || !$page->menu->userCan('edit')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to edit this page.'
                ], 403);
            }
        }

        DB::beginTransaction();
        try {
            // Create a PageDto instance with validated request data
            $pageDto = new PageDto(
                $request->input('menu_id'),
                $request->input('type', 'website'),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                Purifier::clean(html_entity_decode($request->input('content'))) ?? null,
                Purifier::clean(html_entity_decode($request->input('content_hi'))) ?? null,
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                0,
                0,
                $request->input('default_menu', 0),
                $page->remarks,
                null,
                $page->created_by,
                auth()->user()->id
            );

            // Use the PageService to create a new page
            $page = $this->pageService->update($pageDto, $page->id);

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                ], 500);
            }

            // Manually update the updated_at timestamp if provided
            if ($request->filled('updated_at')) {
                $page->updated_at = $request->input('updated_at');
                $page->save();
            }

            // Add the page files
            $fileCountArray = json_decode($request->input('fileCountArray', '[]'), true);
            if (is_array($fileCountArray) && count($fileCountArray) > 0) {
                foreach ($fileCountArray as $key => $count) {
                    $pageFileDto = new PageFileDto(
                        $page->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        strip_tags($request->input('title_' . $count)) ?? null,
                        strip_tags($request->input('title_hi_' . $count)) ?? null,
                        $page->created_by,
                        auth()->user()->id,
                        $request->input('upload_date_' . $count) ?? date('Y-m-d'),
                        $request->input('order_number_' . $count) ?? 0
                    );
                    $result = $this->pageFileService->create($pageFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while saving page files.',
                        ], 500);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully!',
                'data' => $page,
            ], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Page updation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            DB::rollBack();

            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        DB::beginTransaction();
        try {
            $result = $this->pageService->delete($page->id);
            if (!$result) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting page.',
                ], 500);
            }

            // Delete files
            $files = $page->files;
            foreach ($files as $file) {
                $result = $this->pageFileService->delete($file->id);
                if (!$result) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error while deleting page files.',
                    ], 500);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Page deleted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyFile(PageFile $pageFile)
    {
        try {
            $result = $this->pageFileService->delete($pageFile->id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting page.',
                ], 500);
            }

            return response()->json(['message' => 'Page file deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function updateFile(Request $request, PageFile $pageFile)
    {
        DB::beginTransaction();
        try {
            // Validate incoming request
            $validated = [
                'upload_date' => 'nullable|date',
                'file_name' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
                'file_name_hi' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
                'title' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'"\/@#\$%?!\+=[\]\*]*$/u',
                'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'"\/@#\$%?!\+=[\]\*]*$/u',
                'order_number' => 'nullable|integer|min:0',
            ];

            $validator = Validator::make($request->all(), $validated);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create DTO with existing values as fallback
            $pageFileDto = new PageFileDto(
                $pageFile->page_id,
                $request->hasFile('file_name') ? $request->file('file_name') : $pageFile->file_name,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : $pageFile->file_name_hi,
                $request->filled('title') ? strip_tags($request->input('title')) : $pageFile->title,
                $request->filled('title_hi') ? strip_tags($request->input('title_hi')) : $pageFile->title_hi,
                $pageFile->created_by,
                auth()->user()->id,
                $request->filled('upload_date') ? $request->input('upload_date') : $pageFile->upload_date,
                $request->filled('order_number') ? $request->input('order_number') : ($pageFile->order_number ?? 0)
            );

            // Use existing service update method
            $result = $this->pageFileService->update($pageFileDto, $pageFile->id);

            if (!$result) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating page file.',
                ], 500);
            }

            // Update additional fields not in DTO
            if ($request->filled('updated_date')) {
                $pageFile->updated_at = $request->input('updated_date');
                $pageFile->save();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'File updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page file update failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function approve(ApproveRequest $request, Page $page)
    {
        $user = auth()->user();
        if (!$user->hasRole('ADMIN') && !$user->hasRole('SUPERADMIN')) {
            if (!$page->menu || !$page->menu->userCan('approve')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to approve content for this menu.'
                ], 403);
            }
        }
        try {
            $pageDto = new PageDto(
                $page->menu_id,
                $page->type,
                $page->title,
                $page->title_hi,
                $page->content,
                $page->content_hi,
                $page->featured_image,
                $request->input('is_approved'),
                0,
                $page->default_menu,
                strip_tags($request->input('remarks')) ?? null,
                $page->publish_remark,
                $page->created_by,
                auth()->user()->id
            );

            $updated = $this->pageService->approve($pageDto, $page->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving page.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Page approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Page $page)
    {
        $user = auth()->user();
        if (!$user->hasRole('ADMIN') && !$user->hasRole('SUPERADMIN')) {
            if (!$page->menu || !$page->menu->userCan('publish')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to publish content for this menu.'
                ], 403);
            }
        }
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $page->is_approved == 1 || $isPublished == 1 ? 1 : $page->is_approved;
            $remarks = $page->is_approved == 1
                ? $page->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $page->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;
            $pageDto = new PageDto(
                $page->menu_id,
                $page->type,
                $page->title,
                $page->title_hi,
                $page->content,
                $page->content_hi,
                $page->featured_image,
                $isApproved,
                $isPublished,
                $page->default_menu,
                $remarks,
                $publishRemark,
                $page->created_by,
                auth()->id()
            );

            $updated = $this->pageService->publish($pageDto, $page->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing page.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Page publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllPages()
    {
        try {
            $pages = $this->pageService->findAllForPublic();
            return response()->json([
                'success' => true,
                'data' => $pages
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetching all pages failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching pages.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function findByUrlForPublic($url)
    {
        try {
            $pages = $this->pageService->findByUrlForPublic($url);
            return response()->json([
                'success' => true,
                'data' => $pages
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetching all pages failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching pages.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
    public function findByMenuForPublic(Request $request)
    {
        try {
            $url = $request->query('url');
            $pages = $this->pageService->findByMenuUrlForPublic($url);
            return response()->json([
                'success' => true,
                'data' => $pages
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetching all pages failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching pages.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Export page files to Excel.
     */
    public function exportFiles(Page $page)
    {
        try {
            $fileName = 'page_' . $page->id . '_files_' . date('Y-m-d_His') . '.xlsx';
            return Excel::download(new PageFilesExport($page->id), $fileName);
        } catch (\Exception $e) {
            Log::error('Page files export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export page files.');
        }
    }

    /**
     * Import page files metadata from Excel.
     */
    public function importFiles(Request $request, Page $page)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $import = new PageFilesImport($page->id);
            Excel::import($import, $request->file('import_file'));

            $updatedCount = $import->getUpdatedCount();
            $insertedCount = $import->getInsertedCount();
            $skippedCount = $import->getSkippedCount();

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$insertedCount} record(s) added, {$updatedCount} record(s) updated, {$skippedCount} row(s) skipped.",
            ]);
        } catch (\Exception $e) {
            Log::error('Page files import failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download blank Excel template for page files import.
     */
    public function downloadFilesTemplate()
    {
        return Excel::download(new PageFilesFormatExport, 'page_files_import_template.xlsx');
    }
}
