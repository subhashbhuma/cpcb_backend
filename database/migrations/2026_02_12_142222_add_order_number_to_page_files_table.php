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
        Schema::table('page_files', function (Blueprint $table) {
            $table->integer('order_number')->default(0)->after('description_hi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_files', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
