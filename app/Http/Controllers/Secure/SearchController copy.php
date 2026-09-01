<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Tender;
use App\Models\Publication;
use App\Models\TechnicalReport;
use App\Models\Direction;
use App\Models\Announcement;
use App\Models\LatestCpcb;
use App\Models\Circular;
use App\Models\StudiesReport;

class SearchController extends Controller
{
    /**
     * Mapping of searchable content types to their model classes
     */
    protected $searchableModels = [
        'tender' => Tender::class,
        'publication' => Publication::class,
        'technical_report' => TechnicalReport::class,
        'direction' => Direction::class,
        'announcement' => Announcement::class,
        'latest_cpcb' => LatestCpcb::class,
        'circular' => Circular::class,
        'studies_report' => StudiesReport::class,
    ];

    /**
     * URL mapping for each content type
     */
    protected $typeUrls = [
        'tender' => '/tenders',
        'publication' => '/publications',
        'technical_report' => '/technical-report',
        'direction' => '/directions',
        'announcement' => '/announcements',
        'latest_cpcb' => '/latest-cpcb',
        'circular' => '/circulars',
        'studies_report' => '/reports',
    ];

    /**
     * Global search across multiple content types
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function globalSearch(Request $request)
    {
        try {
            $query = $request->input('query', '');
            $types = $request->input('types', array_keys($this->searchableModels));
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort', 'relevance');
            $sortOrder = $request->input('order', 'desc');

            // Validate query
            if (empty(trim($query))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required',
                    'data' => [],
                    'meta' => [
                        'total_records' => 0,
                        'filtered_count' => 0,
                        'current_page' => 1,
                        'per_page' => $perPage,
                        'last_page' => 0,
                        'type_counts' => [],
                    ]
                ], 400);
            }

            $results = [];
            $typeCounts = [];

            // Search across each selected content type
            foreach ($types as $type) {
                if (!isset($this->searchableModels[$type])) {
                    continue;
                }

                $modelClass = $this->searchableModels[$type];
                $modelResults = $this->searchModel($modelClass, $query, $type);

                $typeCounts[$type] = $modelResults->count();
                $results = array_merge($results, $modelResults->toArray());
            }

            // Sort results
            if ($sortBy === 'relevance') {
                usort($results, function ($a, $b) use ($sortOrder) {
                    return $sortOrder === 'desc'
                        ? $b['relevance_score'] <=> $a['relevance_score']
                        : $a['relevance_score'] <=> $b['relevance_score'];
                });
            } elseif ($sortBy === 'date') {
                usort($results, function ($a, $b) use ($sortOrder) {
                    return $sortOrder === 'desc'
                        ? strtotime($b['publish_date']) <=> strtotime($a['publish_date'])
                        : strtotime($a['publish_date']) <=> strtotime($b['publish_date']);
                });
            }

            // Paginate results
            $total = count($results);
            $offset = ($page - 1) * $perPage;
            $paginatedResults = array_slice($results, $offset, $perPage);

            return response()->json([
                'success' => true,
                'data' => $paginatedResults,
                'meta' => [
                    'total_records' => $total,
                    'filtered_count' => $total,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'last_page' => ceil($total / $perPage),
                    'type_counts' => $typeCounts,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Global search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Search within a specific content type
     * 
     * @param Request $request
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
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

            $query = $request->input('query', '');
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            if (empty(trim($query))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required',
                ], 400);
            }

            $modelClass = $this->searchableModels[$type];
            $results = $this->searchModel($modelClass, $query, $type);

            // Paginate
            $total = $results->count();
            $offset = ($page - 1) * $perPage;
            $paginatedResults = $results->slice($offset, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => $paginatedResults,
                'meta' => [
                    'total_records' => $total,
                    'filtered_count' => $total,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'last_page' => ceil($total / $perPage),
                ]
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
     * Get search suggestions for autocomplete
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchSuggestions(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $query = str_replace(['%', '_'], ['\\%', '\\_'], $query);
            $limit = $request->input('limit', 5);

            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'suggestions' => []
                ]);
            }

            $suggestions = [];

            // Get suggestions from tender titles
            $tenderSuggestions = Tender::where('is_published', 1)
                ->where(function ($q) use ($query) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$query}%"])
                        ->orWhereRaw('title_hi LIKE ?', ["%{$query}%"]);
                })
                ->limit($limit)
                ->pluck('title')
                ->toArray();

            $suggestions = array_merge($suggestions, $tenderSuggestions);

            // Remove duplicates and limit
            $suggestions = array_unique($suggestions);
            $suggestions = array_slice($suggestions, 0, $limit);

            return response()->json([
                'success' => true,
                'suggestions' => array_values($suggestions)
            ]);
        } catch (\Exception $e) {
            Log::error('Search suggestions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'suggestions' => []
            ], 500);
        }
    }

    /**
     * Search a specific model for matching records
     * 
     * @param string $modelClass
     * @param string $query
     * @param string $type
     * @return \Illuminate\Support\Collection
     */
    protected function searchModel($modelClass, $query, $type)
    {
        $tableName = (new $modelClass)->getTable();

        $results = $modelClass::where('is_published', 1)
            ->where(function ($q) use ($query, $tableName, $type) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$query}%"])
                    ->orWhereRaw('title_hi LIKE ?', ["%{$query}%"]);

                // Add description search if column exists
                if (Schema::hasColumn($tableName, 'description')) {
                    $q->orWhereRaw('LOWER(description) LIKE ?', ["%{$query}%"])
                        ->orWhereRaw('description_hi LIKE ?', ["%{$query}%"]);
                }

                // Add specific fields for certain types
                if ($type === 'tender' && Schema::hasColumn($tableName, 'tender_reference_no')) {
                    $q->orWhereRaw('LOWER(tender_reference_no) LIKE ?', ["%{$query}%"]);
                }

                if ($type === 'direction' && Schema::hasColumn($tableName, 'direction_number')) {
                    $q->orWhereRaw('LOWER(direction_number) LIKE ?', ["%{$query}%"]);
                }

                if ($type === 'circular' && Schema::hasColumn($tableName, 'circular_number')) {
                    $q->orWhereRaw('LOWER(circular_number) LIKE ?', ["%{$query}%"]);
                }
            })
            ->get()
            ->map(function ($item) use ($type, $query) {
                return [
                    'type' => $type,
                    'id' => $item->id,
                    'title' => $item->title,
                    'title_hi' => $item->title_hi ?? '',
                    'description' => $item->description ?? '',
                    'description_hi' => $item->description_hi ?? '',
                    'file_path_en' => $item->file_path_en ?? null,
                    'file_path_hi' => $item->file_path_hi ?? null,
                    'publish_date' => $item->publish_date ?? $item->created_at,
                    'url' => $this->typeUrls[$type] ?? '/',
                    'relevance_score' => $this->calculateRelevance($item, $query),
                ];
            });

        return $results;
    }

    /**
     * Calculate relevance score for a search result
     * 
     * @param mixed $item
     * @param string $query
     * @return float
     */
    protected function calculateRelevance($item, $query)
    {
        $score = 0.0;
        $queryLower = strtolower($query);

        // Exact title match (highest weight)
        if (strtolower($item->title) === $queryLower) {
            $score += 10.0;
        }

        // Title contains query (high weight)
        if (stripos($item->title, $query) !== false) {
            $score += 5.0;
            // Bonus for match at start of title
            if (stripos($item->title, $query) === 0) {
                $score += 2.0;
            }
        }

        // Hindi title match
        if (isset($item->title_hi) && stripos($item->title_hi, $query) !== false) {
            $score += 5.0;
        }

        // Description match (medium weight)
        if (isset($item->description) && stripos($item->description, $query) !== false) {
            $score += 2.0;
        }

        // Hindi description match
        if (isset($item->description_hi) && stripos($item->description_hi, $query) !== false) {
            $score += 2.0;
        }

        // Reference number match (for tenders, directions, circulars)
        $refFields = ['tender_reference_no', 'direction_number', 'circular_number'];
        foreach ($refFields as $field) {
            if (isset($item->$field) && stripos($item->$field, $query) !== false) {
                $score += 3.0;
            }
        }

        // Recency bonus (newer content gets slight boost)
        $publishDate = $item->publish_date ?? $item->created_at;
        $daysSincePublish = now()->diffInDays($publishDate);
        if ($daysSincePublish < 30) {
            $score += 1.0;
        } elseif ($daysSincePublish < 90) {
            $score += 0.5;
        }

        return $score;
    }
}
