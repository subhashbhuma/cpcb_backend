<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('social_media_platforms', 'name_hi')) {
            Schema::table('social_media_platforms', function (Blueprint $table) {
                $table->string('name_hi')->nullable()->after('name');
            });
        }

        // Translated names for platforms in Hindi
        $translations = [
            'Facebook'  => 'फेसबुक',
            'Twitter'   => 'ट्विटर',
            'X'         => 'एक्स (ट्विटर)',
            'Instagram' => 'इंस्टाग्राम',
            'LinkedIn'  => 'लिंक्डइन',
            'Linkedin'  => 'लिंक्डइन',
            'YouTube'   => 'यूट्यूब',
            'Youtube'   => 'यूट्यूब',
            'TikTok'    => 'टिकटॉक',
            'Snapchat'  => 'स्नैपचैट',
            'Pinterest' => 'पिंटरेस्ट',
            'WhatsApp'  => 'व्हाट्सएप',
            'Telegram'  => 'टेलीग्राम',
        ];

        foreach ($translations as $name => $nameHi) {
            DB::table('social_media_platforms')
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->update(['name_hi' => $nameHi]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('social_media_platforms', 'name_hi')) {
            Schema::table('social_media_platforms', function (Blueprint $table) {
                $table->dropColumn('name_hi');
            });
        }
    }
};
