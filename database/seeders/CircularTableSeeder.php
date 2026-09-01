<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CircularTableSeeder extends Seeder
{
    public function run(): void
{
    DB::table('circulars')->truncate();

    // 1. Fetch English reports
    $enReports = DB::connection('mysql_legacy_en')
        ->table('cmsm_report')
        ->whereIn('report_type', [3, 4, 5])
        ->get();

    $hiReports = DB::connection('mysql_legacy_hi')
        ->table('cmsm_report')
        ->whereIn('report_type', [3, 4, 5])
        ->get();

    $this->command->info("Migrating Circulars...");
    $this->command->getOutput()->progressStart(count($enReports));

    DB::transaction(function () use ($enReports, $hiReports) {
        $divisions = DB::table('divisions')->get();
        foreach ($enReports as $report) {
            $hiReport = $hiReports->get($report->publication_period);

            $safeDate = $this->parseLegacyDate($report->added_on);

            // Cleaner category mapping
            $category = match ((int)$report->report_type) {
                3 => 1,
                4 => 3,
                5 => 2,
                default => null,
            };

             $download_link = $report->download_link;
                if (!str_contains($download_link, '.pdf')) {
                    $decoded = base64_decode($download_link, true);
                    if ($decoded) {
                        $download_link = basename($decoded);
                    }
                }
                
            $matchedDivisionId = null;

            if (!empty($report->division)) {
                $divText = trim($report->division);

                if ($divText) {
                    $matchedDiv = $divisions->first(function ($d) use ($divText) {
                        return strcasecmp(trim($d->title), $divText) === 0;
                    });

                    if ($matchedDiv) {
                        $matchedDivisionId = $matchedDiv->id;
                    }
                }
            }

            DB::table('circulars')->insert([
                'title'           => $report->title,
                'title_hi'        => $hiReport?->title ? $hiReport?->title:$report->title,
                'published_date'  => $report->publication_period,
                'file_name'       => $download_link,
                'file_name_hi'    => null,
                'category'        => $category,
                'division_id'     => $matchedDivisionId,
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
    $this->command->info("Successfully migrated circulars.");
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