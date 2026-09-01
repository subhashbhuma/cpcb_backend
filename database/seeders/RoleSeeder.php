<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();
        Role::firstOrCreate(
            ['id' => 1, 'name' => 'ADMIN', 'guard_name' => 'web', 'landing_page_url' => '/secure/dashboard/admin']
        );
        Role::firstOrCreate(
            ['id' => 2, 'name' => 'SUPERADMIN', 'guard_name' => 'web', 'landing_page_url' => '/secure/dashboard/superadmin']
        );
        Role::firstOrCreate(
            ['id' => 3, 'name' => 'PUBLISHER', 'guard_name' => 'web', 'landing_page_url' => '/secure/dashboard/publisher']
        );
        Role::firstOrCreate(
            ['id' => 4, 'name' => 'APPROVER', 'guard_name' => 'web', 'landing_page_url' => '/secure/dashboard/approver']
        );
        Role::firstOrCreate(
            ['id' => 5, 'name' => 'DATA_ENTRY_OPERATOR', 'guard_name' => 'web', 'landing_page_url' => '/secure/dashboard/data_entry_operator']
        );
        Role::firstOrCreate(
            ['id' => 6, 'name' => 'EMPLOYEE', 'guard_name' => 'web', 'landing_page_url' => '/secure/dashboard/employee']
        );
    }
}
