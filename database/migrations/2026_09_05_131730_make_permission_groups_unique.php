<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear caches first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Illuminate\Support\Facades\Cache::flush();

        // Find all duplicate permission_groups in menus
        $duplicates = DB::table('menus')
            ->whereNotNull('permission_group')
            ->whereNull('deleted_at')
            ->select('permission_group', DB::raw('count(*) as cnt'))
            ->groupBy('permission_group')
            ->having(DB::raw('count(*)'), '>', 1)
            ->get();

        $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];

        foreach ($duplicates as $duplicate) {
            $oldGroup = $duplicate->permission_group;
            Log::info("Migrating duplicate permission group: " . $oldGroup);
            
            // Get all menus sharing this group
            $menus = Menu::where('permission_group', $oldGroup)->orderBy('id')->get();

            // Fetch old permission models
            $oldPermModels = [];
            foreach ($actions as $action) {
                $oldPermName = strtolower($action . ' ' . $oldGroup);
                $oldPermModel = Permission::where('name', $oldPermName)->first();
                if ($oldPermModel) {
                    $oldPermModels[$action] = $oldPermModel;
                }
            }

            foreach ($menus as $index => $menu) {
                // Generate a unique permission group using our new helper
                $newGroup = Menu::buildUniquePermissionGroup($menu);
                
                // If the auto-generated newGroup is exactly the same as oldGroup, we still have a problem.
                // In that case, keep the first one as-is, and append (ID) to others.
                if (strtolower($newGroup) === strtolower($oldGroup)) {
                    if ($index === 0) {
                        continue; // Keep the original one for the very first menu
                    }
                    $newGroup = $newGroup . ' (Zone ' . $menu->id . ')';
                }

                // If somehow it's still duplicate in the new scheme, append ID
                $exists = Menu::where('permission_group', $newGroup)->where('id', '!=', $menu->id)->exists();
                if ($exists) {
                    $newGroup = $newGroup . ' (' . $menu->id . ')';
                }

                Log::info("  - Menu ID {$menu->id}: Renaming group to '{$newGroup}'");

                // Update the menu (saveQuietly to avoid triggering saved events which do more cache flushes)
                $menu->permission_group = $newGroup;
                $menu->saveQuietly();

                // Create new permissions and migrate old assignees
                foreach ($actions as $action) {
                    $newPermName = strtolower($action . ' ' . $newGroup);
                    
                    $newPerm = Permission::updateOrCreate(
                        ['name' => $newPermName, 'guard_name' => 'web'],
                        ['group' => $newGroup]
                    );

                    // If we found the old permission for this action, give the new permission to anyone who had the old one
                    if (isset($oldPermModels[$action])) {
                        $oldPerm = $oldPermModels[$action];
                        
                        // Migrate Roles
                        $roles = DB::table('role_has_permissions')->where('permission_id', $oldPerm->id)->pluck('role_id');
                        foreach ($roles as $roleId) {
                            DB::table('role_has_permissions')->updateOrInsert([
                                'permission_id' => $newPerm->id,
                                'role_id' => $roleId
                            ]);
                        }

                        // Migrate Users (Direct Permissions)
                        $users = DB::table('model_has_permissions')->where('permission_id', $oldPerm->id)->get();
                        foreach ($users as $userPivot) {
                            DB::table('model_has_permissions')->updateOrInsert([
                                'permission_id' => $newPerm->id,
                                'model_type' => $userPivot->model_type,
                                'model_id' => $userPivot->model_id
                            ]);
                        }
                    }
                }
            }
        }
        
        // After finishing all migration, the old permissions are still there.
        // It's safe to leave them, or we could delete permissions that have no matching menu.
        // We will leave them for safety, but we ensure cache is clear.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Illuminate\Support\Facades\Cache::flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this perfectly is hard because we'd merge multiple unique groups back to one.
        // Data migration, down is empty
    }
};
