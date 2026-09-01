<?php

namespace Database\Seeders;

use App\Models\HomeAbout;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeAboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeAbout::create([
            'title' => 'Welcome to Our Platform',
            'title_hi' => 'हमारे मंच में आपका स्वागत है',
            'description' => 'We provide top-notch services to enhance your digital presence.',
            'description_hi' => 'हम आपकी डिजिटल उपस्थिति को बढ़ाने के लिए उच्च गुणवत्ता वाली सेवाएं प्रदान करते हैं।',
            'button_link' => 'https://example.com/about',
            'image' => 'default.jpg', // Make sure this image exists in the public storage or seed file accordingly
            'is_approved' => 1,
            'is_published' => 1,
            'remarks' => 'Seeded data',
            'created_by' => 1,
            'updated_by' => null,
        ]);
    }
}
