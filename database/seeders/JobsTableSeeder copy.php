<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enRecords = DB::connection('mysql_legacy_en')->table('cmsm_career_en')->get();
        foreach ($enRecords as $en) {
            $hi = DB::connection('mysql_legacy_hi')
                ->table('cmsm_career_hi')
                ->where('career_id', $en->career_id)
                ->first();

            $startDate = $this->parseDate($en->issue_date);
            $endDate = $this->parseDate($en->last_date);
            $createdAt = $this->parseTimestamp($en->added_on);
            DB::table('jobs')->updateOrInsert(
                ['id' => $en->career_id],
                [
                    'title'                            => $en->title,
                    'title_hi'                         => $hi ? $hi->title : $en->title,
                    'start_date'                       => $startDate ?? now(),
                    'end_date'                         => $endDate,
                    'advertisement_file_name'          => $this->cleanFile($en->advertisement_array),
                    'direct_application_form_name'     => $this->cleanFile($en->application_form_array),
                    'deputation_application_form_name' => $this->cleanFile($en->application_form_deputation),
                    'advertisement_file_hi_name'          => $hi ? $this->cleanFile($hi->advertisement_array) : null,
                    'direct_application_form_hi_name'     => $hi ? $this->cleanFile($hi->application_form_array) : null,
                    'deputation_application_form_hi_name' => $hi ? $this->cleanFile($hi->application_form_deputation) : null,
                    'is_approved'  => 1,
                    'is_published' => 1,
                    'created_by'   => 1,
                    'created_at'   => $createdAt,
                    'updated_at'   => now(),
                ]
            );
        }

        $this->command->info('Successfully migrated ' . $enRecords->count() . ' jobs.');
    }
    private function cleanFile($value)
    {
        $trimmed = trim($value);
        if (empty($trimmed) || $trimmed === '0') {
            return null;
        }
        return $trimmed;
    }
    private function parseDate($date)
    {
        if (!$date || $date == '0000-00-00' || $date == '1969-12-31') {
            return null;
        }
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    private function parseTimestamp($date)
    {
        if (!$date || $date == '0000-00-00 00:00:00') {
            return now();
        }
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return now();
        }
    }
}
