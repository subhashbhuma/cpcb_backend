<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('menu_locations')->truncate();
        $locations = [
            [
                'id' => 1,
                'location_code' => 'header',
                'location_name' => 'Header',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'location_code' => 'inner-pages',
                'location_name' => 'Inner Pages',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'location_code' => 'footer-quickLink',
                'location_name' => 'Useful Links',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'location_code' => 'top-header',
                'location_name' => 'Top Header',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'location_code' => 'useful-links',
                'location_name' => 'Useful Links',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'location_code' => 'default',
                'location_name' => 'Default',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'location_code' => 'importantLink',
                'location_name' => 'Important Links',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'location_code' => 'sidebar',
                'location_name' => 'Sidebar',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'location_code' => 'employee',
                'location_name' => 'Employees Corner',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Using updateOrInsert to prevent duplicate errors if run multiple times
        foreach ($locations as $location) {
            DB::table('menu_locations')->updateOrInsert(
                ['id' => $location['id']],
                $location
            );
        }
    }
}
