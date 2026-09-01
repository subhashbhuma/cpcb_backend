<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('direction_subjects')->truncate();
        $subjectsArr = [
            "Online monitoring",
            "Batteries Management",
            "Biomedical Waste",
            "MSW",
            "Plastic Waste",
            "Sewage treatment Plant",
            "CETP",
            "E-Waste",
            "Waste Tyre",
            "ETP",
            "Air Pollution",
            "Water Pollution",
            "Hazardous waste",
            "Industrial pollution",
            "Vehicular pollution",
            "Urban pollution",
            "Noise pollution",
            "Ganga (GPI)",
            "General",
            "Others",
        ];


        
        $this->command->info("Seeding " . count($subjectsArr) . " direction subjects...");

        foreach ($subjectsArr as $title) {
            DB::table('direction_subjects')->updateOrInsert(
                ['title' => $title],
                [
                    'direction_act_type_id'=>2,
                    'title_hi'     => $title,
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

        $this->command->info("\nDirection subjects seeded successfully.");
    }
}
