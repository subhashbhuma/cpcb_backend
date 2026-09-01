<?php

namespace App\Http\Controllers\Secure;
use App\DTO\AnnouncementDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    protected $announcementService;

    public function __construct()
    {
        $this->announcementService = new AnnouncementService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Announcements';
        return view('secure.announcements.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = Announcement::query();

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $announcements = $query->orderBy('id', 'DESC')->get();

            return DataTables::of($announcements)
                ->addColumn('type', function ($announcement) {
                    return ucfirst($announcement->file_or_link ?? 'N/A');
                })
                ->addColumn('preview', function ($announcement) {
                    if ($announcement->file_or_link === 'file') {
                        $files = '';
                        if ($announcement->file_name) {
                            $files .= "<a href='" . generate_file_view_path_for_backend($announcement->file_url) . "' target='_BLANK'>View File</a>";
                        }

                        if ($announcement->file_name_hi) {
                            $files .= "<br /><br /> <a href='" . generate_file_view_path_for_backend($announcement->file_url_hi) . "' target='_BLANK'>View File</a>";
                        }

                        return $files;
                    }

                    if ($announcement->file_or_link === 'link' && $announcement->page_link) {
                        return "<a href='" . $announcement->page_link . "' target='_blank'>View Link</a>";
                    }

                    return '—';
                })
                ->addColumn('action', function ($announcement) {
                    $button = '';
                    if (auth()->user()->can('view announcement')) {
                        $button .= '<a href="' . route('announcements.show', $announcement->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit announcement')) {
                        $button .= '<a href="' . route('announcements.edit', $announcement->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete announcement')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-announcement" data-id="' . $announcement->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'preview'])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Announcements';
        return view('secure.announcements.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        try {

            $announcementDto = new AnnouncementDto(
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->input('file_or_link'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                strip_tags($request->input('page_link')) ?? null,
                $request->input('status'),
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id,
                $request->input('published_date')
            );


            $announcement = $this->announcementService->create($announcementDto);

            if (!$announcement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving announcement.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Announcement created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Announcement addition failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Announcements';
        $announcement = $this->announcementService->findById($id);
        return view('secure.announcements.show', compact('announcement', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Announcements';
        $announcement = $this->announcementService->findById($id);
        return view('secure.announcements.edit', compact('announcement', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        try {
            $announcementDto = new AnnouncementDto(
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->input('file_or_link'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                strip_tags($request->input('page_link')) ?? null,
                $request->input('status'),
                0,
                0,
                null,
                null,
                $announcement->created_by,
                auth()->user()->id,
                $request->input('published_date')
            );
            $announcement = $this->announcementService->update($announcementDto, $announcement->id);

            if (!$announcement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating announcement.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Announcement updation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $announcement = $this->announcementService->delete($id);
            if (!$announcement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting announcement.',
                ], 500);
            }

            return response()->json(['message' => 'Announcement moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Announcement deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Announcement $announcement)
    {
        try {
            $announcementDto = new AnnouncementDto(
                $announcement->title,
                $announcement->title_hi,
                $announcement->file_or_link,
                $announcement->file_name,
                $announcement->file_name_hi,
                $announcement->page_link,
                $announcement->status,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                null,
                $announcement->created_by,
                auth()->user()->id,
                $announcement->published_date
            );

            $updated = $this->announcementService->approve($announcementDto, $announcement->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving announcement.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Announcement approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Announcement $announcement)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $announcement->is_approved == 1 || $isPublished == 1 ? 1 : $announcement->is_approved;
            $remarks = $announcement->is_approved == 1
                ? $announcement->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $announcement->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $announcementDto = new AnnouncementDto(
                $announcement->title,
                $announcement->title_hi,
                $announcement->file_or_link,
                $announcement->file_name,
                $announcement->file_name_hi,
                $announcement->page_link,
                $announcement->status,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $announcement->created_by,
                auth()->user()->id,
                $announcement->published_date
            );

            $updated = $this->announcementService->publish($announcementDto, $announcement->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing announcement.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Announcement publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            $baseQuery = \App\Models\Announcement::where('is_published', 1);

            $cutoffDate = \Carbon\Carbon::now()->subDays(30)->startOfDay();
            $query = (clone $baseQuery);

            if ($type === 'latest') {
                $query->where('published_date', '>=', $cutoffDate);
            } elseif ($type === 'archive') {
                $query->where('published_date', '<', $cutoffDate);
            }

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
                        ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'published_date');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            $perPage = (int) $request->get('per_page', 10);
            $results = $query->paginate($perPage);

            $totalCount = $results->total();
            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalCount,
                    'filtered_count' => $totalCount,
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => \App\Models\Announcement::latest('updated_at')->first()?->updated_at,
                ],
                'data' => \App\Http\Resources\PublicAnnouncementResource::collection($results),
            ], 200);

        } catch (\Exception $e) {
            Log::error("Announcement DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $announcements = $this->announcementService->findForPublic();
        return response()->json($announcements);
    }


    public function fetchAllForPublicLatest()
    {
        $announcements = $this->announcementService->findForPublic();
        return response()->json([
            'status' => true,
            'data' => \App\Http\Resources\PublicAnnouncementResource::collection($announcements),
        ], 200);
    }
}
