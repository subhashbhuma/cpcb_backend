<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\AgraAirQuality;
use App\Models\Announcement;
use App\Models\AnnualReport;
use App\Models\CommentReport;
use App\Models\Direction;
use App\Models\Directory;
use App\Models\Division;
use App\Models\Event;
use App\Models\Faq;
use App\Models\FortnightlyReport;
use App\Models\Job;
use App\Models\JobPost;
use App\Models\LatestCpcb;
use App\Models\LettersIssued;
use App\Models\NgtCourtCase;
use App\Models\Page;
use App\Models\PageFile;
use App\Models\PhotoGallery;
use App\Models\Portal;
use App\Models\Publication;
use App\Models\SiteSetting;
use App\Models\StudiesReport;
use App\Models\TechnicalReport;
use App\Models\Tender;
use App\Models\VideoGallery;

class SearchController extends Controller
{
    /**
     * Cache for Schema::hasColumn to avoid repeated DB schema queries
     */
    protected static $columnCache = [];

    /**
     * Mapping of searchable content types to their model classes.
     * Each entry: 'type_key' => [model, fallback_url, label]
     */
    protected $searchableModels = [
        'agra_air_quality'   => [AgraAirQuality::class,  '/agra-air-quality',  'Agra Air Quality'],
        'announcement'       => [Announcement::class,    '/announcements',     'Announcement'],
        'annual_report'      => [AnnualReport::class,    '/annual-reports',    'Annual Report'],
        'comment_report'     => [CommentReport::class,   '/comment-reports',   'Comment Report'],
        'direction'          => [Direction::class,       '/directions',        'Direction'],
        'directory'          => [Directory::class,       '/contact/directory', 'Directory'],
        'division'           => [Division::class,        '/divisions',         'Division'],
        'event'              => [Event::class,           '/events',            'Event'],
        'faq'                => [Faq::class,             '/faqs',              'FAQ'],
        'fortnightly_report' => [FortnightlyReport::class, '/fortnightly-reports', 'Fortnightly Report'],
        'job'                => [Job::class,             '/jobs',              'Job'],
        'job_post'           => [JobPost::class,         '/jobs',              'Job Post'],
        'latest_cpcb'        => [LatestCpcb::class,      '/latest-cpcb',       'Latest CPCB'],
        'letters_issued'     => [LettersIssued::class,   '/letters-issued',    'Letters Issued'],
        'ngt_court_case'     => [NgtCourtCase::class,    '/ngt-court-cases',   'NGT Court Case'],
        'page'               => [Page::class,            '/',                  'Page'],
        'page_file'          => [PageFile::class,        '/',                  'Page File'],
        'photo_gallery'      => [PhotoGallery::class,    '/photo-gallery',     'Photo Gallery'],
        'portal'             => [Portal::class,          '/portals',           'Portal'],
        'publication'        => [Publication::class,     '/publications',      'Publication'],
        'site_setting'       => [SiteSetting::class,     '/',                  'Site Setting'],
        'studies_report'     => [StudiesReport::class,   '/reports',           'Studies Report'],
        'technical_report'   => [TechnicalReport::class, '/technical-report',  'Technical Report'],
        'tender'             => [Tender::class,          '/tenders',           'Tender'],
        'video_gallery'      => [VideoGallery::class,    '/video-gallery',     'Video Gallery'],
    ];

    /**
     * Special column configurations for tables that deviate from the standard pattern.
     * Keys: type_key
     * Values: ['title_col', 'title_hi_col', 'desc_col', 'desc_hi_col', 'has_is_published', 'has_is_approved']
     */
    protected $tableConfig = [
        // directories uses name/name_hi and assigned_work instead of title/description
        'directory'   => [
            'title_col'    => 'name',
            'title_hi_col' => 'name_hi',
            'desc_col'     => 'assigned_work',
            'desc_hi_col'  => 'assigned_work_hi',
            'has_is_published' => true,
            'has_is_approved'  => true,
        ],
        // faqs uses question/answer instead of title/description
        'faq'         => [
            'title_col'    => 'question',
            'title_hi_col' => 'question_hi',
            'desc_col'     => 'answer',
            'desc_hi_col'  => 'answer_hi',
            'has_is_published' => true,
            'has_is_approved'  => true,
        ],
        // page_files uses title/title_hi and has no description, is_published or is_approved
        'page_file' => [
            'title_col'    => 'title',
            'title_hi_col' => 'title_hi',
            'desc_col'     => null,
            'desc_hi_col'  => null,
            'has_is_published' => false,
            'has_is_approved'  => false,
        ],
        // site_settings uses site_name/site_name_hi and has no standard is_published/is_approved
        'site_setting' => [
            'title_col'    => 'site_name',
            'title_hi_col' => 'site_name_hi',
            'desc_col'     => 'site_address',
            'desc_hi_col'  => 'site_address_hi',
            'has_is_published' => false,
            'has_is_approved'  => false,
        ],
    ];

    /**
     * Cached Schema::hasColumn check
     */
    protected function cachedHasColumn(string $table, string $col): bool
    {
        $key = "{$table}.{$col}";
        if (!isset(static::$columnCache[$key])) {
            static::$columnCache[$key] = Schema::hasColumn($table, $col);
        }
        return static::$columnCache[$key];
    }

    /**
     * Global search across all configured content types.
     */
    public function globalSearch(Request $request)
    {
        try {
            $query     = $request->input('query', '');
            $types     = $request->input('types', array_keys($this->searchableModels));
            $page      = (int) $request->input('page', 1);
            $perPage   = (int) $request->input('per_page', 10);
            $sortBy    = $request->input('sort', 'relevance');
            $sortOrder = $request->input('order', 'desc');

            if (empty(trim($query))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required',
                    'data'    => [],
                    'meta'    => [
                        'total_records'  => 0,
                        'filtered_count' => 0,
                        'current_page'   => 1,
                        'per_page'       => $perPage,
                        'last_page'      => 0,
                        'type_counts'    => [],
                    ],
                ], 400);
            }

            $results    = [];
            $typeCounts = [];

            foreach ($types as $type) {
                if (!isset($this->searchableModels[$type])) {
                    continue;
                }

                [$modelClass, $fallbackUrl, $label] = $this->searchableModels[$type];

                $modelResults = $this->searchModel($modelClass, $query, $type, $fallbackUrl, $label);

                $typeCounts[$type] = $modelResults->count();
                $results           = array_merge($results, $modelResults->toArray());
            }

            // Sort results
            if ($sortBy === 'relevance') {
                usort($results, fn ($a, $b) => $sortOrder === 'desc'
                    ? $b['relevance_score'] <=> $a['relevance_score']
                    : $a['relevance_score'] <=> $b['relevance_score']
                );
            } elseif ($sortBy === 'date') {
                usort($results, fn ($a, $b) => $sortOrder === 'desc'
                    ? strtotime($b['publish_date']) <=> strtotime($a['publish_date'])
                    : strtotime($a['publish_date']) <=> strtotime($b['publish_date'])
                );
            }

            $total            = count($results);
            $offset           = ($page - 1) * $perPage;
            $paginatedResults = array_slice($results, $offset, $perPage);

            return response()->json([
                'success' => true,
                'data'    => $paginatedResults,
                'meta'    => [
                    'total_records'  => $total,
                    'filtered_count' => $total,
                    'current_page'   => $page,
                    'per_page'       => $perPage,
                    'last_page'      => $perPage > 0 ? (int) ceil($total / $perPage) : 0,
                    'type_counts'    => $typeCounts,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Global search error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
                'error'   => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Search within a specific content type.
     */
    public function searchByType(Request $request, string $type)
    {
        try {
            if (!isset($this->searchableModels[$type])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid content type',
                ], 400);
            }

            $query   = $request->input('query', '');
            $page    = (int) $request->input('page', 1);
            $perPage = (int) $request->input('per_page', 10);

            if (empty(trim($query))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required',
                ], 400);
            }

            [$modelClass, $fallbackUrl, $label] = $this->searchableModels[$type];
            $results = $this->searchModel($modelClass, $query, $type, $fallbackUrl, $label);

            $total            = $results->count();
            $offset           = ($page - 1) * $perPage;
            $paginatedResults = $results->slice($offset, $perPage)->values();

            return response()->json([
                'success' => true,
                'data'    => $paginatedResults,
                'meta'    => [
                    'total_records'  => $total,
                    'filtered_count' => $total,
                    'current_page'   => $page,
                    'per_page'       => $perPage,
                    'last_page'      => $perPage > 0 ? (int) ceil($total / $perPage) : 0,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Type search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Get search suggestions for autocomplete.
     */
    public function getSearchSuggestions(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $query = str_replace(['%', '_'], ['\\%', '\\_'], $query);
            $limit = (int) $request->input('limit', 5);

            if (strlen($query) < 2) {
                return response()->json(['success' => true, 'suggestions' => []]);
            }

            $searchTerm  = '%' . strtolower($query) . '%';
            $suggestions = collect();

            // Tender titles
            $suggestions = $suggestions->merge(
                Tender::where('is_published', 1)
                    ->where(fn ($q) => $q
                        ->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(title_hi) LIKE ?', [$searchTerm])
                    )
                    ->limit($limit)
                    ->pluck('title')
            );

            // Page titles
            $suggestions = $suggestions->merge(
                Page::where('is_published', 1)
                    ->where('is_approved', 1)
                    ->where(fn ($q) => $q
                        ->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(title_hi) LIKE ?', [$searchTerm])
                    )
                    ->limit($limit)
                    ->pluck('title')
            );

            // Announcement titles
            $suggestions = $suggestions->merge(
                Announcement::where('is_published', 1)
                    ->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                    ->limit($limit)
                    ->pluck('title')
            );

            // Direction titles
            $suggestions = $suggestions->merge(
                Direction::where('is_published', 1)
                    ->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                    ->limit($limit)
                    ->pluck('title')
            );

            // FAQ questions
            $suggestions = $suggestions->merge(
                Faq::where('is_published', 1)
                    ->whereRaw('LOWER(question) LIKE ?', [$searchTerm])
                    ->limit($limit)
                    ->pluck('question')
            );

            $suggestions = $suggestions->unique()->take($limit)->values()->toArray();

            return response()->json(['success' => true, 'suggestions' => $suggestions]);
        } catch (\Exception $e) {
            Log::error('Search suggestions error: ' . $e->getMessage());
            return response()->json(['success' => false, 'suggestions' => []], 500);
        }
    }

    /**
     * Search a model for matching records, handling table-specific column differences.
     */
    protected function searchModel(string $modelClass, string $query, string $type, string $fallbackUrl, string $label): \Illuminate\Support\Collection
    {
        $tableName    = (new $modelClass)->getTable();
        $escapedQuery = str_replace(['%', '_'], ['\\%', '\\_'], $query);
        $searchTerm   = '%' . strtolower($escapedQuery) . '%';

        // Resolve column config for this type
        $config      = $this->tableConfig[$type] ?? null;
        $titleCol    = $config['title_col']    ?? 'title';
        $titleHiCol  = $config['title_hi_col'] ?? 'title_hi';
        $descCol     = $config['desc_col']     ?? 'description';
        $descHiCol   = $config['desc_hi_col']  ?? 'description_hi';
        $hasPublished = $config['has_is_published'] ?? true;
        $hasApproved  = $config['has_is_approved']  ?? true;

        // Verify columns actually exist
        $hasTitleCol   = $this->cachedHasColumn($tableName, $titleCol);
        $hasTitleHiCol = $this->cachedHasColumn($tableName, $titleHiCol);
        $hasDescCol    = $this->cachedHasColumn($tableName, $descCol);
        $hasDescHiCol  = $this->cachedHasColumn($tableName, $descHiCol);

        // Additional searchable columns
        $hasBriefSummary   = $this->cachedHasColumn($tableName, 'brief_summary');
        $hasBriefSummaryHi = $this->cachedHasColumn($tableName, 'brief_summary_hi');
        $hasFileEn         = $this->cachedHasColumn($tableName, 'file_path_en');
        $hasFileHi         = $this->cachedHasColumn($tableName, 'file_path_hi');
        $hasPubDate        = $this->cachedHasColumn($tableName, 'publish_date');
        $hasMenuId         = $this->cachedHasColumn($tableName, 'menu_id');
        $hasContent        = ($type === 'page') && $this->cachedHasColumn($tableName, 'content');
        $hasContentHi      = ($type === 'page') && $this->cachedHasColumn($tableName, 'content_hi');

        // Type-specific reference columns
        $refCol = match ($type) {
            'tender'       => $this->cachedHasColumn($tableName, 'tender_reference_no') ? 'tender_reference_no' : null,
            'direction'    => $this->cachedHasColumn($tableName, 'direction_number')    ? 'direction_number'    : null,
            default        => null,
        };

        // Build SELECT columns — only fetch what we need
        $selectCols = ['id', 'created_at'];

        if ($hasTitleCol)   $selectCols[] = $titleCol;
        if ($hasTitleHiCol) $selectCols[] = $titleHiCol;
        if ($hasDescCol)    $selectCols[] = $descCol;
        if ($hasDescHiCol)  $selectCols[] = $descHiCol;
        if ($hasBriefSummary)   $selectCols[] = 'brief_summary';
        if ($hasBriefSummaryHi) $selectCols[] = 'brief_summary_hi';
        if ($hasFileEn)     $selectCols[] = 'file_path_en';
        if ($hasFileHi)     $selectCols[] = 'file_path_hi';
        if ($hasPubDate)    $selectCols[] = 'publish_date';
        if ($hasMenuId)     $selectCols[] = 'menu_id';
        if ($hasContent)    $selectCols[] = 'content';
        if ($hasContentHi)  $selectCols[] = 'content_hi';
        if ($refCol)        $selectCols[] = $refCol;

        // PageFile needs file_name columns for its appended file_url_en/file_url_hi accessors
        if ($type === 'page_file') {
            if ($this->cachedHasColumn($tableName, 'file_name'))    $selectCols[] = 'file_name';
            if ($this->cachedHasColumn($tableName, 'file_name_hi')) $selectCols[] = 'file_name_hi';
        }

        // Directory needs specific columns for rich officer details
        if ($type === 'directory') {
            $selectCols = array_merge($selectCols, [
                'name', 'name_hi', 'designation', 'designation_hi',
                'division_id', 'office_ph_no', 'mobile_no', 'email',
                'ext_number', 'assigned_work', 'assigned_work_hi', 'image', 'updated_at'
            ]);
        }

        // Build base query
        $dbQuery = $modelClass::select(array_unique($selectCols));

        // Apply published/approved filters
        if ($hasPublished && $this->cachedHasColumn($tableName, 'is_published')) {
            $dbQuery->where('is_published', 1);
        }
        if ($type === 'page' && $hasApproved && $this->cachedHasColumn($tableName, 'is_approved')) {
            $dbQuery->where('is_approved', 1);
        }

        // Eager-load relations
        if ($type === 'page') {
            $dbQuery->with(['menu:id,url']);
        } elseif ($type === 'directory') {
            $dbQuery->with(['division:id,title,title_hi']);
        }

        // Build WHERE search conditions
        $dbQuery->where(function ($q) use (
            $searchTerm, $tableName, $type,
            $titleCol, $hasTitleCol, $titleHiCol, $hasTitleHiCol,
            $descCol, $hasDescCol, $descHiCol, $hasDescHiCol,
            $hasBriefSummary, $hasBriefSummaryHi,
            $hasContent, $hasContentHi, $refCol
        ) {
            $first = true;

            if ($hasTitleCol) {
                $q->whereRaw("LOWER({$titleCol}) LIKE ?", [$searchTerm]);
                $first = false;
            }
            if ($hasTitleHiCol) {
                $q->orWhereRaw("LOWER({$titleHiCol}) LIKE ?", [$searchTerm]);
            }
            if ($hasDescCol)    $q->orWhereRaw("LOWER({$descCol}) LIKE ?", [$searchTerm]);
            if ($hasDescHiCol)  $q->orWhereRaw("LOWER({$descHiCol}) LIKE ?", [$searchTerm]);
            if ($hasBriefSummary)   $q->orWhereRaw('LOWER(brief_summary) LIKE ?', [$searchTerm]);
            if ($hasBriefSummaryHi) $q->orWhereRaw('LOWER(brief_summary_hi) LIKE ?', [$searchTerm]);
            if ($hasContent)    $q->orWhereRaw('LOWER(content) LIKE ?', [$searchTerm]);
            if ($hasContentHi)  $q->orWhereRaw('LOWER(content_hi) LIKE ?', [$searchTerm]);
            if ($refCol)        $q->orWhereRaw("LOWER({$refCol}) LIKE ?", [$searchTerm]);

            if ($type === 'directory') {
                $q->orWhereRaw('LOWER(designation) LIKE ?', [$searchTerm]);
                $q->orWhereRaw('LOWER(designation_hi) LIKE ?', [$searchTerm]);
                $q->orWhereRaw('LOWER(email) LIKE ?', [$searchTerm]);
                $q->orWhereRaw('LOWER(mobile_no) LIKE ?', [$searchTerm]);
                $q->orWhereRaw('LOWER(office_ph_no) LIKE ?', [$searchTerm]);
                $q->orWhereRaw('LOWER(ext_number) LIKE ?', [$searchTerm]);
                $q->orWhereHas('division', function ($dq) use ($searchTerm) {
                    $dq->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                       ->orWhereRaw('LOWER(title_hi) LIKE ?', [$searchTerm]);
                });
            }
        });

        return $dbQuery->get()->map(function ($item) use (
            $type, $query, $fallbackUrl, $label,
            $titleCol, $titleHiCol, $descCol, $descHiCol,
            $hasBriefSummary, $hasContent
        ) {
            $url         = $fallbackUrl;
            $title       = $item->{$titleCol} ?? '';
            $titleHi     = $item->{$titleHiCol} ?? '';
            $description = '';
            $descriptionHi = '';

            // Description / summary logic
            if ($hasContent && $type === 'page') {
                // Page: get URL from menu relationship, use content as description
                $url = ($item->menu && $item->menu->url) ? $item->menu->url : '/';
                $description   = $item->content    ? Str::limit(strip_tags($item->content),    200) : '';
                $descriptionHi = $item->content_hi ? Str::limit(strip_tags($item->content_hi), 200) : '';
            } elseif ($hasBriefSummary && !empty($item->brief_summary)) {
                $description   = Str::limit(strip_tags($item->brief_summary),    200);
                $descriptionHi = Str::limit(strip_tags($item->brief_summary_hi ?? ''), 200);
            } elseif (isset($item->{$descCol})) {
                $description   = Str::limit(strip_tags($item->{$descCol} ?? ''),    200);
                $descriptionHi = Str::limit(strip_tags($item->{$descHiCol} ?? ''), 200);
            }

            $baseResult = [
                'type'            => $type,
                'type_label'      => $label,
                'id'              => $item->id,
                'title'           => $title,
                'title_hi'        => $titleHi,
                'description'     => $description,
                'description_hi'  => $descriptionHi,
                'file_path_en'    => $item->file_url_en ?? $item->file_path_en ?? null,
                'file_path_hi'    => $item->file_url_hi ?? $item->file_path_hi ?? null,
                'publish_date'    => $item->publish_date ?? $item->created_at,
                'url'             => $url,
                'relevance_score' => $this->calculateRelevance($item, $query, $titleCol, $descCol),
            ];

            if ($type === 'directory') {
                $baseResult['name'] = $item->name;
                $baseResult['name_hi'] = $item->name_hi ?? $item->name;
                $baseResult['designation'] = $item->designation;
                $baseResult['designation_hi'] = $item->designation_hi ?? $item->designation;
                $baseResult['division'] = $item->division ? $item->division->title : null;
                $baseResult['division_hi'] = $item->division ? ($item->division->title_hi ?? $item->division->title) : null;
                $baseResult['email'] = $item->email;
                $baseResult['mobile_no'] = $item->mobile_no;
                $baseResult['office_ph_no'] = $item->office_ph_no;
                $baseResult['ext_number'] = $item->ext_number;
                $baseResult['image_url'] = $item->image_url;
                $baseResult['assigned_work'] = $item->assigned_work;
                $baseResult['assigned_work_hi'] = $item->assigned_work_hi ?? $item->assigned_work;
            }

            return $baseResult;
        });
    }

    /**
     * Calculate relevance score for a search result.
     */
    protected function calculateRelevance($item, string $query, string $titleCol = 'title', string $descCol = 'description'): float
    {
        $score      = 0.0;
        $queryLower = strtolower($query);

        $titleValue = $item->{$titleCol} ?? '';
        $titleHi    = $item->title_hi ?? $item->question_hi ?? $item->name_hi ?? $item->site_name_hi ?? '';

        // Exact title match (highest weight)
        if (strtolower($titleValue) === $queryLower) {
            $score += 10.0;
        }

        // Title contains query (high weight)
        if (stripos($titleValue, $query) !== false) {
            $score += 5.0;
            if (stripos($titleValue, $query) === 0) {
                $score += 2.0; // Bonus for match at start
            }
        }

        // Hindi title match
        if ($titleHi && stripos($titleHi, $query) !== false) {
            $score += 3.0;
        }

        // Description match (medium weight)
        $descValue = $item->{$descCol} ?? '';
        if ($descValue && stripos($descValue, $query) !== false) {
            $score += 2.0;
        }

        // Brief summary match
        if (isset($item->brief_summary) && stripos($item->brief_summary, $query) !== false) {
            $score += 1.5;
        }

        // Content match (pages)
        if (isset($item->content) && stripos($item->content, $query) !== false) {
            $score += 2.0;
        }

        // Reference number match
        foreach (['tender_reference_no', 'direction_number'] as $field) {
            if (isset($item->{$field}) && stripos($item->{$field}, $query) !== false) {
                $score += 3.0;
            }
        }

        // Recency bonus
        $publishDate = $item->publish_date ?? $item->created_at;
        if ($publishDate) {
            $daysSince = now()->diffInDays($publishDate);
            if ($daysSince < 30) {
                $score += 1.0;
            } elseif ($daysSince < 90) {
                $score += 0.5;
            }
        }

        return $score;
    }
}
