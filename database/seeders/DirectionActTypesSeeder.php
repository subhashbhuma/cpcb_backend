<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionActTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actTypesArr = [
            "Section 18(1)(b)",
            "Section 5 EP Act",
        ];

        $this->command->info("Seeding " . count($actTypesArr) . " direction act types...");

        foreach ($actTypesArr as $title) {
            DB::table('direction_act_types')->updateOrInsert(
                ['title' => $title],
                [
                    'title_hi'     => translateToHindi($title),
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

        $this->command->info("\nDirection act types seeded successfully.");
    }
}
