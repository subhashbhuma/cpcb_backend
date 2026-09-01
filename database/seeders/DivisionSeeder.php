<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['title' => 'Chairman', 'title_hi' => 'अध्यक्ष'],
            ['title' => 'Member Secretary', 'title_hi' => 'सदस्य सचिव'],
            ['title' => 'Industrial Pollution Control Divisions', 'title_hi' => 'उद्योग प्रदूषण नियंत्रण विभाग'],
            ['title' => 'Urban Pollution Control Divisions', 'title_hi' => 'शहरी प्रदूषण नियंत्रण विभाग'],
            ['title' => 'Waste Management Divisions', 'title_hi' => 'कचरा प्रबंधन विभाग'],
            ['title' => 'Air Quality Management Divisions', 'title_hi' => 'वायु गुणवत्ता प्रबंधन विभाग'],
            ['title' => 'Laboratory Divisions', 'title_hi' => 'प्रयोगशाला विभाग'],
            ['title' => 'Administrative Divisions', 'title_hi' => 'प्रशासनिक विभाग'],
            ['title' => 'Other Divisions', 'title_hi' => 'अन्य विभाग'],
        ];

        $now = Carbon::now();

        foreach ($divisions as $division) {
            Division::updateOrCreate(
                ['title' => $division['title']],
                [
                    'title' => $division['title'],
                    'title_hi' => $division['title_hi'],
                    'is_approved' => 1,
                    'is_published' => 1,
                    'created_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
