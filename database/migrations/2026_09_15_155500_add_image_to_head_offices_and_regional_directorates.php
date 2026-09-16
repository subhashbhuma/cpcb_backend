<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('head_offices') && !Schema::hasColumn('head_offices', 'image')) {
            Schema::table('head_offices', function (Blueprint $table) {
                $table->string('image')->nullable()->after('email');
            });
        }

        if (Schema::hasTable('regional_directorates') && !Schema::hasColumn('regional_directorates', 'image')) {
            Schema::table('regional_directorates', function (Blueprint $table) {
                $table->string('image')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('head_offices') && Schema::hasColumn('head_offices', 'image')) {
            Schema::table('head_offices', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

        if (Schema::hasTable('regional_directorates') && Schema::hasColumn('regional_directorates', 'image')) {
            Schema::table('regional_directorates', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
