<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('direction_types')->truncate();
        /*
        |--------------------------------------------------------------------------
        | Act Type ID = 1
        |--------------------------------------------------------------------------
        */
        $typesActType1 = [
            "Environmental Compensation",
            "Environmental Compensation & Show cause",
            "Environmental Compensation & Closure",
            "Environmental Compensation & Revoked",
            "Closure direction",
            "Show cause Direction",
            "General Direction",
            "Revoked Direction",
            "Modified Direction",
            "Analyse report",
        ];

        /*
        |--------------------------------------------------------------------------
        | Act Type ID = 2
        |--------------------------------------------------------------------------
        */
        $typesActType2 = [
            "Closure direction",
            "Show cause notice",
            "General Direction",
            "Analyse report",
            "Revoked Direction",
            "Modified Direction",
        ];

        $this->seedByActType(1, $typesActType1);
        $this->seedByActType(2, $typesActType2);

        $this->command->info("\nDirection types seeded successfully.");
    }

    /**
     * Common seed logic
     */
    private function seedByActType(int $actTypeId, array $types): void
    {
        $this->command->info("\nSeeding Direction Types for Act Type ID = {$actTypeId}");

        foreach ($types as $title) {
            DB::table('direction_types')->updateOrInsert(
                [
                    'title' => $title,
                    'direction_act_type_id' => $actTypeId,
                ],
                [
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
    }
}
