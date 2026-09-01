<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PhotoGalleryFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('photo_gallery_files')->truncate();
        $legacyRecords = DB::connection('mysql_legacy_en')
            ->table('cmsm_gallery')
            ->get();

        $count = $legacyRecords->count();
        $this->command->info("Found {$count} gallery files. Starting migration...");

        foreach ($legacyRecords as $record) {
            
            $createdAt = $record->upload_date=='0000-00-00'? $record->added_on:$record->upload_date;
           
            DB::table('photo_gallery_files')->updateOrInsert(
                ['id' => $record->gallery_id],
                [
                    'photo_gallery_id' => $record->category_id,
                    'file_name'        => $record->added_file,
                    'title'            => $record->category_name,
                    'title_hi'         => $record->category_name_hindi,
                    'created_by'       => 1,
                    'updated_by'       => 1,
                    'created_at'       => $createdAt,
                    'updated_at'       =>$createdAt,
                    'deleted_at'       => null,
                ]
            );

            // Progress dot
            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nPhoto Gallery Files migration completed successfully.");
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