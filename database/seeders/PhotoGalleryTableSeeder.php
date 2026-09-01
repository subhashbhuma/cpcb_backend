<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PhotoGalleryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('photo_galleries')->truncate();
        $legacyRecords = DB::connection('mysql_legacy_en')
            ->table('cmsm_category')
            ->get();

        $count = $legacyRecords->count();
        $this->command->info("Found {$count} photos. Starting migration with translation...");

        foreach ($legacyRecords as $record) {
            
            $englishTitle = $record->category_name;
            $createdAt = $this->parseTimestamp($record->added_on);    
            $hindiTitle = translateToHindi($englishTitle);
            DB::table('photo_galleries')->updateOrInsert(
                ['id' => $record->category_id], 
                [
                    'gallery_event_id' => $record->category, 
                    'title'            => $englishTitle,
                    'title_hi'         => $hindiTitle,
                    'featured_image'   => $record->added_file,
                    'description'      => null,
                    'description_hi'   => null,
                    'date'             => $createdAt,
                    'is_approved'      => 1,
                    'is_published'     => 1,
                    'remarks'          => null,
                    'created_by'       => 1,
                    'updated_by'       => 1,
                    'created_at'       => $createdAt,
                    'updated_at'       => $createdAt,
                    'deleted_at'       => null,
                ]
            );
            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nPhoto Gallery migration completed successfully.");
    }

   

    /**
     * Helper: Parse Timestamps
     */
    private function parseTimestamp($date)
    {
        if (!$date || $date == '0000-00-00 00:00:00') {
            return now();
        }
        try {
            return date('Y-m-d H:i:s', strtotime($date));
        } catch (\Exception $e) {
            return now();
        }
    }
}