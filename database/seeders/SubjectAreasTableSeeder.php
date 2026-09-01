<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectAreasTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subject_areas')->truncate();
        // 1. Get all unique English Subject Areas
        $enSubjects = DB::connection('mysql_legacy_en')
            ->table('cmsm_report')
            ->where('report_type', 2)
            ->whereNotNull('subject_area')
            ->whereNotIn('subject_area', ['', '-', '0'])
            ->distinct()
            ->pluck('subject_area');

        $this->command->getOutput()->progressStart(count($enSubjects));

        foreach ($enSubjects as $rawTitle) {
            $cleanTitle = trim($rawTitle);
            
            // 2. Look for Hindi translation in Hindi DB
            // We search by the English string to find the row, then take 'titlehindi'
            $hindiData = DB::connection('mysql_legacy_hi')
                ->table('cmsm_report')
                ->where('report_type', 2)
                ->where('subject_area', $cleanTitle)
                ->whereNotIn('subject_area', ['', '-', '0'])
                ->first();

            // If no Hindi title found, or if it's identical to English, 
            // you might want to leave it empty or use a translation helper
            $hindiTitle = ($hindiData && $hindiData->subject_area) ? trim($hindiData->subject_area) : $cleanTitle;

            DB::table('subject_areas')->updateOrInsert(
                ['title' => $cleanTitle], // Unique constraint check
                [
                    'title_hi'     => $hindiTitle,
                    'is_approved'  => 1,
                    'is_published' => 1,
                    'created_by'   => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }
}