<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_files', function (Blueprint $table) {
            $table->date('upload_date')->nullable()->after('description_hi');
        });

        // Update existing data: set upload_date to updated_at date
        DB::table('page_files')->update([
            'upload_date' => DB::raw('updated_at')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_files', function (Blueprint $table) {
            $table->dropColumn('upload_date');
        });
    }
};
