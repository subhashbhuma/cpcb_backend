<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class EventGalleryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gallery_events')->truncate();
        $legacyRecords = DB::connection('mysql_legacy_en')
            ->table('cmsm_categorytop')
            ->get();

        $count = $legacyRecords->count();
        $this->command->info("Found {$count} records. Starting translation...");

        foreach ($legacyRecords as $index => $record) {
            $englishTitle = $record->cat_name;
            $createdAt = $this->parseTimestamp($record->added_on);
            $hindiTitle = translateToHindi($englishTitle);
            DB::table('gallery_events')->updateOrInsert(
                ['id' => $record->id],
                [
                    'title'          => $englishTitle,
                    'title_hi'       => $hindiTitle, 
                    'featured_image' => $record->added_file,
                    'is_approved'    => 1,
                    'is_published'   => 1,
                    'created_by'     => 1,
                    'updated_by'     => 1,
                    'created_at'     => $createdAt,
                    'updated_at'     => now(),
                ]
            );
            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nMigration completed successfully.");
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