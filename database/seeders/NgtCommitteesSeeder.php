<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NgtCommitteesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
        $createdBy = 1;
        $newFiles = [
    
        ];

        // Format and merge new files
        foreach ($newFiles as $fileName) {
            $files[] = [
                'file' => $fileName,
                'name' => strtoupper(str_replace(['_', '-', '.pdf', '.xlsx', '.docx'], ' ', $fileName))
            ];
        }

        // Database Injection
        foreach ($files as $f) {
            DB::table('media')->updateOrInsert(
                [
                    'file_name' => $f['file'],
                ],
                [
                    'original_name' => $f['name'],
                    'mime_type'     => Str::endsWith($f['file'], '.pdf') ? 'application/pdf' : 'application/octet-stream',
                    'size'          => 1024,
                    'alt_text'      => $f['name'],
                    'alt_text_hi'   => $f['name'],
                    'created_by'    => $createdBy,
                    'updated_by'    => 1,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]
            );
        }
    }
}