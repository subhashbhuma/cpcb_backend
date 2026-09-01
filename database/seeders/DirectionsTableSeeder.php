<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DirectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('directions')->truncate();

        $this->command->info('Migrating Directions from legacy database...');

        // Fetch legacy data
        // Using 'mysql_legacy_en' connection as per PublicationSeeder pattern
        $legacyDirections = DB::connection('mysql_legacy_en')->table('cmsm_directions')->get();
        
        $this->command->getOutput()->progressStart(count($legacyDirections));

        foreach ($legacyDirections as $old) {
            
            // 1. Act Type
            // Map legacy 'act_type' string to ID. Create if not exists.
            $actTypeId = null;
            if (!empty($old->act_type)) {
                $actType = DB::table('direction_act_types')->where('title', trim($old->act_type))->first();
                if (!$actType) {
                    $actTypeId = DB::table('direction_act_types')->insertGetId([
                        'title' => trim($old->act_type),
                        'title_hi' => trim($old->act_type),
                        'is_approved' => 1,
                        'is_published' => 1,
                        'created_by' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $actTypeId = $actType->id;
                }
            }

            // 2. Direction Type (Notice Type)
            $typeId = null;
            if (!empty($old->notice_type)) {
                // Scope by Act Type
                $type = DB::table('direction_types')
                    ->where('title', trim($old->notice_type))
                    ->where('direction_act_type_id', $actTypeId)
                    ->first();

                if (!$type) {
                    $typeId = DB::table('direction_types')->insertGetId([
                        'direction_act_type_id' => $actTypeId, 
                        'title' => trim($old->notice_type),
                        'title_hi' => trim($old->notice_type),
                        'is_approved' => 1,
                        'is_published' => 1,
                        'created_by' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $typeId = $type->id;
                }
            }

            // 3. Subject (Section)
            $subjectId = null;
            if (!empty($old->section)) {
                // Scope by Act Type
                $subject = DB::table('direction_subjects')
                    ->where('title', trim($old->section))
                    ->where('direction_act_type_id', $actTypeId)
                    ->first();

                if (!$subject) {
                    $subjectId = DB::table('direction_subjects')->insertGetId([
                        'direction_act_type_id' => $actTypeId,
                        'title' => trim($old->section),
                        'title_hi' => trim($old->section),
                        'is_approved' => 1,
                        'is_published' => 1,
                        'created_by' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $subjectId = $subject->id;
                }
            }

            // 4. Category (Comma Separated)
            $categoryIds = [];
            if (!empty($old->category)) {
                $cats = explode(',', $old->category);
                foreach ($cats as $catName) {
                    $catName = trim($catName);
                    if (empty($catName)) continue;

                    // Scope by Act Type
                    $cat = DB::table('direction_categories')
                        ->where('title', $catName)
                        ->where('direction_act_type_id', $actTypeId)
                        ->first();

                    if (!$cat) {
                        $newId = DB::table('direction_categories')->insertGetId([
                            'direction_act_type_id' => $actTypeId,
                            'title' => $catName,
                            'title_hi' => $catName, // User prefers raw
                            'is_approved' => 1,
                            'is_published' => 1,
                            'created_by' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $categoryIds[] = $newId;
                    } else {
                        $categoryIds[] = $cat->id;
                    }
                }
            }
            $categoryIdsStr = !empty($categoryIds) ? implode(',', $categoryIds) : null;

            // 5. State (Comma Separated)
            $stateIds = [];
            if (!empty($old->state)) {
                $states = explode(',', $old->state);
                foreach ($states as $stName) {
                    $stName = trim($stName);
                    if ($stName == 'All') $stName = 'All States'; 
                    if (empty($stName)) continue;

                    // States are usually global, no act_type scope in previous seeders/logic observed?
                    // Checking Step 598: updateOrInsert(['title' => $state], ...). No act_type_id.
                    $st = DB::table('direction_states')->where('title', $stName)->first();
                    if (!$st) {
                        $newId = DB::table('direction_states')->insertGetId([
                            'title' => $stName,
                            'title_hi' => $stName,
                            'is_approved' => 1,
                            'is_published' => 1,
                            'created_by' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $stateIds[] = $newId;
                    } else {
                        $stateIds[] = $st->id;
                    }
                }
            }
            $stateIdsStr = !empty($stateIds) ? implode(',', $stateIds) : null;

            // 6. Issued To (Comma Separated)
            $issuedToIds = [];
            if (!empty($old->issued_to)) {
                $issuedList = explode(',', $old->issued_to);
                foreach ($issuedList as $issuedName) {
                    $issuedName = trim($issuedName);
                    if (empty($issuedName)) continue;

                    // Scope by Act Type
                    $issued = DB::table('direction_issued_tos')
                        ->where('title', $issuedName)
                        ->where('direction_act_type_id', $actTypeId)
                        ->first();

                    if (!$issued) {
                        $newId = DB::table('direction_issued_tos')->insertGetId([
                            'direction_act_type_id' => $actTypeId,
                            'title' => $issuedName,
                            'title_hi' => $issuedName,
                            'is_approved' => 1,
                            'is_published' => 1,
                            'created_by' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $issuedToIds[] = $newId;
                    } else {
                        $issuedToIds[] = $issued->id;
                    }
                }
            }
            $issuedToIdsStr = !empty($issuedToIds) ? implode(',', $issuedToIds) : null;
            $fileName = $this->clean($old->download_link);
            $publishDate = $this->parseDate($old->year);

            // Insert into new table
            DB::table('directions')->insert([
                'direction_act_type_id' => $actTypeId,
                'direction_type_id'     => $typeId,
                'direction_subject_id'  => $subjectId,
                'title'                 => $old->body_name,
                'title_hi'              => $old->body_name,
                'publish_date'          => $publishDate,
                'file_name'             => $fileName,
                'file_name_hi'          => null,
                'is_approved'           => 1,
                'is_published'          => 1,
                'remarks'               => null,
                'created_by'            => 1,
                'updated_by'            => 1,
                'direction_state_id'    => $stateIdsStr,
                'direction_category_id' => $categoryIdsStr,
                'direction_issued_to_id'=> $issuedToIdsStr,
                'created_at'            => $this->parseTimestamp($old->added_on),
                'updated_at'            => $publishDate,
            ]);

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Directions migrated successfully.');
    }

    private function clean($val) {
        $v = trim($val);
        return (empty($v) || in_array($v, ['-', '0', 'null'])) ? null : $v;
    }

    private function parseTimestamp($date) {
        if (!$date || in_array(trim($date), ['0000-00-00 00:00:00', '0000-00-00', ''])) return null;
        try { return Carbon::parse($date); } catch (\Exception $e) { return null; }
    }

    private function parseDate($date) {
        // Handle year only or full date
        if (!$date || in_array(trim($date), ['0000-00-00', ''])) return null;
        try { 
            return Carbon::parse($date)->format('Y-m-d'); 
        } catch (\Exception $e) { return null; }
    }
}
