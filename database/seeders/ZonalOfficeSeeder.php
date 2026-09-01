<?php

namespace Database\Seeders;

use App\Models\ZonalOffice;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ZonalOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            ['en' => 'CPCB, Delhi', 'hi' => 'सीपीसीबी, दिल्ली'],
            ['en' => 'CPCB-HO', 'hi' => 'सीपीसीबी-मुख्यालय'],
            ['en' => 'CPCB RD Bengaluru', 'hi' => 'सीपीसीबी आरडी बेंगलुरु'],
            ['en' => 'CPCB RD Kolkata', 'hi' => 'सीपीसीबी आरडी कोलकाता'],
            ['en' => 'RD LUCKNOW', 'hi' => 'आरडी लखनऊ'],
            ['en' => 'CPCB RD Bhopal', 'hi' => 'सीपीसीबी आरडी भोपाल'],
            ['en' => 'CPCB, RD Vadodara', 'hi' => 'सीपीसीबी आरडी वडोदरा'],
            ['en' => 'CPCB, RD Shillong', 'hi' => 'सीपीसीबी आरडी शिलांग'],
            ['en' => 'CPCB RD PUNE', 'hi' => 'सीपीसीबी आरडी पुणे'],
            ['en' => 'CPCB, RD Chennai', 'hi' => 'सीपीसीबी आरडी चेन्नई'],
            ['en' => 'Civil Construction Unit, MoEF&CC', 'hi' => 'सिविल निर्माण इकाई, MoEF&CC'],
            ['en' => 'CPCB Project Office, Agra', 'hi' => 'सीपीसीबी परियोजना कार्यालय, आगरा'],
        ];

        $now = Carbon::now();

        foreach ($offices as $office) {
            ZonalOffice::updateOrCreate(
                ['title' => $office['en']],
                [
                    'title' => $office['en'],
                    'title_hi' => $office['hi'],
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
