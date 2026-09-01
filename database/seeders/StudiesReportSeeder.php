<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudiesReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('studies_reports')->truncate();
        $reports = [
            [
                'title' => 'Health impact assessment of fire crackers bursting during Dusshera & Diwali',
                'title_hi' => 'दशहरा और दिवाली के दौरान पटाखे फोड़ने का स्वास्थ्य पर प्रभाव का आकलन',
                'division_id' => 38, // AQM
                'year' => 2017,
                'file' => 'Health-impact-assessment-of-fire-crackers-2017.pdf'
            ],
            [
                'title' => 'Health effects of chronic exposure to smoke from biomass fuel burning in rural households',
                'title_hi' => 'ग्रामीण घरों में जलने वाले बायोमास ईंधन से होने वाले धुएं के क्रोनिक जोखिम के स्वास्थ्य प्रभाव',
                'division_id' => 38, // AQM
                'year' => 2012,
                'file' => 'Health-effects-of-chronic-exposure-smoke-2012.pdf'
            ],
            [
                'title' => 'Epidemiological Study on Effect of Air Pollution on Human Health (Adults) in Delhi',
                'title_hi' => 'दिल्ली में मानव स्वास्थ्य (वयस्कों) पर वायु प्रदूषण के प्रभाव पर महामारी विज्ञान का अध्ययन',
                'division_id' => 38, // AQM
                'year' => 2012,
                'file' => 'Epidemiological_study_Adult_Peer reviewed-2012.pdf'
            ],
            [
                'title' => 'Study on Ambient air quality, respiratory symptoms and lung function of children in Delhi',
                'title_hi' => 'दिल्ली में परिवेशी वायु गुणवत्ता, श्वसन लक्षण और बच्चों के फेफड़ों के कार्य पर अध्ययन',
                'division_id' => 38, // AQM
                'year' => 2012,
                'file' => 'Study-Air-Quality-health-effects_Children-2012.pdf'
            ],
            [
                'title' => 'Awareness note on mobile tower radiation & its impacts on environment',
                'title_hi' => 'मोबाइल टॉवर विकिरण और पर्यावरण पर इसके प्रभावों के बारे में जागरूकता नोट',
                'division_id' => 39, // UPC-I
                'year' => 2015,
                'file' => 'Note_Mobile_Tower_Radiation.pdf'
            ],
            [
                'title' => 'Mobile tower installations in India & its impact on Environment',
                'title_hi' => 'भारत में मोबाइल टावर की स्थापना और पर्यावरण पर इसका प्रभाव',
                'division_id' => 39, // UPC-I
                'year' => 2010,
                'file' => 'Newsletters_68_mobiletower.pdf'
            ],
        ];

        $this->command->info("Seeding " . count($reports) . " study reports...");

        foreach ($reports as $report) {
            DB::table('studies_reports')->updateOrInsert(
                ['title' => $report['title']],
                [
                    'title_hi'     => $report['title_hi'],
                    'division_id'  => $report['division_id'],
                    'report_year'  => $report['year'],
                    'file_name'    => $report['file'],
                    'file_name_hi' => null,
                    'is_approved'  => 1,
                    'is_published' => 1,
                    'created_by'   => 1,
                    'updated_by'   => 1,
                    'created_at'   => '2019-08-12 00:00:00',
                    'updated_at'   => '2019-08-12 00:00:00',
                ]
            );

            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nStudy reports seeded successfully.");
    }
}