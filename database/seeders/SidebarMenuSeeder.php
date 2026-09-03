<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class SidebarMenuSeeder extends Seeder
{
    /**
     * Seed sidebar menus with parent-child hierarchy.
     */
    public function run(): void
    {
        Menu::where('location', 'sidebar')->delete();


        $menus = [
            // ─── Website Setup ────────────────────────────────────────────────
            ['title' => 'Website Setup', 'is_caption' => true, 'order' => 10],

            ['title' => 'Pages', 'icon' => 'ti ti-file', 'group' => 'Page', 'url' => 'secure/pages', 'order' => 11],
            ['title' => 'File Manager', 'icon' => 'ti ti-photo', 'group' => 'Media', 'url' => 'secure/medias', 'order' => 12],

            // Who Is Who group
            ['title' => 'Who Is Who Setup', 'icon' => 'ti ti-users', 'group' => 'Who is who', 'url' => 'secure/who-is-who', 'order' => 13],
            ['title' => 'Head Office Setup', 'icon' => 'ti ti-building', 'group' => 'Head Office Setup', 'url' => 'secure/head_offices', 'order' => 14],
            ['title' => 'Regional Directorate', 'icon' => 'ti ti-building-community', 'group' => 'Regional Directorate Setup', 'url' => 'secure/regional_directorates', 'order' => 15],

            ['title' => 'Slider Setup', 'icon' => 'ti ti-slideshow', 'group' => 'Slider', 'url' => 'secure/sliders', 'order' => 16],
            ['title' => 'Latest CPCB Setup', 'icon' => 'ti ti-world', 'group' => 'Latest CPCB', 'url' => 'secure/latest-cpcbs', 'order' => 17],
            ['title' => 'Announcement Setup', 'icon' => 'ti ti-speakerphone', 'group' => 'Announcements', 'url' => 'secure/announcements', 'order' => 18],

            // Job Setup
            [
                'title' => 'Job Setup',
                'icon' => 'ti ti-file-invoice',
                'order' => 19,
                'children' => [
                    ['title' => 'Jobs', 'group' => 'Job', 'url' => 'secure/jobs'],
                    ['title' => 'Job Posts', 'group' => 'Job Post', 'url' => 'secure/job-posts'],
                    ['title' => 'Recruitment Announcements', 'group' => 'Recruitment Announcement', 'url' => 'secure/recruitment-announcements'],
                ]
            ],

            // Technical Report Setup
            [
                'title' => 'Technical Report Setup',
                'icon' => 'ti ti-file-analytics',
                'order' => 20,
                'children' => [
                    ['title' => 'Subject Area', 'group' => 'Subject Area Setup', 'url' => 'secure/subject-area'],
                    ['title' => 'Technical Report', 'group' => 'Technical Report', 'url' => 'secure/technical_report'],
                ]
            ],

            ['title' => 'Studies & Reports', 'icon' => 'ti ti-report-analytics', 'group' => 'Studies Reports', 'url' => 'secure/studies_reports', 'order' => 21],
            ['title' => 'Annual Report Setup', 'icon' => 'ti ti-calendar', 'group' => 'Annual Report', 'url' => 'secure/annual_report', 'order' => 22],

            // Publication Setup
            [
                'title' => 'Publication Setup',
                'icon' => 'ti ti-clipboard',
                'order' => 23,
                'children' => [
                    ['title' => 'Category', 'group' => 'Publication Category', 'url' => 'secure/publication_category'],
                    ['title' => 'Publications', 'group' => 'Publication', 'url' => 'secure/publication'],
                ]
            ],

            // Tender Setup
            [
                'title' => 'Tender Setup',
                'icon' => 'ti ti-clipboard',
                'order' => 24,
                'children' => [
                    ['title' => 'Category', 'group' => 'Tender Category', 'url' => 'secure/tender_category'],
                    ['title' => 'Zonal Office', 'group' => 'Tender Zonal Office', 'url' => 'secure/zonal_office'],
                    ['title' => 'Tender', 'group' => 'Tender', 'url' => 'secure/tenders'],
                ]
            ],

            ['title' => 'Circular Setup', 'icon' => 'ti ti-bell', 'group' => 'Circular', 'url' => 'secure/circulars', 'order' => 25],
            ['title' => 'Contact Detail Setup', 'icon' => 'ti ti-phone', 'group' => 'Contact Details', 'url' => 'secure/contact-details', 'order' => 26],
            ['title' => 'Directory Setup', 'icon' => 'ti ti-phone', 'group' => 'Directory', 'url' => 'secure/directories', 'order' => 27],

            // Direction Setup
            [
                'title' => 'Direction Setup',
                'icon' => 'ti ti-directions',
                'order' => 28,
                'children' => [
                    ['title' => 'Act Types', 'group' => 'Direction Act Type', 'url' => 'secure/direction_act_type'],
                    ['title' => 'Direction Type', 'group' => 'Direction Type', 'url' => 'secure/direction-types'],
                    ['title' => 'Subject', 'group' => 'Direction Subject', 'url' => 'secure/direction_subject'],
                    ['title' => 'States', 'group' => 'Direction State', 'url' => 'secure/direction_state'],
                    ['title' => 'Category', 'group' => 'Direction Category', 'url' => 'secure/direction_category'],
                    ['title' => 'Issues To', 'group' => 'Direction Issued To', 'url' => 'secure/direction_issued_to'],
                    ['title' => 'Directions', 'group' => 'Direction', 'url' => 'secure/direction'],
                    ['title' => 'Letters issues', 'group' => 'Latest Issued', 'url' => 'secure/letters-issued'],
                ]
            ],

            ['title' => 'Comment Reports', 'icon' => 'ti ti-messages', 'group' => 'Comment Report', 'url' => 'secure/comment-reports', 'order' => 29],
            ['title' => 'NGT Court Cases', 'icon' => 'ti ti-report', 'group' => 'NGT Court Cases', 'url' => 'secure/ngt-court-cases', 'order' => 30],
            ['title' => 'Fortnightly Reports', 'icon' => 'ti ti-calendar-stats', 'group' => 'Fortnightly Reports', 'url' => 'secure/fortnightly-reports', 'order' => 31],

            // Agra Air Quality Setup
            [
                'title' => 'Agra Air Quality Setup',
                'icon' => 'ti ti-wind',
                'order' => 32,
                'children' => [
                    ['title' => 'Zones', 'group' => 'Quality Zone', 'url' => 'secure/quality-zones'],
                    ['title' => 'Air Quality', 'group' => 'Agra Air Quality', 'url' => 'secure/agra-air-qualities'],
                ]
            ],

            ['title' => 'FAQ Setup', 'icon' => 'ti ti-messages', 'group' => 'Faq', 'url' => 'secure/faq', 'order' => 33],

            // Environmental Regulation
            [
                'title' => 'Environmental Regulation',
                'icon' => 'ti ti-social',
                'order' => 34,
                'children' => [
                    ['title' => 'Tab', 'group' => 'Environmental Regulation Tab', 'url' => 'secure/environmental-regulation'],
                    ['title' => 'Detail', 'group' => 'Environmental Regulation Detail', 'url' => 'secure/environmental-regulation-details'],
                ]
            ],

            // Information Center
            [
                'title' => 'Information Center',
                'icon' => 'ti ti-info-circle',
                'order' => 35,
                'children' => [
                    ['title' => 'Tab', 'group' => 'Information Centers', 'url' => 'secure/information-centers'],
                    ['title' => 'Detail', 'group' => 'Information Center Details', 'url' => 'secure/information-center-details'],
                ]
            ],

            ['title' => 'Gallery Event Setup', 'icon' => 'ti ti-calendar', 'group' => 'Gallery Event', 'url' => 'secure/gallery-events', 'order' => 36],
            ['title' => 'Gallery Setup', 'icon' => 'ti ti-camera', 'group' => 'Photo Gallery', 'url' => 'secure/photo-gallery', 'order' => 37],
            ['title' => 'Video Gallery Setup', 'icon' => 'ti ti-video', 'group' => 'Video Gallery', 'url' => 'secure/video-gallery', 'order' => 38],
            ['title' => 'Social Media Setup', 'icon' => 'ti ti-social', 'group' => 'Social Media', 'url' => 'secure/social-medias', 'order' => 39],
            ['title' => 'Govt. Portal Setup', 'icon' => 'ti ti-world', 'group' => 'Government Portals', 'url' => 'secure/government-portals', 'order' => 40],
            ['title' => 'CPCB. Portal Setup', 'icon' => 'ti ti-building-community', 'group' => 'CPCB Portals', 'url' => 'secure/cpcb-portals', 'order' => 41],
            ['title' => 'EPR Portal Setup', 'icon' => 'ti ti-building-community', 'group' => 'EPR Portals', 'url' => 'secure/epr-portals', 'order' => 42],
            ['title' => 'Feedback', 'icon' => 'ti ti-book', 'group' => 'Feedback', 'url' => 'secure/feedback', 'order' => 43],
            ['title' => 'Complaint', 'icon' => 'ti ti-clipboard-list', 'group' => 'Complaint', 'url' => 'secure/complaint', 'order' => 44],

            // Master Setup
            [
                'title' => 'Master Setup',
                'icon' => 'ti ti-settings',
                'order' => 44,
                'children' => [
                    ['title' => 'Divisions', 'group' => 'Division Setup', 'url' => 'secure/division'],
                    ['title' => 'Designations', 'group' => 'Designation Setup', 'url' => 'secure/designation'],
                    ['title' => 'Query Form Subjects', 'group' => 'Query Form Subject', 'url' => 'secure/query_form_subject'],
                    ['title' => 'Complaint Form Subjects', 'group' => 'Complaint Form Subject', 'url' => 'secure/complaint_form_subject'],
                ]
            ],

            // ─── Logs ─────────────────────────────────────────────────────────
            ['title' => 'Logs', 'is_caption' => true, 'order' => 100],

            ['title' => 'Audit Log', 'icon' => 'ti ti-book', 'group' => 'Audit Log', 'url' => 'secure/audit-logs', 'order' => 101],
            ['title' => 'Authentication Log', 'icon' => 'ti ti-login', 'group' => 'Authentication Log', 'url' => 'secure/authentication-logs', 'order' => 102],

            // ─── Site Settings ────────────────────────────────────────────────
            ['title' => 'Site Setting', 'is_caption' => true, 'order' => 110],

            ['title' => 'Site Settings', 'icon' => 'ti ti-user-plus', 'group' => 'Site Setting', 'url' => 'secure/site-settings', 'order' => 111],
            ['title' => 'Menu Setup', 'icon' => 'ti ti-menu-2', 'group' => 'Site Setting', 'url' => 'secure/menus', 'order' => 112],

            // ─── Authentication Setup ─────────────────────────────────────────
            ['title' => 'Authentication Setup', 'is_caption' => true, 'order' => 120],

            ['title' => 'Users Setup', 'icon' => 'ti ti-lock', 'group' => 'User', 'url' => 'secure/users', 'order' => 121],
            ['title' => 'Employee Setup', 'icon' => 'ti ti-users', 'group' => 'Employee', 'url' => 'secure/employee', 'order' => 122],
            ['title' => 'Employee Menu', 'icon' => 'ti ti-menu-2', 'group' => 'Employee Menu', 'url' => 'secure/employee-menus', 'order' => 123],
            ['title' => 'Roles Setup', 'icon' => 'ti ti-user-plus', 'group' => 'Role', 'url' => 'secure/roles', 'order' => 124],
        ];

        $this->seedMenus($menus);

        // Ensure ALL sidebar menus (including parents and captions) have a unique permission group
        // so they can be individually assigned and have active checkboxes in the matrix.
        $allSidebarMenus = Menu::where('location', 'sidebar')
            ->where(function ($q) {
                $q->whereNull('permission_group')->orWhere('permission_group', '');
            })
            ->get();

        foreach ($allSidebarMenus as $menu) {
            // Use the title as the permission group
            $menu->permission_group = $menu->title;
            $menu->save(); // Triggers saved observer to create permissions
        }

        // Restore granular permission groups for frontend menus based on their titles
        $frontendMenus = Menu::where('location', '!=', 'sidebar')->get();

        foreach ($frontendMenus as $menu) {
            $menu->permission_group = $menu->title;
            $menu->save();
        }

        // Flush Cache so that sidebar changes take effect immediately
        \Illuminate\Support\Facades\Cache::flush();

        $this->command->info('Sidebar menus seeded with hierarchy and Page permission groups synchronized.');
    }

    /**
     * Recursive helper to seed menus.
     */
    private function seedMenus(array $menus, $parentId = null): void
    {
        foreach ($menus as $index => $menuData) {
            $isCaption = $menuData['is_caption'] ?? false;
            $url = $menuData['url'] ?? '#';
            $icon = $menuData['icon'] ?? null;
            $order = $menuData['order'] ?? ($index + 1);

            if ($isCaption) {
                $url = '#';
                $icon = null;
            }

            $menu = Menu::updateOrCreate(
                [
                    'location' => 'sidebar',
                    'title' => $menuData['title'],
                    'parent_id' => $parentId
                ],
                [
                    'title_hi' => $menuData['title'],
                    'type' => 'URL',
                    'url' => $url,
                    'icon_type' => 'ICON',
                    'icon_png' => $icon,
                    'permission_group' => $menuData['group'] ?? $menuData['title'],
                    'order' => $order,
                    'is_caption' => $isCaption,
                    'created_by' => 1,
                ]
            );

            if (isset($menuData['children'])) {
                $this->seedMenus($menuData['children'], $menu->id);
            }
        }
    }
}
