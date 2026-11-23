<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class DisableUsersExceptAdminApproverAndEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Disables users by setting deleted_at to the current datetime
     * where email is not in ('admin@example.com', 'approve@gmail.com')
     * and role is not EMPLOYEE.
     */
    public function run(): void
    {
        $excludedEmails = [
            'admin@example.com',
            'superadmin@gmail.com',
        ];

        $now = Carbon::now();

        // Find users who are not deleted, not in the excluded emails list,
        // and do not have the EMPLOYEE role.
        $affectedUsers = User::withoutTrashed()
            ->whereNotIn('email', $excludedEmails)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'EMPLOYEE');
            });

        $count = $affectedUsers->count();

        $affectedUsers->update([
            'deleted_at' => $now,
            'updated_at' => $now,
        ]);

        $this->command->info("Successfully disabled {$count} user(s) (set deleted_at to {$now->toDateTimeString()}).");
    }
}
