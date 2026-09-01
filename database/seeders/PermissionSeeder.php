<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Permission Groups
        $permissionGroups = [
            'Website Dashboard' => [
                'view website dashboard',
            ],
            'User' => [
                'view user',
                'add user',
                'edit user',
                'delete user',
                'reset password'
            ],
            'Employee' => [
                'view employee',
                'add employee',
                'edit employee',
                'delete employee'
            ],

             'Employee Menu' => [
                'view employee menu',
                'add employee menu',
                'edit employee menu',
                'delete employee menu'
            ],
            'Role' => [
                'view role',
                'add role',
                'edit role',
                'delete role'
            ],
            'Site Setting' => [
                'edit site setting',
                'menu setup'
            ],
            'Page' => [
                'view page',
                'add page',
                'edit page',
                'delete page',
                'publish page',
                'approve page'
            ],
            'Query Form Subject' => [
                'view query form subject',
                'add query form subject',
                'edit query form subject',
                'delete query form subject',
                'publish query form subject',
                'approve query form subject'
            ],
            'Complaint Form Subject' => [
                'view complaint form subject',
                'add complaint form subject',
                'edit complaint form subject',
                'delete complaint form subject',
                'publish complaint form subject',
                'approve complaint form subject'
            ],
            'Feedback' => [
                'view feedback',
                'edit feedback',
                'delete feedback',
                'respond to feedback'
            ],
            'Complaint' => [
                'view complaint',
                'edit complaint',
                'delete complaint',
                'respond to complaint'
            ],
            'Tender Zonal Office' => [
                'view zonal office',
                'add zonal office',
                'edit zonal office',
                'delete zonal office',
                'publish zonal office',
                'approve zonal office'
            ],
            'Tender Category' => [
                'view tender category',
                'add tender category',
                'edit tender category',
                'delete tender category',
                'publish tender category',
                'approve tender category'
            ],
            'Tender' => [
                'view tender',
                'add tender',
                'edit tender',
                'delete tender',
                'publish tender',
                'approve tender'
            ],
            'Slider' => [
                'view slider',
                'add slider',
                'edit slider',
                'delete slider',
                'publish slider',
                'approve slider'
            ],
            'Announcements' => [
                'view announcement',
                'add announcement',
                'edit announcement',
                'delete announcement',
                'publish announcement',
                'approve announcement'
            ],
            'Government Portals' => [
                'view government portal',
                'add government portal',
                'edit government portal',
                'delete government portal',
                'publish government portal',
                'approve government portal'
            ],
            'CPCB Portals' => [
                'view cpcb portal',
                'add cpcb portal',
                'edit cpcb portal',
                'delete cpcb portal',
                'publish cpcb portal',
                'approve cpcb portal'
            ],
            'EPR Portals' => [
                'view epr portal',
                'add epr portal',
                'edit epr portal',
                'delete epr portal',
                'publish epr portal',
                'approve epr portal'
            ],
            'Who is who' => [
                'view who is who',
                'add who is who',
                'edit who is who',
                'delete who is who',
                'publish who is who',
                'approve who is who'
            ],
            'Subject Area Setup' => [
                'view subject area',
                'add subject area',
                'edit subject area',
                'delete subject area',
                'publish subject area',
                'approve subject area'
            ],
            'Division Setup' => [
                'view division',
                'add division',
                'edit division',
                'delete division',
                'publish division',
                'approve division'
            ],
            'Designation Setup' => [
                'view designation',
                'add designation',
                'edit designation',
                'delete designation',
                'publish designation',
                'approve designation'
            ],
            'Technical Report' => [
                'view technical report',
                'add technical report',
                'edit technical report',
                'delete technical report',
                'publish technical report',
                'approve technical report'
            ],
            'Circular' => [
                'view circular',
                'add circular',
                'edit circular',
                'delete circular',
                'publish circular',
                'approve circular'
            ],
            'Gallery Event' => [
                'view gallery event',
                'add gallery event',
                'edit gallery event',
                'delete gallery event',
                'publish gallery event',
                'approve gallery event'
            ],
            'Photo Gallery Sub Event' => [
                'view photo gallery sub event',
                'add photo gallery sub event',
                'edit photo gallery sub event',
                'delete photo gallery sub event',
                'publish photo gallery sub event',
                'approve photo gallery sub event'
            ],
            'Photo Gallery' => [
                'view photo gallery',
                'add photo gallery',
                'edit photo gallery',
                'delete photo gallery',
                'publish photo gallery',
                'approve photo gallery'
            ],
            'Video Gallery' => [
                'view video gallery',
                'add video gallery',
                'edit video gallery',
                'delete video gallery',
                'publish video gallery',
                'approve video gallery'
            ],
            'Contact Details' => [
                'view contact detail',
                'add contact detail',
                'edit contact detail',
                'delete contact detail',
                'publish contact detail',
                'approve contact detail'
            ],

            'Directory' => [
                'view directory',
                'add directory',
                'edit directory',
                'delete directory',
                'publish directory',
                'approve directory'
            ],


            'Social Media' => [
                'view social media',
                'add social media',
                'edit social media',
                'delete social media',
                'publish social media',
                'approve social media'
            ],
            'Audit Log' => [
                'view audit log',
            ],
            'Authentication Log' => [
                'view authentication log',
            ],
            'Job' => [
                'view job',
                'add job',
                'edit job',
                'delete job',
                'publish job',
                'approve job'
            ],

            'Job Post' => [
                'view job post',
                'add job post',
                'edit job post',
                'delete job post',
                'publish job post',
                'approve job post',
            ],

            'Recruitment Announcement' => [
                'view recruitment announcement',
                'add recruitment announcement',
                'edit recruitment announcement',
                'delete recruitment announcement',
                'approve recruitment announcement',
                'publish recruitment announcement',
            ],

            'Annual Report' => [
                'view annual report',
                'add annual report',
                'edit annual report',
                'delete annual report',
                'publish annual report',
                'approve annual report'
            ],
            'Publication Category' => [
                'view publication category',
                'add publication category',
                'edit publication category',
                'delete publication category',
                'publish publication category',
                'approve publication category'
            ],
            'Publication' => [
                'view publication',
                'add publication',
                'edit publication',
                'delete publication',
                'publish publication',
                'approve publication'
            ],
            'Faq' => [
                'view faq',
                'add faq',
                'edit faq',
                'delete faq',
                'publish faq',
                'approve faq'
            ],
            'Direction Type' => [
                'view direction_type',
                'add direction_type',
                'edit direction_type',
                'delete direction_type',
                'publish direction_type',
                'approve direction_type',
            ],
            'Laboratories Page' => [
                'view laboratories page',
                'add laboratories page',
                'edit laboratories page',
                'delete laboratories page',
                'publish laboratories page',
                'approve laboratories page'
            ],
            'Laboratories Page Files' => [
                'view laboratories page file',
                'add laboratories page file',
                'edit laboratories page file',
                'delete laboratories page file',
                'publish laboratories page file',
                'approve laboratories page file'
            ],
            'Latest CPCB' => [
                'view latest cpcb',
                'add latest cpcb',
                'edit latest cpcb',
                'delete latest cpcb',
                'publish latest cpcb',
                'approve latest cpcb'
            ],

            'NGT Court Cases' => [
                'view ngt court case',
                'add ngt court case',
                'edit ngt court case',
                'delete ngt court case',
                'publish ngt court case',
                'approve ngt court case'
            ],

            'Fortnightly Reports' => [
                'view fortnightly report',
                'add fortnightly report',
                'edit fortnightly report',
                'delete fortnightly report',
                'publish fortnightly report',
                'approve fortnightly report'
            ],

            'Environmental Regulation Tab' => [
                'view environmental regulation',
                'add environmental regulation',
                'edit environmental regulation',
                'delete environmental regulation',
                'publish environmental regulation',
                'approve environmental regulation'
            ],

            'Environmental Regulation Detail' => [
                'view environmental regulation detail',
                'add environmental regulation detail',
                'edit environmental regulation detail',
                'delete environmental regulation detail',
                'publish environmental regulation detail',
                'approve environmental regulation detail'
            ],

            'Information Centers' => [
                'view information centers',
                'add information centers',
                'edit information centers',
                'delete information centers',
                'publish information centers',
                'approve information centers'
            ],

            'Information Center Details' => [
                'view information center detail',
                'add information center detail',
                'edit information center detail',
                'delete information center detail',
                'publish information center detail',
                'approve information center detail'
            ],

            'Studies Reports' => [
                'view studies report',
                'add studies report',
                'edit studies report',
                'delete studies report',
                'publish studies report',
                'approve studies report'
            ],
            'Head Office Setup' => [
                'view head office',
                'add head office',
                'edit head office',
                'delete head office',
                'publish head office',
                'approve head office'
            ],
            'Regional Directorate Setup' => [
                'view regional directorate',
                'add regional directorate',
                'edit regional directorate',
                'delete regional directorate',
                'approve regional directorate',
                'publish regional directorate',
            ],
            'Quality Zone' => [
                'view quality zone',
                'add quality zone',
                'edit quality zone',
                'delete quality zone',
                'approve quality zone',
                'publish quality zone',
            ],
            'Agra Air Quality' => [
                'view agra air quality',
                'add agra air quality',
                'edit agra air quality',
                'delete agra air quality',
                'approve agra air quality',
                'publish agra air quality',
            ],
            'Direction Category' => [
                'view direction category',
                'add direction category',
                'edit direction category',
                'delete direction category',
                'publish direction category',
                'approve direction category'
            ],
            'Direction State' => [
                'view direction state',
                'add direction state',
                'edit direction state',
                'delete direction state',
                'publish direction state',
                'approve direction state'
            ],
            'Direction Issued To' => [
                'view direction issued to',
                'add direction issued to',
                'edit direction issued to',
                'delete direction issued to',
                'publish direction issued to',
                'approve direction issued to'
            ],
            'Direction Subject' => [
                'view direction subject',
                'add direction subject',
                'edit direction subject',
                'delete direction subject',
                'publish direction subject',
                'approve direction subject'
            ],
            'Direction Act Type' => [
                'view direction act type',
                'add direction act type',
                'edit direction act type',
                'delete direction act type',
                'publish direction act type',
                'approve direction act type'
            ],
            'Direction' => [
                'view direction',
                'add direction',
                'edit direction',
                'delete direction',
                'publish direction',
                'approve direction'
            ],
            'Letters Issued' => [
                'view letters_issued',
                'add letters_issued',
                'edit letters_issued',
                'delete letters_issued',
                'publish letters_issued',
                'approve letters_issued'
            ],
            'Comment Report' => [
                'view comment report',
                'add comment report',
                'edit comment report',
                'delete comment report',
                'publish comment report',
                'approve comment report'
            ],
        ];

        // Load all existing permissions into memory keyed by name for O(1) lookups
        $allPermissions = Permission::all()->keyBy('name');

        $newPermissions = [];
        $permissionsToInsert = [];

        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permissionName) {
                $existing = $allPermissions->get($permissionName);
                if ($existing) {
                    // Update group if changed
                    if ($existing->group !== $group) {
                        Permission::where('id', $existing->id)->update(['group' => $group]);
                    }
                } else {
                    $permissionsToInsert[] = [
                        'name' => $permissionName,
                        'group' => $group,
                        'guard_name' => 'web',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $newPermissions[] = $permissionName;
                }
            }
        }

        if (!empty($permissionsToInsert)) {
            // Bulk insert new permissions in a single query
            Permission::insert($permissionsToInsert);
        }

        if (!empty($newPermissions)) {
            \Log::info('PermissionSeeder: Created ' . count($newPermissions) . ' new permissions: ' . implode(', ', $newPermissions));
        }

        // Reset Spatie cache so new permissions are available
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Roles - only create if missing, then give NEW permissions to admin roles
        $roles = ['ADMIN', 'SUPERADMIN', 'EMPLOYEE'];
        $allPermissions = Permission::all();
        $allPermissionIds = $allPermissions->pluck('id')->toArray();

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (in_array($roleName, ['ADMIN', 'SUPERADMIN'])) {
                // Get currently assigned permission IDs for this role to find what is missing
                $currentPermissionIds = $role->permissions()->pluck('id')->toArray();
                $missingPermissionIds = array_diff($allPermissionIds, $currentPermissionIds);

                if (!empty($missingPermissionIds)) {
                    // Only attach missing permissions (runs in one optimized query)
                    $role->permissions()->attach($missingPermissionIds);
                }
            } elseif ($roleName === 'EMPLOYEE') {
                // Only sync employee defaults if the role has no permissions yet
                if ($role->permissions->isEmpty()) {
                    $employeePermissions = $allPermissions->whereIn('name', [
                        'view circular',
                        'view website dashboard'
                    ]);
                    $role->syncPermissions($employeePermissions);
                }
            }
        }
    }
}
