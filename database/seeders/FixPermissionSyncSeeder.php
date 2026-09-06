<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

/**
 * FixPermissionSyncSeeder
 *
 * Fixes the permission mismatch between menu-generated permission names
 * and route middleware permission names for ALL users and roles.
 *
 * Problem: Routes use `can:view tender` but the menu permission_group "Tender Setup"
 *          generates `view tender setup`. When admins assign permissions via the
 *          User Permission Matrix, users get `view tender setup` but routes expect
 *          `view tender` → 403 Forbidden.
 *
 * Solution: For every menu-group-style permission a user/role has, also assign
 *           the corresponding route-level permission using the mapping from
 *           config/route_permission_map.php.
 *
 * Run: php artisan db:seed --class=FixPermissionSyncSeeder
 */
class FixPermissionSyncSeeder extends Seeder
{
    /**
     * Standard actions used by the permission system.
     */
    private array $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];

    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════╗');
        $this->command->info('║   Fix Permission Sync Seeder                ║');
        $this->command->info('║   Aligning menu permissions with routes     ║');
        $this->command->info('╚══════════════════════════════════════════════╝');
        $this->command->info('');

        // Load the mapping config
        $map = config('route_permission_map');
        if (empty($map)) {
            $this->command->error('❌ config/route_permission_map.php is empty or not found!');
            return;
        }

        $this->command->info("Loaded " . count($map) . " permission group → route mappings.");
        $this->command->info('');

        // ─── Step 1: Ensure all route permissions exist in the DB ───────────
        $this->command->info('── Step 1: Ensuring route permissions exist in DB ──');
        $routePermsCreated = 0;

        // Get all unique route entities from the map values
        $routeEntities = array_unique(array_values($map));

        foreach ($routeEntities as $routeEntity) {
            foreach ($this->actions as $action) {
                $permName = strtolower($action . ' ' . $routeEntity);

                // Find or create. Use the route entity name (Title Case) as the group
                // so it appears organized in the permission table
                $perm = Permission::where('name', $permName)->where('guard_name', 'web')->first();
                if (!$perm) {
                    Permission::create([
                        'name'       => $permName,
                        'guard_name' => 'web',
                        'group'      => ucwords($routeEntity),
                    ]);
                    $routePermsCreated++;
                }
            }
        }

        $this->command->info("   Created {$routePermsCreated} missing route permissions.");
        $this->command->info('');

        // ─── Step 2: Build the full permission name mapping ─────────────────
        // For each menu group → route entity mapping, create the permission name mapping
        // e.g., 'view tender setup' => 'view tender'
        $permNameMap = []; // old_perm_name => new_perm_name
        foreach ($map as $menuGroup => $routeEntity) {
            $menuGroupLower = strtolower(trim($menuGroup));
            $routeEntityLower = strtolower(trim($routeEntity));

            // Skip if they're the same (no mapping needed)
            if ($menuGroupLower === $routeEntityLower) {
                continue;
            }

            foreach ($this->actions as $action) {
                $oldPerm = strtolower($action . ' ' . $menuGroupLower);
                $newPerm = strtolower($action . ' ' . $routeEntityLower);
                $permNameMap[$oldPerm] = $newPerm;
            }
        }

        $this->command->info('── Step 2: Permission name mappings ──');
        $this->command->info("   Found " . count($permNameMap) . " permission name mappings.");

        // Show a sample
        $sample = array_slice($permNameMap, 0, 5, true);
        foreach ($sample as $old => $new) {
            $this->command->info("   Example: '{$old}' → '{$new}'");
        }
        $this->command->info('');

        // ─── Step 3: Sync permissions for ALL users ─────────────────────────
        $this->command->info('── Step 3: Syncing permissions for all users ──');

        $users = User::with('permissions', 'roles')->get();
        $usersFixed = 0;
        $permsAdded = 0;

        foreach ($users as $user) {
            // Skip ADMIN and SUPERADMIN (they bypass permission checks)
            if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
                continue;
            }

            $directPerms = $user->getDirectPermissions()->pluck('name')->toArray();
            $missingPerms = [];

            foreach ($directPerms as $permName) {
                $permNameLower = strtolower($permName);

                // Check if this permission has a mapping to a route permission
                if (isset($permNameMap[$permNameLower])) {
                    $routePerm = $permNameMap[$permNameLower];

                    // If user doesn't already have the route permission, add it
                    if (!in_array($routePerm, $directPerms) && !in_array($routePerm, $missingPerms)) {
                        $missingPerms[] = $routePerm;
                    }
                }
            }

            if (!empty($missingPerms)) {
                // Give additional permissions (don't remove existing ones)
                foreach ($missingPerms as $mp) {
                    $permModel = Permission::where('name', $mp)->where('guard_name', 'web')->first();
                    if ($permModel && !$user->hasDirectPermission($permModel)) {
                        $user->givePermissionTo($permModel);
                        $permsAdded++;
                    }
                }
                $usersFixed++;
                $this->command->info("   ✓ User '{$user->email}' — added " . count($missingPerms) . " route permissions");
            }
        }

        $this->command->info("   Fixed {$usersFixed} users, added {$permsAdded} permissions total.");
        $this->command->info('');

        // ─── Step 4: Sync permissions for ALL roles ─────────────────────────
        $this->command->info('── Step 4: Syncing permissions for all roles ──');

        $roles = Role::with('permissions')->get();
        $rolesFixed = 0;
        $rolePermsAdded = 0;

        foreach ($roles as $role) {
            // Skip ADMIN/SUPERADMIN roles
            if (in_array($role->name, ['ADMIN', 'SUPERADMIN'])) {
                continue;
            }

            $rolePerms = $role->permissions->pluck('name')->toArray();
            $missingPerms = [];

            foreach ($rolePerms as $permName) {
                $permNameLower = strtolower($permName);

                if (isset($permNameMap[$permNameLower])) {
                    $routePerm = $permNameMap[$permNameLower];

                    if (!in_array($routePerm, $rolePerms) && !in_array($routePerm, $missingPerms)) {
                        $missingPerms[] = $routePerm;
                    }
                }
            }

            if (!empty($missingPerms)) {
                foreach ($missingPerms as $mp) {
                    $permModel = Permission::where('name', $mp)->where('guard_name', 'web')->first();
                    if ($permModel) {
                        $role->givePermissionTo($permModel);
                        $rolePermsAdded++;
                    }
                }
                $rolesFixed++;
                $this->command->info("   ✓ Role '{$role->name}' — added " . count($missingPerms) . " route permissions");
            }
        }

        $this->command->info("   Fixed {$rolesFixed} roles, added {$rolePermsAdded} permissions total.");
        $this->command->info('');

        // ─── Step 5: Clear Spatie cache ─────────────────────────────────────
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        \Illuminate\Support\Facades\Cache::flush();

        $this->command->info('── Step 5: Cache cleared ──');
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════╗');
        $this->command->info('║   ✅ Permission sync complete!               ║');
        $this->command->info('╚══════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->info("Summary:");
        $this->command->info("  • Route permissions created: {$routePermsCreated}");
        $this->command->info("  • Users fixed: {$usersFixed} ({$permsAdded} permissions added)");
        $this->command->info("  • Roles fixed: {$rolesFixed} ({$rolePermsAdded} permissions added)");
        $this->command->info('');
    }
}
