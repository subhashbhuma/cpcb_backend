<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DirectionOcems2016Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        $now = Carbon::now();
        $createdBy = 1; // Assuming admin user ID is 1

        $files = [

            ['file' => 'NOT_AVAILABLE.pdf', 'name' => 'NOT_AVAILABLE'],
            
            // Closure Directions
            ['file' => 'Aluminium_closure.PDF', 'name' => '01-ALUMINIUM (Closure)'],
            ['file' => 'Cement_closure.pdf', 'name' => '02-CEMENT (Closure)'],
            ['file' => 'copper_closure.PDF', 'name' => '04-COPPER (Closure)'],
            ['file' => 'Distillery_Closure.pdf', 'name' => '05-DISTILLERY (Closure)'],
            ['file' => 'Dye_and_Dye_closure.pdf', 'name' => '06-DYE & DYE INT. (Closure)'],
            ['file' => 'Fertilizer_closure.pdf', 'name' => '07-FERTILIZER (Closure)'],
            ['file' => 'Steel_plants_and_sponge_iron_closure.PDF', 'name' => '08-IRON & STEEL (Closure)'],
            ['file' => 'Oilrefinery_closure.PDF', 'name' => '09-OIL REFINERY (Closure)'],
            ['file' => 'pesticide_closure.pdf', 'name' => '10-PESTICIDE (Closure)'],
            ['file' => 'Petrochemical_closure.pdf', 'name' => '11-PETROCHEMICALS (Closure)'],
            ['file' => 'Pharma_closure.pdf', 'name' => '12-PHARMACEUTICALS (Closure)'],
            ['file' => 'thermal_power_plant_closure.PDF', 'name' => '13-POWER PLANT (Closure)'],
            ['file' => 'Pulp_and_Paper_closure.pdf', 'name' => '14-PULP & PAPER (Closure)'],
            ['file' => 'sugar_closure.pdf', 'name' => '15-SUGAR (Closure)'],
            ['file' => 'textile_dying_and_bleaching_closure.PDF', 'name' => '17-TEXTILE, DYEING & BLEACHING (Closure)'],
            ['file' => 'chemical_closure.pdf', 'name' => '18-CHEMICAL (Closure)'],
            ['file' => 'zinc_CLOSURE.PDF', 'name' => '19-ZINC (Closure)'],

            // Revoked Directions
            ['file' => 'Cement_revoked.pdf', 'name' => '02-CEMENT (Revoked)'],
            ['file' => 'Copper_Revoked.pdf', 'name' => '04-COPPER (Revoked)'],
            ['file' => 'Distillery_Revoked.pdf', 'name' => '05-DISTILLERY (Revoked)'],
            ['file' => 'Dye_and_Dye_Revoked.pdf', 'name' => '06-DYE & DYE INT. (Revoked)'],
            ['file' => 'Fertilizer_revoked.PDF', 'name' => '07-FERTILIZER (Revoked)'],
            ['file' => 'Iron&Steel_Revoked.pdf', 'name' => '08-IRON & STEEL (Revoked)'],
            ['file' => 'pesticide_Revoked.pdf', 'name' => '10-PESTICIDE (Revoked)'],
            ['file' => 'Pharma_Revoked .pdf', 'name' => '12-PHARMACEUTICALS (Revoked)'],
            ['file' => 'thermal_power_plant_revoked.pdf', 'name' => '13-POWER PLANT (Revoked)'],
            ['file' => 'Pulp&Paper_Revoke.pdf', 'name' => '14-PULP & PAPER (Revoked)'],
            ['file' => 'sugar_revoked.pdf', 'name' => '15-SUGAR (Revoked)'],
            ['file' => 'chemical_revoked.pdf', 'name' => '17-CHEMICAL (Revoked)'],
            ['file' => 'food_and_beverages_revoked.pdf', 'name' => '19-FOOD & BEVERAGES (Revoked)'],

            // Show Cause Directions
            ['file' => 'Aluminium_show_cause.pdf', 'name' => '01-ALUMINIUM (Show Cause)'],
            ['file' => 'Cement_show_cause.pdf', 'name' => '02-CEMENT (Show Cause)'],
            ['file' => 'ChlorAlkali_show_cause.pdf', 'name' => '03-CHLOR ALKALI (Show Cause)'],
            ['file' => 'Copper_show_cause.pdf', 'name' => '04-COPPER (Show Cause)'],
            ['file' => 'Distillery_show_cause.pdf', 'name' => '05-DISTILLERY (Show Cause)'],
            ['file' => 'Dye_and_Dye_show_cause.pdf', 'name' => '06-DYE & DYE INT. (Show Cause)'],
            ['file' => 'Fertilizer_show_cause.pdf', 'name' => '07-FERTILIZER (Show Cause)'],
            ['file' => 'Iron_steel_show_cause.pdf', 'name' => '08-IRON & STEEL (Show Cause)'],
            ['file' => 'OilRefinary_show_cause.pdf', 'name' => '09-OIL REFINERY (Show Cause)'],
            ['file' => 'Pesticide_show_cause.pdf', 'name' => '10-PESTICIDE (Show Cause)'],
            ['file' => 'Petrochemical_show_cause.pdf', 'name' => '11-PETROCHEMICALS (Show Cause)'],
            ['file' => 'Pharmaceuticals_show_cause.pdf', 'name' => '12-PHARMACEUTICALS (Show Cause)'],
            ['file' => 'TPP_show_cause.pdf', 'name' => '13-POWER PLANT (Show Cause)'],
            ['file' => 'PulpPaper_show_cause.pdf', 'name' => '14-PULP & PAPER (Show Cause)'],
            ['file' => 'Sugar_show_cause.pdf', 'name' => '15-SUGAR (Show Cause)'],
            ['file' => 'Tannery_show_cause.pdf', 'name' => '16-TANNERY (Show Cause)'],
            ['file' => 'chemicals_show_cause.pdf', 'name' => '17-CHEMICAL (Show Cause)'],
            ['file' => 'Zinc_show_cause.pdf', 'name' => '18-ZINC (Show Cause)'],
            ['file' => 'textile_Show_cause.pdf', 'name' => '19-TEXTILE (Show Cause)'],

            // Modified Directions
            ['file' => 'pharma_modified.pdf', 'name' => '12-PHARMACEUTICALS (Modified)'],
            ['file' => 'F_&_B_Modified.pdf', 'name' => '19-FOOD & BEVERAGES (Modified)'],

            // Ganga Basin (GPI) Directions
            ['file' => 'Dir_Textile.pdf', 'name' => 'GPI 01-TEXTILE, DYEING & BLEACHING'],
            ['file' => 'Dir_Slaughter_House.pdf', 'name' => 'GPI 02-SLAUGHTER HOUSE'],
            ['file' => 'Dir_Food_Dairy_Bevrg.pdf', 'name' => 'GPI 03-FOOD, DAIRY & BEVERAGE'],
            ['file' => 'Dir_Chemical.pdf', 'name' => 'GPI 04-CHEMICAL & PHARMACEUTICAL'],
            ['file' => 'Direc_Tannery_Ngrba.pdf', 'name' => 'GPI 05-TANNERY'],
            ['file' => 'Dir_Distillery.pdf', 'name' => 'GPI 06-DISTILLERY, BREWERY & MALTIING'],
            ['file' => 'Dir_Pulp_and_paper.pdf', 'name' => 'GPI 07-PULP & PAPER'],
            ['file' => 'Dir_Other.pdf', 'name' => 'GPI 08-OTHERS'],
        ];

        foreach ($files as $f) {
          
             DB::table('media')->updateOrInsert(
                [
                    'file_name' => $f['file'],
                    'original_name' => $f['name'],
                ],
                [
                'mime_type'     => 'application/pdf',
                'size'          => 1024,
                'alt_text'      => $f['name'],
                'alt_text_hi'   => $f['name'],
                'created_by'    => $createdBy,
                'updated_by'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
                ]
            );
        }
    }
         
}
