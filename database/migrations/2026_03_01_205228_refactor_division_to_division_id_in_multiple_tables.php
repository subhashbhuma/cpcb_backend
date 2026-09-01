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
        $tables = ['circulars', 'directories', 'head_offices', 'studies_reports'];

        // 1. Add division_id column
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'division_id')) {
                    $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
                }
            });
        }

        // 2. Data Migration: Try to map existing division text to division_id
        $divisions = \Illuminate\Support\Facades\DB::table('divisions')->get();

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'division')) {
                $records = \Illuminate\Support\Facades\DB::table($table)->whereNotNull('division')->where('division', '!=', '')->get();
                foreach ($records as $record) {
                    // Simple search for division text in divisions table
                    $matched = $divisions->first(function ($div) use ($record) {
                        return stripos(trim($record->division), trim($div->title)) !== false;
                    });

                    if ($matched) {
                        \Illuminate\Support\Facades\DB::table($table)
                            ->where('id', $record->id)
                            ->update(['division_id' => $matched->id]);
                    }
                }
            }
        }

        // 3. Drop old columns
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'division')) {
                    $table->dropColumn('division');
                }
                if (Schema::hasColumn($table->getTable(), 'division_hi')) {
                    $table->dropColumn('division_hi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['circulars', 'directories', 'head_offices', 'studies_reports'];

        // 1. Restore old columns
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('division')->nullable();
                $table->string('division_hi')->nullable();
            });
        }

        // 2. Data Migration: Map back if needed (we map division_id back to text)
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'division_id')) {
                $records = \Illuminate\Support\Facades\DB::table($table)->whereNotNull('division_id')->get();
                foreach ($records as $record) {
                    $division = \Illuminate\Support\Facades\DB::table('divisions')->where('id', $record->division_id)->first();
                    if ($division) {
                         \Illuminate\Support\Facades\DB::table($table)
                            ->where('id', $record->id)
                            ->update([
                                'division' => $division->title,
                                'division_hi' => $division->title_hi
                            ]);
                    }
                }
            }
        }

        // 3. Drop division_id column
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['division_id']);
                $table->dropColumn('division_id');
            });
        }
    }
};
