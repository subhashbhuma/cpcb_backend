<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class EmployeeMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'title' => 'Circulars',
                'icon' => 'ti ti-bell',
                'order' => 2,
                'location' => 'employee',
                'children' => [
                    ['location' => 'employee', 'title' => 'Circular', 'group' => 'Circular Circular', 'url' => 'secure/circulars/circular', 'order' => 1],
                    ['location' => 'employee', 'title' => 'Office Order', 'group' => 'Circular Office Order', 'url' => 'secure/circulars/office_order', 'order' => 2],
                    ['location' => 'employee', 'title' => 'Memorandum', 'group' => 'Circular Memorandum', 'url' => 'secure/circulars/memorandum', 'order' => 3],
                ]
            ],
        ];

        $this->seedMenus($menus);
    }

    /**
     * Recursive helper to seed menus.
     */
    private function seedMenus(array $menus, $parentId = null): void
    {
        foreach ($menus as $index => $menuData) {
            $url = $menuData['url'] ?? '#';
            $icon = $menuData['icon'] ?? null;
            $order = $menuData['order'] ?? ($index + 1);

            $menu = Menu::updateOrCreate(
                [
                    'location' => 'employee',
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
                    'created_by' => 1,
                ]
            );

            if (isset($menuData['children'])) {
                $this->seedMenus($menuData['children'], $menu->id);
            }
        }
    }
}

