<?php

namespace App\Http\Controllers\Secure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Roles";
        $roles = Role::with('permissions')->get();
        return view('secure.roles.index', compact('pageTitle', 'roles'));
    }

    public function fetchRolesForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $totalPermissions = Permission::count();
            $currentRole = Auth::user()->roles->first()->name;
            $roles = Role::with('permissions')->
                where(function ($query) use ($currentRole) {
                    if ($currentRole == 'SUPERADMIN') {
                        $query->where('name', '!=', 'SUPERADMIN');
                    } else {
                        $query->where('name', '!=', 'ADMIN');
                        $query->where('name', '!=', 'SUPERADMIN');
                    }
                })
                ->select('id', 'name', 'landing_page_url');


            return DataTables::of($roles)
                ->addColumn('permissions', function ($role) use ($totalPermissions) {

                    $rolePermissions = $role->permissions->pluck('name')->toArray();
                    $rolePermissionsCount = count($rolePermissions);
                    if ($rolePermissionsCount === $totalPermissions && $totalPermissions > 0) {
                        return '<span class="badge bg-success rounded-pill">Full Permissions</span>';
                    }
                    if ($rolePermissionsCount < 1) {
                        return '<span class="badge bg-danger rounded-pill">No Permissions</span>';
                    }

                    $html = '
    <div class="dropdown">
        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
            Permissions
        </button>

        <div class="dropdown-menu p-2 permission-menu">

            <input type="text" class="form-control form-control-sm mb-2 permission-search" placeholder="Search...">

            <div class="permission-list">';

                    foreach ($rolePermissions as $permission) {
                        $html .= '<div class="permission-item" data-name="' . strtolower($permission) . '">'
                            . ucfirst($permission) .
                            '</div>';
                    }

                    $html .= '</div></div></div>';

                    return $html;
                })
                ->addColumn('action', function ($role) {

                    $button = '';
                    if (auth()->user()->can('view role')) {
                        $button .= '<a href="' . route('roles.show', $role->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit role')) {
                        $button .= '<a href="' . route('roles.edit', $role->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete role')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-role" data-id="' . $role->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;


                    return '<a href="' . route('roles.edit', $role->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a>
                    <button class="btn btn-sm btn-danger delete-role" data-id="' . $role->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = "Create Role";
        $permissionGroups = Permission::all()->groupBy('group'); // Group by stored category
        return view('secure.roles.create', compact('permissionGroups', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            // Custom validation messages
            $messages = [
                'name.required' => 'Role name is required.',
                'name.unique' => 'Role name must be unique.',
                'name.min' => 'Role name must be at least 3 characters.',
            ];

            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name|min:3|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
                'permissions' => 'array|required',
                'landing_page_url' => 'nullable|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            ], $messages);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create role and assign permissions
            $role = Role::create([
                'name' => $request->name,
                'landing_page_url' => $request->landing_page_url
            ]);
            $role->syncPermissions($request->permissions);
            \Illuminate\Support\Facades\Cache::flush();

            return response()->json(['message' => 'Role created successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error in creating role: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred. Please try again later.' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions;
        $permissionGroups = $rolePermissions->groupBy('group');
        $pageTitle = "View Role";

        return view('secure.roles.show', compact('role', 'rolePermissions', 'permissionGroups', 'pageTitle'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissionGroups = Permission::all()->groupBy(function ($item) {
            return ucfirst(explode(' ', $item->name)[1] ?? 'Miscellaneous');
        });
        $rolePermissions = $role->permissions()->pluck('name')->toArray();

        return view('secure.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'))->with('pageTitle', 'Edit Role');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {


            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u|unique:roles,name,' . $role->id,
                'landing_page_url' => 'nullable|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $role->update([
                'name' => $request->name,
                'landing_page_url' => $request->landing_page_url
            ]);
            $role->syncPermissions($request->permissions);
            \Illuminate\Support\Facades\Cache::flush();

            return response()->json(['message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete(); // Soft Delete

            return response()->json(['message' => 'Role moved to trash successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }
}
