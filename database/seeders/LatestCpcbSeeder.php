<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LatestCpcbSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing data
        DB::table('latest_cpcbs')->truncate();

        // 2. Fetch legacy data
        $enLatest = DB::connection('mysql_legacy_en')->table('cmsm_latest')->get();
        $hiLatest = DB::connection('mysql_legacy_hi')->table('cmsm_latest')->get();

        $processedHindiIds = [];

        $this->command->info('Starting Migration for Latest CPCB...');

        DB::transaction(function () use ($enLatest, $hiLatest, &$processedHindiIds) {
            $this->command->getOutput()->progressStart(count($enLatest));

            // --- STEP 1: Process English Records (and pair with Hindi) ---
            foreach ($enLatest as $en) {
                // Attempt to find matching Hindi record by date
                $hi = $hiLatest->where('latest_date', $en->latest_date)
                    ->filter(function ($item) use ($processedHindiIds) {
                        return !in_array($item->lastest_id, $processedHindiIds);
                    })
                    ->first();

                // Fallback: Match by Title if date match is too broad
                if (!$hi) {
                    $hi = $hiLatest->filter(function ($item) use ($en, $processedHindiIds) {
                        return trim($item->title) === trim($en->title) && !in_array($item->lastest_id, $processedHindiIds);
                    })->first();
                }

                if ($hi) {
                    $processedHindiIds[] = $hi->lastest_id;
                }

                $this->insertLatestRow($en, $hi);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();

            // --- STEP 2: Process Standalone Hindi Records ---
            $remainingHindi = $hiLatest->whereNotIn('lastest_id', $processedHindiIds);

            if ($remainingHindi->count() > 0) {
                $this->command->info('Processing remaining Hindi-only records...');
                $this->command->getOutput()->progressStart(count($remainingHindi));

                foreach ($remainingHindi as $hiOnly) {
                    $this->insertLatestRow(null, $hiOnly);
                    $this->command->getOutput()->progressAdvance();
                }
                $this->command->getOutput()->progressFinish();
            }
        });

        $this->command->info('Migration completed successfully.');
    }

    private function insertLatestRow($en, $hi)
    {
        $primary = $en ?? $hi;

        DB::table('latest_cpcbs')->insert([
            'title' => $en->title ?? (function_exists('translateToEnglish') ? translateToEnglish($hi->title) : $hi->title),
            'title_hi' => $hi->title ?? (function_exists('translateToHindi') ? translateToHindi($en->title) : $en->title),
            'file_name' => $en ? $this->clean($en->download_link) : null,
            'file_name_hi' => $hi ? $this->clean($hi->download_link) : null,
            'publish_date' => $this->parseDate($primary->latest_date),
            'is_approved' => 1,
            'is_published' => 1,
            'remarks' => 'Migrated from legacy ID: ' . ($en->lastest_id ?? 'N/A') . ' (EN), ' . ($hi->lastest_id ?? 'N/A') . ' (HI)',
            'created_by' => 1,
            'created_at' => $this->parseTimestamp($primary->added_on),
            'updated_at' => $this->parseTimestamp($primary->added_on),
        ]);
    }

    private function clean($val)
    {
        $v = trim($val);
        return (empty($v) || in_array($v, ['-', '0', 'null', 'NULL'])) ? null : $v;
    }

    private function parseDate($date)
    {
        if (!$date || in_array(trim($date), ['0000-00-00', '00-00-0000', '-', '']))
            return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseTimestamp($date)
    {
        if (!$date || in_array(trim($date), ['0000-00-00 00:00:00', '0000-00-00', '']))
            return null;
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }
}