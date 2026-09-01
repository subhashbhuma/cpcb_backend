<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DirectionSpcb181bSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        $createdBy = 1;

        $files = [
            ['file' => 'directions_violation_of_provisions.pdf', 'name' => 'Directions - Violation of Provisions'],
            ['file' => 'UEPPCB_Distilleries_30.12.2016.pdf', 'name' => 'UEPPCB Distilleries (30-12-2016)'],
            ['file' => 'UPPCB_Distilleries_30.12.2016.pdf', 'name' => 'UPPCB Distilleries (30-12-2016)'],
            ['file' => 'treatment_of_untreated_sewage.pdf', 'name' => 'Treatment of Untreated Sewage'],
            ['file' => 'Direction_control_of_pollution.pdf', 'name' => 'Direction - Control of Pollution'],
            ['file' => 'COMPLIANCE_OF_TEXTILE_UNITS.pdf', 'name' => 'Compliance of Textile Units'],
            ['file' => 'Dombivli_CETP.pdf', 'name' => 'Dombivli CETP'],
            ['file' => 'Dir_All_SPCBs.pdf', 'name' => 'Direction to All SPCBs'],
            ['file' => 'WB_SPCB_02-11-2016.PDF', 'name' => 'WB SPCB (02-11-2016)'],
            ['file' => 'Dir_to_Raj_SPCB.pdf', 'name' => 'Direction to Rajasthan SPCB'],
            ['file' => '18(1)(B)GND_WATER_Dudhawada.PDF', 'name' => '18(1)(b) Ground Water Dudhawada'],
            ['file' => 'Direction_sugar18(1)(b).PDF', 'name' => 'Direction Sugar 18(1)(b)'],
            ['file' => '18_1_(b)_Gujarat.pdf', 'name' => '18(1)(b) Gujarat'],
            ['file' => 'Directions_on_Revised_CEPI.pdf', 'name' => 'Directions on Revised CEPI'],
            ['file' => 'Directions30-12-2015.pdf', 'name' => 'Directions (30-12-2015)'],

            // Non-Attainment Areas
            ['file' => 'Andhra_Pradesh_nonattainment.pdf', 'name' => 'Andhra Pradesh (Non-Attainment)'],
            ['file' => 'Assam_nonattainment.pdf', 'name' => 'Assam (Non-Attainment)'],
            ['file' => 'Chandigarh_nonattainment.pdf', 'name' => 'Chandigarh (Non-Attainment)'],
            ['file' => 'Chhattisgarh_nonattainment.pdf', 'name' => 'Chhattisgarh (Non-Attainment)'],
            ['file' => 'Gujarat_nonattainment.pdf', 'name' => 'Gujarat (Non-Attainment)'],
            ['file' => 'HimachalPradesh_nonattainment.pdf', 'name' => 'Himachal Pradesh (Non-Attainment)'],
            ['file' => 'Jammu&Kashmir_nonattainment.pdf', 'name' => 'Jammu & Kashmir (Non-Attainment)'],
            ['file' => 'Jharkhand_nonattainment.pdf', 'name' => 'Jharkhand (Non-Attainment)'],
            ['file' => 'Karnataka_nonattainment.pdf', 'name' => 'Karnataka (Non-Attainment)'],
            ['file' => 'MadhyaPradesh_nonattainment.pdf', 'name' => 'Madhya Pradesh (Non-Attainment)'],
            ['file' => 'Maharashtra_nonattainment.pdf', 'name' => 'Maharashtra (Non-Attainment)'],
            ['file' => 'Meghalaya_nonattainment.pdf', 'name' => 'Meghalaya (Non-Attainment)'],
            ['file' => 'Nagaland_nonattainment.pdf', 'name' => 'Nagaland (Non-Attainment)'],
            ['file' => 'Odisha_nonattainment.pdf', 'name' => 'Odisha (Non-Attainment)'],
            ['file' => 'Punjab_nonattainment.pdf', 'name' => 'Punjab (Non-Attainment)'],
            ['file' => 'Rajasthan_nonattainment.pdf', 'name' => 'Rajasthan (Non-Attainment)'],
            ['file' => 'TamilNadu_nonattainment.pdf', 'name' => 'Tamil Nadu (Non-Attainment)'],
            ['file' => 'Telangana_nonattainment.pdf', 'name' => 'Telangana (Non-Attainment)'],
            ['file' => 'UttarPradesh_nonattainment.pdf', 'name' => 'Uttar Pradesh (Non-Attainment)'],
            ['file' => 'Uttarakhand_nonattainment.pdf', 'name' => 'Uttarakhand (Non-Attainment)'],
            ['file' => 'WestBengal_nonattainment.pdf', 'name' => 'West Bengal (Non-Attainment)'],

            // 18(1)(b) 2015 - Sewage/Specific State Directions
            ['file' => 'And_Nicobar_swg_18(1)(b)_2015.pdf', 'name' => 'A&N Islands 18(1)(b) 2015'],
            ['file' => 'AndhraP_swg_18(1)(b)_2015.pdf', 'name' => 'Andhra Pradesh 18(1)(b) 2015'],
            ['file' => 'ArunachalP_swg_18(1)(b)_2015.pdf', 'name' => 'Arunachal Pradesh 18(1)(b) 2015'],
            ['file' => 'Assam_swg_18(1)(b)_2015.pdf', 'name' => 'Assam 18(1)(b) 2015'],
            ['file' => 'Bihar_swg_18(1)(b)_2015.pdf', 'name' => 'Bihar 18(1)(b) 2015'],
            ['file' => 'Chandigarh_swg_18(1)(b)_2015.pdf', 'name' => 'Chandigarh 18(1)(b) 2015'],
            ['file' => 'Chtisgrh_swg_18(1)(b)_2015.pdf', 'name' => 'Chhattisgarh 18(1)(b) 2015'],
            ['file' => 'DadNagHaveli_swg_18(1)(b)_2015.pdf', 'name' => 'Dadra & Nagar Haveli 18(1)(b) 2015'],
            ['file' => 'Daman&Diu_swg_18(1)(b)_2015.pdf', 'name' => 'Daman & Diu 18(1)(b) 2015'],
            ['file' => 'Delhi_swg_18(1)(b)_2015.pdf', 'name' => 'Delhi 18(1)(b) 2015'],
            ['file' => 'Goa_swg_18(1)(b)_2015.pdf', 'name' => 'Goa 18(1)(b) 2015'],
            ['file' => 'Gujrat_swg_18(1)(b)_2015.pdf', 'name' => 'Gujarat 18(1)(b) 2015'],
            ['file' => 'Haryana_swg_18(1)(b)_2015.pdf', 'name' => 'Haryana 18(1)(b) 2015'],
            ['file' => 'HP_swg_18(1)(b)_2015.pdf', 'name' => 'Himachal Pradesh 18(1)(b) 2015'],
            ['file' => 'J&K_swg_18(1)(b)_2015.pdf', 'name' => 'J&K 18(1)(b) 2015'],
            ['file' => 'Jharkhand_swg_18(1)(b)_2015.pdf', 'name' => 'Jharkhand 18(1)(b) 2015'],
            ['file' => 'Karnatk_swg_18(1)(b)_2015.pdf', 'name' => 'Karnataka 18(1)(b) 2015'],
            ['file' => 'kerala_swg_18(1)(b)_2015.pdf', 'name' => 'Kerala 18(1)(b) 2015'],
            ['file' => 'Lakshdweep_swg_18(1)(b)_2015.pdf', 'name' => 'Lakshadweep 18(1)(b) 2015'],
            ['file' => 'Maharashtra_swg_18(1)(b)_2015.pdf', 'name' => 'Maharashtra 18(1)(b) 2015'],
            ['file' => 'Manipur_swg_18(1)(b)_2015.pdf', 'name' => 'Manipur 18(1)(b) 2015'],
            ['file' => 'MP_swg_18(1)(b)_2015.pdf', 'name' => 'Madhya Pradesh 18(1)(b) 2015'],
            ['file' => 'Mghlya_swg_18(1)(b)_2015.pdf', 'name' => 'Meghalaya 18(1)(b) 2015'],
            ['file' => 'Mzm_swg_18(1)(b)_2015.pdf', 'name' => 'Mizoram 18(1)(b) 2015'],
            ['file' => 'Nglnd_swg_18(1)(b)_2015.pdf', 'name' => 'Nagaland 18(1)(b) 2015'],
            ['file' => 'Orissa_swg_18(1)(b)_2015.pdf', 'name' => 'Orissa 18(1)(b) 2015'],
            ['file' => 'Puducherry_swg_18(1)(b)_2015.pdf', 'name' => 'Puducherry 18(1)(b) 2015'],
            ['file' => 'Punjab_swg_18(1)(b)_2015.pdf', 'name' => 'Punjab 18(1)(b) 2015'],
            ['file' => 'Rajasthan_swg_18(1)(b)_2015.pdf', 'name' => 'Rajasthan 18(1)(b) 2015'],
            ['file' => 'Sikkim_swg_18(1)(b)_2015.pdf', 'name' => 'Sikkim 18(1)(b) 2015'],
            ['file' => 'Tnd_swg_18(1)(b)_2015.pdf', 'name' => 'Tamil Nadu 18(1)(b) 2015'],
            ['file' => 'Telangana_swg_18(1)(b)_2015.pdf', 'name' => 'Telangana 18(1)(b) 2015'],
            ['file' => 'Tripura_swg_18(1)(b)_2015.pdf', 'name' => 'Tripura 18(1)(b) 2015'],
            ['file' => 'Uttrakhand_swg_18(1)(b)_2015.pdf', 'name' => 'Uttarakhand 18(1)(b) 2015'],
            ['file' => 'UP_swg_18(1)(b)_2015.pdf', 'name' => 'Uttar Pradesh 18(1)(b) 2015'],
            ['file' => 'WB_swg_18(1)(b)_2015.pdf', 'name' => 'West Bengal 18(1)(b) 2015'],

            // State specific 2015 - 18(1)(b)
            ['file' => 'Bihar2015_18(1)(b).pdf', 'name' => 'Bihar 18(1)(b) 2015'],
            ['file' => 'Chattisgarh2015_18(1)(b).pdf', 'name' => 'Chhattisgarh 18(1)(b) 2015'],
            ['file' => 'Delhi2015_18(1)(b).pdf', 'name' => 'Delhi 18(1)(b) 2015'],
            ['file' => 'Haryana2015_18(1)(b).pdf', 'name' => 'Haryana 18(1)(b) 2015'],
            ['file' => 'Jharkhand2015_18(1)(b).pdf', 'name' => 'Jharkhand 18(1)(b) 2015'],
            ['file' => 'Karnataka2015_18(1)(b).pdf', 'name' => 'Karnataka 18(1)(b) 2015'],
            ['file' => 'MP2015_18(1)(b).pdf', 'name' => 'Madhya Pradesh 18(1)(b) 2015'],
            ['file' => 'Punjab2015_18(1)(b).pdf', 'name' => 'Punjab 18(1)(b) 2015'],
            ['file' => 'Telangana2015_18(1)(b).PDF', 'name' => 'Telangana 18(1)(b) 2015'],
            ['file' => 'Uttarakhand2015_18(1)(b).pdf', 'name' => 'Uttarakhand 18(1)(b) 2015'],
            ['file' => 'UP2015_18(1)(b).pdf', 'name' => 'Uttar Pradesh 18(1)(b) 2015'],
            ['file' => 'WB2015_18(1)(b).pdf', 'name' => 'West Bengal 18(1)(b) 2015'],
            ['file' => 'CommonAllSPCB_2015.pdf', 'name' => 'Common to All SPCBs 2015'],

            // 2014 Directions
            ['file' => 'Karnataka2014.PDF', 'name' => 'Karnataka 2014'],
            ['file' => 'Kerala2014_18(1)(b).pdf', 'name' => 'Kerala 18(1)(b) 2014'],
            ['file' => 'Mah2014.pdf', 'name' => 'Maharashtra 2014'],
            ['file' => 'Rajasthan_2014.pdf', 'name' => 'Rajasthan 2014'],
            ['file' => 'UK_2014.pdf', 'name' => 'Uttarakhand 2014'],
            ['file' => 'UP_2014.pdf', 'name' => 'Uttar Pradesh 2014'],
            ['file' => 'WB_2014.pdf', 'name' => 'West Bengal 2014'],
            ['file' => 'Common_all_2014.pdf', 'name' => 'Common to All 2014'],

            // 2013 Directions
            ['file' => 'AP2013.PDF', 'name' => 'Andhra Pradesh 2013'],
            ['file' => 'Assam2013.pdf', 'name' => 'Assam 2013'],
            ['file' => 'GSPCB2013.PDF', 'name' => 'Gujarat 2013'],
            ['file' => 'MP20134.PDF', 'name' => 'Madhya Pradesh 2013'],
            ['file' => 'RSPCB2013.PDF', 'name' => 'Rajasthan 2013'],
            ['file' => 'TNPCB2013.PDF', 'name' => 'Tamil Nadu 2013'],
            ['file' => 'UK2013.PDF', 'name' => 'Uttarakhand 2013'],
            ['file' => 'UP2013.pdf', 'name' => 'Uttar Pradesh 2013'],

            // 2012 Directions
            ['file' => 'AndhraPradesh12.pdf', 'name' => 'Andhra Pradesh 2012'],
            ['file' => 'Assam2012.pdf', 'name' => 'Assam 2012'],
            ['file' => 'Bihar2012.PDF', 'name' => 'Bihar 2012'],
            ['file' => 'Chhattisgarh12.pdf', 'name' => 'Chhattisgarh 2012'],
            ['file' => 'Gujarat12.PDF', 'name' => 'Gujarat 2012'],
            ['file' => 'kar2012.pdf', 'name' => 'Karnataka 2012'],
            ['file' => 'KeralaSection18 (i)(b)2012.PDF', 'name' => 'Kerala 18(1)(b) 2012'],
            ['file' => 'MP12.pdf', 'name' => 'Madhya Pradesh 2012'],
            ['file' => 'Maharashtra12.pdf', 'name' => 'Maharashtra 2012'],
            ['file' => 'Orissa12.pdf', 'name' => 'Orissa 2012'],
            ['file' => 'Rajasthan12.pdf', 'name' => 'Rajasthan 2012'],
            ['file' => 'Tamil_Nadu12.pdf', 'name' => 'Tamil Nadu 2012'],
            ['file' => 'Uttarakhand12.PDF', 'name' => 'Uttarakhand 2012'],
            ['file' => 'UP2012.pdf', 'name' => 'Uttar Pradesh 2012'],
            ['file' => 'West_Bengal12.pdf', 'name' => 'West Bengal 2012'],
            ['file' => 'CommontoallSPCBs&PCCs.pdf', 'name' => 'Common to All SPCBs/PCCs'],

            // 2011 Directions
            ['file' => 'AP2011.pdf', 'name' => 'Andhra Pradesh 2011'],
            ['file' => 'Assam2011.pdf', 'name' => 'Assam 2011'],
            ['file' => 'follow-up.pdf', 'name' => 'Follow-up Direction 2011'],
            ['file' => 'Har2011.pdf', 'name' => 'Haryana 2011'],
            ['file' => 'Jharkhand2011.pdf', 'name' => 'Jharkhand 2011'],
            ['file' => 'kar2011.pdf', 'name' => 'Karnataka 2011'],
            ['file' => 'kerala2011.pdf', 'name' => 'Kerala 2011'],
            ['file' => 'Mah2011.pdf', 'name' => 'Maharashtra 2011'],
            ['file' => 'Ori2011.pdf', 'name' => 'Orissa 2011'],
            ['file' => 'Pun2011.pdf', 'name' => 'Punjab 2011'],
            ['file' => 'Raj2011.pdf', 'name' => 'Rajasthan 2011'],
            ['file' => 'TN2011.pdf', 'name' => 'Tamil Nadu 2011'],
            ['file' => 'UK2011.pdf', 'name' => 'Uttarakhand 2011'],
            ['file' => 'UP2011.pdf', 'name' => 'Uttar Pradesh 2011'],
            ['file' => 'WB2011.pdf', 'name' => 'West Bengal 2011'],
            ['file' => 'direction_sec_18_2011.pdf', 'name' => 'Direction Section 18 2011'],

            // 2010 Directions
            ['file' => 'AP2010.pdf', 'name' => 'Andhra Pradesh 2010'],
            ['file' => 'Assam2010.pdf', 'name' => 'Assam 2010'],
            ['file' => 'follow-up1.pdf', 'name' => 'Follow-up Direction 2010 (1)'],
            ['file' => 'Guj2010.pdf', 'name' => 'Gujarat 2010'],
            ['file' => 'follow-up.pdf2', 'name' => 'Follow-up Direction 2010 (2)'],
            ['file' => 'Kar2010.pdf', 'name' => 'Karnataka 2010'],
            ['file' => 'Ker2010.pdf', 'name' => 'Kerala 2010'],
            ['file' => 'MP2010.pdf', 'name' => 'Madhya Pradesh 2010'],
            ['file' => 'Mah2010.pdf', 'name' => 'Maharashtra 2010'],
            ['file' => 'Ori2010.pdf', 'name' => 'Orissa 2010'],
            ['file' => 'Pun2010.pdf', 'name' => 'Punjab 2010'],
            ['file' => 'Raj2010.pdf', 'name' => 'Rajasthan 2010'],
            ['file' => 'TN2010.pdf', 'name' => 'Tamil Nadu 2010'],
            ['file' => 'UK2010.pdf', 'name' => 'Uttarakhand 2010'],
            ['file' => 'UP2010.pdf', 'name' => 'Uttar Pradesh 2010'],
            ['file' => 'WB2010.pdf', 'name' => 'West Bengal 2010'],
            ['file' => 'direction_sec_18_ 2010.PDF', 'name' => 'Direction Section 18 2010'],
        ];

        foreach ($files as $f) {
            DB::table('media')->updateOrInsert(
                ['file_name' => $f['file']],
                [
                    'original_name' => $f['name'],
                    'mime_type'     => 'application/pdf',
                    'size'          => 1024,
                    'alt_text'      => $f['name'],
                    'alt_text_hi'   => $f['name'],
                    'created_by'    => $createdBy,
                    'updated_by'    => $createdBy,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]
            );
        }
    }
}