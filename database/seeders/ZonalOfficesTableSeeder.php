<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonalOfficesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch data from legacy databases
        $enOffices = DB::connection('mysql_legacy_en')
            ->table('cmsm_tender')
            ->whereNotNull('zonal_office')
            ->whereNotIn('zonal_office', ['', '-'])
            ->distinct()
            ->pluck('zonal_office');

        $hiOffices = DB::connection('mysql_legacy_hi')
            ->table('cmsm_tender')
            ->whereNotNull('zonal_office')
            ->whereNotIn('zonal_office', ['', '-'])
            ->distinct()
            ->pluck('zonal_office')
            ->toArray();

        $this->command->getOutput()->progressStart(count($enOffices));

        // Use a transaction for high-speed insertion in PostgreSQL
        DB::transaction(function () use ($enOffices, $hiOffices) {
            foreach ($enOffices as $office) {
                $cleanTitle = trim($office);
                
                // Logic: if exists in Hindi DB use it, else use English title
                $hindiTitle = in_array($cleanTitle, $hiOffices) ? $cleanTitle : $cleanTitle;

                DB::table('zonal_offices')->updateOrInsert(
                    ['title' => $cleanTitle],
                    [
                        'title_hi'     => translateToHindi($hindiTitle),
                        'is_approved'  => 1,
                        'is_published' => 1,
                        'created_by'   => 1,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]
                );
                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
        $this->command->info("Successfully migrated zonal offices.");
    }
}