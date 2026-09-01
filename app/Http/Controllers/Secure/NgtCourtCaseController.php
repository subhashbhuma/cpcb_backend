<?php

namespace App\Http\Controllers\Secure;

use App\DTO\NgtCourtCaseDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNgtCourtCaseRequest;
use App\Http\Requests\UpdateNgtCourtCaseRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicNgtCourtCaseResource;
use App\Models\NgtCourtCase;
use App\Services\NgtCourtCaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class NgtCourtCaseController extends Controller
{
    protected $ngtCourtCaseService;

    public function __construct()
    {
        $this->ngtCourtCaseService = new NgtCourtCaseService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'NGT Court Cases';
        return view('secure.ngt_court_case.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = NgtCourtCase::query();
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
                ->editColumn('publish_date', function ($data) {
                    if ($data->publish_date) {
                        return Carbon::parse($data->publish_date)->format('d-m-Y');
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
                    if (auth()->user()->can('view ngt court case')) {
                        $button .= '<a href="' . route('ngt-court-cases.show', $data->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit ngt court case')) {
                        $button .= '<a href="' . route('ngt-court-cases.edit', $data->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete ngt court case')) {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add NGT Court Case';
        return view('secure.ngt_court_case.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNgtCourtCaseRequest $request)
    {
        try {
            $dto = new NgtCourtCaseDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('qa_number'),
                $request->input('qa_number_hi'),
                $request->input('type'),
                $request->input('type_hi'),
                $request->input('publish_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->ngtCourtCaseService->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!',
                'redirect_url' => route('ngt-court-cases.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('NGT Court Case addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View NGT Court Case';
        $record = $this->ngtCourtCaseService->findById($id);
        return view('secure.ngt_court_case.show', compact('record', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit NGT Court Case';
        $data = $this->ngtCourtCaseService->findById($id);
        return view('secure.ngt_court_case.edit', compact('data', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNgtCourtCaseRequest $request, $id)
    {
        try {
            $record = $this->ngtCourtCaseService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new NgtCourtCaseDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('qa_number'),
                $request->input('qa_number_hi'),
                $request->input('type'),
                $request->input('type_hi'),
                $request->input('publish_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $updated = $this->ngtCourtCaseService->update($dto, $id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!',
                'redirect_url' => route('ngt-court-cases.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('NGT Court Case updation failed: ' . $e->getMessage());
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
            $result = $this->ngtCourtCaseService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('NGT Court Case deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, NgtCourtCase $ngtCourtCase)
    {
        try {
            $updated = $this->ngtCourtCaseService->approve(
                $ngtCourtCase->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $ngtCourtCase->publish_remark
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
            Log::error('NGT Court Case approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function publish(PublishRequest $request, NgtCourtCase $ngtCourtCase)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $ngtCourtCase->is_approved == 1 || $isPublished == 1 ? 1 : $ngtCourtCase->is_approved;
            $remarks = $ngtCourtCase->is_approved == 1
                ? $ngtCourtCase->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $ngtCourtCase->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->ngtCourtCaseService->publish(
                $ngtCourtCase->id,
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
            Log::error('NGT Court Case publishing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $limit = $request->get('limit', null);
        $data = $this->ngtCourtCaseService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => PublicNgtCourtCaseResource::collection($data)
        ]);
    }

    public function fetchAllForPublicDataTable(Request $request)
    {
        try {
            $baseQuery = NgtCourtCase::select('title', 'title_hi', 'qa_number', 'qa_number_hi', 'type', 'type_hi', 'publish_date', 'file_name', 'file_name_hi')->where('is_published', 1);
            $query = clone $baseQuery;

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(type) LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'publish_date');
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
                    'lastUpdatedOn' => NgtCourtCase::getLastUpdatedOrCreatedAt('ngt_court_cases'),
                ],
                'data' => PublicNgtCourtCaseResource::collection($results),
            ], 200);
        } catch (\Exception $e) {
            Log::error("DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
