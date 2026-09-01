<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        Designation::truncate();
            $designations = [
                ['title' => 'ACCOUNTS ASSISTANT', 'title_hi' => 'लेखा सहायक'],
                ['title' => 'ACCOUNTS OFFICER', 'title_hi' => 'लेखा अधिकारी'],
                ['title' => 'ADMINISTRATIVE OFFICER', 'title_hi' => 'प्रशासनिक अधिकारी'],
                ['title' => 'ASSISTANT', 'title_hi' => 'सहायक'],
                ['title' => 'ASSISTANT ACCOUNTS OFFICER', 'title_hi' => 'सहायक लेखा अधिकारी'],
                ['title' => 'ASSISTANT DIRECTOR (O.L.)', 'title_hi' => 'सहायक निदेशक (राजभाषा)'],
                ['title' => 'ASSISTANT LAW OFFICER', 'title_hi' => 'सहायक विधि अधिकारी'],
                ['title' => 'DATA ENTRY OPERATOR GRADE-I', 'title_hi' => 'डाटा एंट्री ऑपरेटर ग्रेड-I'],
                ['title' => 'DATA ENTRY OPERATOR GRADE-II', 'title_hi' => 'डाटा एंट्री ऑपरेटर ग्रेड-II'],
                ['title' => 'DATA ENTRY OPERATOR GRADE-II (AD-HOC)', 'title_hi' => 'डाटा एंट्री ऑपरेटर ग्रेड-II (तदर्थ)'],
                ['title' => 'DATA ENTRY OPERATOR GRADE-II (TS)', 'title_hi' => 'डाटा एंट्री ऑपरेटर ग्रेड-II (टी.एस.)'],
                ['title' => 'DATA PROCESSING ASSISTANT', 'title_hi' => 'डाटा प्रोसेसिंग सहायक'],
                ['title' => 'DRAFTING SUPERVISOR', 'title_hi' => 'प्रारूपण पर्यवेक्षक'],
                ['title' => 'DRIVER', 'title_hi' => 'चालक'],
                ['title' => 'DRIVER (ORDINARY GRADE)', 'title_hi' => 'चालक (साधारण ग्रेड)'],
                ['title' => 'DRIVER GRADE-I', 'title_hi' => 'चालक ग्रेड-I'],
                ['title' => 'DRIVER GRADE-II', 'title_hi' => 'चालक ग्रेड-II'],
                ['title' => 'FIELD ATTENDANT', 'title_hi' => 'फील्ड अटेंडेंट'],
                ['title' => 'HINDI TYPIST (TS)', 'title_hi' => 'हिंदी टंकक (टी.एस.)'],
                ['title' => 'JUNIOR HINDI TRANSLATOR', 'title_hi' => 'कनिष्ठ हिंदी अनुवादक'],
                ['title' => 'JUNIOR LABORATORY ASSISTANT', 'title_hi' => 'कनिष्ठ प्रयोगशाला सहायक'],
                ['title' => 'JUNIOR LABORATORY ASSISTANT (TS)', 'title_hi' => 'कनिष्ठ प्रयोगशाला सहायक (टी.एस.)'],
                ['title' => 'JUNIOR TECHNICIAN', 'title_hi' => 'कनिष्ठ तकनीशियन'],
                ['title' => 'LAW OFFICER', 'title_hi' => 'विधि अधिकारी'],
                ['title' => 'LOWER DIVISION CLERK', 'title_hi' => 'अवर श्रेणी लिपिक'],
                ['title' => 'LOWER DIVISION CLERK (TS)', 'title_hi' => 'अवर श्रेणी लिपिक (टी.एस.)'],
                ['title' => 'MEMBER SECRETARY', 'title_hi' => 'सदस्य सचिव'],
                ['title' => 'MULTI TASKING STAFF', 'title_hi' => 'मल्टी टास्किंग स्टाफ'],
                ['title' => 'MULTI TASKING STAFF (ADHOC)', 'title_hi' => 'मल्टी टास्किंग स्टाफ (तदर्थ)'],
                ['title' => 'MULTI TASKING STAFF GRADE-I', 'title_hi' => 'मल्टी टास्किंग स्टाफ ग्रेड-I'],
                ['title' => 'PRIVATE SECRETARY', 'title_hi' => 'निजी सचिव'],
                ['title' => 'PROJECT SCIENTIST-I (PCE)', 'title_hi' => 'परियोजना वैज्ञानिक-I (पीसीई)'],
                ['title' => 'PUBLICATION ASSISTANT', 'title_hi' => 'प्रकाशन सहायक'],
                ['title' => "SCIENTIST 'B'", 'title_hi' => "वैज्ञानिक 'बी'"],
                ['title' => "SCIENTIST 'C'", 'title_hi' => "वैज्ञानिक 'सी'"],
                ['title' => "SCIENTIST 'D'", 'title_hi' => "वैज्ञानिक 'डी'"],
                ['title' => "SCIENTIST 'E'", 'title_hi' => "वैज्ञानिक 'ई'"],
                ['title' => "SCIENTIST 'F'", 'title_hi' => "वैज्ञानिक 'एफ'"],
                ['title' => 'SECTION OFFICER', 'title_hi' => 'अनुभाग अधिकारी'],
                ['title' => 'SENIOR ADMINISTRATIVE OFFICER', 'title_hi' => 'वरिष्ठ प्रशासनिक अधिकारी'],
                ['title' => 'SENIOR DRAUGHTSMAN', 'title_hi' => 'वरिष्ठ ड्राफ्ट्समैन'],
                ['title' => 'SENIOR LABORATORY ASSISTANT', 'title_hi' => 'वरिष्ठ प्रयोगशाला सहायक'],
                ['title' => 'SENIOR LAW OFFICER', 'title_hi' => 'वरिष्ठ विधि अधिकारी'],
                ['title' => 'SENIOR SCIENTIFIC ASSISTANT', 'title_hi' => 'वरिष्ठ वैज्ञानिक सहायक'],
                ['title' => 'SENIOR TECHNICAL SUPERVISOR', 'title_hi' => 'वरिष्ठ तकनीकी पर्यवेक्षक'],
                ['title' => 'SENIOR TRANSLATOR', 'title_hi' => 'वरिष्ठ अनुवादक'],
                ['title' => 'STENOGRAPHER GRADE-I', 'title_hi' => 'आशुलिपिक ग्रेड-I'],
                ['title' => 'TECHNICAL SUPERVISOR', 'title_hi' => 'तकनीकी पर्यवेक्षक'],
                ['title' => 'TECHNICAL SUPERVISOR (TS)', 'title_hi' => 'तकनीकी पर्यवेक्षक (टी.एस.)'],
                ['title' => 'UPPER DIVISION CLERK', 'title_hi' => 'उच्च श्रेणी लिपिक'],
            ];

        $now = Carbon::now();

        foreach ($designations as $designation) {
            Designation::updateOrCreate(
                ['title' => $designation['title']],
                [
                    'title' => $designation['title'],
                    'title_hi' => $designation['title_hi'],
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
