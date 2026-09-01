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
        DB::table('tenders')->truncate();
        DB::table('tender_corrigendums')->truncate();
        // 1. Fetch all records from both legacy databases
        $enTenders = DB::connection('mysql_legacy_en')->table('cmsm_tender')->get();
        $hiTenders = DB::connection('mysql_legacy_hi')->table('cmsm_tender')->get();

        // Used to track which Hindi records were merged during the English pass
        $processedHindiIds = [];

        $this->command->info('Starting Migration...');
        $this->command->getOutput()->progressStart(count($enTenders));

        DB::transaction(function () use ($enTenders, $hiTenders, &$processedHindiIds) {
            
            // --- STEP 1: Process English Tenders (839 records) ---
            foreach ($enTenders as $en) {
                // Find matching Hindi record based on dates
                $hi = $hiTenders->where('start_date_selling', $en->start_date_selling)
                                ->where('closing_date_time', $en->closing_date_time)
                                ->first();

                if ($hi) {
                    $processedHindiIds[] = $hi->tender_id; // Mark Hindi ID as "merged"
                }

                $this->insertTenderRow($en, $hi);
                $this->command->getOutput()->progressAdvance();
            }

            // --- STEP 2: Process Standalone Hindi Tenders (approx 47 records) ---
            // Filter Hindi records that were NOT processed in Step 1
            $remainingHindi = $hiTenders->whereNotIn('tender_id', $processedHindiIds);
            
            $this->command->getOutput()->progressFinish();
            $this->command->info('Processing remaining Hindi-only records...');
            $this->command->getOutput()->progressStart(count($remainingHindi));

            foreach ($remainingHindi as $hiOnly) {
                // We pass null for English because this record exists only in the Hindi DB
                $this->insertTenderRow(null, $hiOnly);
                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
        $this->command->info('Migration completed successfully.');
    }

    private function insertTenderRow($en, $hi)
    {
        // Determine the source for dates and status (English preferred, fallback to Hindi)
        $primary = $en ?? $hi;

        // Resolve Foreign Keys
        $catTitle = trim($en->tender_category ?? ($hi->tender_category ?? ''));
        $officeTitle = trim($en->zonal_office ?? ($hi->zonal_office ?? ''));

        $catId = DB::table('tender_categories')->where('title', $catTitle)->value('id');
        $officeId = DB::table('zonal_offices')->where('title', $officeTitle)->value('id');

        DB::table('tenders')->insert([
            'tender_category_id' => $catId,
            'zonal_office_id'    => $officeId,
            
            // TITLES: If one language is missing, we use the other as fallback
            'title'              => $en->tender_name ?? $hi->tender_name ?? 'Untitled',
            'title_hi'           => $hi->tender_name_hindi ?? $en->tender_name_hindi ?? null,
            
            'description'        => $en->scope_of_work ?? null,
            'description_hi'     => $hi->scope_of_work ?? null,
            
            'issuing_authority'    => $en->issuing_authority ?? null,
            'issuing_authority_hi' => $hi->issuing_authority_hindi ?? null,
            
            'start_date'         => $this->parseDate($primary->start_date_selling),
            'end_date'           => $this->parseDate($primary->closing_date_time),
            'publish_date'       => $this->parseDate($primary->closing_date_time),
            
            // FILES: Use Hindi file if available, otherwise English
            'file_name'          => $en ? $this->clean($en->added_files) : 'no_file.pdf',
            'file_name_hi'       => $hi ? $this->clean($hi->added_files) : ($en ? $this->clean($en->added_files) : 'no_file.pdf'),
            
            'is_approved'        => 1,
            'is_published'       => ($primary->status == 1) ? 1 : 0,
            'created_by'         => 1,
            'created_at'         => $this->parseTimestamp($primary->added_on),
            'updated_at'         => now(),
        ]);

        // Process Corrigendums using the legacy tender_id
        $this->migrateCorrigendums($primary->tender_id, $en, $hi);
    }

    private function migrateCorrigendums($legacyTenderId, $enTender, $hiTender)
    {
        // Get corrigendums from both sources using the legacy ID
        $enCorrs = DB::connection('mysql_legacy_en')->table('cmsm_corrigendums')->where('tender_id', $legacyTenderId)->get();
        $hiCorrs = DB::connection('mysql_legacy_hi')->table('cmsm_corrigendums')->where('tender_id', $legacyTenderId)->get();

        // Process English corrigendums first
        foreach ($enCorrs as $ec) {
            $hc = $hiCorrs->where('corrigendum_name', $ec->corrigendum_name)->first();

            DB::table('tender_corrigendums')->insert([
                'tender_id'    => $legacyTenderId, // Assuming local ID matches legacy for now, or use lastInsertId()
                'title'        => $ec->corrigendum_name,
                'title_hi'     => $hc->corrigendum_name_hindi ?? $ec->corrigendum_name,
                'file_name'    => $this->clean($ec->file_path),
                'file_name_hi' => $hc ? $this->clean($hc->file_path) : $this->clean($ec->file_path),
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
        
        // If it's a Hindi-only Tender, process those corrigendums
        if (!$enTender && count($hiCorrs) > 0) {
            foreach ($hiCorrs as $hc) {
                DB::table('tender_corrigendums')->insert([
                    'tender_id'    => $legacyTenderId,
                    'title'        => $hc->corrigendum_name ?? 'Corrigendum',
                    'title_hi'     => $hc->corrigendum_name_hindi ?? null,
                    'file_name'    => $this->clean($hc->file_path),
                    'file_name_hi' => $this->clean($hc->file_path),
                    'created_by'   => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
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