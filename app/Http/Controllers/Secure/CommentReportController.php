<?php

namespace App\Http\Controllers\Secure;

use App\DTO\CommentReportDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentReportRequest;
use App\Http\Requests\UpdateCommentReportRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\CommentReport;
use App\Services\CommentReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CommentReportController extends Controller
{
    protected $commentReportService;

    public function __construct()
    {
        $this->commentReportService = new CommentReportService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Comment Reports';
        return view('secure.comment-reports.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = CommentReport::query();

            $data = $query->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addColumn('file', function ($commentReport) {
                    if ($commentReport->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($commentReport->file_url) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
                })
                ->addColumn('file_hi', function ($commentReport) {
                    if ($commentReport->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($commentReport->file_url_hi) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
                })
                ->editColumn('published_date', function ($commentReport) {
                    return $commentReport->published_date ? $commentReport->published_date->format('d-m-Y') : '';
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_published == 1) {
                        return '<span class="badge bg-success">' . $row->is_published_desc . '</span>';
                    } else {
                        return '<span class="badge bg-warning">' . $row->is_published_desc . '</span>';
                    }
                })
                ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved == 1) {
                        return '<span class="badge bg-success">' . $row->is_approved_desc . '</span>';
                    } elseif ($row->is_approved == 2) {
                        return '<span class="badge bg-danger">' . $row->is_approved_desc . '</span>';
                    } else {
                        return '<span class="badge bg-warning">' . $row->is_approved_desc . '</span>';
                    }
                })
                ->addColumn('action', function ($commentReport) {
                    $button = '';
                    if (auth()->user()->can('view comment report')) {
                        $button .= '<a href="' . route('comment-reports.show', $commentReport->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit comment report')) {
                        $button .= '<a href="' . route('comment-reports.edit', $commentReport->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete comment report')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-comment-report" data-id="' . $commentReport->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file', 'file_hi', 'status', 'approval_status'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Comment Report';
        return view('secure.comment-reports.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentReportRequest $request)
    {
        try {
            $dto = new CommentReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('emails'),
                $request->input('published_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->commentReportService->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving comment report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comment Report created successfully!',
                'redirect_url' => route('comment-reports.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Comment Report addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Comment Report';
        $record = $this->commentReportService->findById($id);
        return view('secure.comment-reports.show', compact('record', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Comment Report';
        $commentReport = $this->commentReportService->findById($id);
        return view('secure.comment-reports.edit', compact('commentReport', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentReportRequest $request, $id)
    {
        try {
            $record = $this->commentReportService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new CommentReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('emails'),
                $request->input('published_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $updated = $this->commentReportService->update($dto, $id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating comment report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comment Report updated successfully!',
                'redirect_url' => route('comment-reports.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Comment Report updation failed: ' . $e->getMessage());
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
            $result = $this->commentReportService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting comment report.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Comment Report moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Comment Report deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, CommentReport $commentReport)
    {
        try {
            $updated = $this->commentReportService->approve(
                $commentReport->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $commentReport->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving comment report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Comment Report approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function publish(PublishRequest $request, CommentReport $commentReport)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $commentReport->is_approved == 1 || $isPublished == 1 ? 1 : $commentReport->is_approved;
            $remarks = $commentReport->is_approved == 1
                ? $commentReport->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $commentReport->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->commentReportService->publish(
                $commentReport->id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing comment report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Comment Report publishing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            $baseQuery = CommentReport::where('is_published', 1);

            $threeMonthsAgo = \Carbon\Carbon::now()->subDays(7)->startOfDay();
            $query = (clone $baseQuery);

            if ($type === 'latest') {
                $query->where('published_date', '>=', $threeMonthsAgo);
            } elseif ($type === 'archive') {
                $query->where('published_date', '<=', $threeMonthsAgo);
            }

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
                        ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%" . strtolower($search) . "%"])
                        ->orWhereRaw('description_hi LIKE ?', ["%{$search}%"]);
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
                    'lastUpdatedOn' => CommentReport::getLastUpdatedOrCreatedAt(),
                ],
                'data' => \App\Http\Resources\PublicCommentReportResource::collection($results),
            ], 200);

        } catch (\Exception $e) {
            Log::error("Comment Report DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
