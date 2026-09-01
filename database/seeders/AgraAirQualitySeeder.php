<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgraAirQualitySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing data
        DB::table('agra_air_qualities')->truncate();

        $enBulletins = DB::connection('mysql_legacy_en')->table('cmsm_bulletin')
        ->where('link_section', 1)
        ->where('quality_zone', '!=', '0')
        ->where('quality_zone', '!=', '')
        ->whereNotNull('quality_zone')
        ->get();

        $this->command->info('Starting Migration for Agra Air Quality (English Source)...');

        if ($enBulletins->isEmpty()) {
            $this->command->warn('No records found in the legacy table.');
            return;
        }

        $this->command->getOutput()->progressStart(count($enBulletins));

        foreach ($enBulletins as $en) {
            DB::table('agra_air_qualities')->insert([
                'quality_zone_id' => $en->quality_zone,
                'title'           => $en->link_title,
                'title_hi'        => $en->link_title,
                'file_name'       => $this->clean($en->download_link),
                'file_name_hi'    => null,
                'for_date'        => $this->parseDate($en->for_date),
                'is_approved'     => 1,
                'is_published'    => 1,
                'remarks'         => 'Migrated from Legacy ID: ' . $en->bulletin_id . ' (EN Source)',
                'created_by'      => 1,
                'created_at'      => $this->parseDate($en->for_date),
                'updated_at'      => $this->parseDate($en->for_date),
            ]);

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Agra Air Quality migration completed successfully.');
    }

   
    private function clean($val) {
        $v = trim($val);
        return (empty($v) || in_array($v, ['-', '0', 'null', 'NULL'])) ? null : $v;
    }

    private function parseDate($date) {
        if (!$date) return null;
        try { 
            return Carbon::parse($date)->format('Y-m-d'); 
        } catch (\Exception $e) { 
            return null; 
        }
    }
}