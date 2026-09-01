<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\DivisionOrder;

class DivisionOrderSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['order_no' => 1, 'title' => 'Chairman Section', 'title_hi' => 'अध्यक्ष अनुभाग'],
            ['order_no' => 2, 'title' => 'MS Section', 'title_hi' => 'सदस्य सचिव अनुभाग'],
            ['order_no' => 3, 'title' => 'PCP Div.', 'title_hi' => 'पीसीपी प्रभाग'],
            ['order_no' => 4, 'title' => 'PR Div.', 'title_hi' => 'पीआर प्रभाग'],
            ['order_no' => 5, 'title' => 'ETU', 'title_hi' => 'ईटीयू'],

            ['order_no' => 6, 'title' => 'IPC-I', 'title_hi' => 'आईपीसी-I'],
            ['order_no' => 7, 'title' => 'IPC-II', 'title_hi' => 'आईपीसी-II'],
            ['order_no' => 8, 'title' => 'IPC-III', 'title_hi' => 'आईपीसी-III'],
            ['order_no' => 9, 'title' => 'IPC-IV', 'title_hi' => 'आईपीसी-IV'],
            ['order_no' => 10, 'title' => 'IPC-V', 'title_hi' => 'आईपीसी-V'],
            ['order_no' => 11, 'title' => 'IPC-VI', 'title_hi' => 'आईपीसी-VI'],
            ['order_no' => 12, 'title' => 'IPC-VII', 'title_hi' => 'आईपीसी-VII'],

            ['order_no' => 13, 'title' => 'UPC-I', 'title_hi' => 'यूपीसी-I'],
            ['order_no' => 14, 'title' => 'UPC-II', 'title_hi' => 'यूपीसी-II'],

            ['order_no' => 15, 'title' => 'WM-I', 'title_hi' => 'डब्ल्यूएम-I'],
            ['order_no' => 16, 'title' => 'WM-II', 'title_hi' => 'डब्ल्यूएम-II'],
            ['order_no' => 17, 'title' => 'WM-III', 'title_hi' => 'डब्ल्यूएम-III'],

            ['order_no' => 18, 'title' => 'AQM', 'title_hi' => 'एक्यूएम'],
            ['order_no' => 19, 'title' => 'AQMN', 'title_hi' => 'एक्यूएमएन'],

            ['order_no' => 20, 'title' => 'WQM-I', 'title_hi' => 'डब्ल्यूक्यूएम-I'],
            ['order_no' => 21, 'title' => 'WQM-II', 'title_hi' => 'डब्ल्यूक्यूएम-II'],

            ['order_no' => 22, 'title' => 'IT Division', 'title_hi' => 'आईटी प्रभाग'],

            ['order_no' => 23, 'title' => 'Air Lab', 'title_hi' => 'वायु प्रयोगशाला'],
            ['order_no' => 24, 'title' => 'Biological Lab', 'title_hi' => 'जैविक प्रयोगशाला'],
            ['order_no' => 25, 'title' => 'Instrumentation Lab', 'title_hi' => 'उपकरण प्रयोगशाला'],
            ['order_no' => 26, 'title' => 'Trace Organic Lab', 'title_hi' => 'ट्रेस ऑर्गेनिक प्रयोगशाला'],
            ['order_no' => 27, 'title' => 'Water & Waste Water', 'title_hi' => 'जल एवं अपशिष्ट जल'],

            ['order_no' => 28, 'title' => 'Law Div.', 'title_hi' => 'विधि प्रभाग'],

            ['order_no' => 29, 'title' => 'Research & Development', 'title_hi' => 'अनुसंधान एवं विकास'],
            ['order_no' => 30, 'title' => 'Administration (Coordination)', 'title_hi' => 'प्रशासन (समन्वय)'],
            ['order_no' => 31, 'title' => 'Administration (Recruitment)', 'title_hi' => 'प्रशासन (भर्ती)'],
            ['order_no' => 32, 'title' => 'Administration (Personal)', 'title_hi' => 'प्रशासन (कार्मिक)'],
            ['order_no' => 33, 'title' => 'Administration (Material)', 'title_hi' => 'प्रशासन (सामग्री)'],

            ['order_no' => 34, 'title' => 'Finance & Account', 'title_hi' => 'वित्त एवं लेखा'],
            ['order_no' => 35, 'title' => 'New Pension Scheme', 'title_hi' => 'नई पेंशन योजना'],
            ['order_no' => 36, 'title' => 'Building Division', 'title_hi' => 'भवन प्रभाग'],
            ['order_no' => 37, 'title' => 'Hindi section', 'title_hi' => 'हिंदी अनुभाग'],
            ['order_no' => 38, 'title' => 'OH & SMS', 'title_hi' => 'ओएच एवं एसएमएस'],
            ['order_no' => 39, 'title' => 'Lab Mangt. System', 'title_hi' => 'प्रयोगशाला प्रबंधन प्रणाली'],

            ['order_no' => 40, 'title' => 'RD Bengaluru', 'title_hi' => 'आरडी बेंगलुरु'],
            ['order_no' => 41, 'title' => 'RD Bhopal', 'title_hi' => 'आरडी भोपाल'],
            ['order_no' => 42, 'title' => 'RD Kolkata', 'title_hi' => 'आरडी कोलकाता'],
            ['order_no' => 43, 'title' => 'RD Lucknow', 'title_hi' => 'आरडी लखनऊ'],
            ['order_no' => 44, 'title' => 'RD Shillong', 'title_hi' => 'आरडी शिलांग'],
            ['order_no' => 45, 'title' => 'RD Vadodara', 'title_hi' => 'आरडी वडोदरा'],
            ['order_no' => 46, 'title' => 'RD Pune', 'title_hi' => 'आरडी पुणे'],
            ['order_no' => 47, 'title' => 'RD Chandigarh', 'title_hi' => 'आरडी चंडीगढ़'],
            ['order_no' => 48, 'title' => 'RD Chennai', 'title_hi' => 'आरडी चेन्नई'],
            ['order_no' => 49, 'title' => 'PO Agra', 'title_hi' => 'पीओ आगरा'],
        ];

        $now = Carbon::now();

        foreach ($divisions as $division) {
            DivisionOrder::updateOrCreate(
                ['order_no' => $division['order_no']],
                [
                    'title' => $division['title'],
                    'title_hi' => $division['title_hi'],
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}