<?php

namespace App\Http\Controllers\Secure;

use App\DTO\TenderCorrigendumDto;
use App\DTO\TenderDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderRequest;
use App\Http\Requests\UpdateTenderRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicTenderResource;
use App\Models\Tender;
use App\Models\TenderCorrigendum;
use App\Services\TenderCorrigendumService;
use App\Services\TenderService;
use App\Services\DivisionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TenderController extends Controller
{
    protected $tenderService;
    protected $corrigendumService;
    protected $divisionService;

    public function __construct()
    {
        $this->tenderService = new TenderService();
        $this->corrigendumService = new TenderCorrigendumService();
        $this->divisionService = new DivisionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Tenders';
        return view('secure.tenders.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = Tender::with('division');

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $tenders = $query->orderBy('id', 'DESC');
            return DataTables::of($tenders)
                ->editColumn('publish_date', function ($tender) {
                    return $tender->publish_date ? $tender->publish_date->format('Y-m-d H:i') : '';
                })
                ->editColumn('start_date', function ($tender) {
                    return $tender->start_date ? $tender->start_date->format('Y-m-d H:i') : '';
                })
                ->editColumn('end_date', function ($tender) {
                    return $tender->end_date ? $tender->end_date->format('Y-m-d H:i') : '';
                })
                ->addColumn('file_name', function ($tender) {
                    if ($tender->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($tender->file_url) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('file_name_hi', function ($tender) {
                    if ($tender->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($tender->file_url_hi) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('division_name', function ($tender) {
                    return $tender->division?->title ?? '';
                })
                ->addColumn('action', function ($tender) {
                    $button = '';
                    if (auth()->user()->can('view tender')) {
                        $button .= '<a href="' . route('tenders.show', $tender->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit tender')) {
                        $button .= '<a href="' . route('tenders.edit', $tender->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete tender')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-tender" data-id="' . $tender->id . '" title="Delete">
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
        $pageTitle = 'Add Tenders';
        $divisions = $this->divisionService->findAll();
        return view('secure.tenders.create', compact('pageTitle', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenderRequest $request)
    {
        DB::beginTransaction();
        try {
            $tenderDto = new TenderDto(
                $request->input('division_id'),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('issuing_authority'))),
                strip_tags(html_entity_decode($request->input('issuing_authority_hi'))),
                $request->input('publish_date'),
                $request->input('start_date'),
                $request->input('end_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                auth()->user()->id
            );

            $tender = $this->tenderService->create($tenderDto);

            if (!$tender) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving tender.',
                ], 500);
            }


            // Add the corrigendum files
            if (count(json_decode($request->corrigendumCountArray)) > 0) {
                foreach (json_decode($request->corrigendumCountArray) as $key => $count) {
                    $tenderCorrigendumDto = new TenderCorrigendumDto(
                        $tender->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        strip_tags(html_entity_decode($request->input('title_' . $count))) ?? null,
                        strip_tags(html_entity_decode($request->input('title_hi_' . $count))) ?? null,
                        strip_tags(html_entity_decode($request->input('description_' . $count))) ?? null,
                        strip_tags(html_entity_decode($request->input('description_hi_' . $count))) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->corrigendumService->create($tenderCorrigendumDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while saving tender corrigendums.',
                            'data' => $result
                        ], 500);
                    }
                }
            }


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tender created successfully!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tender addition failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while adding tender.'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Tenders';
        $tender = $this->tenderService->findById($id);
        return view('secure.tenders.show', compact('tender', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Tenders';
        $tender = $this->tenderService->findById($id);
        $divisions = $this->divisionService->findAll();
        return view('secure.tenders.edit', compact('tender', 'pageTitle', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenderRequest $request, Tender $tender)
    {
        DB::beginTransaction();
        try {
            $tenderDto = new TenderDto(
                $request->input('division_id'),
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('issuing_authority'))),
                strip_tags(html_entity_decode($request->input('issuing_authority_hi'))),
                $request->input('publish_date'),
                $request->input('start_date'),
                $request->input('end_date'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                $tender->created_by,
                auth()->user()->id
            );

            $updated = $this->tenderService->update($tenderDto, $tender->id);

            if (!$updated) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating tender.',
                ], 500);
            }


            // Add the corrigendum files
            if (count(json_decode($request->corrigendumCountArray)) > 0) {
                foreach (json_decode($request->corrigendumCountArray) as $key => $count) {
                    $tenderCorrigendumDto = new TenderCorrigendumDto(
                        $tender->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        strip_tags(html_entity_decode($request->input('title_' . $count)) ?? null),
                        strip_tags(html_entity_decode($request->input('title_hi_' . $count)) ?? null),
                        strip_tags(html_entity_decode($request->input('description_' . $count)) ?? null),
                        strip_tags(html_entity_decode($request->input('description_hi_' . $count)) ?? null),
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->corrigendumService->create($tenderCorrigendumDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while saving tender corrigendums.',
                            'data' => $result
                        ], 500);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tender updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tender updation failed: ' . $e->getMessage());
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
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tender = $this->tenderService->delete($id);
            if (!$tender) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting tender.',
                ], 500);
            }

            return response()->json(['message' => 'Tender moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Tender deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroyCorrigendum(TenderCorrigendum $tenderCorrigendum)
    {
        try {
            $result = $this->corrigendumService->delete($tenderCorrigendum->id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting tender corrigendum.',
                ], 500);
            }

            return response()->json(['message' => 'Tender corrigendum deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Tender $tender)
    {
        try {
            $updated = $this->tenderService->approve(
                $tender->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $tender->publish_remark
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
            Log::error('Tender approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }


    public function publish(PublishRequest $request, Tender $tender)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $tender->is_approved == 1 || $isPublished == 1 ? 1 : $tender->is_approved;
            $remarks = $tender->is_approved == 1
                ? $tender->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $tender->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->tenderService->publish(
                $tender->id,
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
            Log::error('Tender publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $tender = $this->tenderService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $tender
        ]);
    }

    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            $baseQuery = Tender::with(['corrigendumns', 'division'])->where('is_published', 1);

            // Define the threshold (3 months ago)
            // $threeMonthsAgo = Carbon::now()->subMonths(3)->startOfDay();
            $threeMonthsAgo = Carbon::now()->subDays(30)->startOfDay();
            $query = (clone $baseQuery);


            if ($type === 'latest') {
                $query->where('publish_date', '>=', $threeMonthsAgo);
            } elseif ($type === 'archive') {
                $query->where('publish_date', '<=', $threeMonthsAgo);
            }

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
                $query->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
                    ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'start_date');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            $perPage = (int) $request->get('per_page', 10);
            $results = $query->paginate($perPage);

            // $totalForType = (clone $baseQuery)->where('publish_date', $type === 'latest' ? '>=' : '<=', $threeMonthsAgo)->count();
            $totalCount = $results->total();
            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalCount,
                    'filtered_count' => $totalCount,
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => Tender::getLastUpdatedOrCreatedAt(),
                ],
                'data' => PublicTenderResource::collection($results),
            ], 200);
        } catch (\Exception $e) {
            Log::error("DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
