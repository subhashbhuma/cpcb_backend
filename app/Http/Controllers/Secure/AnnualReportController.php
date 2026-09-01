<?php

namespace App\Http\Controllers\Secure;

use App\DTO\AnnualReportDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnualReportRequest;
use App\Http\Requests\UpdateAnnualReportRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\AnnualReportResource;
use App\Models\AnnualReport;
use App\Services\AnnualReportService;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class AnnualReportController extends Controller
{
    protected $annualReportServices;
    public $carbon;
    public function __construct()
    {
        $this->annualReportServices = new AnnualReportService();
        $this->carbon = new Carbon();
    }

    public function index(Request $request)
    {
        $pageTitle = "Annual Report";
        return view('secure.annual_report.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = AnnualReport::query();

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $annual_report = $query->orderBy('id', 'DESC')->get();
            return DataTables::of($annual_report)
                ->addColumn('file_name', function ($annual_report) {
                    if ($annual_report->file_name) {
                        return "<a href=" . $annual_report->file_url . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('file_name_hi', function ($annual_report) {
                    if ($annual_report->file_name_hi) {
                        return "<a href=" . $annual_report->file_url_hi . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })

                ->addColumn('action', function ($annual_report) {
                    $button = '';
                    if (auth()->user()->can('view annual report')) {
                        $button .= '<a href="' . route('annual_report.show', $annual_report->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit annual report')) {
                        $button .= '<a href="' . route('annual_report.edit', $annual_report->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete annual report')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-annual_report" data-id="' . $annual_report->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi',])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Annual Report';
        return view('secure.annual_report.create', compact('pageTitle'));
    }

    public function store(StoreAnnualReportRequest $request)
    {
        try {
            $createdAt = Carbon::now();
            $annualReportDto = new AnnualReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('release_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved default
                0, // is_published default
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                $createdAt,
                auth()->user()->id,
                $createdAt
            );

            $annual_report = $this->annualReportServices->create($annualReportDto);

            if (!$annual_report) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving Annual Report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Annual Report created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Annual Report addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Annual Report';
        $annual_report = $this->annualReportServices->findById($id);
        return view('secure.annual_report.show', compact('annual_report', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Annual Report';
        $annual_report = $this->annualReportServices->findById($id);
        return view('secure.annual_report.edit', compact('annual_report', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnualReportRequest $request, AnnualReport $annualReport)
    {
        try {
            $annualReportDto = new AnnualReportDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('release_date'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $annualReport->created_by,
                $annualReport->created_at,
                auth()->user()->id,
                $this->carbon->now()
            );

            $updated = $this->annualReportServices->update($annualReportDto, $annualReport->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating Annual Report.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Annual Report updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Annual Report updation failed: ' . $e->getMessage());
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
            $annual_report = $this->annualReportServices->delete($id);
            if (!$annual_report) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting TechnAnnualical Report.',
                ], 500);
            }

            return response()->json(['message' => 'Annual Report moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Annual Report deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $report = AnnualReport::findOrFail($id);
            $updated = $this->annualReportServices->approve(
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
            Log::error('Annual Report approval failed: ' . $e->getMessage());
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
            $report = AnnualReport::findOrFail($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $report->is_approved == 1 || $isPublished == 1 ? 1 : $report->is_approved;
            $remarks = $report->is_approved == 1
                ? $report->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $report->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->annualReportServices->publish($report->id, $isApproved, $remarks, $isPublished, $publishRemark);

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
            Log::error('Annual Report publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $annualReport = $this->annualReportServices->findForPublic($request->limit);
        return response()->json([
            'success' => true,
            'data' => $annualReport
        ]);
    }



    public function fetchAllForPublicDataTable(Request $request)
    {
        try {
            $baseQuery = AnnualReport::where('is_published', 1);

            $query = (clone $baseQuery);

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'release_date');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            $perPage = (int) $request->get('per_page', 10);
            $results = $query->paginate($perPage);

            // --- DYNAMIC COUNTS ---
            $totalForType = (clone $baseQuery)->count();

            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalForType,
                    'filtered_count' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => AnnualReport::getLastUpdatedOrCreatedAt(),
                ],
                'data' => AnnualReportResource::collection($results),
            ], 200);
        } catch (\Exception $e) {
            Log::error("DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
