<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConvertBooleanColumnsToIntegerSeeder extends Seeder
{
    /**
     * List of database tables to check and convert.
     * Add any additional tables to this array as needed.
     *
     * @var array<string>
     */
    protected array $tables = [
        // Tables currently containing boolean is_approved / is_published columns
        'comment_reports',
        'direction_states',
        'regional_directorates',

        // Other project tables
        'agra_air_qualities',
        'announcements',
        'annual_reports',
        'circulars',
        'complaint_form_subjects',
        'complaint_histories',
        'complaints',
        'contact_details',
        'designations',
        'direction_act_types',
        'direction_categories',
        'direction_issued_tos',
        'direction_subjects',
        'direction_types',
        'directions',
        'directories',
        'divisions',
        'environmental_regulation_details',
        'environmental_regulations',
        'events',
        'faq',
        'faqs',
        'feedback_histories',
        'feedbacks',
        'fortnightly_reports',
        'gallery_events',
        'government_portals',
        'head_offices',
        'home_abouts',
        'information_center_details',
        'information_centers',
        'job_posts',
        'job_results',
        'jobs',
        'laboratories_category',
        'laboratories_file',
        'laboratories_page',
        'latest_cpcbs',
        'letters_issued',
        'ngt_court_cases',
        'page_images',
        'pages',
        'photo_galleries',
        'photo_gallery_files',
        'portals',
        'publication_categories',
        'publications',
        'quality_zones',
        'query_form_subjects',
        'quick_links',
        'recruitment_announcements',
        'regional_directories',
        'sliders',
        'social_media',
        'social_media_platforms',
        'studies_reports',
        'subject_areas',
        'technical_reports',
        'tender_categories',
        'tender_corrigendums',
        'tenders',
        'users',
        'video_galleries',
        'visitor_stats',
        'visitors',
        'who_is_whos',
        'zonal_offices',
    ];

    /**
     * Target column names to inspect and convert (both singular and past-tense variants).
     *
     * @var array<string>
     */
    protected array $targetColumns = [
        'is_approve',
        'is_approved',
        'is_publish',
        'is_published',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driver = DB::getDriverName();
        $this->command?->info("🚀 Starting conversion of boolean columns to integer (Driver: {$driver})...");
        $this->command?->info(str_repeat('─', 70));

        $convertedCount = 0;
        $skippedCount = 0;
        $missingTableCount = 0;

        foreach ($this->tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                $this->command?->warn("⚠️  Table '{$tableName}' does not exist. Skipping.");
                $missingTableCount++;
                continue;
            }

            foreach ($this->targetColumns as $columnName) {
                if (!Schema::hasColumn($tableName, $columnName)) {
                    continue;
                }

                $columnType = $this->getColumnDataType($tableName, $columnName, $driver);
                $isBoolean = $this->isBooleanType($columnType);

                if (!$isBoolean) {
                    $skippedCount++;
                    continue;
                }

                $this->command?->line("🔄 Converting <comment>{$tableName}.{$columnName}</comment> (current type: <info>{$columnType}</info>) to integer...");

                try {
                    $this->convertColumnToInteger($tableName, $columnName, $driver);
                    $convertedCount++;
                    $this->command?->info("   ✅ Successfully converted {$tableName}.{$columnName} to integer (true -> 1, false -> 0).");
                } catch (\Throwable $e) {
                    $this->command?->error("   ❌ Failed to convert {$tableName}.{$columnName}: " . $e->getMessage());
                }
            }
        }

        $this->command?->info(str_repeat('─', 70));
        $this->command?->info("🎉 Conversion completed!");
        $this->command?->info("   - Total columns converted : {$convertedCount}");
        $this->command?->info("   - Columns already integer/other : {$skippedCount}");
        if ($missingTableCount > 0) {
            $this->command?->info("   - Tables not found : {$missingTableCount}");
        }
    }

    /**
     * Determine the database column data type.
     */
    protected function getColumnDataType(string $tableName, string $columnName, string $driver): string
    {
        if ($driver === 'pgsql') {
            $colInfo = DB::selectOne("
                SELECT data_type, udt_name 
                FROM information_schema.columns 
                WHERE table_schema = 'public' 
                  AND table_name = ? 
                  AND column_name = ?
            ", [$tableName, $columnName]);

            if ($colInfo) {
                return strtolower($colInfo->data_type ?: $colInfo->udt_name);
            }
        }

        return strtolower(Schema::getColumnType($tableName, $columnName));
    }

    /**
     * Check if the data type represents a boolean.
     */
    protected function isBooleanType(string $type): bool
    {
        return in_array($type, ['boolean', 'bool']);
    }

    /**
     * Convert boolean column to integer and map values: true -> 1, false -> 0.
     */
    protected function convertColumnToInteger(string $tableName, string $columnName, string $driver): void
    {
        if ($driver === 'pgsql') {
            // Drop existing default constraint (which is boolean) to allow type alteration
            DB::statement("ALTER TABLE \"{$tableName}\" ALTER COLUMN \"{$columnName}\" DROP DEFAULT");

            // Convert column type to integer and cast values: true -> 1, false -> 0, null -> 0
            DB::statement("ALTER TABLE \"{$tableName}\" ALTER COLUMN \"{$columnName}\" TYPE integer USING (CASE WHEN \"{$columnName}\" IS TRUE THEN 1 WHEN \"{$columnName}\" IS FALSE THEN 0 ELSE 0 END)");

            // Set new integer default value to 0
            DB::statement("ALTER TABLE \"{$tableName}\" ALTER COLUMN \"{$columnName}\" SET DEFAULT 0");
        } elseif ($driver === 'mysql') {
            // Update values: true/1 -> 1, false/0 -> 0
            DB::statement("UPDATE `{$tableName}` SET `{$columnName}` = CASE WHEN `{$columnName}` = 1 OR `{$columnName}` IS TRUE THEN 1 ELSE 0 END");

            // Modify column definition to integer
            DB::statement("ALTER TABLE `{$tableName}` MODIFY `{$columnName}` INT NOT NULL DEFAULT 0");
        } else {
            // Generic SQLite / other database fallback
            DB::statement("UPDATE \"{$tableName}\" SET \"{$columnName}\" = CASE WHEN \"{$columnName}\" = 1 THEN 1 ELSE 0 END");
        }
    }
}
