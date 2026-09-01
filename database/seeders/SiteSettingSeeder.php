<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::insert([
            'site_name' => 'Central Pollution Control Board',
            'site_tag_line' => 'Ministry of Environment, Forest and Climate Change',
            'seo_keywords' => 'Central Pollution Control Board',
            'seo_description' => 'Central Pollution Control Board',
            'header_logo' => 'logo.png',
            'footer_logo' => 'logo.png',
            'favicon' => 'favicon.ico',
        ]);
    }
}
