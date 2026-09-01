<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\TranslatorService;
use Carbon\Carbon;

class JobsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jobs')->truncate();
        DB::table('job_posts')->truncate();
        DB::table('recruitment_announcements')->truncate();
        $translator = app(TranslatorService::class);
        $enRecords = DB::connection('mysql_legacy_en')->table('cmsm_career')->get();
        foreach ($enRecords as $en) {
            $hi = DB::connection('mysql_legacy_hi')
                ->table('cmsm_career')
                ->where('career_id', $en->career_id)
                ->first();

            // Logic: Use English date, but fallback to Hindi date if English is null/invalid
            $startDate = $this->parseDate($en->issue_date) ?? ($hi ? $this->parseDate($hi->issue_date) : null);
            $endDate   = $this->parseDate($en->last_date)  ?? ($hi ? $this->parseDate($hi->last_date) : null);
            
            // Priority for creation timestamp
            $createdAt = $this->parseTimestamp($en->added_on);

            DB::table('jobs')->updateOrInsert(
                ['id' => $en->career_id],
                [
                    // 'title'                               => $en->title,
                    // 'title_hi'                            => $hi ? $hi->title : $en->title,
                    // 'start_date'                          => $startDate,
                    // 'end_date'                            => $endDate,
                    // 'advertisement_file_name'             => $this->cleanFile($en->advertisement_array),
                    // 'direct_application_form_name'        => $this->cleanFile($en->application_form_array),
                    // 'deputation_application_form_name'    => $this->cleanFile($en->application_form_deputation),
                    // 'advertisement_file_hi_name'          => $hi ? $this->cleanFile($hi->advertisement_array) : null,
                    // 'direct_application_form_hi_name'     => $hi ? $this->cleanFile($hi->application_form_array) : null,
                    // 'deputation_application_form_hi_name' => $hi ? $this->cleanFile($hi->application_form_deputation) : null,
                    // 'is_approved'  => 1,
                    // 'is_published' => 1,
                    // 'created_by'   => 1,
                    // 'created_at'   => $createdAt,
                    // 'updated_at'   => $createdAt,

                'title' => $en->title,
                'title_hi' => $hi ? $hi->title : $translator->translate($en->title),
                'job_type' => $en->is_new==1?'regular':'contract',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'advertisement_file_name' => $this->cleanFile($en->advertisement_array),
                'advertisement_file_hi_name' => $hi ? $this->cleanFile($hi->advertisement_array) : null,
                'direct_application' =>'offline',
                'deputation_application' => 'offline',
                'direct_application_form_name' => $this->cleanFile($en->application_form_array),
                'direct_application_form_hi_name' => $hi ? $this->cleanFile($hi->application_form_array) : null,
                'deputation_application_form_name' => $this->cleanFile($en->application_form_deputation),
                'deputation_application_form_hi_name' => $hi ? $this->cleanFile($hi->application_form_deputation) : null,
                'direct_application_url' =>  null,
                'deputation_application_url' => null,
                'online_form_url' => null,
                'walk_in_interview_date' => $en->interview_date=='0000-00-00'?null:$en->interview_date,
                'is_approved' => 1,
                'is_published' => 1,
                'created_by' => 1,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                ]
            );

            DB::table('job_posts')->insertGetId([
                'job_id' => $en->career_id,
                'title' => $en->post_name,
                'title_hi' => $hi ? $hi->post_name : $translator->translate($en->post_name),
                'is_approved' => 1,
                'is_published' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Successfully migrated ' . $enRecords->count() . ' jobs.');
    }

    private function cleanFile($value)
    {
        $trimmed = trim($value);
        return (empty($trimmed) || $trimmed === '0') ? null : $trimmed;
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
            return null;
        }
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }
}