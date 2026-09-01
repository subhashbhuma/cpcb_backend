<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CommentReportTableSeeder extends Seeder
{
    public function run(): void
{
    DB::table('comment_reports')->truncate();

    // 1. Fetch English reports
    $enReports = DB::connection('mysql_legacy_en')
        ->table('cmsm_news')
        ->get();

    // 2. Fetch Hindi reports and key them by date_of_publication for instant lookup
    $hiReports = DB::connection('mysql_legacy_hi')
        ->table('cmsm_news')
        ->get()
        ->keyBy('date_of_publication');

    $this->command->info("Migrating Comment Reports...");
    $this->command->getOutput()->progressStart(count($enReports));

    DB::transaction(function () use ($enReports, $hiReports) {
        foreach ($enReports as $report) {
            $hiReport = $hiReports->get($report->date_of_publication);
            $safeDate = $this->parseLegacyDate($report->added_on);
            DB::table('comment_reports')->insert([
                'title'           => $report->news_body_english,
                'title_hi'        => $hiReport?->news_body_english ? $hiReport?->news_body_english : translateToHindi($report->news_body_english),
                'description'           => $report->title_english,
                'description_hi'        => $hiReport?->title_english ? $hiReport?->title_english : translateToHindi($report->title_english),
                'published_date'  => $report->date_of_publication,
                'file_name'       => $report->added_files,
                'file_name_hi'    => null,
                'emails'          => $report->title_hindi,
                'is_approved'     => 1,
                'is_published'    => 1,
                'remarks'         => "Legacy ID: " . $report->news_id,
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