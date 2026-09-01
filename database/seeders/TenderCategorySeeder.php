<?php

namespace Database\Seeders;

use App\Models\TenderCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TenderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['en' => 'Lab Items', 'hi' => 'प्रयोगशाला की वस्तुएं'],
            ['en' => 'Security Guards', 'hi' => 'सुरक्षा कर्मी'],
            ['en' => 'Canteen and Catering', 'hi' => 'कैंटीन और खान-पान'],
            ['en' => 'UPS', 'hi' => 'यूपीएस'],
            ['en' => 'Consultancy Services', 'hi' => 'परामर्श सेवाएँ'],
            ['en' => 'CAMC', 'hi' => 'सीएएमसी'],
            ['en' => 'Conference Facilities', 'hi' => 'सम्मेलन सुविधाएँ'],
            ['en' => 'Hiring of Vehicles', 'hi' => 'वाहनों को किराए पर लेना'],
            ['en' => 'Software development', 'hi' => 'सॉफ्टवेयर विकास'],
            ['en' => 'Miscellaneous works', 'hi' => 'विविध कार्य'],
            ['en' => 'Refilling of Gas', 'hi' => 'गैस की रिफिलिंग'],
            ['en' => 'Earth Pits', 'hi' => 'अर्थ पिट्स'],
            ['en' => 'Weighing Balance', 'hi' => 'वजन संतुलन'],
            ['en' => 'Civil & Electrical', 'hi' => 'सिविल और इलेक्ट्रिकल'],
            ['en' => 'Printers', 'hi' => 'प्रिंटर'],
            ['en' => 'Elevators', 'hi' => 'लिफ्ट'],
            ['en' => 'Chemicals', 'hi' => 'रसायन'],
            ['en' => 'Glassware', 'hi' => 'कांच का सामान'],
            ['en' => 'Water Purification system', 'hi' => 'जल शोधन प्रणाली'],
        ];

        $now = Carbon::now();

        foreach ($categories as $category) {
            TenderCategory::updateOrCreate(
                ['title' => $category['en']],
                [
                    'title' => $category['en'],
                    'title_hi' => $category['hi'],
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
