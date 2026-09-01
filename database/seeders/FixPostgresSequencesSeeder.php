<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Fixes PostgreSQL sequence out-of-sync issues for ALL tables.
 *
 * This happens when rows are inserted with explicit IDs (e.g., in seeders),
 * which doesn't advance the PostgreSQL auto-increment sequence. As a result,
 * future inserts without an explicit ID try to reuse an existing ID and fail
 * with: "duplicate key value violates unique constraint".
 *
 * Usage:
 *   php artisan db:seed --class=FixPostgresSequencesSeeder
 */
class FixPostgresSequencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🔧 Fixing PostgreSQL sequences for all tables...');
        $this->command->info(str_repeat('─', 60));

        // Get all sequences in the database
        $sequences = DB::select("
            SELECT
                seq.relname AS sequence_name,
                tab.relname AS table_name,
                col.attname AS column_name
            FROM pg_class seq
            JOIN pg_depend dep ON dep.objid = seq.oid
            JOIN pg_class tab ON dep.refobjid = tab.oid
            JOIN pg_attribute col ON col.attrelid = tab.oid AND col.attnum = dep.refobjsubid
            WHERE seq.relkind = 'S'
            ORDER BY tab.relname
        ");

        if (empty($sequences)) {
            $this->command->warn('No sequences found in the database.');
            return;
        }

        $fixed = 0;
        $skipped = 0;
        $alreadySynced = 0;

        foreach ($sequences as $seq) {
            $tableName = $seq->table_name;
            $columnName = $seq->column_name;
            $sequenceName = $seq->sequence_name;

            // Get current max value in the table
            $maxId = DB::table($tableName)->max($columnName);

            if (is_null($maxId)) {
                // Table is empty, reset sequence to 1
                DB::statement("ALTER SEQUENCE \"{$sequenceName}\" RESTART WITH 1");
                $this->command->line("  ⏭  <comment>{$tableName}.{$columnName}</comment> → empty table, sequence reset to 1");
                $skipped++;
                continue;
            }

            // Get current sequence value
            $currentSeqVal = DB::selectOne("SELECT last_value FROM \"{$sequenceName}\"")->last_value;

            if ($currentSeqVal >= $maxId) {
                $this->command->line("  ✅ <info>{$tableName}.{$columnName}</info> → already synced (max={$maxId}, seq={$currentSeqVal})");
                $alreadySynced++;
                continue;
            }

            // Fix: set the sequence to the max id
            DB::statement("SELECT setval('\"' || ? || '\"', ?)", [$sequenceName, $maxId]);
            $this->command->line("  🔧 <error>{$tableName}.{$columnName}</error> → FIXED (max={$maxId}, was={$currentSeqVal}, now={$maxId})");
            $fixed++;
        }

        $this->command->info(str_repeat('─', 60));
        $this->command->info("📊 Summary:");
        $this->command->info("   Total sequences scanned: " . count($sequences));
        $this->command->info("   🔧 Fixed (out of sync):  {$fixed}");
        $this->command->info("   ✅ Already synced:       {$alreadySynced}");
        $this->command->info("   ⏭  Empty tables:         {$skipped}");
        $this->command->info('');

        if ($fixed > 0) {
            $this->command->info("✨ All sequences are now synced! New inserts will work correctly.");
        } else {
            $this->command->info("👍 All sequences were already in sync. Nothing to fix.");
        }

        $this->command->info('');
    }
}
