<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cast show_order to bigint. Uses USING to convert existing string values to bigint.
        DB::statement("ALTER TABLE directories ALTER COLUMN show_order TYPE bigint USING (show_order::bigint);");
        DB::statement("ALTER TABLE directories ALTER COLUMN show_order SET DEFAULT 1;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to character varying if needed (safe fallback).
        DB::statement("ALTER TABLE directories ALTER COLUMN show_order TYPE character varying USING (show_order::text);");
        DB::statement("ALTER TABLE directories ALTER COLUMN show_order SET DEFAULT '1';");
    }
};
