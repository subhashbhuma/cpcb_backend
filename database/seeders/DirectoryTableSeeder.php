<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DirectoryTableSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('directories')->truncate();
        $legacyRecords = DB::connection('mysql_legacy_en')->table('cmsm_directory')->get();
        $divisions = DB::table('divisions')->get();
        foreach ($legacyRecords as $row) {
            $createdAt = $this->parseTimestamp($row->added_on);


            $matchedDivisionId = null;

            if (!empty($row->division)) {
                $divText = trim($row->division);

                if ($divText) {
                    $matchedDiv = $divisions->first(function ($d) use ($divText) {
                        return strcasecmp(trim($d->title), $divText) === 0;
                    });

                    if ($matchedDiv) {
                        $matchedDivisionId = $matchedDiv->id;
                    }
                }
            }


            DB::table('directories')->insert(
                [
                    'image' => $row->cpcb_no != "NULL" ? $row->cpcb_no . '.png' : null,
                    'cpcb_no' => $this->cleanValue($row->cpcb_no),
                    'name' => $row->employee_name,
                    'email' => $this->cleanValue($row->email),
                    'name_hi' => $this->cleanValue($row->employee_name_in_hindi) ?? $this->cleanValue($row->employee_name),
                    'designation' => $this->cleanValue($row->designation),
                    'designation_hi' => $this->cleanValue($row->designation_hindi) ?? $this->cleanValue($row->designation),
                    'division_id' => $this->cleanValue($matchedDivisionId),
                    'office_ph_no' => $this->cleanValue($row->office_ph_no),
                    'mobile_no' => $this->cleanValue($row->mobile_no),
                    'ext_number' => $this->cleanValue($row->extension_no),
                    'order_no' => $row->orderno,
                    'show_order' => $row->show_order,
                    'remarks' => "Migrated Legacy ID: " . $row->directory_id,
                    'publish_remark' => "Migrated Legacy ID: " . $row->directory_id,
                    'is_approved' => 1,
                    'is_published' => ($row->status == 1) ? 1 : 0,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );
        }

        $this->command->info('Successfully migrated ' . $legacyRecords->count() . ' entries with auto-increment IDs.');
    }

    private function cleanValue($value)
    {
        $trimmed = trim($value);
        return (empty($trimmed) || $trimmed === '0' || $trimmed === 'NULL') ? null : $trimmed;
    }

    private function parseTimestamp($date)
    {
        if (!$date || $date == '0000-00-00 00:00:00')
            return;
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return;
        }
    }
}
