<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialMediaPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            ['name' => 'Facebook', 'name_hi' => 'फेसबुक'],
            ['name' => 'Twitter', 'name_hi' => 'ट्विटर'],
            ['name' => 'Instagram', 'name_hi' => 'इंस्टाग्राम'],
            ['name' => 'LinkedIn', 'name_hi' => 'लिंक्डइन'],
            ['name' => 'YouTube', 'name_hi' => 'यूट्यूब'],
        ];

        $now = Carbon::now();

        foreach ($platforms as &$platform) {
            $platform['created_at'] = $now;
            $platform['updated_at'] = $now;
        }

        DB::table('social_media_platforms')->insert($platforms);
    }
}
