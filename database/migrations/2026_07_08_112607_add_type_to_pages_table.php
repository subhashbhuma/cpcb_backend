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
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('type', ['website', 'employee'])->default('employee')->after('id');
        });

        // Update existing pages to 'website'
        \Illuminate\Support\Facades\DB::table('pages')->update(['type' => 'website']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
