<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LettersIssued;
use App\Models\DirectionState;

class LettersIssuedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          DB::table('letters_issued')->truncate();
        $stateMap = [
            'Uttar Pradesh' => 1,
            'Delhi' => 24,
            'Uttarakhand' => 8,
            'West Bengal' => 16,
            'Bihar' => 9,
            'Haryana' => 11,
            'Punjab' => 7,
            'Jharkhand' => 15,
            'Rajasthan' => 18,
            'Karnataka' => 12,
            'Andhra Pradesh' => 23,
            'Odisha' => 5, // Mapping "Odisha " to 5
            'Kerala' => 22,
            'Mizoram' => 35,
            'Lakshdweep' => 30, // Mapping "Lakshdweep" to "Lakshadweep"
            'All SPCBs & PCCs' => 14, // Mapping to "All States/UTs"
            'All States' => 33,
            'All UTs' => 39,
        ];

        $data = [
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Uttar Pradesh',
                'publish_date' => '02.09.2024',
                'file_url' => 'Letter_29.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Delhi',
                'publish_date' => '02.09.2024',
                'file_url' => 'Letter_28.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Uttarakhand',
                'publish_date' => '02.09.2024',
                'file_url' => 'Letter_27.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'West Bengal',
                'publish_date' => '02.09.2024',
                'file_url' => 'Letter_26.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Bihar',
                'publish_date' => '02.09.2024',
                'file_url' => 'Letter_25.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Haryana',
                'publish_date' => '02.09.2024',
                'file_url' => 'Letter_24.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Delhi,  Bihar, Haryana, Jharkhand, UK, UP, West Bengal',
                'publish_date' => '16-12-2022',
                'file_url' => 'Letter_23.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Punjab',
                'publish_date' => '22-07-2021',
                'file_url' => 'Letter_22.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Punjab',
                'publish_date' => '22-07-2021',
                'file_url' => 'Letter_21.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Punjab',
                'publish_date' => '22-07-2021',
                'file_url' => 'Letter_20.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Punjab',
                'publish_date' => '22-07-2021',
                'file_url' => 'Letter_19.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Punjab',
                'publish_date' => '22-07-2021',
                'file_url' => 'Letter_18.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'All SPCBs & PCCs',
                'publish_date' => '29.01.2021',
                'file_url' => 'OM_siting criteria_PP.PDF',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Delhi, UP, Haryana and Rajasthan',
                'publish_date' => '27.01.2021',
                'file_url' => 'Letter_16.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'All SPCBs & PCCs',
                'publish_date' => '12.01.2021',
                'file_url' => '315_1610623895_mediaphoto6740.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Delhi, UP, Haryana and Rajasthan',
                'publish_date' => '14.10.2020',
                'file_url' => 'Letter_15.pdf',
            ],
            [
                'title' => 'MS (as per list)',
                'title_hi' => 'सदस्य सचिव (सूची के अनुसार)',
                'states_text' => 'SPCBs & PCCs',
                'publish_date' => '16.10.2020',
                'file_url' => 'Letter_14.pdf',
            ],
            [
                'title' => 'Chairman',
                'title_hi' => 'अध्यक्ष',
                'states_text' => 'All SPCBs & PCCs',
                'publish_date' => '08.05.2020',
                'file_url' => '294_1588938958_mediaphoto10277.PDF',
            ],
            [
                'title' => 'Chairman',
                'title_hi' => 'अध्यक्ष',
                'states_text' => 'Lakshdweep',
                'publish_date' => '13.06.2019',
                'file_url' => 'Letter_13.pdf',
            ],
            [
                'title' => 'Chairman',
                'title_hi' => 'अध्यक्ष',
                'states_text' => 'Jharkhand',
                'publish_date' => '13.06.2019',
                'file_url' => 'Letter_12.pdf',
            ],
            [
                'title' => 'Chairman',
                'title_hi' => 'अध्यक्ष',
                'states_text' => 'Haryana',
                'publish_date' => '13.06.2019',
                'file_url' => 'Letter_11.pdf',
            ],
            [
                'title' => 'Chairman',
                'title_hi' => 'अध्यक्ष',
                'states_text' => 'Mizoram',
                'publish_date' => '13.06.2019',
                'file_url' => 'Letter_10.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Jharkhand',
                'publish_date' => '28-02-2018',
                'file_url' => 'MS_JharkhandSPCB_01.03.2019.pdf',
            ],
            [
                'title' => 'Chairman',
                'title_hi' => 'अध्यक्ष',
                'states_text' => 'All SPCBs & PCCs',
                'publish_date' => '31-12-2018',
                'file_url' => 'Letter-AllSPCBs-PCCs-31122018.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Karnataka',
                'publish_date' => '12-11-2018',
                'file_url' => 'letter_KSPCB_12.11.2018.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Andhra Pradesh',
                'publish_date' => '02-04-2018',
                'file_url' => 'letter_APPCB_02.04.2018.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Jharkhand',
                'publish_date' => '28-02-2018',
                'file_url' => 'MS_JharkhandSPCB_28.02.2018.pdf',
            ],
            [
                'title' => 'RD Kolkata',
                'title_hi'=>'क्षेत्रीय निदेशालय, कोलकाता',
                'states_text' => 'West Bengal',
                'publish_date' => '05-02-2018',
                'file_url' => 'letter_RDKolkata_05.02.2018.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Odisha',
                'publish_date' => '31-01-2018',
                'file_url' => 'letter_ODSPCB_31.01.2018.pdf',
            ],
            [
                'title' => 'MS',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Delhi PCC,  Haryana, Rajasthan, UP',
                'publish_date' => '03-01-2018',
                'file_url' => 'Letter_four_states_03.01.2018.pdf',
            ],
            [
                'title' => 'Member Secretary (MS)',
                'title_hi' => 'सदस्य सचिव',
                'states_text' => 'Karnataka',
                'publish_date' => '24-11-2017',
                'file_url' => 'MS_KeralaSPCB_24.11.2017.pdf',
            ],
        ];

        $otherData = [
            ['title' => 'M/s INGA Pharmaceuticals Plot no. B1 (Part)', 'states_text' => 'Tamil Nadu', 'publish_date' => '30-01-2025', 'file_url' => 'Letter-39-1.pdf'],
            ['title' => 'Central Board of Indirect taxes and Customs', 'states_text' => 'Delhi', 'publish_date' => '01-02-2022', 'file_url' => 'Letter-38.pdf'],
            ['title' => 'M/s Garg Acrylics Ltd. (Garment Division)', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-37.pdf'],
            ['title' => 'M/s Nahar Spinning Mills Ltd. (Dyeing Unit)', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-36.pdf'],
            ['title' => 'M/s Turbo Tools (Clothing Division)', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-35.pdf'],
            ['title' => 'M/s Top Gear Fashions', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-34.pdf'],
            ['title' => 'M/s Ashoka Dyeing & Finishing Mills Pvt. Ltd.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-33.pdf'],
            ['title' => 'M/s Garg Acrelics Ltd.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-32.pdf'],
            ['title' => 'M/s Ludhiana Beverage Pvt ltd.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-31.pdf'],
            ['title' => 'M/s Shital Pibers Limlted.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-30.pdf'],
            ['title' => 'M/s Eveline International.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-29.pdf'],
            ['title' => 'M/s Sunrise Dyers & Processors Pvt.Ltd.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-28.pdf'],
            ['title' => 'M/s Vardhman Polytex Limited.', 'states_text' => 'Punjab', 'publish_date' => '16-07-2021', 'file_url' => 'Letter-27.pdf'],
            ['title' => 'M/s Talwandi Sabo Power Ltd.', 'states_text' => 'Punjab', 'publish_date' => '04-11-2020', 'file_url' => 'Letter-26.pdf'],


            ['title_hi'=>'	
दक्षिणी दिल्ली नगर निगम', 'title' => 'SDMC', 'states_text' => 'Delhi', 'publish_date' => '18-06-2019', 'file_url' => 'Letter-25.pdf'],
            ['title_hi'=>'पूर्वी दिल्ली नगर निगम', 'title' => 'EDMC', 'states_text' => 'Delhi', 'publish_date' => '18-06-2019', 'file_url' => 'Letter-24.pdf'],
            ['title_hi'=>'उत्तरी दिल्ली नगर निगम', 'title' => 'NDMC', 'states_text' => 'Delhi', 'publish_date' => '18-06-2019', 'file_url' => 'Letter-23.pdf'],
            ['title_hi'=>'मैसर्स रेडिको खेतान लि', 'title' => 'M/s Radico Khaitan Ltd.', 'states_text' => 'Uttar Pradesh', 'publish_date' => '18.02.2019', 'file_url' => 'Radico-khaitan-18022019.pdf'],
            ['title_hi'=>'दिल्ली जल बोर्ड', 'title' => 'Delhi Jal Board', 'states_text' => 'Delhi', 'publish_date' => '28-11-2018', 'file_url' => '201812042045.pdf'],
            ['title_hi'=>'मैसर्स बिलासराइका स्पोंज आइरन इंडिया प्राइवेट लिमिटेड', 'title' => 'M/s Bilasraika Sponge Iron India pvt. ltd.', 'states_text' => 'Telangana', 'publish_date' => '27-08-2018', 'file_url' => 'bilasraika-28.08.2018.pdf'],
            ['title_hi'=>'	
मेसर्स वेदांत लि', 'title' => 'M/s Vedanta. ltd.', 'states_text' => 'Goa', 'publish_date' => '28-08-2018', 'file_url' => 'vedanta-28.08.2018.pdf'],
            ['title_hi'=>'मैसर्स बिलासराइका स्पोंज आइरन इंडिया प्राइवेट लिमिटेड', 'title' => 'M/s Bilasraika Sponge Iron India pvt. ltd.', 'states_text' => 'Telangana', 'publish_date' => '13-08-2018', 'file_url' => 'letter_9814_13.08.2018.pdf'],
            ['title_hi'=>'मैसर्स बिलासराइका स्पोंज आइरन इंडिया प्राइवेट लिमिटेड', 'title' => 'M/s Bilasraika Sponge Iron India pvt. ltd.', 'states_text' => 'Telangana', 'publish_date' => '31-07-2018', 'file_url' => 'letter_3692_31.07.2018.pdf'],
            ['title_hi'=>'मैसर्स बी पी सी एल कोच्ची रिफाइनरी', 'title' => 'M/s BPCL Kochi Refinery', 'states_text' => 'Kerala', 'publish_date' => '06-03-2018', 'file_url' => 'letters_6_industries_16.05.2018.pdf'],
            ['title_hi'=>'	
मैसर्स ओ एन जी सी टटिफका रिफाइनरी', 'title' => 'M/s ONGC Tatiphaka Refinery', 'states_text' => 'Andhra Pradesh', 'publish_date' => '06-03-2018', 'file_url' => 'letters_6_industries_16.05.2018.pdf'],
            ['title_hi'=>'मैसर्स एच पी सी एल विसाखा रिफाइनरी', 'title' => 'M/s HPCL Visakha Refinery', 'states_text' => 'Andhra Pradesh', 'publish_date' => '06-03-2018', 'file_url' => 'letters_6_industries_16.05.2018.pdf'],
            ['title_hi'=>'मैसर्स एच पी सी एल माहुल', 'title' => 'M/s HPCL Mahul', 'states_text' => 'Maharashtra', 'publish_date' => '06-03-2018', 'file_url' => 'letters_6_industries_16.05.2018.pdf'],
            ['title_hi'=>'	
मैसर्स बी पी सी एल मुंबई रिफाइनरी', 'title' => 'M/s BPCL Mumbai Refinery', 'states_text' => 'Maharashtra', 'publish_date' => '06-03-2018', 'file_url' => 'letters_6_industries_16.05.2018.pdf'],
            ['title_hi'=>'मैसर्स भारत ओमान रिफाइनरीज़ लिमिटेड', 'title' => 'M/s Bharat Oman Refineries Ltd.', 'states_text' => 'Madhya Pradesh', 'publish_date' => '06-03-2018', 'file_url' => 'letters_6_industries_16.05.2018.pdf'],
            ['title_hi'=>'मैसर्स रिलायंस इंडस्ट्रीज लिमिटेड', 'title' => 'M/s Reliance Industries Ltd.', 'states_text' => 'Gujarat', 'publish_date' => '06-03-2018', 'file_url' => '20180309185240.pdf'],
            ['title_hi'=>'	
मैसर्स एएमएल स्टील एंड पावर लिमिटेड', 'title' => 'M/s AML Steel & Power Ltd.', 'states_text' => 'Jharkhand', 'publish_date' => '27-02-2018', 'file_url' => 'letter_AML_steelandpower_27.02.2018.pdf'],
            ['title_hi'=>'	
मैसर्स दामोदर इस्पात लिमिटेड', 'title' => 'M/s Damodar Ispat Ltd.', 'states_text' => 'West Bengal', 'publish_date' => '13-02-2018', 'file_url' => 'letter_damodar_ispat_13.02.2018.pdf'],
            ['title_hi'=>'	
07 राष्ट्रीय राजधानी क्षेत्रीय जिलों के जिला मजिस्ट्रेट', 'title' => 'District Magistrate of 07 NCR Districts', 'states_text' => 'Uttar Pradesh', 'publish_date' => '13-02-2018', 'file_url' => 'brick_kiln_uttarpradesh_13.02.2018.pdf'],
            ['title_hi'=>'13 राष्ट्रीय राजधानी क्षेत्रीय जिलों के उप आयुक्त / कलेक्टर', 'title' => 'Deputy Commissioners/Collectors of 13 NCR Districts', 'states_text' => 'Haryana', 'publish_date' => '13-02-2018', 'file_url' => 'brick_kiln_haryana_13.02.2018.pdf'],
            ['title_hi'=>'02 राष्ट्रीय राजधानी क्षेत्रीय जिलों के उप जिला कलेक्टर और जिला मजिस्ट्रेट', 'title' => 'District Collector & District Magistrate of 02 NCR Districts', 'states_text' => 'Rajasthan', 'publish_date' => '13-02-2018', 'file_url' => 'brick_kiln_rajasthan_13.02.2018.pdf'],
            ['title_hi'=>'	
मैसर्स एसबीक्यू स्टील लिमिटेड', 'title' => 'M/s SBQ Steel ltd', 'states_text' => 'Andhra Pradesh', 'publish_date' => '17-01-2018', 'file_url' => 'letter_17.01.2017.pdf'],
            ['title_hi'=>'मैसर्स महेश्वरी इस्पात लिमिटेड', 'title' => 'M/s Maheshwary Ispat ltd', 'states_text' => 'West Bengal', 'publish_date' => '22-11-2017', 'file_url' => 'letter_13992_22.11.17.pdf'],
            ['title_hi'=>'मैसर्स गंगा रिसौर्सेस प्रा. लि.', 'title' => 'M/s Ganga Resources pvt. ltd', 'states_text' => 'Chhattisgarh', 'publish_date' => '16-10-2017', 'file_url' => 'letter_12033_16.10.17.pdf'],
            ['title_hi'=>'	
मैसर्स कमल स्पंज स्टील एंड पावर लि।', 'title' => 'M/s Kamal Sponge steel & Power ltd.', 'states_text' => 'Madhya Pradesh', 'publish_date' => '16-10-2017', 'file_url' => 'letter_12048_16.10.17.pdf'],
        ];

        // Process SPCB Type Data
        foreach ($data as $item) {
            $this->processItem($item, 'SPCB', $stateMap);
        }

        // Process OTHER Type Data
        foreach ($otherData as $item) {
            $this->processItem($item, 'OTHER', $stateMap);
        }
    }

    private function processItem($item, $type, $stateMap)
    {
            $stateIds = [];
            
            $cleanStates = str_replace(
                [' and ', ' &amp; ', ' & ', '&'], 
                ',', 
                $item['states_text']
            );
            
            // Special cases
            if (stripos($cleanStates, 'All SPCBs') !== false || stripos($cleanStates, 'SPCBs') !== false) {
                 $id = $stateMap['All SPCBs & PCCs'] ?? null;
                 if ($id) $stateIds[] = $id;
            } else {
                 $parts = explode(',', $cleanStates);
                 foreach ($parts as $part) {
                     $part = trim($part);
                     // Fix aliases
                     if ($part == 'UK') $part = 'Uttarakhand';
                     if ($part == 'UP') $part = 'Uttar Pradesh';
                     if ($part == 'Delhi PCC') $part = 'Delhi';
                     
                     if (isset($stateMap[$part])) {
                         $stateIds[] = $stateMap[$part];
                     } else {
                         try {
                              $state = DirectionState::where('title', 'like', $part)->first();
                              if ($state) {
                                  $stateIds[] = $state->id;
                              }
                         } catch (\Exception $e) {}
                     }
                 }
            }
            $fileName = basename($item['file_url']);
            try {
                $dateStr = str_replace('.', '-', $item['publish_date']);
                $date = Carbon::createFromFormat('d-m-Y', $dateStr)->format('Y-m-d');
            } catch (\Exception $e) {
                // Try dot format if dash failed, though we replaced dots above.
                try {
                     $date = Carbon::createFromFormat('Y-m-d', $dateStr)->format('Y-m-d');
                } catch (\Exception $e2) {
                     $date = now();
                }
            }

            LettersIssued::create([
                'type' => $type,
                'title' => $item['title'],
                'title_hi' => $item['title_hi'] ?? $item['title'], // Fallback for OTHER type
                'publish_date' => $date,
                'file_name' => $fileName,
                'file_name_hi' => null,
                'is_approved' => 1,
                'is_published' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'direction_state_id' => implode(',', array_unique($stateIds)),
            ]);
    }
}
