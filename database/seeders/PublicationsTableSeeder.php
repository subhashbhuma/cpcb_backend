<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicationsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate new PostgreSQL tables
        DB::table('publications')->truncate();
        DB::table('publication_categories')->truncate();
        $this->command->info('Migrating Publication Categories...');
        $oldReportTypes = DB::connection('mysql_legacy_en')->table('cmsm_report_type')->orderBy('report_type_id', 'asc')->get();

        foreach ($oldReportTypes as $type) {
            $fullName = trim($type->report_type);
            $title = $fullName;
            $code = null;

            if (preg_match('/^(.*?)\s*\((.*?)\)$/', $fullName, $matches)) {
                $title = trim($matches[1]);
                $code = trim($matches[2]);
            }

            DB::table('publication_categories')->insert([
                'id'           => $type->report_type_id,
                'title'        => $title,
                'title_hi'     => translateToHindi($title), 
                'code'         => $code,
                'code_hi'         => translateToHindi($code),
                'is_approved'  => 1,
                'is_published' => 1,
                'created_by'   => 1,
                'created_at'   => now(),
            ]);
        }

        $this->command->info('Migrating Publications...');
        $oldPublications = DB::connection('mysql_legacy_en')->table('cmsm_publication')->get();
        $this->command->getOutput()->progressStart(count($oldPublications));

        DB::transaction(function () use ($oldPublications) {
            foreach ($oldPublications as $old) {
                $catId = DB::table('publication_categories')
                    ->where(DB::raw("CONCAT(title, ' (', code, ')')"), trim($old->publication_type))
                    ->orWhere('title', trim($old->publication_type))
                    ->value('id');

                DB::table('publications')->insert([
                    'category_id'  => $catId,
                    'title'        => $old->title,
                    'title_hi'     => translateToHindi($old->title),
                    'price'        => (float) $old->cost,
                    'file_name'    => $this->clean($old->download_link),
                    'file_name_hi' => $this->clean($old->download_link),
                    'is_approved'  => 1,
                    'is_published' => ($old->status == 1) ? 1 : 0,
                    'published_date' => $this->parseTimestamp($old->publication_period),
                    'created_by'   => 1,
                    'created_at'   => $this->parseTimestamp($old->added_on),
                    'updated_at'   => $this->parseTimestamp($old->added_on),
                ]);

                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
        $this->command->info('Migration completed successfully.');
    }

    private function clean($val) {
        $v = trim($val);
        return (empty($v) || in_array($v, ['-', '0', 'null'])) ? null : $v;
    }

    private function parseTimestamp($date) {
        if (!$date || in_array(trim($date), ['0000-00-00 00:00:00', '0000-00-00', ''])) return null;
        try { return Carbon::parse($date); } catch (\Exception $e) { return null; }
    }
}