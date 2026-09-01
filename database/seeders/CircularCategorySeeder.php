<?php

namespace Database\Seeders;

use App\Models\CircularCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CircularCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CircularCategory::truncate();
        $circularCategories = [
            [
                'name' => 'Circular',
                'name_hi' => 'परिपत्र',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Memorandum',
                'name_hi' => 'ज्ञापन',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Office Order',
                'name_hi' => 'कार्यालय आदेश',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($circularCategories as $category) {
            CircularCategory::create($category);
        }
        $this->command->info('Circular categories seeded successfully!');
    }
}
