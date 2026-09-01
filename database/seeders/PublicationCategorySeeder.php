<?php

namespace Database\Seeders;

use App\Models\PublicationCategory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PublicationCategorySeeder extends Seeder
{
    public function run()
    {
        // English Data
        $en_data = [
            1 => 'Control of Urban Pollution Series (CUPS)',
            2 => 'Programme Objectives Series (PROBES)',
            3 => 'Comprehensive Industry Document Series (COINDS)',
            4 => 'Assessment and Development Study of River Basin Series (ADSORBS)',
            5 => 'Coastal Pollution Control Series (COPOCS)',
            6 => 'Laboratory Analytical Techniques Series (LATS)',
            7 => 'Monitoring of Indian Notional Aquatic Resources Series (MINARS)',
            8 => 'National Ambient Air Quality Monitoring Series (NAAQMS)',
            9 => 'Ecological Imact Assessment Series (EIAS)',
            10 => 'Pollution Control Law Series (PCLS)',
            11 => 'Hazardous Waste Management Series (HAZWAMS)',
            12 => 'Resource Recycling Series (RERES)',
            13 => 'Ground Water Quality Series (GWQS)',
            14 => 'Information Manual on Pollution Abatement and Cleaner Technologies Series (IMPACTS)',
            15 => 'Environmental Mapping and Planning Series (EMAPS)',
            16 => 'Trace Organic Series (TOS)',
            17 => 'National Ambient Noise Monitoring Network (NANMN)',
            18 => 'Interstate state River Boundry Monitoring (IRBM)'
        ];

        // Hindi Data
        $hi_data = [
            1 => 'शहरी प्रदूषण नियंत्रण श्रृंखला (सी यू पी एस)',
            2 => 'कार्यक्रम उद्देश्य श्रृंखला (पी आर ओ बी ई एस)',
            3 => 'व्यापक उद्योग दस्तावेज़ शृंखला (सी ओ आई एन डी एस)',
            4 => 'नदी बेसिन का आकलन एवं विकास अध्ययन श्रृंखला (ए डी एस ओ आर बी एस)',
            5 => 'तटीय प्रदूषण नियंत्रण श्रृंखला (सी ओ पी ओ सी एस)',
            6 => 'प्रयोगशाला विश्लेषण तकनीक श्रृंखला (एल ए टी एस)',
            7 => 'भारतीय राष्ट्रीय जलीय संसाधन श्रृंखला (एम आई एन ए आर एस)',
            8 => 'नेशनल एम्बिएंट एयर क्वालिटी मानीटरिंग श्रृंखला (एन ए ए क्यू एम एस)',
            9 => 'पारिस्थितिकीय प्रभाव आकलन श्रृंखला (ई आई ए एस)',
            10 => 'प्रदूषण नियंत्रण कानून श्रृंखला (पी सी एल एस)',
            11 => 'खतरनाक अपशिष्ट प्रबंधन श्रृंखला (एच ए ज़ेड ए एम एस)',
            12 => 'संसाधन री-साइक्लिंग श्रृंखला (आर ई आर ई एस)',
            13 => 'भू-जल गुणवत्ता श्रृंखला (जी डबल्यू क्यू एस)',
            14 => 'प्रदूषण उपशमन एवं स्वच्छ प्रौद्योगिकी सूचना मैन्युअल श्रृंखला (आई एम पी ए सी टी एस)',
            15 => 'पर्यावरणीय मैपिंग इवान योजना श्रृंखला (ई एम ए पी एस)',
            16 => 'ट्रेस ओरगनिक श्रृंखला (टी ओ एस)',
            17 => 'राष्ट्रीय एम्बिएंट शोर मानीटरिंग नेटवर्क (एन ए एन एम एन)',
            18 => 'अंतर्राज्यीय नदी सीमा निगरानी (आई आर बी एम)'
        ];

        foreach ($en_data as $id => $en_item) {
            // Extract English Title and Code
            preg_match('/^(.*)\s\((.*)\)$/', $en_item, $en_matches);
            $title_en = isset($en_matches[1]) ? trim($en_matches[1]) : $en_item;
            $code_en  = isset($en_matches[2]) ? trim($en_matches[2]) : null;

            // Extract Hindi Title and Code
            $hi_item = $hi_data[$id] ?? $en_item;
            preg_match('/^(.*)\s\((.*)\)$/', $hi_item, $hi_matches);
            $title_hi = isset($hi_matches[1]) ? trim($hi_matches[1]) : $hi_item;
            $code_hi  = isset($hi_matches[2]) ? trim($hi_matches[2]) : null;

            PublicationCategory::updateOrCreate(
                ['id' => $id], // Use ID to ensure alignment
                [
                    'title'         => $title_en,
                    'title_hi'      => $title_hi,
                    'code'          => $code_en,
                    'code_hi'       => $code_hi,
                    'is_approved'   => 1,
                    'is_published'  => 1,
                    'created_by'    => 1,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]
            );
        }
    }
}
