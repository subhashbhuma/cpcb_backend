<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TechnicalReportsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('technical_reports')->truncate();

        // --------------------------------------------------
        // Step 1: Fetch English Legacy Reports
        // --------------------------------------------------
        $legacyData = DB::connection('mysql_legacy_en')
            ->table('cmsm_report')
            ->where('report_type', 2)
            ->get();

        // --------------------------------------------------
        // Step 2: Preload Hindi Reports (Optimized)
        // --------------------------------------------------
        $hindiReports = DB::connection('mysql_legacy_hi')
            ->table('cmsm_report')
            ->where('report_type', 2)
            ->get()
            ->keyBy('publication_period'); // Fast lookup

        $this->command->info("Step 1: Rectifying Subject Areas...");

        // --------------------------------------------------
        // Step 3: Insert / Update Subject Areas
        // --------------------------------------------------
        foreach ($legacyData as $row) {

            $cleanSubject = trim($row->subject_area ?? '');

            if (!empty($cleanSubject) && $cleanSubject !== '-' && $cleanSubject !== '0') {

                $hiRow = $hindiReports[$row->publication_period] ?? null;

                DB::table('subject_areas')->updateOrInsert(
                    ['title' => $cleanSubject],
                    [
                        'title_hi'     => $hiRow->subject_area ?? $cleanSubject,
                        'is_approved'  => 1,
                        'is_published' => 1,
                        'created_by'   => 1,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]
                );
            }
        }

        // --------------------------------------------------
        // Step 4: Preload Lookup Maps
        // --------------------------------------------------
        $subjectMap = DB::table('subject_areas')
            ->pluck('id', 'title')
            ->toArray();

        $divisions = DB::table('divisions')->get();

        $this->command->info("Step 2: Migrating Technical Reports...");
        $this->command->getOutput()->progressStart(count($legacyData));

        // --------------------------------------------------
        // Step 5: Migrate Reports
        // --------------------------------------------------
        DB::transaction(function () use (
            $legacyData,
            $hindiReports,
            $subjectMap,
            $divisions
        ) {

            foreach ($legacyData as $report) {

                $hindiReport = $hindiReports[$report->publication_period] ?? null;

                // -----------------------------
                // Match Division
                // -----------------------------
               

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


                // -----------------------------
                // Safe Dates
                // -----------------------------
                $safeDate = $this->parseLegacyDate($report->added_on);
                $safePublication = $this->parseLegacyDate($report->publication_period);

                // -----------------------------
                // Insert Report
                // -----------------------------
                DB::table('technical_reports')->insert([
                    'subject_area_id' => $subjectMap[trim($report->subject_area)] ?? null,
                    'division_id'     => $matchedDivisionId,
                    'title'           => $report->title,
                    'title_hi'        => $hindiReport->titlehindi ?? ($report->titlehindi ?? $report->title),
                    'release_date'    => $safePublication?->toDateString(),
                    'file_name'       => $report->download_link,
                    'file_name_hi'    => $hindiReport->download_link ?? null,
                    'is_approved'     => 1,
                    'is_published'    => 1,
                    'remarks'         => "Legacy Report ID: " . $report->report_id,
                    'created_by'      => 1,
                    'created_at'      => $safeDate?->toDateString(),
                    'updated_at'      => $safeDate?->toDateString(),
                ]);

                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
        $this->command->info("Migration completed successfully.");
    }

    /**
     * Prevents PostgreSQL SQLSTATE[22007] errors.
     */
    private function parseLegacyDate($dateString)
    {
        if (
            empty($dateString) ||
            str_contains($dateString, '0000-00-00') ||
            $dateString == '0'
        ) {
            return null;
        }

        try {
            $date = Carbon::parse($dateString);

            if ($date->year <= 0) {
                return null;
            }

            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }
}