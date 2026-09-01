<?php

namespace App\Http\Controllers\Secure;

use Mews\Purifier\Facades\Purifier;
use App\DTO\LatestCpcbDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLatestCpcbRequest;
use App\Http\Requests\UpdateLatestCpcbRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicCpcbResource;
use App\Models\LatestCpcb;
use App\Services\LatestCpcbService;
use App\Services\DivisionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LatestCpcbController extends Controller
{
    protected $latestCpcbService;
    protected $divisionService;

    public function __construct()
    {
        $this->latestCpcbService = new LatestCpcbService();
        $this->divisionService = new DivisionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Latest CPCB';
        return view('secure.latest_cpcb.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $records = $this->latestCpcbService->findAll();
            return DataTables::of($records)
                ->addColumn('division_name', function ($data) {
                    return $data->division ? $data->division->title . ' (' . $data->division->title_hi . ')' : '—';
                })
                ->addColumn('file_name', function ($data) {
                    if ($data->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($data->file_url) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('file_name_hi', function ($data) {
                    if ($data->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($data->file_url_hi) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('action', function ($data) {
                    $button = '';
                    if (auth()->user()->can('view latest cpcb')) {
                        $button .= '<a href="' . route('latest-cpcbs.show', $data->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit latest cpcb')) {
                        $button .= '<a href="' . route('latest-cpcbs.edit', $data->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete latest cpcb')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-record" data-id="' . $data->id . '" title="Delete Record">
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
        $pageTitle = 'Add Latest CPCB';
        $divisions = $this->divisionService->findPublished();
        return view('secure.latest_cpcb.create', compact('pageTitle', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLatestCpcbRequest $request)
    {
        try {
            $LatestCpcbDto = new LatestCpcbDto(
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->input('publish_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0,
                0,
                null,
                $request->input('publish_remark'),
                auth()->user()->id,
                auth()->user()->id,
                $request->input('division_id')
            );

            $result = $this->latestCpcbService->create($LatestCpcbDto);

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
            Log::error('Latest CPCB addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Latest CPCB';
        $data = $this->latestCpcbService->findById($id);
        return view('secure.latest_cpcb.show', compact('data', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Latest CPCB';
        $data = $this->latestCpcbService->findById($id);
        $divisions = $this->divisionService->findPublished();
        return view('secure.latest_cpcb.edit', compact('data', 'pageTitle', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLatestCpcbRequest $request, LatestCpcb $latestCpcb)
    {
        try {
            $LatestCpcbDto = new LatestCpcbDto(
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->input('publish_date'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0,
                0,
                null,
                null,
                $latestCpcb->created_by,
                auth()->user()->id,
                $request->input('division_id')
            );

            $updated = $this->latestCpcbService->update($LatestCpcbDto, $latestCpcb->id);

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
            Log::error('Latest CPCB updation failed: ' . $e->getMessage());
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
            $result = $this->latestCpcbService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Latest CPCB deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, LatestCpcb $latestCpcb)
    {
        try {
            $LatestCpcbDto = new LatestCpcbDto(
                $latestCpcb->title,
                $latestCpcb->title_hi,
                $latestCpcb->publish_date,
                $latestCpcb->file_name,
                $latestCpcb->file_name_hi,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                $latestCpcb->publish_remark,
                $latestCpcb->created_by,
                auth()->user()->id
            );

            $updated = $this->latestCpcbService->approve($LatestCpcbDto, $latestCpcb->id);
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
            Log::error('Latest CPCB approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    public function publish(PublishRequest $request, LatestCpcb $latestCpcb)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $latestCpcb->is_approved == 1 || $isPublished == 1 ? 1 : $latestCpcb->is_approved;
            $remarks = $latestCpcb->is_approved == 1
                ? $latestCpcb->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $latestCpcb->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $LatestCpcbDto = new LatestCpcbDto(
                $latestCpcb->title,
                $latestCpcb->title_hi,
                $latestCpcb->publish_date,
                $latestCpcb->file_name,
                $latestCpcb->file_name_hi,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $latestCpcb->created_by,
                auth()->id()
            );
            $updated = $this->latestCpcbService->publish($LatestCpcbDto, $latestCpcb->id);

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
            Log::error('Latest CPCB publishing failed: ' . $e->getMessage());
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
        $data = $this->latestCpcbService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => PublicCpcbResource::collection($data)
        ]);
    }


    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            $baseQuery = LatestCpcb::where('is_published', 1);
            $now = Carbon::now()->startOfDay();
            $query = clone $baseQuery;

            if ($type === 'latest') {
                $query->whereDate('publish_date', '>=', $now->copy()->subMonth());
            } elseif ($type === 'archive') {
                $query->whereDate('publish_date', '<=', $now->copy()->subMonth());
            }

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'publish_date');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            $perPage = (int) $request->get('per_page', 10);
            $results = $query->paginate($perPage);

            $totalForType = (clone $baseQuery)->where('publish_date', $type === 'latest' ? '>=' : '<=', $now->copy()->subMonth())->count();

            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalForType,
                    'filtered_count' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => LatestCpcb::getLastUpdatedOrCreatedAt('latest_cpcbs'),
                ],
                'data' => PublicCpcbResource::collection($results),
            ], 200);
        } catch (\Exception $e) {
            Log::error("DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
