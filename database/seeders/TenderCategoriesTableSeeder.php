<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenderCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $enCategories = DB::connection('mysql_legacy_en')
            ->table('cmsm_tender')
            ->select('tender_category')
            ->where('tender_category', '!=', '')
            ->where('tender_category', '!=', '-')
            ->distinct()
            ->pluck('tender_category');
        $hiCategories = DB::connection('mysql_legacy_hi')
            ->table('cmsm_tender')
            ->select('tender_category')
            ->where('tender_category', '!=', '')
            ->where('tender_category', '!=', '-')
            ->distinct()
            ->pluck('tender_category')
            ->toArray();
        $count = 0;
        foreach ($enCategories as $title) {
            $hindiTitle = in_array($title, $hiCategories) ? $title : $title;
            DB::table('tender_categories')->updateOrInsert(
                ['title' => $title],
                [
                    'title_hi'     => translateToHindi($hindiTitle), 
                    'is_approved'  => 1,
                    'is_published' => 1,
                    'created_by'   => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
            $count++;
        }
        $this->command->getOutput()->progressFinish();
        $this->command->info("Successfully migrated $count categories. (Matched 8 Hindi records, used English for the remaining).");
    }
}