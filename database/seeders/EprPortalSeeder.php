<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EprPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\EprPortal::truncate();
       $portals = [
            [
                'title' => 'Single Sign-On (SSO) for existing EPR Portals',
                'link' => 'https://epr.cpcb.gov.in/login',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'ELV EPR',
                'link' => 'https://eprelv.cpcb.gov.in/',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Used oil EPR',
                'link' => 'https://eprusedoil.cpcb.gov.in/',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'title' => 'Plastic Waste EPR',
                'link' => 'https://eprplastic.cpcb.gov.in/#/plastic/home',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'title' => 'Battery Waste EPR',
                'link' => 'https://eprbattery.cpcb.gov.in/',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'title' => 'Waste Tyre EPR',
                'link' => 'https://eprtyres.cpcb.gov.in/',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'title' => 'E-Waste EPR',
                'link' => 'https://eprewaste.cpcb.gov.in/#/',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'C&D Waste EPR',
                'link' => '#',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 0,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Non Ferrous EPR',
                'link' => '#',
                'is_approved' => 1,
                'is_published' => 1,
                'is_live' => 0,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach($portals as $portal){
            \App\Models\EprPortal::insert([
                'title' => $portal['title'],
                 'title_hi' => translateToHindi($portal['title']),
                'link' => $portal['link'],
                'is_approved' => $portal['is_approved'],
                'is_published' => $portal['is_published'],
                'is_live' => $portal['is_live'],
                'created_by' => $portal['created_by'],
                'created_at' => $portal['created_at'],
                'updated_at' => $portal['updated_at'],
            ]);
        }
    }
}
