<?php

namespace App\Http\Controllers\Secure;

use App\DTO\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Menu;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Users';
        return view('secure.users.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->userService->findAll();
            return DataTables::of($users)
                ->addColumn('division', function ($user) {
                    return $user->division ? $user->division->title : 'N/A';
                })
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })
                ->orderColumn('roles.name', function ($query, $order) {
                    $query->orderBy(
                        \Illuminate\Support\Facades\DB::table('roles')
                            ->select('name')
                            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                            ->whereColumn('model_has_roles.model_id', 'users.id')
                            ->where('model_has_roles.model_type', \App\Models\User::class)
                            ->limit(1),
                        $order
                    );
                })
                ->orderColumn('division.title', function ($query, $order) {
                    $query->orderBy(
                        \App\Models\Division::select('title')
                            ->whereColumn('divisions.id', 'users.division_id')
                            ->limit(1),
                        $order
                    );
                })
                ->addColumn('action', function ($user) {
                    $button = '';

                      if (Auth::user()->hasRole('ADMIN') || Auth::user()->hasRole('SUPERADMIN') ) {
                            $button .= '<button class="btn btn-sm btn-primary btn-reset-password" data-id="' . $user->id . '" title="Reset Password">
                                <i class="fa fa-sync"></i>
                            </button> ';   
                    }

                    if (Auth::user()->hasRole('SUPERADMIN')) {
                        $button .= '<button class="btn btn-sm btn-success btn-unlock-account" data-id="' . $user->id . '" title="Unlock Account">
                            <i class="fa fa-unlock"></i>
                        </button> ';
                    }

                    if (auth()->user()->can('edit user')) {
                        $button .= '<a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete user')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Users';
        $currentRole = Auth::user()?->roles?->first()?->name ?? 'SUPERADMIN';

        $roles = Role::where(function ($query) use ($currentRole) {
            if ($currentRole == 'SUPERADMIN') {
                $query->where('name', '!=', 'SUPERADMIN');
            } else {
                $query->where('name', '!=', 'ADMIN');
                $query->where('name', '!=', 'SUPERADMIN');
            }
            $query->where('name', '!=', 'EMPLOYEE');
        })->get();

        [$sidebarGroups, $pageGroups, $permissionGroups] = $this->getMenuMatrixData();
        $divisions = Division::where('is_new', 1)->get(['id', 'title']);

        return view('secure.users.create', compact(
            'pageTitle',
            'roles',
            'sidebarGroups',
            'pageGroups',
            'permissionGroups',
            'divisions'
        ));
    }

    protected function flattenMenusForMatrix($menu, $prefix, $depth, &$result, $excludeGroups = [], &$seenGroups = [], $visitedIds = [])
    {
        if (!$menu || in_array($menu->id, $visitedIds) || $depth > 20) {
            return;
        }
        $visitedIds[] = $menu->id;

        // Skip if this menu's permission group is in the exclude list (singular/plural aware)
        if ($menu->permission_group && $this->isExcludedGroup($menu->permission_group, $excludeGroups)) {
            return;
        }

        // Deduplicate menus that share the exact same permission group,
        // so they don't appear multiple times in the matrix and cause cascading selections.
        if ($menu->permission_group) {
            $key = strtolower(trim($menu->permission_group));
            if (isset($seenGroups[$key])) {
                return;
            }
            $seenGroups[$key] = true;
        }

        // Add current menu
        $menu->slNo = $prefix;
        $menu->depth = $depth;
        $result[] = $menu;

        $children = isset($menu->custom_children) ? $menu->custom_children : $menu->children;
        if ($children && $children->isNotEmpty()) {
            $childIndex = 1;
            foreach ($children as $child) {
                $this->flattenMenusForMatrix($child, $prefix . '.' . $childIndex, $depth + 1, $result, $excludeGroups, $seenGroups, $visitedIds);
                $childIndex++;
            }
        }
    }

    private function isExcludedGroup($groupName, array $excludeGroups): bool
    {
        $groupName = strtolower(trim($groupName));
        if (in_array($groupName, $excludeGroups)) {
            return true;
        }

        // Check singular/plural variants
        // e.g. 'publications' -> 'publication', 'faqs' -> 'faq', 'annual reports' -> 'annual report'
        $variants = [];
        if (str_ends_with($groupName, 's')) {
            $variants[] = substr($groupName, 0, -1);
        } else {
            $variants[] = $groupName . 's';
        }

        // e.g. 'epr portal' <-> 'epr portals'
        if ($groupName === 'epr portal') {
            $variants[] = 'epr portals';
        } elseif ($groupName === 'epr portals') {
            $variants[] = 'epr portal';
        }

        foreach ($variants as $variant) {
            if (in_array($variant, $excludeGroups)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Check if a menu or any of its descendants have pages created.
     */
    protected function menuHasPages($menu, $visited = []): bool
    {
        if (!$menu || in_array($menu->id, $visited)) {
            return false;
        }
        $visited[] = $menu->id;

        if ($menu->pages && $menu->pages->isNotEmpty()) {
            return true;
        }

        $children = isset($menu->custom_children) ? $menu->custom_children : $menu->children;
        if ($children && $children->isNotEmpty()) {
            foreach ($children as $child) {
                if ($this->menuHasPages($child, $visited)) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {

            $userDto = new UserDto(
                $request->input('name'),
                $request->input('email'),
                $request->input('mobile_number'),
                'Password@123',
                $request->input('roles'),
                $request->input('permissions', []),
                auth()->user()->id,
                auth()->user()->id,
                $request->input('division_id')
            );

            $user = $this->userService->create($userDto);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving user.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!'
            ], 201);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            Log::error('Error while saving user: ' . $msg);
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->userService->findById($id);
        if (!$user) {
            abort(404, 'User not found.');
        }

        $currentRole = Auth::user()?->roles?->first()?->name ?? 'SUPERADMIN';

        $roles = Role::where(function ($query) use ($currentRole) {
            if ($currentRole == 'SUPERADMIN') {
                $query->where('name', '!=', 'SUPERADMIN');
            } else {
                $query->where('name', '!=', 'ADMIN');
                $query->where('name', '!=', 'SUPERADMIN');
            }
            $query->where('name', '!=', 'EMPLOYEE');
        })->get();

        $userRoles = $user->roles ? $user->roles->pluck('name')->toArray() : [];
        $userPermissions = method_exists($user, 'getDirectPermissions')
            ? $user->getDirectPermissions()->pluck('name')->toArray()
            : [];

        [$sidebarGroups, $pageGroups, $permissionGroups] = $this->getMenuMatrixData();
        $divisions = Division::where('is_new', 1)->get(['id', 'title']);

        return view('secure.users.edit', compact(
            'user',
            'roles',
            'userRoles',
            'userPermissions',
            'sidebarGroups',
            'pageGroups',
            'permissionGroups',
            'divisions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $userDto = new UserDto(
                $request->input('name'),
                $request->input('email'),
                $request->input('mobile_number'),
                '',
                $request->input('roles'),
                $request->input('permissions', []),
                $user->created_by,
                auth()->user()->id,
                $request->input('division_id')
            );
            $user = $this->userService->update($userDto, $user->id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating user.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            Log::error('Error while updating user: ' . $msg . ' | ' . $e->getTraceAsString());
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->userService->delete($id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting user.',
                ], 500);
            }

            return response()->json(['message' => 'User moved to trash successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }


     public function resetPassword(User $user)
    {
        try {
            $userDto = new UserDto(
                $user->name,
                $user->email,
                $user->mobile_number,
                Hash::make(app('config')->get('app.default_password')),  
                $user->getRoleNames()->toArray(), 
                $user->permissions,
                $user->created_by,
                auth()->id()
            );

            $user = $this->userService->resetPassword($userDto, $user->id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while reseting user password.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'User password reseted successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unlockAccount(User $user)
    {
        try {
            if (!Auth::user()->hasRole('SUPERADMIN')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }

            $user->update([
                'lockout_until' => null,
                'failed_logins' => 0,
                'current_session_id' => null,
                'session_id' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User account unlocked successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build sidebar and page menu groups along with their permission groups for the permission matrix.
     */
    protected function getMenuMatrixData(): array
    {
        // 1. Fetch all menus and pages without triggering the recursive 'children' relation
        $allMenus = Menu::without('children')->with('pages')->get();

        // 2. Build the tree manually to avoid Eloquent infinite recursion on circular DB data
        $menusById = [];
        foreach ($allMenus as $menu) {
            $menu->custom_children = collect();
            $menusById[$menu->id] = $menu;
        }

        foreach ($allMenus as $menu) {
            if ($menu->parent_id && isset($menusById[$menu->parent_id])) {
                // Ignore self-referencing circles
                if ($menu->id !== $menu->parent_id) {
                    $menusById[$menu->parent_id]->custom_children->push($menu);
                }
            }
        }

        // 1. Sidebar Menus
        $sidebarRootMenus = $allMenus->where('location', 'sidebar')
            ->where('parent_id', null)
            ->sortBy('order');

        $sidebarGroups = [];
        $sNo = 1;
        $seenSidebarGroups = [];
        foreach ($sidebarRootMenus as $root) {
            $flattened = [];
            $this->flattenMenusForMatrix($root, (string) $sNo, 0, $flattened, [], $seenSidebarGroups);
            $sidebarGroups[] = [
                'root' => $root,
                'menus' => $flattened
            ];
            $sNo++;
        }

        // 2. Page Menus - Only show menus that have pages created (self or any descendant)
        $pageRootMenus = $allMenus->where('location', '!=', 'sidebar')
            ->where('parent_id', null)
            ->filter(fn($menu) => $this->menuHasPages($menu))
            ->sortBy(function ($menu) {
                return ($menu->location == 'header' ? '0' : '1') . $menu->location . str_pad($menu->order, 5, '0', STR_PAD_LEFT);
            });

        $pageGroups = [];
        $pNo = 1;
        // Extract all sidebar permission groups to exclude from Page Menus
        $sidebarMenusList = collect($sidebarGroups)->pluck('menus')->flatten();
        $sidebarPermissionGroups = $sidebarMenusList->pluck('permission_group')
            ->filter()
            ->map(fn($g) => strtolower(trim($g)))
            ->unique()
            ->toArray();

        $seenPageGroups = [];
        foreach ($pageRootMenus as $root) {
            $flattened = [];
            $this->flattenMenusForMatrix($root, (string) $pNo, 0, $flattened, $sidebarPermissionGroups, $seenPageGroups);
            if (!empty($flattened)) {
                $pageGroups[] = [
                    'root' => $root,
                    'menus' => $flattened
                ];
                $pNo++;
            }
        }

        // Get permissions grouped for all displayed menus
        $permissionGroups = $this->getPermissionGroupsForMenus($sidebarGroups, $pageGroups);

        return [$sidebarGroups, $pageGroups, $permissionGroups];
    }

    /**
     * Resiliently fetch and group permissions for displayed menus.
     * Ensures all 6 CRUD/workflow permissions exist and have proper grouping.
     */
    protected function getPermissionGroupsForMenus($sidebarGroups, $pageGroups)
    {
        $allDisplayedMenus = collect($sidebarGroups)->pluck('menus')->flatten()
            ->merge(collect($pageGroups)->pluck('menus')->flatten());

        $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];

        // Get all relevant permission groups
        $relevantGroups = $allDisplayedMenus->map(function ($menu) {
            return $menu->permission_group ?: $menu->title;
        })
            ->unique()
            ->filter()
            ->values();

        // 1 single query to fetch all permissions
        $existingPermissions = Permission::whereIn('guard_name', ['web', 'api'])
            ->get()
            ->keyBy(fn($p) => strtolower(trim($p->name)));

        $toInsert = [];
        $now = now();

        foreach ($allDisplayedMenus as $menu) {
            if ($menu->is_caption) {
                continue;
            }
            $group = $menu->permission_group ?: $menu->title;
            if (empty($group)) {
                continue;
            }

            foreach ($actions as $action) {
                $permName = strtolower($action . ' ' . $group);
                if (!$existingPermissions->has($permName)) {
                    $toInsert[] = [
                        'name' => $permName,
                        'guard_name' => 'web',
                        'group' => $group,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($toInsert)) {
            // Bulk insert missing permissions in 1 query
            Permission::insertOrIgnore($toInsert);
            $existingPermissions = Permission::whereIn('guard_name', ['web', 'api'])
                ->get()
                ->keyBy(fn($p) => strtolower(trim($p->name)));
        }

        $permissionGroups = collect();
        foreach ($allDisplayedMenus as $menu) {
            if ($menu->is_caption) {
                continue;
            }
            $group = strtolower(trim($menu->permission_group ?: $menu->title));
            if (empty($group)) {
                continue;
            }

            if (!$permissionGroups->has($group)) {
                $permissionGroups->put($group, collect());
            }

            foreach ($actions as $action) {
                $permName = strtolower($action . ' ' . $group);
                if ($existingPermissions->has($permName)) {
                    // Check if it's already in the collection to prevent duplicates if multiple menus share a group
                    $alreadyAdded = $permissionGroups[$group]->contains('name', $existingPermissions[$permName]->name);
                    if (!$alreadyAdded) {
                        $permissionGroups[$group]->push($existingPermissions[$permName]);
                    }
                }
            }
        }

        return $permissionGroups;
    }
}
