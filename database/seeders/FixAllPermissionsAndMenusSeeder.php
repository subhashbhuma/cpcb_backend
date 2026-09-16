<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Menu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixAllPermissionsAndMenusSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('============================================================');
        $this->command->info('🚀 STARTING STANDARDIZATION OF PERMISSIONS AND MENUS');
        $this->command->info('============================================================');

        // Mappings of wrong/plural/mismatched permissions to the standardized singular names
        $permissionMap = [
            // 1. Announcements (plural -> singular)
            'view announcements' => 'view announcement',
            'add announcements' => 'add announcement',
            'edit announcements' => 'edit announcement',
            'delete announcements' => 'delete announcement',
            'publish announcements' => 'publish announcement',
            'approve announcements' => 'approve announcement',

            // 2. Contact Details (plural -> singular)
            'view contact details' => 'view contact detail',
            'add contact details' => 'add contact detail',
            'edit contact details' => 'edit contact detail',
            'delete contact details' => 'delete contact detail',
            'publish contact details' => 'publish contact detail',
            'approve contact details' => 'approve contact detail',

            // 3. CPCB Portals (plural -> singular)
            'view cpcb portals' => 'view cpcb portal',
            'add cpcb portals' => 'add cpcb portal',
            'edit cpcb portals' => 'edit cpcb portal',
            'delete cpcb portals' => 'delete cpcb portal',
            'publish cpcb portals' => 'publish cpcb portal',
            'approve cpcb portals' => 'approve cpcb portal',

            // 4. EPR Portals (plural -> singular)
            'view epr portals' => 'view epr portal',
            'add epr portals' => 'add epr portal',
            'edit epr portals' => 'edit epr portal',
            'delete epr portals' => 'delete epr portal',
            'publish epr portals' => 'publish epr portal',
            'approve epr portals' => 'approve epr portal',

            // 5. Fortnightly Reports (plural -> singular)
            'view fortnightly reports' => 'view fortnightly report',
            'add fortnightly reports' => 'add fortnightly report',
            'edit fortnightly reports' => 'edit fortnightly report',
            'delete fortnightly reports' => 'delete fortnightly report',
            'publish fortnightly reports' => 'publish fortnightly report',
            'approve fortnightly reports' => 'approve fortnightly report',

            // 6. Government Portals (plural -> singular)
            'view government portals' => 'view government portal',
            'add government portals' => 'add government portal',
            'edit government portals' => 'edit government portal',
            'delete government portals' => 'delete government portal',
            'publish government portals' => 'publish government portal',
            'approve government portals' => 'approve government portal',

            // 7. Studies Reports (plural -> singular)
            'view studies reports' => 'view studies report',
            'add studies reports' => 'add studies report',
            'edit studies reports' => 'edit studies report',
            'delete studies reports' => 'delete studies report',
            'publish studies reports' => 'publish studies report',
            'approve studies reports' => 'approve studies report',

            // 8. Publications (plural -> singular)
            'view publications' => 'view publication',
            'add publications' => 'add publication',
            'edit publications' => 'edit publication',
            'delete publications' => 'delete publication',
            'publish publications' => 'publish publication',
            'approve publications' => 'approve publication',

            // 9. Tender Zonal Office -> Zonal Office mapping (prefix mapping)
            'view tender zonal office' => 'view zonal office',
            'add tender zonal office' => 'add zonal office',
            'edit tender zonal office' => 'edit zonal office',
            'delete tender zonal office' => 'delete zonal office',
            'publish tender zonal office' => 'publish zonal office',
            'approve tender zonal office' => 'approve zonal office',

            // 10. Subject Area Setup -> Subject Area mapping (suffix setup mapping)
            'view subject area setup' => 'view subject area',
            'add subject area setup' => 'add subject area',
            'edit subject area setup' => 'edit subject area',
            'delete subject area setup' => 'delete subject area',
            'publish subject area setup' => 'publish subject area',
            'approve subject area setup' => 'approve subject area',

            // 11. Latest Issued -> Letters Issued mapping
            'view Letters issues' => 'view letters_issued',
            'add Letters issues' => 'add letters_issued',
            'edit Letters issues' => 'edit letters_issued',
            'delete Letters issues' => 'delete letters_issued',
            'publish Letters issues' => 'publish letters_issued',
            'approve Letters issues' => 'approve letters_issued',

            // 12. NGT Court Cases (plural -> singular)
            'view ngt court cases' => 'view ngt court case',
            'add ngt court cases' => 'add ngt court case',
            'edit ngt court cases' => 'edit ngt court case',
            'delete ngt court cases' => 'delete ngt court case',
            'publish ngt court cases' => 'publish ngt court case',
            'approve ngt court cases' => 'approve ngt court case',

            // 13. Environmental Regulation Tab -> Environmental Regulation mapping (suffix tab mapping)
            'view environmental regulation tab' => 'view environmental regulation',
            'add environmental regulation tab' => 'add environmental regulation',
            'edit environmental regulation tab' => 'edit environmental regulation',
            'delete environmental regulation tab' => 'delete environmental regulation',
            'publish environmental regulation tab' => 'publish environmental regulation',
            'approve environmental regulation tab' => 'approve environmental regulation',

            // 14. Information Center Details (plural -> singular)
            'view information center details' => 'view information center detail',
            'add information center details' => 'add information center detail',
            'edit information center details' => 'edit information center detail',
            'delete information center details' => 'delete information center detail',
            'publish information center details' => 'publish information center detail',
            'approve information center details' => 'approve information center detail',

            // 15. Division Setup -> Division mapping (suffix setup mapping)
            'view division setup' => 'view division',
            'add division setup' => 'add division',
            'edit division setup' => 'edit division',
            'delete division setup' => 'delete division',
            'publish division setup' => 'publish division',
            'approve division setup' => 'approve division',

            // 16. Designation Setup -> Designation mapping (suffix setup mapping)
            'view designation setup' => 'view designation',
            'add designation setup' => 'add designation',
            'edit designation setup' => 'edit designation',
            'delete designation setup' => 'delete designation',
            'publish designation setup' => 'publish designation',
            'approve designation setup' => 'approve designation',

            // 17. Menu Setup -> Menu mapping
            'menu setup' => 'view menu',

            // 18. Head Office Setup -> Head Office mapping (suffix setup mapping)
            'view head office setup' => 'view head office',
            'add head office setup' => 'add head office',
            'edit head office setup' => 'edit head office',
            'delete head office setup' => 'delete head office',
            'publish head office setup' => 'publish head office',
            'approve head office setup' => 'approve head office',
        ];

        // Mappings of target permissions to their standardized group names
        $permissionGroups = [
            'announcement' => 'Announcement',
            'contact detail' => 'Contact Detail',
            'cpcb portal' => 'CPCB Portal',
            'epr portal' => 'EPR Portal',
            'fortnightly report' => 'Fortnightly Report',
            'government portal' => 'Government Portal',
            'studies report' => 'Studies Report',
            'publication' => 'Publication',
            'zonal office' => 'Zonal Office',
            'subject area' => 'Subject Area',
            'who is who' => 'Who is Who',
            'letters_issued' => 'Letters_Issued',
            'ngt court case' => 'NGT Court Case',
            'environmental regulation' => 'Environmental Regulation',
            'information center detail' => 'Information Center Detail',
            'division' => 'Division',
            'designation' => 'Designation',
            'menu' => 'Menu',
            'head office' => 'Head Office'
        ];

        // Part 1: Ensure all correct permissions exist with correct group names
        $this->command->line('  → Creating correct permission targets...');
        foreach ($permissionMap as $wrong => $correct) {
            $group = 'General';
            foreach ($permissionGroups as $kw => $g) {
                if (str_contains($correct, $kw)) {
                    $group = $g;
                    break;
                }
            }
            Permission::firstOrCreate(
                ['name' => $correct, 'guard_name' => 'web'],
                ['group' => $group]
            );
        }

        // Standardize any existing permissions groups
        foreach ($permissionGroups as $kw => $g) {
            Permission::whereRaw('LOWER(name) = ? OR LOWER(name) IN (?, ?, ?, ?, ?, ?)', [
                strtolower($kw),
                'view ' . strtolower($kw),
                'add ' . strtolower($kw),
                'edit ' . strtolower($kw),
                'delete ' . strtolower($kw),
                'publish ' . strtolower($kw),
                'approve ' . strtolower($kw),
            ])->update(['group' => $g]);
        }

        // Also ensure standard permissions for each group exist
        $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];
        foreach ($permissionGroups as $kw => $groupName) {
            foreach ($actions as $action) {
                $name = $action . ' ' . $kw;
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['group' => $groupName]
                );
            }
        }

        // Part 2: Migrate User direct permissions
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
            
            // Copy Site Setting permissions to Menu permissions if they exist
            $siteMenuMap = [
                'view site setting' => 'view menu',
                'add site setting' => 'add menu',
                'edit site setting' => 'edit menu',
                'delete site setting' => 'delete menu',
                'publish site setting' => 'publish menu',
                'approve site setting' => 'approve menu',
            ];
            foreach ($siteMenuMap as $sitePerm => $menuPerm) {
                if (in_array($sitePerm, $directPermissions) && !in_array($menuPerm, $updatedPerms)) {
                    $updatedPerms[] = $menuPerm;
                    $hasWrong = true;
                }
            }
            
            if ($hasWrong) {
                $user->syncPermissions(array_unique($updatedPerms));
                $userFixCount++;
            }
        }
        $this->command->line("    ✅ Updated {$userFixCount} users.");

        // Part 3: Migrate Role permissions
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
            
            // Copy Site Setting permissions to Menu permissions if they exist
            $siteMenuMap = [
                'view site setting' => 'view menu',
                'add site setting' => 'add menu',
                'edit site setting' => 'edit menu',
                'delete site setting' => 'delete menu',
                'publish site setting' => 'publish menu',
                'approve site setting' => 'approve menu',
            ];
            foreach ($siteMenuMap as $sitePerm => $menuPerm) {
                if (in_array($sitePerm, $rolePermissions) && !in_array($menuPerm, $updatedPerms)) {
                    $updatedPerms[] = $menuPerm;
                    $hasWrong = true;
                }
            }
            
            if ($hasWrong) {
                $role->syncPermissions(array_unique($updatedPerms));
                $roleFixCount++;
            }
        }
        $this->command->line("    ✅ Updated {$roleFixCount} roles.");

        // Part 4: Update Menu records in DB to use the standardized groups
        $this->command->line('  → Updating Menu permission_groups in DB...');
        $menuUpdates = [
            'Announcements' => 'Announcement',
            'Tender Zonal Office' => 'Zonal Office',
            'Subject Area Setup' => 'Subject Area',
            'Government Portals' => 'Government Portal',
            'Contact Details' => 'Contact Detail',
            'Fortnightly Reports' => 'Fortnightly Report',
            'Studies Reports' => 'Studies Report',
            'CPCB Portals' => 'CPCB Portal',
            'EPR Portals' => 'EPR Portal',
            'Who is who' => 'Who is Who',
            'Publications' => 'Publication',
            'Latest Issued' => 'Letters_Issued',
            'Letters Issued' => 'Letters_Issued',
            'NGT Court Cases' => 'NGT Court Case',
            'Environmental Regulation Tab' => 'Environmental Regulation',
            'Information Center Details' => 'Information Center Detail',
            'Division Setup' => 'Division',
            'Designation Setup' => 'Designation',
            'Head Office Setup' => 'Head Office',
        ];

        foreach ($menuUpdates as $oldGroup => $newGroup) {
            $count = Menu::where('permission_group', $oldGroup)->update(['permission_group' => $newGroup]);
            $this->command->line("    ✅ Updated {$count} menus from group '{$oldGroup}' to '{$newGroup}'.");
        }

        // Specific menu updates by ID
        $menuIdUpdates = [
            1538 => 'Menu',
        ];
        foreach ($menuIdUpdates as $menuId => $newGroup) {
            $count = Menu::where('id', $menuId)->update(['permission_group' => $newGroup]);
            $this->command->line("    ✅ Updated menu ID {$menuId} group to '{$newGroup}' (modified {$count} rows).");
        }

        // Part 5: Delete duplicate/wrong permissions from DB
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

        // Part 6: Clear Cache
        $this->command->line('  → Resetting Spatie permissions and Laravel cache...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::flush();

        $this->command->info('============================================================');
        $this->command->info('🎉 PERMISSIONS AND MENUS STANDARDIZATION COMPLETE!');
        $this->command->info('============================================================');
    }
}
