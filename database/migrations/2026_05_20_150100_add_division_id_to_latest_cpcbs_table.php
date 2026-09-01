<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('latest_cpcbs', function (Blueprint $table) {
            if (!Schema::hasColumn('latest_cpcbs', 'division_id')) {
                $table->unsignedBigInteger('division_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('latest_cpcbs', function (Blueprint $table) {
            if (Schema::hasColumn('latest_cpcbs', 'division_id')) {
                $table->dropColumn('division_id');
            }
        });
    }
};
