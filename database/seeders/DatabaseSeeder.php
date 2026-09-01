<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            SiteSettingSeeder::class,
            CircularCategorySeeder::class,
            DivisionSeeder::class,
            HomeAboutSeeder::class,
            SocialMediaPlatformSeeder::class,
            HeadOfficesTableSeeder::class,
        ]);
    }
}
