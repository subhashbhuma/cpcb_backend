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
        $enTenders = DB::connection('mysql_legacy_en')->table('cmsm_tender')->get();
        $hiTenders = DB::connection('mysql_legacy_hi')->table('cmsm_tender')->get();
        $processedHindiIds = [];
        $this->command->info('Starting Migration...');
        DB::transaction(function () use ($enTenders, $hiTenders, &$processedHindiIds) {
            $this->command->getOutput()->progressStart(count($enTenders));

            foreach ($enTenders as $en) {
                $hi = $hiTenders->where('start_date_selling', $en->start_date_selling)
                                ->where('closing_date_time', $en->closing_date_time)
                                ->first();
                if (!$hi) {
                    $hi = $hiTenders->filter(function($item) use ($en) {
                        return trim($item->tender_name) === trim($en->tender_name);
                    })->first();
                }

                $hindiLegacyId = null;
                if ($hi) {
                    $hindiLegacyId = $hi->tender_id;
                    $processedHindiIds[] = $hi->tender_id; 
                }

                $this->insertTenderRow($en, $hi, $en->tender_id, $hindiLegacyId);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();

            // --- STEP 2: Process Standalone Hindi Tenders ---
            $remainingHindi = $hiTenders->whereNotIn('tender_id', $processedHindiIds);
            $this->command->info('Processing remaining Hindi-only records...');
            $this->command->getOutput()->progressStart(count($remainingHindi));

            foreach ($remainingHindi as $hiOnly) {
                $this->insertTenderRow(null, $hiOnly, null, $hiOnly->tender_id);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
        });

        $this->command->info('Migration completed successfully.');
    }

    private function insertTenderRow($en, $hi, $enLegacyId, $hiLegacyId)
    {
        $primary = $en ?? $hi;

        $divisionTitle = trim($en->tender_category ?? ($hi->tender_category ?? ''));
        $divisionId = DB::table('divisions')->where('title', $divisionTitle)->value('id');

        $newTenderId = DB::table('tenders')->insertGetId([
            'division_id'        => $divisionId,
            'title'              => $en->tender_name ?? (translateToEnglish($hi->tender_name)),
            'title_hi'           => $hi->tender_name ?? (translateToHindi($en->tender_name_hindi)),
            'issuing_authority'    => $en->issuing_authority ?? (translateToEnglish($hi->issuing_authority)),
            'issuing_authority_hi' => $hi->issuing_authority ?? (translateToHindi($en->issuing_authority_hindi)),
            'start_date'         => $this->parseDate($primary->start_date_selling),
            'end_date'           => $this->parseDate($primary->closing_date_time),
            'publish_date'       => $this->parseDate($primary->closing_date_time),
            'file_name'          => $en ? $this->clean($en->added_files) : null,
            'file_name_hi'       => $hi ? $this->clean($hi->added_files) : ($en ? $this->clean($en->added_files) : null),
            'is_approved'        => 1,
            'is_published'       => ($primary->status == 1) ? 1 : 0,
            'created_by'         => 1,
            'created_at'         => $this->parseTimestamp($primary->added_on),
            'updated_at'         => $this->parseTimestamp($primary->added_on),
        ]);

        $this->migrateCorrigendums($newTenderId, $enLegacyId, $hiLegacyId);
    }

    private function migrateCorrigendums($newTenderId, $enLegacyId, $hiLegacyId)
    {
        $enCorrs = collect();
        $hiCorrs = collect();

        if ($enLegacyId) {
            $enCorrs = DB::connection('mysql_legacy_en')->table('cmsm_corrigendums')->where('tender_id', $enLegacyId)->get();
        }

        if ($hiLegacyId) {
            $hiCorrs = DB::connection('mysql_legacy_hi')->table('cmsm_corrigendums')->where('tender_id', $hiLegacyId)->get();
        }

        $processedHiCorrIds = [];

        foreach ($enCorrs as $ec) {
            // Match by name within the pool of corrigendums for this specific tender
            $hc = $hiCorrs->where('corrigendum_name', $ec->corrigendum_name)->first();
            
            if ($hc) { $processedHiCorrIds[] = $hc->corrigendum_id; }

            DB::table('tender_corrigendums')->insert([
                'tender_id'    => $newTenderId,
                'title'        => $ec->corrigendum_name ?? (translateToEnglish($hc->corrigendum_name)),
                'title_hi'     => $hc->corrigendum_name ?? (translateToHindi($ec->corrigendum_name_hindi)),
                'file_name'    => $this->clean($ec->file_path),
                'file_name_hi' => $hc ? $this->clean($hc->file_path) : $this->clean($ec->file_path),
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        $remainingHiCorrs = $hiCorrs->whereNotIn('corrigendum_id', $processedHiCorrIds);
        foreach ($remainingHiCorrs as $hcOnly) {
            DB::table('tender_corrigendums')->insert([
                'tender_id'    => $newTenderId,
                'title'        => translateToEnglish($hcOnly->corrigendum_name) ?? null,
                'title_hi'     => translateToHindi($hcOnly->corrigendum_name) ?? null,
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
        return (empty($v) || in_array($v, ['-', '0', 'null'])) ? null : $v;
    }

    private function parseDate($date) {
        if (!$date || in_array(trim($date), ['0000-00-00', '00-00-0000', '-', ''])) return null;
        try { return Carbon::parse($date)->format('Y-m-d'); } catch (\Exception $e) { return null; }
    }

    private function parseTimestamp($date) {
        if (!$date || in_array(trim($date), ['0000-00-00 00:00:00', '0000-00-00', ''])) return null;
        try { return Carbon::parse($date); } catch (\Exception $e) { return null; }
    }
}
