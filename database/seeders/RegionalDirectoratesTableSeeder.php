<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegionalDirectorate;
use Illuminate\Support\Facades\DB;

class RegionalDirectoratesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $lists = [
            [
                'regional_directorate' => 'RD Bhopal',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय भोपाल',
                'title' => 'Sh. A K Vidyarthi',
                'title_hi' => 'श्री ए के विद्यार्थी',
                'designation' => 'Scientist \'F\'',
                'designation_hi' => 'वैज्ञानिक \'एफ\'',
                'email' => 'cpcb.bhopal@gov.in, vidyarthi.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 1,
            ],
            [
                'regional_directorate' => 'RD Bengaluru',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय  बेंगलुरु',
                'title' => 'Sh. J C Babu',
                'title_hi' => 'श्री जे सी बाबू',
                'designation' => 'Scientist \'F\'',
                'designation_hi' => 'वैज्ञानिक \'ई\'',
                'email' => 'jcb.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 2,
            ],
            [
                'regional_directorate' => 'RD Lucknow',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय  लखनऊ',
                'title' => 'Sh. Kamal Kumar',
                'title_hi' => 'श्री कमल कुमार',
                'designation' => 'Scientist \'E\'',
                'designation_hi' => 'वैज्ञानिक \'ई\'',
                'email' => 'rdlucknow.cpcb@gov.in, kamalkumar.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 3,
            ],
            [
                'regional_directorate' => 'RD Kolkata',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय  कोलकाता',
                'title' => 'Sh. M.K. Biswas',
                'title_hi' => 'श्री एम के बिसवास',
                'designation' => 'Scientist \'E\'',
                'designation_hi' => 'वैज्ञानिक \'ई\'',
                'email' => 'mkbiswas.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 4,
            ],
            [
                'regional_directorate' => 'RD Vadodara',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय  वडोदरा',
                'title' => 'Sh. Arvind Kumar Jha',
                'title_hi' => 'श्री अरविंद कुमार झा',
                'designation' => 'Scientist \'E\'',
                'designation_hi' => 'वैज्ञानिक \'ई\'',
                'email' => 'arvindjha.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 5,
            ],
            [
                'regional_directorate' => 'RD Chennai',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय चेन्नई',
                'title' => 'Smt. H D Varalaxmi',
                'title_hi' => 'श्रीमती एच डी वारालक्ष्मी',
                'designation' => 'Scientist \'F\'',
                'designation_hi' => 'वैज्ञानिक \'ई\'',
                'email' => 'vlaxmi.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 6,
            ],
            [
                'regional_directorate' => 'RD Pune',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय पुणे',
                'title' => 'Sh. Pratik D Bharne',
                'title_hi' => 'श्री प्रतीक डी भरणे',
                'designation' => 'Scientist \'F\'',
                'designation_hi' => 'वैज्ञानिक \'ई\'',
                'email' => 'pratik.cpcb@gov.in, pdbharne.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 7,
            ],
            [
                'regional_directorate' => 'RD Chandigarh',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय चंडीगढ़',
                'title' => 'Sh. Narender Sharma',
                'title_hi' => 'श्री नरेंद्र शर्मा',
                'designation' => 'Scientist \'F\'',
                'designation_hi' => 'वैज्ञानिक \'एफ\'',
                'email' => 'narendersharma.cpcb@gov.in, narendersharma.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 8,
            ],
            [
                'regional_directorate' => 'RD Shillong',
                'regional_directorate_hi' => 'क्षेत्रीय निदेशालय शिलांग',
                'title' => 'Sh. Shantanu Dutta',
                'title_hi' => '-',
                'designation' => 'Scientist \'F\'',
                'designation_hi' => '-',
                'email' => 'zoshillong.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 9,
            ],
            [
                'regional_directorate' => 'Project Office Agra',
                'regional_directorate_hi' => 'परियोजना कार्यालय आगरा',
                'title' => 'Sh. Ankur Tiwari',
                'title_hi' => 'श्री अंकुर तिवारी',
                'designation' => 'Scientist \'E\'',
                'designation_hi' => 'वैज्ञानिक \'डी\'',
                'email' => 'poagra.cpcb@nic.in',
                'is_approved' => 1,
                'is_published' => 1,
                'order' => 10,
            ],
        ];

        foreach ($lists as $list) {
            DB::table('regional_directorates')->updateOrInsert(
                ['title' => $list['title']],
                $list
            );
        }
    }
}
