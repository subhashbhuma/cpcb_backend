<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageFile;
use App\Models\Media;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'tenders' => [
                'total' => \App\Models\Tender::count(),
                'published' => \App\Models\Tender::where('is_published', 1)->count(),
                'pending' => \App\Models\Tender::where('is_approved', 0)->count(),
            ],
            'circulars' => [
                'total' => \App\Models\Circular::count(),
                'published' => \App\Models\Circular::where('is_published', 1)->count(),
                'pending' => \App\Models\Circular::where('is_approved', 0)->count(),
            ],
            'directions' => [
                'total' => \App\Models\Direction::count(),
                'published' => \App\Models\Direction::where('is_published', 1)->count(),
                'pending' => \App\Models\Direction::where('is_approved', 0)->count(),
            ],
            'letters_issued' => [
                'total' => \App\Models\LettersIssued::count(),
                'published' => \App\Models\LettersIssued::where('is_published', 1)->count(),
                'pending' => \App\Models\LettersIssued::where('is_approved', 0)->count(),
            ],
            'publications' => [
                'total' => \App\Models\Publication::count(),
                'published' => \App\Models\Publication::where('is_published', 1)->count(),
                'pending' => \App\Models\Publication::where('is_approved', 0)->count(),
            ],
            'annual_reports' => [
                'total' => \App\Models\AnnualReport::count(),
                'published' => \App\Models\AnnualReport::where('is_published', 1)->count(),
                'pending' => \App\Models\AnnualReport::where('is_approved', 0)->count(),
            ],
            'technical_reports' => [
                'total' => \App\Models\TechnicalReport::count(),
                'published' => \App\Models\TechnicalReport::where('is_published', 1)->count(),
                'pending' => \App\Models\TechnicalReport::where('is_approved', 0)->count(),
            ],
            'announcements' => [
                'total' => \App\Models\Announcement::count(),
                'published' => \App\Models\Announcement::where('is_published', 1)->count(),
                'pending' => \App\Models\Announcement::where('is_approved', 0)->count(),
            ],
            'events' => [
                'total' => \App\Models\Event::count(),
                'published' => \App\Models\Event::where('is_published', 1)->count(),
            ],
            'jobs' => [
                'total' => \App\Models\Job::count(),
                'published' => \App\Models\Job::where('is_published', 1)->count(),
                'pending' => \App\Models\Job::where('is_approved', 0)->count(),
            ],
            'users' => [
                'total' => \App\Models\User::count(),
            ],
            'visitors' => [
                'total' => \App\Models\Visitor::count(),
                'today' => \App\Models\Visitor::whereDate('visit_date', Carbon::today())->count(),
            ],
            'menus' => \App\Models\Menu::count(),
            'pages' => \App\Models\Page::count(),
            'media_files' => \App\Models\Media::count(),
            'feedback' => \App\Models\Feedback::count(),
            'complaints' => \App\Models\Complaint::count(),
            'employee_circulars' => \App\Models\Circular::where('category', 1)->where('is_approved', 1)->where('published_date', '>=', Carbon::now()->subMonth())->count(),
            'employee_memorandums' => \App\Models\Circular::where('category', 2)->where('is_approved', 1)->where('published_date', '>=', Carbon::now()->subMonth())->count(),
            'employee_office_orders' => \App\Models\Circular::where('category', 3)->where('is_approved', 1)->where('published_date', '>=', Carbon::now()->subMonth())->count(),
        ];

        $recentActivities = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get();
            
        $logExports = \App\Models\LogExport::where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();

        return view('secure.dashboard.dashboard', compact('stats', 'recentActivities', 'logExports'));
    }

    /**
     * Get today's entry count and documents from page_files and media tables
     */
    public function getTodayEntries()
    {
        try {
            $today = Carbon::today();

            // Get today's page_files entries
            $pageFiles = PageFile::whereDate('created_at', $today)
                ->with('page:id,title,title_hi')
                ->get();

            // Get today's media entries
            $media = Media::whereDate('created_at', $today)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'page_files' => [
                        'count' => $pageFiles->count(),
                        'documents' => $pageFiles->map(function ($file) {
                            return [
                                'id' => $file->id,
                                'page_id' => $file->page_id,
                                'page_title' => $file->page->title ?? null,
                                'page_title_hi' => $file->page->title_hi ?? null,
                                'title' => $file->title,
                                'title_hi' => $file->title_hi,
                                'description' => $file->description,
                                'description_hi' => $file->description_hi,
                                'file_name' => $file->file_name,
                                'file_name_hi' => $file->file_name_hi,
                                'file_path' => $file->file_path,
                                'file_path_hi' => $file->file_path_hi,
                                'file_url_en' => $file->file_url_en,
                                'file_url_hi' => $file->file_url_hi,
                                'created_at' => $file->created_at->format('Y-m-d H:i:s'),
                                'created_by' => $file->created_by,
                            ];
                        })
                    ],
                    'media' => [
                        'count' => $media->count(),
                        'documents' => $media->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'file_name' => $item->file_name,
                                'original_name' => $item->original_name,
                                'mime_type' => $item->mime_type,
                                'size' => $item->size,
                                'alt_text' => $item->alt_text,
                                'alt_text_hi' => $item->alt_text_hi,
                                'media_public_url' => $item->media_public_url,
                                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                                'created_by' => $item->created_by,
                            ];
                        })
                    ],
                    'total_count' => $pageFiles->count() + $media->count()
                ],
                'message' => 'Today\'s entries retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving today\'s entries: ' . $e->getMessage()
            ], 500);
        }
    }
}
