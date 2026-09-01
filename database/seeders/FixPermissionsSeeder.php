<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Menu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Fixes ALL database permission issues and Postgres sequences at once.
 * 
 * 1. Resets PostgreSQL sequences to prevent duplicate key violations (FixPostgresSequencesSeeder).
 * 2. Cleans up wrong/duplicate permissions and aligns them with routes (FixMismatchedPermissionsSeeder).
 * 3. Syncs standard permission and sidebar seeders.
 * 4. Resets Spatie and application cache.
 *
 * Usage on server:
 *   php artisan db:seed --class=FixPermissionsSeeder
 */
class FixPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 STARTING DATABASE REPAIR AND SYNC (ALL-IN-ONE)...');
        $this->command->info(str_repeat('═', 60));

        // =========================================================================
        // PART 1: Reset PostgreSQL auto-increment sequences
        // =========================================================================
        $this->command->info('🔧 Part 1: Syncing PostgreSQL auto-increment sequences...');
        
        $sequences = DB::select("
            SELECT
                seq.relname AS sequence_name,
                tab.relname AS table_name,
                col.attname AS column_name
            FROM pg_class seq
            JOIN pg_depend dep ON dep.objid = seq.oid
            JOIN pg_class tab ON dep.refobjid = tab.oid
            JOIN pg_attribute col ON col.attrelid = tab.oid AND col.attnum = dep.refobjsubid
            WHERE seq.relkind = 'S'
            ORDER BY tab.relname
        ");

        if (empty($sequences)) {
            $this->command->warn('  No sequences found in the database.');
        } else {
            $fixedSeq = 0;
            $alreadySyncedSeq = 0;
            $emptyTablesSeq = 0;

            foreach ($sequences as $seq) {
                $tableName = $seq->table_name;
                $columnName = $seq->column_name;
                $sequenceName = $seq->sequence_name;

                $maxId = DB::table($tableName)->max($columnName);

                if (is_null($maxId)) {
                    DB::statement("ALTER SEQUENCE \"{$sequenceName}\" RESTART WITH 1");
                    $emptyTablesSeq++;
                    continue;
                }

                $currentSeqVal = DB::selectOne("SELECT last_value FROM \"{$sequenceName}\"")->last_value;

                if ($currentSeqVal >= $maxId) {
                    $alreadySyncedSeq++;
                    continue;
                }

                DB::statement("SELECT setval('\"' || ? || '\"', ?)", [$sequenceName, $maxId]);
                $this->command->line("    🔧 Fixed <comment>{$tableName}.{$columnName}</comment> (max={$maxId}, was={$currentSeqVal})");
                $fixedSeq++;
            }

            $this->command->info("  ✅ Postgres Sequences fixed: {$fixedSeq} | Already synced: {$alreadySyncedSeq} | Empty tables: {$emptyTablesSeq}");
        }

        $this->command->info(str_repeat('─', 60));

        // =========================================================================
        // PART 2: Clean up mismatched/duplicate permissions
        // =========================================================================
        $this->command->info('🔧 Part 2: Repairing permission names and group assignments...');

        $permissionMap = [
            'view circulars' => 'view circular',
            'add circulars' => 'add circular',
            'edit circulars' => 'edit circular',
            'delete circulars' => 'delete circular',
            'publish circulars' => 'publish circular',
            'approve circulars' => 'approve circular',
            
            'view contact details directory' => 'view directory',
            'add contact details directory' => 'add directory',
            'edit contact details directory' => 'edit directory',
            'delete contact details directory' => 'delete directory',
            'publish contact details directory' => 'publish directory',
            'approve contact details directory' => 'approve directory',
        ];

        // Ensure all correct permissions exist
        $this->command->line('  → Creating correct permission targets...');
        foreach ($permissionMap as $wrong => $correct) {
            $group = str_contains($correct, 'circular') ? 'Circular' : 'Directory';
            Permission::firstOrCreate(
                ['name' => $correct, 'guard_name' => 'web'],
                ['group' => $group]
            );
        }

        // Migrate User direct permissions
        $this->command->line('  → Migrating direct user permissions...');
        $users = User::all();
        $userFixCount = 0;
        foreach ($users as $user) {
            $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
            $hasWrong = false;
            $updatedPerms = [];
            
            foreach ($directPermissions as $perm) {
                if (isset($permissionMap[$perm])) {
                    $updatedPerms[] = $permissionMap[$perm];
                    $hasWrong = true;
                } else {
                    $updatedPerms[] = $perm;
                }
            }
            
            if ($hasWrong) {
                $user->syncPermissions($updatedPerms);
                $userFixCount++;
            }
        }
        $this->command->line("    ✅ Updated {$userFixCount} users.");

        // Migrate Role permissions
        $this->command->line('  → Migrating role permissions...');
        $roles = Role::all();
        $roleFixCount = 0;
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            $hasWrong = false;
            $updatedPerms = [];
            
            foreach ($rolePermissions as $perm) {
                if (isset($permissionMap[$perm])) {
                    $updatedPerms[] = $permissionMap[$perm];
                    $hasWrong = true;
                } else {
                    $updatedPerms[] = $perm;
                }
            }
            
            if ($hasWrong) {
                $role->syncPermissions($updatedPerms);
                $roleFixCount++;
            }
        }
        $this->command->line("    ✅ Updated {$roleFixCount} roles.");

        // Update Menu records in DB
        $this->command->line('  → Updating Menu permission_groups in DB...');
        $menuUpdates = [
            'Contact Details Directory' => 'Directory',
            'Circulars' => 'Circular',
        ];

        foreach ($menuUpdates as $oldGroup => $newGroup) {
            $count = Menu::where('permission_group', $oldGroup)->update(['permission_group' => $newGroup]);
            $this->command->line("    ✅ Updated {$count} menus from group '{$oldGroup}' to '{$newGroup}'.");
        }

        // Delete duplicate/wrong permissions from DB
        $this->command->line('  → Deleting duplicate/wrong permissions...');
        $deletedCount = 0;
        foreach (array_keys($permissionMap) as $wrongPerm) {
            $permObj = Permission::where('name', $wrongPerm)->first();
            if ($permObj) {
                $permObj->delete();
                $deletedCount++;
            }
        }
        $this->command->line("    ✅ Deleted {$deletedCount} duplicate permissions.");

        $this->command->info(str_repeat('─', 60));

        // =========================================================================
        // PART 3: Run standard seeders and flush cache
        // =========================================================================
        $this->command->info('🔧 Part 3: Syncing seeders and flushing cache...');
        
        $this->command->line('  → Syncing PermissionSeeder...');
        Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);
        
        $this->command->line('  → Syncing SidebarMenuSeeder...');
        Artisan::call('db:seed', ['--class' => 'SidebarMenuSeeder']);

        $this->command->line('  → Resetting Spatie permissions and Laravel cache...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::flush();

        $this->command->info(str_repeat('═', 60));
        $this->command->info('🎉 SYSTEM REPAIR AND SYNC COMPLETE!');
        $this->command->info('');
    }
}
