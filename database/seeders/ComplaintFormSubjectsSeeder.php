<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintFormSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            "Visible Vehicular Emission",
            "Traffic Congestion",
            "Industrial Emission",
            "Open/Garbage Burning",
            "Construction/Demolition Activity",
            "Leaf Burning",
            "Road Dust",
            "Unpved Road/Pit",
            "Fire in Landfill Sites",
            "Air Pollution from Generator",
            "Pollution from Illegal Industry",
            "Open Dumping of Garbage",
            "Industrial waste Dumping",
            "Others"
        ];

        $this->command->info("Seeding " . count($subjects) . " complaint subjects...");

        foreach ($subjects as $subject) {
            DB::table('complaint_form_subjects')->updateOrInsert(
                ['title' => $subject],
                [
                    'title_hi'     => translateToHindi($subject),
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

        $this->command->info("\nComplaint subjects seeded successfully.");
    }
}