<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DirectionOcems2017Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        $createdBy = 1;

        $files = [
            ['file' => 'NOT_AVAILABLE.pdf', 'name' => 'NOT_AVAILABLE'],
            
            // Closure Directions
            ['file' => 'Aluminium_closure_17.pdf', 'name' => '01-ALUMINIUM (Closure)'],
            ['file' => 'Cement_closure17.pdf', 'name' => '02-CEMENT (Closure)'],
            ['file' => 'Chlor_Alkali_Closure17.pdf', 'name' => '03-CHLOR ALKALI (Closure)'],
            ['file' => 'copper_closure17.pdf', 'name' => '04-COPPER (Closure)'],
            ['file' => 'Distt_closure17.pdf', 'name' => '05-DISTILLERY (Closure)'],
            ['file' => 'dyeanddye_closure17.pdf', 'name' => '06-DYE & DYE INT. (Closure)'],
            ['file' => 'Fertilizer_closure_17.pdf', 'name' => '07-FERTILIZER (Closure)'],
            ['file' => 'ironsteel_clos17.pdf', 'name' => '08-IRON & STEEL (Closure)'],
            ['file' => 'oil_and_ref_clos17.pdf', 'name' => '09-OIL REFINERY (Closure)'],
            ['file' => 'Pesticide_close17.pdf', 'name' => '10-PESTICIDE (Closure)'],
            ['file' => 'Petrochemical_closure_17.pdf', 'name' => '11-PETROCHEMICALS (Closure)'],
            ['file' => 'Pharma_close17.pdf', 'name' => '12-PHARMACEUTICALS (Closure)'],
            ['file' => 'TPP_closure17.pdf', 'name' => '13-POWER PLANT (Closure)'],
            ['file' => 'paper_closure17.pdf', 'name' => '14-PULP & PAPER (Closure)'],
            ['file' => 'Sugar_closure17.pdf', 'name' => '15-SUGAR (Closure)'],
            ['file' => 'tannery_closure17.pdf', 'name' => '16-TANNERY (Closure)'],
            ['file' => 'textile_clos17.pdf', 'name' => '17-TEXTILE (Closure)'],
            ['file' => 'Chemical_closure_17.pdf', 'name' => '18-CHEMICAL (Closure)'],

            // Revoked Directions
            ['file' => 'aluminium_revoked17.pdf', 'name' => '01-ALUMINIUM (Revoked)'],
            ['file' => 'Cement_revoked17.pdf', 'name' => '02-CEMENT (Revoked)'],
            ['file' => 'chlor_alkali_revk17.pdf', 'name' => '03-CHLOR ALKALI (Revoked)'],
            ['file' => 'Copper_Revoked17.pdf', 'name' => '04-COPPER (Revoked)'],
            ['file' => 'Distillery_Revoked17.pdf', 'name' => '05-DISTILLERY (Revoked)'],
            ['file' => 'Dye_and_Dye_Revoked17.pdf', 'name' => '06-DYE & DYE INT. (Revoked)'],
            ['file' => 'Fertilizer_revoked17.pdf', 'name' => '07-FERTILIZER (Revoked)'],
            ['file' => 'ironsteel_revoked17.pdf', 'name' => '08-IRON & STEEL (Revoked)'],
            ['file' => 'oilrefinery_revoked17.pdf', 'name' => '09-OIL REFINERY (Revoked)'],
            ['file' => 'pesticide_revoked17.pdf', 'name' => '10-PESTICIDE (Revoked)'],
            ['file' => 'petrochem_revoked17.pdf', 'name' => '11-PETROCHEMICALS (Revoked)'],
            ['file' => 'Pharma_Revoked17.pdf', 'name' => '12-PHARMACEUTICALS (Revoked)'],
            ['file' => 'TPP_revoked17.pdf', 'name' => '13-POWER PLANT (Revoked)'],
            ['file' => 'Pulp&Paper_Revoke17.pdf', 'name' => '14-PULP & PAPER (Revoked)'],
            ['file' => 'sugar_revoked17.pdf', 'name' => '15-SUGAR (Revoked)'],
            ['file' => 'tannery_revoked17.pdf', 'name' => '16-TANNERY (Revoked)'],
            ['file' => 'chemical_Revoked17.pdf', 'name' => '18-CHEMICAL (Revoked)'],
            ['file' => 'food_and_beverages_revoked17.pdf', 'name' => '19-FOOD & BEVERAGES (Revoked)'],

            // Show Cause Directions
            ['file' => 'Aluminium_showcause_17.pdf', 'name' => '01-ALUMINIUM (Show Cause)'],
            ['file' => 'Cement_show_cause_17.pdf', 'name' => '02-CEMENT (Show Cause)'],
            ['file' => 'Chloralkali_showcause17.pdf', 'name' => '03-CHLOR ALKALI (Show Cause)'],
            ['file' => 'copper_show_cause_17.pdf', 'name' => '04-COPPER (Show Cause)'],
            ['file' => 'Distt_showcause17.pdf', 'name' => '05-DISTILLERY (Show Cause)'],
            ['file' => 'Dye_and_Dye_show_cause_17.pdf', 'name' => '06-DYE & DYE INT. (Show Cause)'],
            ['file' => 'Fertilizer_showcause17.pdf', 'name' => '07-FERTILIZER (Show Cause)'],
            ['file' => 'ironsteel_showcause17.pdf', 'name' => '08-IRON & STEEL (Show Cause)'],
            ['file' => 'oil_and_ref_SC17.pdf', 'name' => '09-OIL REFINERY (Show Cause)'],
            ['file' => 'Pest_showcause17.pdf', 'name' => '10-PESTICIDE (Show Cause)'],
            ['file' => 'Petrochem_SC_17 .pdf', 'name' => '11-PETROCHEMICALS (Show Cause)'],
            ['file' => 'pharma_showcause17.pdf', 'name' => '12-PHARMACEUTICALS (Show Cause)'],
            ['file' => 'TPP_show_cause_17.pdf', 'name' => '13-POWER PLANT (Show Cause)'],
            ['file' => 'PulpPaper_show_cause17.pdf', 'name' => '14-PULP & PAPER (Show Cause)'],
            ['file' => 'Sugar_show_cause_17.pdf', 'name' => '15-SUGAR (Show Cause)'],
            ['file' => 'tannery_showcause17.pdf', 'name' => '16-TANNERY (Show Cause)'],
            ['file' => 'textile_Show_cause_17.pdf', 'name' => '17-TEXTILE (Show Cause)'],

            // Modified Directions
            ['file' => 'Aluminium_modified_17.pdf', 'name' => '01-ALUMINIUM (Modified)'],
            ['file' => 'cement_modified17.pdf', 'name' => '02-CEMENT (Modified)'],
            ['file' => 'ironsteel_modified17.pdf', 'name' => '08-IRON & STEEL (Modified)'],
            ['file' => 'pharma_modified.pdf', 'name' => '12-PHARMACEUTICALS (Modified)'],
            ['file' => 'pulp&paper_modif17.pdf', 'name' => '14-PULP & PAPER (Modified)'],
            ['file' => 'tannery_modified17.pdf', 'name' => '16-TANNERY (Modified)'],
            ['file' => 'F_&_B_Modified.pdf', 'name' => '19-FOOD & BEVERAGES (Modified)'],

            // GPI - Closure
            ['file' => 'chemical_gpi_CLOS.pdf', 'name' => 'GPI-CHEMICAL (Closure)'],
            ['file' => 'distillery_gpi_CLOS.pdf', 'name' => 'GPI-DISTILLERY (Closure)'],
            ['file' => 'food_gpi_clos.pdf', 'name' => 'GPI-FOOD (Closure)'],
            ['file' => 'pulpandpaper_gpi_CLOS.pdf', 'name' => 'GPI-PULP & PAPER (Closure)'],
            ['file' => 'slaughterhouse_gpi_CLOS.pdf', 'name' => 'GPI-SLAUGHTER HOUSE (Closure)'],
            ['file' => 'sugar_gpi_CLOS.pdf', 'name' => 'GPI-SUGAR (Closure)'],
            ['file' => 'tannery_gpi_clos.pdf', 'name' => 'GPI-TANNERY (Closure)'],
            ['file' => 'textile_gpi_clos.pdf', 'name' => 'GPI-TEXTILE (Closure)'],
            ['file' => 'others_gpi_clos.pdf', 'name' => 'GPI-OTHERS (Closure)'],

            // GPI - Revoked
            ['file' => 'chemical_gpi_REVK.pdf', 'name' => 'GPI-CHEMICAL (Revoked)'],
            ['file' => 'disttillery_gpi_revok.pdf', 'name' => 'GPI-DISTILLERY (Revoked)'],
            ['file' => 'food_gpi_REVK.pdf', 'name' => 'GPI-FOOD (Revoked)'],
            ['file' => 'pulpandpaper_gpi_REVK.pdf', 'name' => 'GPI-PULP & PAPER (Revoked)'],
            ['file' => 'slaughter_gpi_revk.pdf', 'name' => 'GPI-SLAUGHTER HOUSE (Revoked)'],
            ['file' => 'sugar_gpi_revk.pdf', 'name' => 'GPI-SUGAR (Revoked)'],
            ['file' => 'tannery_gpi_REVK.pdf', 'name' => 'GPI-TANNERY (Revoked)'],
            ['file' => 'textile_gpi_REVK.pdf', 'name' => 'GPI-TEXTILE (Revoked)'],
            ['file' => 'others_gpi_revk.pdf', 'name' => 'GPI-OTHERS (Revoked)'],

            // GPI - Show Cause
            ['file' => 'chemical_gpi_SC.pdf', 'name' => 'GPI-CHEMICAL (Show Cause)'],
            ['file' => 'distillery_gpi_SC.pdf', 'name' => 'GPI-DISTILLERY (Show Cause)'],
            ['file' => 'fertiliser_gpi_SC.pdf', 'name' => 'GPI-FERTILIZER (Show Cause)'],
            ['file' => 'food_gpi_SC.pdf', 'name' => 'GPI-FOOD (Show Cause)'],
            ['file' => 'oil_gpi_SC.pdf', 'name' => 'GPI-OIL REFINERY (Show Cause)'],
            ['file' => 'pulpandpaper_gpi_SC.pdf', 'name' => 'GPI-PULP & PAPER (Show Cause)'],
            ['file' => 'slaughterhouse_gpi_SC.pdf', 'name' => 'GPI-SLAUGHTER HOUSE (Show Cause)'],
            ['file' => 'sugar_gpi_SC.pdf', 'name' => 'GPI-SUGAR (Show Cause)'],
            ['file' => 'tannery_gpi_SC.pdf', 'name' => 'GPI-TANNERY (Show Cause)'],
            ['file' => 'textile_gpi_SC.pdf', 'name' => 'GPI-TEXTILE (Show Cause)'],
            ['file' => 'tpp_gpi_SC.pdf', 'name' => 'GPI-POWER PLANT (Show Cause)'],
            ['file' => 'others_gpi_SC.pdf', 'name' => 'GPI-OTHERS (Show Cause)'],

            // GPI - Modified
            ['file' => 'chemical_gpi_modified.pdf', 'name' => 'GPI-CHEMICAL (Modified)'],
            ['file' => 'distt_gpi_modified.pdf', 'name' => 'GPI-DISTILLERY (Modified)'],
            ['file' => 'fertilizer_gpi_modified.pdf', 'name' => 'GPI-FERTILIZER (Modified)'],
            ['file' => 'food_gpi_modifed.pdf', 'name' => 'GPI-FOOD (Modified)'],
            ['file' => 'paper_gpi_modified.pdf', 'name' => 'GPI-PULP & PAPER (Modified)'],
            ['file' => 'sugar_gpi_modified.pdf', 'name' => 'GPI-SUGAR (Modified)'],
            ['file' => 'tannery_gpi_midified.pdf', 'name' => 'GPI-TANNERY (Modified)'],
            ['file' => 'textile_gpi_modified.pdf', 'name' => 'GPI-TEXTILE (Modified)'],
        ];

        foreach ($files as $f) {
            DB::table('media')->updateOrInsert(
                [
                    'file_name' => $f['file'],
                ],
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