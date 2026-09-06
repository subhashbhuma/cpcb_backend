<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\DTO\UserDto;
use Illuminate\Support\Facades\Cache;

class UserService
{
    private $userDto;
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    public function findById($id)
    {
        return $this->userRepository->findById($id);
    }

    public function create(UserDto $userDto)
    {
        $user = $this->userRepository->create([
            'name' => $userDto->name,
            'email' => $userDto->email,
            'mobile_number' => $userDto->mobile_number,
            'password' => $userDto->password,
            'created_by' => $userDto->created_by,
            'updated_by' => $userDto->updated_by,
            'division_id' => $userDto->division_id,
        ]);

        if (!$user) {
            return false;
        }

        $user->force_password_change = true;
        $user->save();

        $user->passwordHistories()->create([
            'password' => \Illuminate\Support\Facades\Hash::make($userDto->password)
        ]);

        $user->assignRole($userDto->roles);

        // Sync direct user permissions (per-user menu permissions)
        if (!empty($userDto->permissions)) {
            $expandedPermissions = $this->expandPermissions($userDto->permissions);
            $user->syncPermissions($expandedPermissions);
        }

        // Flush Spatie permission cache so $user->can() checks are fresh
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Cache::forget("sidebar_menus_{$user->id}");

        return $user;
    }

    public function update(UserDto $userDto, $id)
    {
        $updateData = [
            'name' => $userDto->name,
            'email' => $userDto->email,
            'mobile_number' => $userDto->mobile_number,
            'created_by' => $userDto->created_by,
            'updated_by' => $userDto->updated_by,
            'division_id' => $userDto->division_id,
        ];

        // Only update password if provided
        if (!empty($userDto->password)) {
            $updateData['password'] = $userDto->password;
        }

        $user = $this->userRepository->update($updateData, $id);

        if (!$user) {
            return false;
        }

        $user->roles()->detach();
        $user->assignRole($userDto->roles);

        // Sync direct user permissions (per-user menu permissions)
        $expandedPermissions = $this->expandPermissions($userDto->permissions ?? []);
        $user->syncPermissions($expandedPermissions);

        // Flush Spatie permission cache so $user->can() checks are fresh
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Touch user to bust the timestamp-based sidebar cache key
        $user->touch();

        Cache::forget("sidebar_menus_{$user->id}");

        return $user;
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function resetPassword(UserDto $userDto, $id)
    {
        $user = $this->userRepository->update([
            'password' => $userDto->password,
            'updated_by' => $userDto->updated_by,
            'force_password_change' => true,
        ], $id);

        if ($user) {
            $user->passwordHistories()->create([
                'password' => \Illuminate\Support\Facades\Hash::make($userDto->password)
            ]);
            
            // Clean up old histories (keep only last 3)
            $historiesToDelete = $user->passwordHistories()
                                      ->latest()
                                      ->skip(3)
                                      ->get();
            foreach ($historiesToDelete as $history) {
                $history->delete();
            }
        }

        return $user;
    }

    /**
     * Expands menu-group permissions into their corresponding route-level permissions.
     */
    private function expandPermissions(array $permissions): array
    {
        if (empty($permissions)) {
            return [];
        }

        $map = config('route_permission_map');
        if (empty($map)) {
            return $permissions;
        }

        $expanded = $permissions;
        $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];

        // Pre-compute the mapping for action + group combinations
        $permNameMap = [];
        foreach ($map as $menuGroup => $routeEntity) {
            $menuGroupLower = strtolower(trim($menuGroup));
            $routeEntityLower = strtolower(trim($routeEntity));

            if ($menuGroupLower === $routeEntityLower) {
                continue;
            }

            foreach ($actions as $action) {
                $oldPerm = strtolower($action . ' ' . $menuGroupLower);
                $newPerm = strtolower($action . ' ' . $routeEntityLower);
                $permNameMap[$oldPerm] = $newPerm;
            }
        }

        // Add matching route permissions
        foreach ($permissions as $perm) {
            $permLower = strtolower($perm);
            if (isset($permNameMap[$permLower])) {
                $routePerm = $permNameMap[$permLower];
                if (!in_array($routePerm, $expanded)) {
                    $expanded[] = $routePerm;
                }
            }
        }

        return array_unique($expanded);
    }
}
