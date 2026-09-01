<?php

namespace App\Http\Controllers\Secure;

use App\DTO\TechnicalReportDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTechnicalReportRequest;
use App\Http\Requests\UpdateTechnicalReportRequest;
use App\Http\Resources\TechnicalReportResource;
use App\Models\TechnicalReport;
use App\Services\DivisionService;
use App\Services\SubjectAreaService;
use App\Services\TechnicalReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use Yajra\DataTables\Facades\DataTables;

class TechnicalReportController extends Controller
{
    protected TechnicalReportService $technicalReportService;
    protected DivisionService $divisionService;
    protected SubjectAreaService $subjectAreaService;

    public function __construct()
    {
        $this->technicalReportService = new TechnicalReportService();
        $this->divisionService = new DivisionService();
        $this->subjectAreaService = new SubjectAreaService();
    }

    /**
     * Display technical report listing page
     */
    public function index(Request $request)
    {
        $pageTitle = "Technical Report";
        return view('secure.technical_report.index', compact('pageTitle'));
    }

    /**
     * Fetch technical reports for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = TechnicalReport::query();

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $reports = $query->orderBy('id', 'DESC')->get();

            return DataTables::of($reports)
                ->addColumn('subject_area_name', function ($report) {
                    return $report->subjectArea?->title ?? '-';
                })
                ->addColumn('division_name', function ($report) {
                    return $report->division?->title ?? '-';
                })
                ->addColumn('file_name', function ($report) {
                    if ($report->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($report->file_url) . " target='_blank'>View File</a>";
                    }
                    return '-';
                })
                ->addColumn('file_name_hi', function ($report) {
                    if ($report->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($report->file_url_hi) . " target='_blank'>View File</a>";
                    }
                    return '-';
                })
                ->addColumn('action', function ($report) {
                    $buttons = '';

                    if (auth()->user()->can('view technical report')) {
                        $buttons .= '<a href="' . route('technical_report.show', $report->id) . '"
                                        class="btn btn-sm btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('edit technical report')) {
                        $buttons .= '<a href="' . route('technical_report.edit', $report->id) . '"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('delete technical report')) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-technical-report"
                                        data-id="' . $report->id . '" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi', 'subject_area_name', 'division_name'])
                ->make(true);
        }
    }

    /**
     * Show create technical report form
     */
    public function create()
    {
        $pageTitle = 'Add Technical Report';
        $divisions = $this->divisionService->findPublished();
        $subjectAreas = $this->subjectAreaService->findPublished();

        return view('secure.technical_report.create', compact('pageTitle', 'divisions', 'subjectAreas'));
    }

    /**
     * Store new technical report
     */
    public function store(StoreTechnicalReportRequest $request)
    {
        DB::beginTransaction();
        try {
            $dto = new TechnicalReportDto(
                $request->subject_area_id,
                $request->division_id,
                $request->title,
                $request->title_hi,
                $request->release_date,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                Carbon::now(),
                auth()->id(),
                Carbon::now()
            );

            $report = $this->technicalReportService->create($dto);

            if (!$report) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving technical report.'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Technical report created successfully!',
                'redirect_url' => route('technical_report.index')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Technical report creation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Display specific technical report
     */
    public function show(string $id)
    {
        $pageTitle = 'View Technical Report';
        $report = $this->technicalReportService->findById($id);

        return view('secure.technical_report.show', compact('report', 'pageTitle'));
    }

    /**
     * Show edit technical report form
     */
    public function edit(TechnicalReport $technicalReport)
    {
        $pageTitle = 'Edit Technical Report';
        $divisions = $this->divisionService->findPublished();
        $subjectAreas = $this->subjectAreaService->findPublished();

        return view('secure.technical_report.edit', compact('technicalReport', 'pageTitle', 'divisions', 'subjectAreas'));
    }

    /**
     * Update technical report
     */
    public function update(UpdateTechnicalReportRequest $request, TechnicalReport $technicalReport)
    {
        DB::beginTransaction();
        try {
            $dto = new TechnicalReportDto(
                $request->subject_area_id,
                $request->division_id,
                $request->title,
                $request->title_hi,
                $request->release_date,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $technicalReport->created_by,
                $technicalReport->created_at,
                auth()->id(),
                Carbon::now()
            );

            $updated = $this->technicalReportService->update($dto, $technicalReport->id);

            if (!$updated) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating technical report.'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Technical report updated successfully!',
                'redirect_url' => route('technical_report.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Technical report update failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Delete technical report
     */
    public function destroy(TechnicalReport $technicalReport)
    {
        DB::beginTransaction();
        try {
            $deleted = $this->technicalReportService->delete($technicalReport->id);

            if (!$deleted) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting technical report.'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Technical report deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Technical report deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Approve/Reject technical report
     */
    public function approve(ApproveRequest $request, $id)
    {
        try {
            $report = TechnicalReport::findOrFail($id);
            $updated = $this->technicalReportService->approve(
                $report->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $report->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving technical report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Technical report approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Publish/Unpublish technical report
     */
    public function publish(PublishRequest $request, $id)
    {
        try {
            $report = TechnicalReport::findOrFail($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $report->is_approved == 1 || $isPublished == 1 ? 1 : $report->is_approved;
            $remarks = $report->is_approved == 1
                ? $report->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $report->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->technicalReportService->publish($report->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing technical report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Technical report publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Fetch published technical reports for public API
     */
    public function fetchAllForPublic()
    {
        $reports = $this->technicalReportService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }


    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            // $threeMonthsAgo = Carbon::now()->subMonths(3)->startOfDay();
            $threeMonthsAgo = date('Y-m-d', strtotime('-365 days'));

            // Start with base query
            $query = TechnicalReport::select('technical_reports.*')
                ->where('technical_reports.is_published', 1);

            // Filter by type
            $query->where(function ($q) use ($type, $threeMonthsAgo) {
                if ($type === 'latest') {
                    $q->where('technical_reports.release_date', '>=', $threeMonthsAgo);
                } else {
                    $q->where('technical_reports.release_date', '<=', $threeMonthsAgo)
                        ->orWhereNull('technical_reports.release_date');
                }
            });

            // --- SEARCH (includes subject_area and division) ---
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);

                // Join tables for search
                $query->leftJoin('subject_areas', 'technical_reports.subject_area_id', '=', 'subject_areas.id')
                    ->leftJoin('divisions', 'technical_reports.division_id', '=', 'divisions.id');

                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(technical_reports.title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(technical_reports.title_hi) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(subject_areas.title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(subject_areas.title_hi) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(divisions.title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(divisions.title_hi) LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING ---
            $sortField = $request->get('sort');
            $sortOrder = $request->get('order');

            if (!$sortOrder) {
                $sortOrder = 'desc';
            }
            if (!$sortField) {
                $sortField = 'release_date';
                $query->orderByRaw(
                    "CASE WHEN $sortField IS NULL THEN 1 ELSE 0 END, $sortField $sortOrder"
                );
            }

            switch ($sortField) {
                case 'subject':
                    // Join if not already joined (from search)
                    if (!$request->filled('search')) {
                        $query->leftJoin('subject_areas', 'technical_reports.subject_area_id', '=', 'subject_areas.id');
                    }
                    $query->orderBy('subject_areas.title', $sortOrder);
                    break;

                case 'division':
                    // Join if not already joined (from search)
                    if (!$request->filled('search')) {
                        $query->leftJoin('divisions', 'technical_reports.division_id', '=', 'divisions.id');
                    }
                    $query->orderBy('divisions.title', $sortOrder);
                    break;

                case 'title':
                    $query->orderBy('technical_reports.title', $sortOrder);
                    break;

                default:
                    $query->orderBy('technical_reports.release_date', $sortOrder);
                    break;
            }

            // --- PAGINATION ---
            $perPage = min((int) $request->get('per_page', 10), 100);
            $results = $query->paginate($perPage);

            // Eager load relations after pagination (only specific columns)
            $results->load(['subjectArea:id,title,title_hi', 'division:id,title,title_hi']);

            // --- COUNTS ---
            $totalForTypeQuery = TechnicalReport::where('is_published', 1)
            ;

            $totalForTypeQuery->where(function ($q) use ($type, $threeMonthsAgo) {
                if ($type === 'latest') {
                    $q->where('technical_reports.release_date', '>=', $threeMonthsAgo);
                } else {
                    $q->where('technical_reports.release_date', '<=', $threeMonthsAgo)
                        ->orWhereNull('technical_reports.release_date');
                }
            });

            $totalForType = $totalForTypeQuery->count();
            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalForType,
                    'filtered_count' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => TechnicalReport::getLastUpdatedOrCreatedAt(),
                ],
                'data' => TechnicalReportResource::collection($results),
            ]);
        } catch (\Exception $e) {
            Log::error("DataTable Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
