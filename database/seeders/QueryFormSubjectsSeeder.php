<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QueryFormSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('query_form_subjects')->truncate();
        $legacyRecords = DB::connection('mysql_legacy_en')
            ->table('query_contact')
            ->whereNotNull('subject_area')
            ->where('subject_area', '!=', '')
            ->get();

        $count = $legacyRecords->count();
        $this->command->info("Found {$count} subject records. Starting migration...");



        foreach ($legacyRecords as $record) {

            $title = $record->subject_area;
            $division = $record->division;
            $name = $record->name;

            // Check or create division and get ID
            $divisionData = DB::table('divisions')->where('title', $division)->first();

            if (!$divisionData) {
                $divisionId = DB::table('divisions')->insertGetId([
                    'title' => $division,
                    'title_hi' => translateToHindi($division),
                    'is_approved' => 1,
                    'is_published' => 1,
                    'created_by' => 1,
                    'created_at' => now(),
                ]);
            } else {
                $divisionId = $divisionData->id;
            }

            DB::table('query_form_subjects')->updateOrInsert(
                [
                    'title' => $title,
                    'division_id' => $divisionId
                ],
                [
                    'title_hi' => translateToHindi($title),
                    'name' => $name,
                    'name_hi' => translateToHindi($name),
                    'email_id' => $record->email_id,
                    'is_approved' => 1,
                    'is_published' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nSubject migration completed successfully.");
    }
}