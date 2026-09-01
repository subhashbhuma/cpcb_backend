<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Collect EN date pairs (normalized)
        |--------------------------------------------------------------------------
        */
        $enDatePairs = DB::connection('mysql_legacy_en')
            ->table('cmsm_career')
            ->select('issue_date', 'last_date')
            ->get()
            ->map(function ($row) {
                return $this->normalizeDate($row->issue_date)
                    . '|' .
                    $this->normalizeDate($row->last_date);
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | 2. Fetch all HI records
        |--------------------------------------------------------------------------
        */
        $hiRecords = DB::connection('mysql_legacy_hi')
            ->table('cmsm_career')
            ->get();

        $this->command->info('HI records NOT existing in EN table (issue_date + last_date)');
        $this->command->getOutput()->progressStart($hiRecords->count());

        /*
        |--------------------------------------------------------------------------
        | 3. Compare & print missing records
        |--------------------------------------------------------------------------
        */
        foreach ($hiRecords as $hi) {

            $key =
                $this->normalizeDate($hi->issue_date)
                . '|' .
                $this->normalizeDate($hi->last_date);

            if (!in_array($key, $enDatePairs)) {

                $message = sprintf(
    'Career ID: %s | Issue: %s | Last: %s | Title: %s',
    $hi->career_id,
    $hi->issue_date,
    $hi->last_date,
    $hi->title
);

$this->command->line($message);
Log::info($message);

            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Check completed.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Normalize Date
    |--------------------------------------------------------------------------
    */
    private function normalizeDate($date): string
    {
        if (
            !$date ||
            $date === '0000-00-00' ||
            $date === '1969-12-31'
        ) {
            return 'NULL';
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return 'INVALID';
        }
    }
}
