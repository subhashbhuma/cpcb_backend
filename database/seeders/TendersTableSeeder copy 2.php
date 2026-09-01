<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TendersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up destination tables
        DB::table('tenders')->truncate();
        DB::table('tender_corrigendums')->truncate();

        // 1. Fetch all records from both legacy databases
        $enTenders = DB::connection('mysql_legacy_en')->table('cmsm_tender')->get();
        $hiTenders = DB::connection('mysql_legacy_hi')->table('cmsm_tender')->get();

        // Track Hindi IDs that were merged during the English pass
        $processedHindiIds = [];

        $this->command->info('Starting Migration...');
        
        DB::transaction(function () use ($enTenders, $hiTenders, &$processedHindiIds) {
            
            // --- STEP 1: Process English Tenders (839 records) ---
            $this->command->info('Step 1: Processing English records and merging Hindi matches...');
            $this->command->getOutput()->progressStart(count($enTenders));

            foreach ($enTenders as $en) {
                // Find matching Hindi record based on dates
                $hi = $hiTenders->where('start_date_selling', $en->start_date_selling)
                                ->where('closing_date_time', $en->closing_date_time)
                                ->first();

                if ($hi) {
                    $processedHindiIds[] = $hi->tender_id; 
                }

                // Insert the record and get the new primary key ID
                $this->insertTenderRow($en, $hi);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();

            // --- STEP 2: Process Standalone Hindi Tenders ---
            $remainingHindi = $hiTenders->whereNotIn('tender_id', $processedHindiIds);
            
            $this->command->info('Step 2: Processing remaining Hindi-only records...');
            $this->command->getOutput()->progressStart(count($remainingHindi));

            foreach ($remainingHindi as $hiOnly) {
                // Pass null for English
                $this->insertTenderRow(null, $hiOnly);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
        });

        $this->command->info('Migration completed successfully.');
    }

    /**
     * Handles the logic of inserting a tender and triggering corrigendum migration
     */
    private function insertTenderRow($en, $hi)
    {
        $primary = $en ?? $hi;

        // Resolve Foreign Keys
        $catTitle = trim($en->tender_category ?? ($hi->tender_category ?? ''));
        $officeTitle = trim($en->zonal_office ?? ($hi->zonal_office ?? ''));

        $catId = DB::table('tender_categories')->where('title', $catTitle)->value('id');
        $officeId = DB::table('zonal_offices')->where('title', $officeTitle)->value('id');

        // Insert and capture the NEW ID to link Corrigendums properly
        $newTenderId = DB::table('tenders')->insertGetId([
            'tender_category_id' => $catId,
            'zonal_office_id'    => $officeId,
            
            'title'              => $en->tender_name ?? ($hi->tender_name ?? 'Untitled'),
            'title_hi'           => $hi->tender_name_hindi ?? ($en->tender_name_hindi ?? null),
            
            'description'        => $en->scope_of_work ?? null,
            'description_hi'     => $hi->scope_of_work ?? null,
            
            'issuing_authority'    => $en->issuing_authority ?? null,
            'issuing_authority_hi' => $hi->issuing_authority_hindi ?? null,
            
            'start_date'         => $this->parseDate($primary->start_date_selling),
            'end_date'           => $this->parseDate($primary->closing_date_time),
            'publish_date'       => $this->parseDate($primary->closing_date_time),
            
            'file_name'          => $en ? $this->clean($en->added_files) : 'no_file.pdf',
            'file_name_hi'       => $hi ? $this->clean($hi->added_files) : ($en ? $this->clean($en->added_files) : 'no_file.pdf'),
            
            'is_approved'        => 1,
            'is_published'       => ($primary->status == 1) ? 1 : 0,
            'created_by'         => 1,
            'created_at'         => $this->parseTimestamp($primary->added_on),
            'updated_at'         => now(),
        ]);

        // Process Corrigendums using the legacy tender_id for lookup but new ID for insertion
        $this->migrateCorrigendums($newTenderId, $primary->tender_id);
    }

    /**
     * Merges English and Hindi corrigendums for a specific tender
     */
    private function migrateCorrigendums($newTenderId, $legacyTenderId)
    {
        $enCorrs = DB::connection('mysql_legacy_en')->table('cmsm_corrigendums')
            ->where('tender_id', $legacyTenderId)->get();
            
        $hiCorrs = DB::connection('mysql_legacy_hi')->table('cmsm_corrigendums')
            ->where('tender_id', $legacyTenderId)->get();

        $processedHiCorrIds = [];

        // Pass 1: English (and matched Hindi)
        foreach ($enCorrs as $ec) {
            $hc = $hiCorrs->where('corrigendum_name', $ec->corrigendum_name)->first();
            
            if ($hc) {
                $processedHiCorrIds[] = $hc->corrigendum_id;
            }

            DB::table('tender_corrigendums')->insert([
                'tender_id'    => $newTenderId, 
                'title'        => $ec->corrigendum_name ?? 'Corrigendum',
                'title_hi'     => $hc->corrigendum_name_hindi ?? ($ec->corrigendum_name_hindi ?? null),
                'file_name'    => $this->clean($ec->file_path),
                'file_name_hi' => $hc ? $this->clean($hc->file_path) : $this->clean($ec->file_path),
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // Pass 2: Standalone Hindi Corrigendums
        $remainingHiCorrs = $hiCorrs->whereNotIn('corrigendum_id', $processedHiCorrIds);
        
        foreach ($remainingHiCorrs as $hcOnly) {
            DB::table('tender_corrigendums')->insert([
                'tender_id'    => $newTenderId,
                'title'        => $hcOnly->corrigendum_name ?? 'Corrigendum',
                'title_hi'     => $hcOnly->corrigendum_name_hindi ?? null,
                'file_name'    => $this->clean($hcOnly->file_path),
                'file_name_hi' => $this->clean($hcOnly->file_path),
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    private function clean($val) {
        $v = trim($val);
        return (empty($v) || in_array($v, ['-', '0', 'null'])) ? 'no_file.pdf' : $v;
    }

    private function parseDate($date) {
        if (!$date || in_array(trim($date), ['0000-00-00', '00-00-0000', '-', ''])) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) { return null; }
    }

    private function parseTimestamp($date) {
        if (!$date || in_array(trim($date), ['0000-00-00 00:00:00', '0000-00-00', ''])) return now();
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) { return now(); }
    }
}