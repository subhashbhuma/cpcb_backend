<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UnlockAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('email', 'admin@example.com')->update([
            'lockout_until' => null,
            'failed_logins' => 0,
            'current_session_id' => null,
            'session_id' => null
            // , 'password' => Hash::make('password')
        ]);
    }
}
