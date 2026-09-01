<?php

namespace App\Http\Controllers\Secure;

use App\DTO\FortnightlyReportDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFortnightlyReportRequest;
use App\Http\Requests\UpdateFortnightlyReportRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\FortnightlyReport;
use App\Services\FortnightlyReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FortnightlyReportController extends Controller
{
    protected $fortnightlyReportService;

    public function __construct()
    {
        $this->fortnightlyReportService = new FortnightlyReportService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Fortnightly Reports';
        return view('secure.fortnightly_report.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = FortnightlyReport::query();
            $data = $query->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addColumn('file_name', function ($data) {
                    if ($data->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($data->file_url) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
                })
                ->addColumn('file_name_hi', function ($data) {
                    if ($data->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($data->file_url_hi) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
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
                ->addColumn('action', function ($data) {
                    $button = '';
                    if (auth()->user()->can('view fortnightly report')) {
                        $button .= '<a href="' . route('fortnightly-reports.show', $data->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }
                    if (auth()->user()->can('edit fortnightly report')) {
                        $button .= '<a href="' . route('fortnightly-reports.edit', $data->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }
                    if (auth()->user()->can('delete fortnightly report')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-record" data-id="' . $data->id . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Fortnightly Report';
        return view('secure.fortnightly_report.create', compact('pageTitle'));
    }

    public function store(StoreFortnightlyReportRequest $request)
    {
        try {
            $dto = new FortnightlyReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->fortnightlyReportService->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!',
                'redirect_url' => route('fortnightly-reports.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Fortnightly Report addition failed: ' . $e->getMessage());
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

    public function show(string $id)
    {
        $pageTitle = 'View Fortnightly Report';
        $record = $this->fortnightlyReportService->findById($id);
        return view('secure.fortnightly_report.show', compact('record', 'pageTitle'));
    }

    public function edit(string $id)
    {
        $pageTitle = 'Edit Fortnightly Report';
        $record = $this->fortnightlyReportService->findById($id);
        return view('secure.fortnightly_report.edit', compact('record', 'pageTitle'));
    }

    public function update(UpdateFortnightlyReportRequest $request, $id)
    {
        try {
            $record = $this->fortnightlyReportService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new FortnightlyReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $updated = $this->fortnightlyReportService->update($dto, $id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!',
                'redirect_url' => route('fortnightly-reports.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fortnightly Report updating failed: ' . $e->getMessage());
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

    public function destroy(string $id)
    {
        try {
            $result = $this->fortnightlyReportService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Fortnightly Report deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, FortnightlyReport $fortnightlyReport)
    {
        try {
            $updated = $this->fortnightlyReportService->approve(
                $fortnightlyReport->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $fortnightlyReport->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fortnightly Report approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function publish(PublishRequest $request, FortnightlyReport $fortnightlyReport)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $fortnightlyReport->is_approved == 1 || $isPublished == 1 ? 1 : $fortnightlyReport->is_approved;
            $remarks = $fortnightlyReport->is_approved == 1
                ? $fortnightlyReport->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $fortnightlyReport->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->fortnightlyReportService->publish(
                $fortnightlyReport->id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fortnightly Report publishing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $limit = $request->get('limit', null);
        $records = $this->fortnightlyReportService->findForPublic($limit);
        return \App\Http\Resources\PublicFortnightlyReportResource::collection($records);
    }

    public function fetchAllForPublicDataTable(Request $request)
    {
        try {
            $baseQuery = FortnightlyReport::where('is_published', 1);
            $query = clone $baseQuery;

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'created_at');
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
                    'lastUpdatedOn' => FortnightlyReport::getLastUpdatedOrCreatedAt(),
                ],
                'data' => \App\Http\Resources\PublicFortnightlyReportResource::collection($results),
            ], 200);
        } catch (\Exception $e) {
            Log::error("DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
