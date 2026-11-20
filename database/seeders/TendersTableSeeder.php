<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TendersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tender_corrigendums')->truncate();
        DB::table('tenders')->truncate();

        $enTenders = DB::connection('mysql_legacy_en')
            ->table('cmsm_tender')
            ->get();

        $this->command->info('Starting Tender Migration...');

        DB::transaction(function () use ($enTenders) {

            $this->command->getOutput()->progressStart(count($enTenders));

            foreach ($enTenders as $en) {

                $this->insertTenderRow($en);

                $this->command->getOutput()->progressAdvance();
            }

            $this->command->getOutput()->progressFinish();
        });

        $this->command->info('Tender migration completed successfully.');
    }

    /**
     * Insert Tender (English + Hindi Translation)
     */
    private function insertTenderRow($en)
    {
        $divisionTitle = trim($en->tender_category ?? '');

        $divisionId = DB::table('divisions')
            ->where('title', $divisionTitle)
            ->value('id');

        $newTenderId = DB::table('tenders')->insertGetId([

            'division_id' => $divisionId,

            // English Fields
            'title' => $en->tender_name,
            'issuing_authority' => $en->issuing_authority,

            // Hindi Fields (Translated from English)
            'title_hi' => translateToHindi($en->tender_name),
            'issuing_authority_hi' => translateToHindi($en->issuing_authority),

            'start_date' => $this->parseDate($en->start_date_selling),
            'end_date' => $this->parseDate($en->closing_date_time),
            'publish_date' => $this->parseDate($en->closing_date_time),

            // Documents
            'file_name' => $this->clean($en->added_files),
            'file_name_hi' => null,

            'is_approved' => 1,
            'is_published' => 1,

            'created_by' => 1,
            'created_at' => $this->parseTimestamp($en->added_on),
            'updated_at' => $this->parseTimestamp($en->added_on),

        ]);

        // Migrate English corrigendums only
        $this->migrateCorrigendums($newTenderId, $en->tender_id);
    }

    /**
     * Migrate Corrigendums (English + Hindi Translation)
     */
    private function migrateCorrigendums($newTenderId, $enLegacyId)
    {
        $corrigendums = DB::connection('mysql_legacy_en')
            ->table('cmsm_corrigendums')
            ->where('tender_id', $enLegacyId)
            ->get();

        foreach ($corrigendums as $corr) {

            DB::table('tender_corrigendums')->insert([

                'tender_id' => $newTenderId,

                // English
                'title' => $corr->corrigendum_name,

                // Hindi Translation
                'title_hi' => translateToHindi($corr->corrigendum_name),

                'file_name' => $this->clean($corr->file_path),
                'file_name_hi' => $this->clean($corr->file_path),

                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ]);
        }
    }

    /**
     * Clean Empty Values
     */
    private function clean($val)
    {
        if ($val === null) {
            return null;
        }

        $v = trim($val);

        return (empty($v) || in_array(strtolower($v), ['-', '0', 'null']))
            ? null
            : $v;
    }

    /**
     * Parse Date
     */
    private function parseDate($date)
    {
        if (
            !$date || in_array(trim($date), [
                '0000-00-00',
                '00-00-0000',
                '-',
                ''
            ])
        ) {
            return null;
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse Timestamp
     */
    private function parseTimestamp($date)
    {
        if (
            !$date || in_array(trim($date), [
                '0000-00-00 00:00:00',
                '0000-00-00',
                ''
            ])
        ) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }
}
