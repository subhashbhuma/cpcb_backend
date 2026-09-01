<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReplaceUrlInDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:replace-url
                            {--old-url=https://164.52.202.77/cpcb/ : The URL to search for}
                            {--new-url=https://staging.akikocloud.xyz/cpcb/ : The URL to replace with}
                            {--force : Actually perform the replacement (default is dry-run)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all tables and text columns in the database and replace old URLs with new ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldUrl = $this->option('old-url');
        $newUrl = $this->option('new-url');
        $force  = $this->option('force');

        $this->info("Old URL: {$oldUrl}");
        $this->info("New URL: {$newUrl}");
        $this->info($force ? '⚡ Mode: LIVE (changes will be applied)' : '🔍 Mode: DRY-RUN (no changes will be made)');
        $this->newLine();

        // Get all user tables from PostgreSQL
        $tables = DB::select("
            SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = 'public'
              AND table_type = 'BASE TABLE'
            ORDER BY table_name
        ");

        $totalReplacements = 0;

        $bar = $this->output->createProgressBar(count($tables));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- %message%');
        $bar->setMessage('Starting...');

        foreach ($tables as $tableRow) {
            $table = $tableRow->table_name;
            $bar->setMessage("Scanning: {$table}");

            // Get all text/varchar columns for this table
            $columns = DB::select("
                SELECT column_name
                FROM information_schema.columns
                WHERE table_schema = 'public'
                  AND table_name = ?
                  AND data_type IN ('character varying', 'text', 'character')
                ORDER BY ordinal_position
            ", [$table]);

            if (empty($columns)) {
                $bar->advance();
                continue;
            }

            foreach ($columns as $colRow) {
                $column = $colRow->column_name;
                $quotedTable  = '"' . $table . '"';
                $quotedColumn = '"' . $column . '"';

                // Count matching rows
                $count = DB::selectOne("
                    SELECT COUNT(*) as cnt
                    FROM {$quotedTable}
                    WHERE {$quotedColumn}::text LIKE ?
                ", ['%' . $oldUrl . '%']);

                $matchCount = $count->cnt ?? 0;

                if ($matchCount > 0) {
                    $this->newLine();
                    $this->warn("  Found {$matchCount} occurrence(s) in [{$table}].[{$column}]");

                    if ($force) {
                        DB::statement("
                            UPDATE {$quotedTable}
                            SET {$quotedColumn} = REPLACE({$quotedColumn}::text, ?, ?)
                            WHERE {$quotedColumn}::text LIKE ?
                        ", [$oldUrl, $newUrl, '%' . $oldUrl . '%']);

                        $this->info("  ✅ Replaced {$matchCount} occurrence(s) in [{$table}].[{$column}]");
                    }

                    $totalReplacements += $matchCount;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($totalReplacements === 0) {
            $this->info('✅ No occurrences of the old URL were found in the database.');
        } elseif ($force) {
            $this->info("✅ Done! Replaced {$totalReplacements} total occurrence(s) across the database.");
        } else {
            $this->warn("Found {$totalReplacements} total occurrence(s). Run with --force to apply replacements:");
            $this->line("  php artisan app:replace-url --force");
        }

        return Command::SUCCESS;
    }
}
