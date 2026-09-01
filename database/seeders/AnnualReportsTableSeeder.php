<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnualReportsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('annual_reports')->truncate();
        // 1. Fetch data from English legacy database where report_type = 1
        $enReports = DB::connection('mysql_legacy_en')
            ->table('cmsm_report')
            ->where('report_type', 1)
            ->get();

        $this->command->info("Migrating Annual Reports...");
        $this->command->getOutput()->progressStart(count($enReports));

        // 2. Use a transaction for PostgreSQL performance and data integrity
        DB::transaction(function () use ($enReports) {
            foreach ($enReports as $report) {
                
                // 3. Match Hindi translation based on publication_period
                $hiReport = DB::connection('mysql_legacy_hi')
                    ->table('cmsm_report')
                    ->where('report_type', 1)
                    ->where('publication_period', $report->publication_period)
                    ->first();

                // 4. Clean the date to prevent PostgreSQL invalid datetime format errors
                $safeDate = $this->parseLegacyDate($report->added_on);
                DB::table('annual_reports')->insert([
                    'title'           => $report->title,
                    // If Hindi DB has titlehindi use it, else check English DB's titlehindi, else fallback to title
                    // 'title_hi'        => $hiReport->titlehindi ?? ($report->titlehindi ?? $report->title),
                    'title_hi' => translateToHindi($report->title),
                    'release_date'    => $report->publication_period,
                    'file_name'       => $report->download_link,
                    'file_name_hi'    => $hiReport->download_link ?? null,
                    'is_approved'     => 1,
                    'is_published'    => 1,
                    'remarks'         => "Legacy ID: " . $report->report_id,
                    'created_by'      => 1,
                    'created_at'      => $safeDate->toDateTimeString(),
                    'updated_at'      => $safeDate->toDateTimeString(),
                ]);

                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
        $this->command->info("Successfully migrated annual reports.");
    }

    /**
     * Fixes SQLSTATE[22007] by handling MySQL "0000-00-00" and invalid formats.
     */
    private function parseLegacyDate($dateString)
    {
        if (empty($dateString) || str_contains($dateString, '0000-00-00') || $dateString == '0') {
            return now();
        }

        try {
            $date = Carbon::parse($dateString);
            
            // PostgreSQL rejects years <= 0 (e.g., -0001-11-30)
            if ($date->year <= 0) {
                return now();
            }
            
            return $date;
        } catch (\Exception $e) {
            return now();
        }
    }
}