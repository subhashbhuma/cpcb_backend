<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->truncate();
        // Ensure roles exist
        $adminRole = Role::firstOrCreate([
            'name' => 'ADMIN',
            'guard_name' => 'web'
        ]);

        $superAdminRole = Role::firstOrCreate([
            'name' => 'SUPERADMIN',
            'guard_name' => 'web'
        ]);
        // $approverRole = Role::where('name', 'APPROVER')->first();
        // $editorRole = Role::where('name', 'EDITOR')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'id' => 1,
                'name' => 'Admin User',
                'mobile_number' => '9988998899',
                'password' => 'password',
                'created_by' => 1,
                'updated_by' => 1
            ]
        );

        $admin->syncRoles([$adminRole]);

        // Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'id' => 2,
                'name' => 'Super Admin User',
                'mobile_number' => '1111111111',
                'password' => 'password',
                'created_by' => 1,
                'updated_by' => 1
            ]
        );

        $superAdmin->syncRoles([$superAdminRole]);

        // // Create Approver User
        // $approver = User::create([
        //     'name' => 'Approver User',
        //     'email' => 'approver@example.com',
        //     'password' => Hash::make('password'),
        // ]);
        // $approver->assignRole($approverRole);

        // // Create Editor User
        // $editor = User::create([
        //     'name' => 'Editor User',
        //     'email' => 'editor@example.com',
        //     'password' => Hash::make('password'),
        // ]);
        // $editor->assignRole($editorRole);
    }
}
