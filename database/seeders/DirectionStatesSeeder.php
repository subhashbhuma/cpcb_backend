<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('direction_states')->truncate();
        $states = [
            // Common / Collective
            "Common to All",
            "All States",
            "All UTs",
            "All States/UTs",
            "Ganga Basin States",

            // States
            "Andhra Pradesh",
            "Arunachal Pradesh",
            "Assam",
            "Bihar",
            "Chhattisgarh",
            "Goa",
            "Gujarat",
            "Haryana",
            "Himachal Pradesh",
            "Jammu & Kashmir",
            "Jharkhand",
            "Karnataka",
            "Kerala",
            "Madhya Pradesh",
            "Maharashtra",
            "Manipur",
            "Meghalaya",
            "Mizoram",
            "Nagaland",
            "Odisha",
            "Punjab",
            "Rajasthan",
            "Sikkim",
            "Tamil Nadu",
            "Telangana",
            "Tripura",
            "Uttar Pradesh",
            "Uttarakhand",
            "West Bengal",

            // UTs
            "Andaman & Nicobar Islands",
            "Chandigarh",
            "Delhi",
            "Dadra & Nagar Haveli and Daman & Diu",
            "Lakshadweep",
            "Puducherry",
            "Ladakh",
        ];

        $this->command->info("Seeding " . count($states) . " direction states...");

        foreach ($states as $state) {
            DB::table('direction_states')->updateOrInsert(
                ['title' => $state],
                [
                    'title_hi'     => $state,
                    'is_approved'  => 1,
                    'is_published' => 1,
                    'created_by'   => 1,
                    'updated_by'   => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );

            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nDirection states seeded successfully.");
    }
}
