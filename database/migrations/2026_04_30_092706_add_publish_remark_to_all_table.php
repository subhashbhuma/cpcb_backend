<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration {
    protected array $tables = [
        "agra_air_qualities",
        "announcements",
        "annual_reports",
        "circulars",
        "comment_reports",
        "complaint_form_subjects",
        "complaint_histories",
        "complaints",
        "contact_details",
        "designations",
        "direction_act_types",
        "direction_categories",
        "direction_issued_tos",
        "direction_states",
        "direction_subjects",
        "direction_types",
        "directions",
        "directories",
        "divisions",
        "environmental_regulation_details",
        "environmental_regulations",
        "events",
        "faq",
        "faqs",
        "feedback_histories",
        "feedbacks",
        "fortnightly_reports",
        "gallery_events",
        "government_portals",
        "head_offices",
        "home_abouts",
        "information_center_details",
        "information_centers",
        "job_posts",
        "job_results",
        "jobs",
        "laboratories_category",
        "laboratories_file",
        "laboratories_page",
        "latest_cpcbs",
        "letters_issued",
        "ngt_court_cases",
        "page_images",
        "pages",
        "photo_galleries",
        "photo_gallery_files",
        "portals",
        "publication_categories",
        "publications",
        "quality_zones",
        "query_form_subjects",
        "quick_links",
        "recruitment_announcements",
        "regional_directorates",
        "regional_directories",
        "sliders",
        "social_media",
        "social_media_platforms",
        "studies_reports",
        "subject_areas",
        "technical_reports",
        "tender_categories",
        "tender_corrigendums",
        "tenders",
        "users",
        "video_galleries",
        "visitor_stats",
        "visitors",
        "who_is_whos",
        "zonal_offices",
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {

            try {

                if (!Schema::hasTable($tableName)) {
                    Log::warning("Table not found: {$tableName}");
                    continue;
                }

                // Required columns check
                $requiredColumns = ['is_approved', 'is_published', 'remarks'];

                foreach ($requiredColumns as $col) {
                    if (!Schema::hasColumn($tableName, $col)) {
                        Log::info("Skipping {$tableName} - missing column: {$col}");
                        continue 2; // skip this table
                    }
                }

                // Already exists check
                if (Schema::hasColumn($tableName, 'publish_remark')) {
                    Log::info("Column already exists in {$tableName}");
                    continue;
                }

                // Add column safely
                Schema::table($tableName, function (Blueprint $table) {
                    $table->text('publish_remark')->nullable()->after('remarks');
                });

                Log::info("publish_remark added to {$tableName}");

            } catch (\Throwable $e) {

                Log::error("Migration failed for table: {$tableName}", [
                    'error' => $e->getMessage(),
                ]);

                // DO NOT throw -> prevents full migration crash
                continue;
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {

            try {

                if (
                    Schema::hasTable($tableName) &&
                    Schema::hasColumn($tableName, 'publish_remark')
                ) {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->dropColumn('publish_remark');
                    });

                    Log::info("publish_remark dropped from {$tableName}");
                }

            } catch (\Throwable $e) {

                Log::error("Rollback failed for table: {$tableName}", [
                    'error' => $e->getMessage(),
                ]);

                continue;
            }
        }
    }
};