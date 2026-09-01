<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionTableSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠ Remove truncate in production
        DB::table('divisions')->truncate();

        /*
        |--------------------------------------------------------------------------
        | 1. Hardcoded Divisions
        |--------------------------------------------------------------------------
        */

        $staticDivisions = [
            ['division' => 'Chairman Section', 'division_hi' => 'अध्यक्ष अनुभाग'],
            ['division' => 'Member Secretary Section', 'division_hi' => 'सदस्य सचिव अनुभाग'],
            ['division' => 'PCP Division', 'division_hi' => 'पीसीपी विभाग'],
            ['division' => 'PR Division', 'division_hi' => 'जन संपर्क प्रभाग'],
            ['division' => 'ETU Division', 'division_hi' => 'ईटीयू प्रभाग'],
            ['division' => 'IPC -I Division', 'division_hi' => 'आईपीसी -1 प्रभाग'],
            ['division' => 'IPC-II Division', 'division_hi' => 'आईपीसी -2 प्रभाग'],
            ['division' => 'IPC-III Division', 'division_hi' => 'आईपीसी-3 प्रभाग'],
            ['division' => 'IPC-IV Division', 'division_hi' => 'आईपीसी-4 प्रभाग'],
            ['division' => 'IPC-V Division', 'division_hi' => 'आईपीसी-5 प्रभाग'],
            ['division' => 'IPC-VI Division', 'division_hi' => 'आईपीसी-6 प्रभाग'],
            ['division' => 'IPC-VII Division', 'division_hi' => 'आईपीसी-7 प्रभाग'],
            ['division' => 'UPC - I Division', 'division_hi' => 'यूपीसी - 1 प्रभाग'],
            ['division' => 'UPC - II Division', 'division_hi' => 'यूपीसी - 2 प्रभाग'],
            ['division' => 'WM - I Division', 'division_hi' => 'डब्ल्यूएम - 1 प्रभाग'],
            ['division' => 'WM - II Division', 'division_hi' => 'डब्ल्यूएम - 2 प्रभाग'],
            ['division' => 'WM - III Division', 'division_hi' => 'डब्ल्यूएम - 3 प्रभाग'],
            ['division' => 'AQM Division', 'division_hi' => 'एक्यूएम प्रभाग'],
            ['division' => 'AQMN Division', 'division_hi' => 'एक्यूएमन प्रभाग'],
            ['division' => 'WQM - I Division', 'division_hi' => 'डब्ल्यूक्यूएम - 1 प्रभाग'],
            ['division' => 'WQM - II Division', 'division_hi' => 'डब्ल्यूक्यूएम - 2 प्रभाग'],
            ['division' => 'IT Division', 'division_hi' => 'सूचना प्रौद्योगिकी प्रभाग'],
            ['division' => 'Circular Economy Cell', 'division_hi' => 'सर्कुलर इकोनॉमी सेल'],
            ['division' => 'R & D Division', 'division_hi' => 'आर एंड डी प्रभाग'],
            ['division' => 'Law Division', 'division_hi' => 'कानून प्रभाग'],
            ['division' => 'Air Quality Lab', 'division_hi' => 'वायु गुणवत्ता प्रयोगशाला'],
            ['division' => 'Instrumentation Lab', 'division_hi' => 'इंस्ट्रुमेंटेशन प्रयोगशाला'],
            ['division' => 'Water & Wastewater Lab', 'division_hi' => 'जल और अपशिष्ट प्रयोगशाला'],
            ['division' => 'Bio Lab', 'division_hi' => 'बायो लैब'],
            ['division' => 'Building Division', 'division_hi' => 'बिल्डिंग प्रभाग'],
            ['division' => 'Library', 'division_hi' => 'लाइब्रेरी'],
            ['division' => 'Account Section', 'division_hi' => 'लेखा अनुभाग'],
            ['division' => 'Admin. Recruitment Section', 'division_hi' => 'व्यवस्थापक भर्ती अनुभाग'],
            ['division' => 'Admin. Personnel Section', 'division_hi' => 'व्यवस्थापक कार्मिक अनुभाग'],
            ['division' => 'Admin. Material Section', 'division_hi' => 'सामग्री अनुभाग'],
            ['division' => 'Admin. Coordination Div.', 'division_hi' => 'व्यवस्थापक समन्वय और चिकित्सा अनुभाग'],
            ['division' => 'Hindi Section', 'division_hi' => 'हिंदी अनुभाग'],
            ['division' => 'AQM', 'division_hi' => 'ए क्यू एम'],
            ['division' => 'UPC-I', 'division_hi' => 'यूपीसी - I'],
        ];

        /*
        |--------------------------------------------------------------------------
        | 2. Insert Static Divisions
        |--------------------------------------------------------------------------
        */

        foreach ($staticDivisions as $division) {

            $divisionName = trim($division['division']);
            $divisionHi   = trim($division['division_hi'] ?? '');

            if (empty($divisionHi)) {
                $divisionHi = $divisionName; // fallback
            }

            DB::table('divisions')->updateOrInsert(
                ['title' => $divisionName],
                [
                    'title_hi' => $divisionHi,
                    'is_approved'      => 1,
                    'is_published'     => 1,
                    'created_by'       => 1,
                    'created_at'       => now(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Get Legacy Divisions
        |--------------------------------------------------------------------------
        */

        $directoryDivisions = DB::connection('mysql_legacy_en')
            ->table('cmsm_directory')
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->pluck('division')
            ->toArray();

        $reportDivisions = DB::connection('mysql_legacy_en')
            ->table('cmsm_report')
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->pluck('division')
            ->toArray();

        $legacyDivisions = array_unique(
            array_map('trim', array_merge($directoryDivisions, $reportDivisions))
        );

        /*
        |--------------------------------------------------------------------------
        | 4. Insert Legacy Divisions (Skip duplicates automatically)
        |--------------------------------------------------------------------------
        */

        foreach ($legacyDivisions as $divisionName) {

            if (empty($divisionName)) continue;

            DB::table('divisions')->updateOrInsert(
                ['title' => $divisionName],
                [
                    'title_hi' => translateToHindi($divisionName),
                    'is_approved'      => 1,
                    'is_published'     => 1,
                    'created_by'       => 1,
                    'created_at'       => now(),
                ]
            );
        }

        $this->command->info('Divisions migrated successfully.');
    }
}