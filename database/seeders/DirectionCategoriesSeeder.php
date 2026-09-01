<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('direction_categories')->truncate();
        /*
        |--------------------------------------------------------------------------
        | Act Type ID = 1 (Industry / Sector Categories)
        |--------------------------------------------------------------------------
        */
        $categoriesActType1 = [
            "General",
            "Aluminium",
            "Cement",
            "Chemical",
            "Chlor Alkali",
            "Copper",
            "Distillery",
            "Dye & Dye",
            "Fertilizer",
            "Food & Beverages",
            "Ganga Basin",
            "Iron & Steel",
            "Oil Refinery",
            "Pesticide",
            "Petrochemical",
            "Petrol Pump",
            "Pharmaceutical",
            "Power plant",
            "Pulp & Paper",
            "Slaughter House",
            "Sugar",
            "Tannery",
            "Textile",
            "Zinc",
            "C & D waste",
            "MSW",
            "Plastic Waste",
            "Common TSDF",
            "Others",
        ];

        /*
        |--------------------------------------------------------------------------
        | Act Type ID = 2 (Acts)
        |--------------------------------------------------------------------------
        */
        $categoriesActType2 = [
            "Air Act",
            "Water Act",
            "Both Air and Water Act",
        ];

        $this->seedByActType(1, $categoriesActType1);
        $this->seedByActType(2, $categoriesActType2);

        $this->command->info("\nDirection categories seeded successfully.");
    }

    /**
     * Common insert/update logic
     */
    private function seedByActType(int $actTypeId, array $categories): void
    {
        $this->command->info("\nSeeding Direction Categories for Act Type ID = {$actTypeId}");

        foreach ($categories as $title) {
            DB::table('direction_categories')->updateOrInsert(
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
