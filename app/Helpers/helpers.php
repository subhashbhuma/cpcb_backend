<?php

use App\Models\Circular;
use App\Models\Event;
use App\Models\Tender;
use Illuminate\Support\Facades\Http;

function customURIEncode($data)
{
    return urldecode(base64_encode($data));
}

function customURIDecode($data)
{
    return base64_decode(urldecode($data));
}


function generate_file_view_path_for_backend($path)
{
    if(!$path) return ;
   $cleanPath = str_replace(url('/storage/'), '', $path);
    return route('backend.file.view', [
        'code' => customURIEncode($cleanPath),
    ]);
}


if (!function_exists('getLocalizedDataFromObj')) {

    function getLocalizedDataFromObj($model, $fieldName)
    {
        if (!$model || !$fieldName) {
            return '';
        }

        $locale = app()->getLocale();
        $localizedField = $locale === 'hi' ? "{$fieldName}_hi" : $fieldName;

        $value = data_get($model, $localizedField);

        if (empty($value)) {
            $value = data_get($model, $fieldName);
        }

        return $value ?? '';
    }
}


if (!function_exists('getLocalizedDataFromArray')) {
    /**
     * Retrieve the localized title based on the current application locale.
     *
     * @param  object  $menu
     * @param  string  $defaultField
     * @param  string  $localizedField
     * @return string
     */
    function getLocalizedDataFromArray($menu, $fieldName)
    {
        if (!is_array($menu) || !$fieldName) {
            return '';
        }

        $locale = app()->getLocale();
        $localizedKey = $locale === 'hi' ? $fieldName . '_hi' : $fieldName;

        $value = $menu[$localizedKey] ?? null;

        if (empty($value)) {
            $value = $menu[$fieldName] ?? '';
        }

        return $value;
    }
}
if (!function_exists('formatBytes')) {
    function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        $size = $bytes / pow(1024, $i);

        return round($size, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('getWhatsNew')) {
    function getWhatsNew($limit = 5)
    {
        $circulars = Circular::select('*')
            ->with('circularCategory')
            ->where([
                'is_published' => 1,
                'is_approved' => 1,
            ])
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->type = 'circular';
                return $item;
            });

        $tenders = Tender::select('*')
            ->with('division')
            ->where([
                'is_published' => 1,
                'is_approved' => 1,
            ])
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->type = 'tender';
                return $item;
            });

        $events = Event::select('*')
            ->where([
                'is_published' => 1,
                'is_approved' => 1,
            ])
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->type = 'event';
                return $item;
            });

        $whatsNew = $circulars->concat($tenders)->concat($events);
        // Sort by created_at descending (latest to oldest)
        $whatsNew = $whatsNew->sortByDesc('created_at');

        // Apply the limit to the combined and sorted collection
        if ($limit !== null) {
            $whatsNew = $whatsNew->take($limit);
        }

        return $whatsNew;
    }
}

if (!function_exists('getFileMeta')) {
    function getFileMeta($filePath)
    {
        $publicPrefix = asset('storage') . '/';
        $absolutePath = str_replace($publicPrefix, storage_path('app/public/'), $filePath);

        $exists = file_exists($absolutePath);

        $sizeBytes = $exists ? filesize($absolutePath) : 0;
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        //  Format size into human-readable string
        $formattedSize = '0 Bytes';
        if ($sizeBytes > 0) {
            $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            $power = floor(log($sizeBytes, 1024));
            $formattedSize = number_format($sizeBytes / pow(1024, $power), 2) . ' ' . $units[$power];
        }

        return [
            // 'file'=>base64_encode($filePath),
            'size' => $sizeBytes > 0 ? number_format($sizeBytes, 2) : '0.00',
            'formatted_size' => $formattedSize,
            'extension' => strtoupper($extension),
            'exists' => $exists,
        ];
    }
}

// if (!function_exists('translateToHindi')) {
//     function translateToHindi($text)
//     {
//         if (empty($text))
//             return null;

//         try {
//             $response = Http::get('https://translate.googleapis.com/translate_a/single', [
//                 'client' => 'gtx',
//                 'sl' => 'en',
//                 'tl' => 'hi',
//                 'dt' => 't',
//                 'q' => $text,
//             ]);

//             if ($response->successful()) {
//                 $data = $response->json();
//                 if (isset($data[0][0][0])) {
//                     return $data[0][0][0];
//                 }
//             }
//         } catch (\Exception $e) {

//         }

//         return $text;
//     }
// }

// if (!function_exists('translateToEnglish')) {
//     function translateToEnglish($text)
//     {
//         if (empty($text))
//             return null;

//         try {
//             $response = Http::get('https://translate.googleapis.com/translate_a/single', [
//                 'client' => 'gtx',
//                 'sl' => 'hi', // source: Hindi
//                 'tl' => 'en', // target: English
//                 'dt' => 't',
//                 'q' => $text,
//             ]);

//             if ($response->successful()) {
//                 $data = $response->json();
//                 if (isset($data[0][0][0])) {
//                     return $data[0][0][0];
//                 }
//             }
//         } catch (\Exception $e) {
//             // optionally log error
//             // Log::error($e->getMessage());
//         }

//         return $text;
//     }
// }

if (!function_exists('translateToHindi')) {
    function translateToHindi($text)
    {
        if (empty($text))
            return null;

        try {
            $translator = app(\App\Services\TranslatorService::class);
            $translated = $translator->translate($text);

            return !empty($translated) ? $translated : $text;
        } catch (\Exception $e) {
            Log::error('Translation Exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return $text;
        }
    }
}

if (!function_exists('translateToEnglish')) {
    function translateToEnglish($text)
    {
        if (empty($text))
            return null;

        try {
            $translator = app(\App\Services\TranslatorService::class);
            $translated = $translator->translate($text, 'eng');

            return !empty($translated) ? $translated : $text;
        } catch (\Exception $e) {
            Log::error('Translation Exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return $text;
        }
    }
}


function getLastUpdatedOn()
{
    $cacheKey = 'app_last_updated_timestamp';
    $cacheDuration = 300; // 5 minutes

    $cachedResult = Cache::get($cacheKey);
    if ($cachedResult !== null) {
        return $cachedResult;
    }

    $tables = [
        'agra_air_qualities',
        'announcements',
        'annual_reports',
        'circulars',
        'comment_reports',
        'directions',
        'directories',
        'divisions',
        'events',
        'faqs',
        'fortnightly_reports',
        'jobs',
        'job_posts',
        'job_results',
        'latest_cpcbs',
        'letters_issued',
        'ngt_court_cases',
        'pages',
        'photo_galleries',
        'publications',
        'sliders',
        'technical_reports',
        'tenders',
        'video_galleries',
        'studies_reports',
        'site_settings',
        'portals',
    ];

    try {
        // Use a more efficient approach with limit to reduce data processing
        $maxTimestamp = null;

        $queries = [];
        foreach ($tables as $table) {
            $queries[] = "SELECT MAX(COALESCE(updated_at, created_at)) as last_updated FROM {$table}";
        }
        $unionQuery = "SELECT MAX(last_updated) as last_updated FROM (" . implode(" UNION ALL ", $queries) . ") as combined_updates";
        $result = DB::selectOne($unionQuery);
        $maxTimestamp = $result ? $result->last_updated : null;

        if (!$maxTimestamp) {
            $formattedDate = 'Not Available';
        } else {
            if (class_exists('Carbon\Carbon')) {
                $formattedDate = \Carbon\Carbon::parse($maxTimestamp)->format('d-m-Y');
            } else {
                $formattedDate = date('d-m-Y', strtotime($maxTimestamp));
            }
        }

        Cache::put($cacheKey, $formattedDate, $cacheDuration);
        return $formattedDate;
    } catch (\Exception $e) {
        Log::error('Error in getLastUpdatedOnWithIndexes: ' . $e->getMessage());
        return 'Not Available';
    }
}

function statusBadge($isApproved, $isPublished): string
{
    // Approval rejected
    if ((int) $isApproved === 2) {
        return '<span class="badge bg-danger">
                    <i class="pi pi-times-circle"></i> Approval Rejected
                </span>';
    }

    // Pending approval
    if ((int) $isApproved === 0) {
        return '<span class="badge bg-warning">
                    <i class="pi pi-clock"></i> Pending Approval
                </span>';
    }

    // Published
    if ((int) $isPublished === 1) {
        return '<span class="badge bg-success">
                    <i class="pi pi-check-circle"></i> Published
                </span>';
    }

    // Unpublished
    if ((int) $isPublished === 2) {
        return '<span class="badge bg-secondary">
                    <i class="pi pi-eye-slash"></i> Unpublished
                </span>';
    }

    // Approved but not yet published
    return '<span class="badge bg-info">
                <i class="pi pi-send"></i> Approved — Pending Publication
            </span>';
}
