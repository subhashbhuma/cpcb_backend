<?php

namespace App\Http\Controllers\Secure;

use App\DTO\StudiesReportDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudiesReportRequest;
use App\Http\Requests\UpdateStudiesReportRequest;
use App\Http\Resources\PublicPageCategoryResource;
use App\Http\Resources\PublicStudiesReportResource;
use App\Models\StudiesReport;
use App\Services\PageCategoryService;
use App\Services\StudiesReportService;
use App\Services\DivisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use Yajra\DataTables\Facades\DataTables;

class StudiesReportController extends Controller
{
    protected $studiesReportService;
    protected $pageCategoryService;
    protected $divisionService;

    public function __construct()
    {
        $this->studiesReportService = new StudiesReportService();
        $this->pageCategoryService = new PageCategoryService();
        $this->divisionService = new DivisionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Studies & Reports';
        return view('secure.studies_reports.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource for Datatable.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->studiesReportService->findAll();
            return DataTables::of($data)
                ->addColumn('file_name', function ($row) {
                    if ($row->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($row->file_url) . " target='_BLANK'>View Document</a>";
                    }
                    return '';
                })
                ->addColumn('file_name_hi', function ($row) {
                    if ($row->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($row->file_url_hi) . " target='_BLANK'>View Document</a>";
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    if (auth()->user()->can('view studies report')) {
                        $button .= '<a href="' . route('studies_reports.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit studies report')) {
                        $button .= '<a href="' . route('studies_reports.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete studies report')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Studies & Reports';
        $divisions = $this->divisionService->findPublished();
        return view('secure.studies_reports.create', compact('pageTitle', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudiesReportRequest $request)
    {
        try {
            $dto = new StudiesReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('division_id'),           // division_id input
                $request->input('report_year'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved default
                0, // is_published default
                null,
                null, // publish_remark
                auth()->user()->id,
                auth()->user()->id
            );

            $result = $this->studiesReportService->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Studies Report addition failed: ' . $e->getMessage());
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Studies & Reports';
        $studiesReport = $this->studiesReportService->findById($id);
        return view('secure.studies_reports.show', compact('studiesReport', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Studies & Reports';
        $studiesReport = $this->studiesReportService->findById($id);
        $divisions = $this->divisionService->findPublished();
        return view('secure.studies_reports.edit', compact('studiesReport', 'pageTitle', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudiesReportRequest $request, StudiesReport $studiesReport)
    {
        try {
            $dto = new StudiesReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('division_id'),
                $request->input('report_year'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $studiesReport->created_by,
                auth()->user()->id
            );

            $updated = $this->studiesReportService->update($dto, $studiesReport->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Studies Report updation failed: ' . $e->getMessage());
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
            $result = $this->studiesReportService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Studies Report deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $report = StudiesReport::findOrFail($id);
            $updated = $this->studiesReportService->approve(
                $report->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $report->publish_remark
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
            Log::error('Approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, string $id)
    {
        try {
            $report = StudiesReport::findOrFail($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $report->is_approved == 1 || $isPublished == 1 ? 1 : $report->is_approved;
            $remarks = $report->is_approved == 1
                ? $report->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $report->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->studiesReportService->publish($report->id, $isApproved, $remarks, $isPublished, $publishRemark);

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
            Log::error('Publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $limit = $request->get('limit', null);
        $data = $this->studiesReportService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => PublicStudiesReportResource::collection($data),
            'lastUpdatedOn' => StudiesReport::getLastUpdatedOrCreatedAt(),
        ]);
    }
}

