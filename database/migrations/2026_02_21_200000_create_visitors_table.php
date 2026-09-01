<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            // Add new columns for richer tracking
            if (!Schema::hasColumn('visitors', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('visitors', 'page_url')) {
                $table->string('page_url', 500)->nullable()->after('user_agent');
            }
            if (!Schema::hasColumn('visitors', 'session_id')) {
                $table->string('session_id', 100)->nullable()->after('page_url');
            }

            // Add indexes for performance
            $table->index('ip_address', 'visitors_ip_address_index');
            $table->index('visit_date', 'visitors_visit_date_index');
        });

        // Add composite unique constraint (ignore if already exists)
        try {
            Schema::table('visitors', function (Blueprint $table) {
                $table->unique(['ip_address', 'visit_date'], 'visitors_ip_date_unique');
            });
        } catch (\Exception $e) {
            // Constraint may already exist, skip silently
        }
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex('visitors_ip_address_index');
            $table->dropIndex('visitors_visit_date_index');
            $table->dropUnique('visitors_ip_date_unique');
            $table->dropColumn(['user_agent', 'page_url', 'session_id']);
        });
    }
};
