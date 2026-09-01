<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DirectionDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDirectionRequest;
use App\Http\Requests\UpdateDirectionRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicDirectionResource;
use App\Models\Direction;
use App\Services\DirectionActTypeService;
use App\Services\DirectionCategoryService;
use App\Services\DirectionIssuedToService;
use App\Services\DirectionService;
use App\Services\DirectionStateService;
use App\Services\DirectionSubjectService;
use App\Services\DirectionTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DirectionController extends Controller
{
    protected $directionService;
    protected $actTypeService;
    protected $typeService;
    protected $subjectService;
    protected $stateService;
    protected $categoryService;
    protected $issuedToService;

    public function __construct()
    {
        $this->directionService = new DirectionService();
        $this->actTypeService = new DirectionActTypeService();
        $this->typeService = new DirectionTypeService();
        $this->subjectService = new DirectionSubjectService();
        $this->stateService = new DirectionStateService();
        $this->categoryService = new DirectionCategoryService();
        $this->issuedToService = new DirectionIssuedToService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Directions';
        return view('secure.direction.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = Direction::query();

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $directions = $query->orderBy('id', 'DESC');
            return DataTables::of($directions)
                ->addColumn('file_name', function ($direction) {
                    if ($direction->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($direction->file_url) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
                })
                ->addColumn('file_name_hi', function ($direction) {
                    if ($direction->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($direction->file_url_hi) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
                })
                ->addColumn('action', function ($direction) {
                    $button = '';
                    if (auth()->user()->can('view direction')) {
                        $button .= '<a href="' . route('direction.show', $direction->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit direction')) {
                        $button .= '<a href="' . route('direction.edit', $direction->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete direction')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-direction" data-id="' . $direction->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
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
                ->rawColumns(['action', 'file_name', 'file_name_hi', 'status', 'approval_status'])
                ->make(true);
        }
    }

    // public function fetchAllForPublicDataTable(Request $request, $type = null)
    // {
    //     try {
    //         $actTypeId = ($type === 'section-18') ? 2 : 1;
    //         $baseQuery = Direction::where('direction_act_type_id', $actTypeId)
    //             ->where('is_published', 1);
    //         if ($type === 'section-5ep') {
    //             $baseQuery->whereDate('publish_date', '>', '2020-12-31');
    //         } else if ($type === 'section-5ep-old') {
    //             $baseQuery->whereDate('publish_date', '<', '2021-12-31');
    //         }

    //         $totalForType = (clone $baseQuery)->count();

    //         // --- SEARCH LOGIC ---
    //         $query = clone $baseQuery;
    //         if ($request->filled('search')) {
    //             $search = $request->search;
    //             $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
    //             $query->where(function ($q) use ($search) {
    //                 $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
    //                     ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
    //             });
    //         }

    //         // --- SORTING & PAGINATION ---
    //         $sortField = $request->get('sort', 'publish_date');
    //         $sortOrder = $request->get('order', 'desc');
    //         $perPage = (int) $request->get('per_page', 10);

    //         $results = $query->orderBy($sortField, $sortOrder)->paginate($perPage);

    //         // --- METADATA ---
    //         $lastUpdated = Direction::getLastUpdatedOrCreatedAt();

    //         return response()->json([
    //             'status' => true,
    //             'meta' => [
    //                 'total_records' => $totalForType,
    //                 'filtered_count' => $results->total(),
    //                 'current_page' => $results->currentPage(),
    //                 'per_page' => $results->perPage(),
    //                 'last_page' => $results->lastPage(),
    //                 'lastUpdatedOn' => $lastUpdated,
    //             ],
    //             'data' => \App\Http\Resources\PublicDirectionResource::collection($results),
    //         ], 200);

    //     } catch (\Exception $e) {
    //         \Log::error("Direction DataTable Fetch Error: " . $e->getMessage());
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Internal Server Error'
    //         ], 500);
    //     }
    // }


    public function fetchAllForPublicDataTable(Request $request, $type = null)
    {
        try {

            $actTypeId = ($type === 'section-18') ? 2 : 1;

            $sortField = $request->get('sort', 'publish_date');

            $sortOrder = $request->get('order', 'desc');

            $perPage = (int) $request->get('per_page', 10);

            $search = trim($request->search ?? '');

            /*
            |--------------------------------------------------------------------------
            | BASE QUERY
            |--------------------------------------------------------------------------
            */

            $query = Direction::query()
                ->select('directions.*')
                ->where('direction_act_type_id', $actTypeId)
                ->where('is_published', 1);

            /*
            |--------------------------------------------------------------------------
            | TYPE FILTER
            |--------------------------------------------------------------------------
            */

            if ($type === 'section-5ep') {

                $query->whereDate(
                    'publish_date',
                    '>',
                    '2020-12-31'
                );

            } elseif ($type === 'section-5ep-old') {

                $query->whereDate(
                    'publish_date',
                    '<',
                    '2021-12-31'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if (!empty($search)) {

                $query->where(function ($q) use ($search) {


                    $q->where(
                        'title',
                        'ILIKE',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'title_hi',
                            'ILIKE',
                            "%{$search}%"
                        )


                        // publish date
                        ->orWhereRaw(
                            "TO_CHAR(publish_date, 'YYYY-MM-DD') ILIKE ?",
                            ["%{$search}%"]
                        )

                        ->orWhereRaw(
                            "TO_CHAR(publish_date, 'DD-MM-YYYY') ILIKE ?",
                            ["%{$search}%"]
                        )

                        ->orWhereRaw(
                            "TO_CHAR(publish_date, 'DD Mon YYYY') ILIKE ?",
                            ["%{$search}%"]
                        )

                        /*
                        |--------------------------------------------------------------------------
                        | CATEGORY SEARCH
                        |--------------------------------------------------------------------------
                        */

                        ->orWhereRaw("
                    EXISTS (
                        SELECT 1
                        FROM direction_categories dc
                        WHERE dc.id::text = ANY(
                            string_to_array(
                                directions.direction_category_id,
                                ','
                            )
                        )
                        AND (
                            dc.title ILIKE ?
                            OR dc.title_hi ILIKE ?
                        )
                    )
                ", [
                            "%{$search}%",
                            "%{$search}%"
                        ])

                        /*
                        |--------------------------------------------------------------------------
                        | STATE SEARCH
                        |--------------------------------------------------------------------------
                        */

                        ->orWhereRaw("
                    EXISTS (
                        SELECT 1
                        FROM direction_states ds
                        WHERE ds.id::text = ANY(
                            string_to_array(
                                directions.direction_state_id,
                                ','
                            )
                        )
                        AND (
                            ds.title ILIKE ?
                            OR ds.title_hi ILIKE ?
                        )
                    )
                ", [
                            "%{$search}%",
                            "%{$search}%"
                        ])

                        /*
                        |--------------------------------------------------------------------------
                        | ISSUED TO SEARCH
                        |--------------------------------------------------------------------------
                        */

                        ->orWhereRaw("
                    EXISTS (
                        SELECT 1
                        FROM direction_issued_tos di
                        WHERE di.id::text = ANY(
                            string_to_array(
                                directions.direction_issued_to_id,
                                ','
                            )
                        )
                        AND (
                            di.title ILIKE ?
                            OR di.title_hi ILIKE ?
                        )
                    )
                ", [
                            "%{$search}%",
                            "%{$search}%"
                        ]);
                });
            }

            /*
            |--------------------------------------------------------------------------
            | ORIGINAL SL NO
            |--------------------------------------------------------------------------
            */

            $subQuery = Direction::query()
                ->selectRaw("
                id,
                ROW_NUMBER() OVER (
                    ORDER BY publish_date DESC, id DESC
                ) as original_sl_no
            ")
                ->where('direction_act_type_id', $actTypeId)
                ->where('is_published', 1);

            if ($type === 'section-5ep') {

                $subQuery->whereDate(
                    'publish_date',
                    '>',
                    '2020-12-31'
                );

            } elseif ($type === 'section-5ep-old') {

                $subQuery->whereDate(
                    'publish_date',
                    '<',
                    '2021-12-31'
                );
            }

            $query->leftJoinSub(
                $subQuery,
                'sl_table',
                function ($join) {

                    $join->on(
                        'directions.id',
                        '=',
                        'sl_table.id'
                    );
                }
            );

            $query->addSelect(
                'sl_table.original_sl_no as sl_no'
            );

            /*
            |--------------------------------------------------------------------------
            | SORT
            |--------------------------------------------------------------------------
            */

            $results = $query
                ->orderBy($sortField, $sortOrder)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'status' => true,

                'meta' => [

                    'total_records' =>
                        $results->total(),

                    'filtered_count' =>
                        $results->total(),

                    'current_page' =>
                        $results->currentPage(),

                    'per_page' =>
                        $results->perPage(),

                    'last_page' =>
                        $results->lastPage(),

                    'lastUpdatedOn' =>
                        Direction::getLastUpdatedOrCreatedAt(),
                ],

                'data' =>
                    PublicDirectionResource::collection(
                        $results
                    ),

            ], 200);

        } catch (\Exception $e) {

            \Log::error($e->getMessage());

            return response()->json([

                'status' => false,

                'message' => $e->getMessage(),

            ], 500);
        }
    }
    public function create()
    {
        $pageTitle = 'Add Directions';
        $actTypes = $this->actTypeService->findAll();
        $types = $this->typeService->findAll();
        $subjects = $this->subjectService->findAll();
        $states = $this->stateService->findAll();
        $categories = $this->categoryService->findAll();
        $issuedTos = $this->issuedToService->findAll();

        return view('secure.direction.create', compact('pageTitle', 'actTypes', 'states', 'categories', 'issuedTos', 'types', 'subjects'));
    }

    public function store(StoreDirectionRequest $request)
    {
        try {
            $directionDto = new DirectionDto(
                $request->input('direction_act_type_id'),
                $request->input('direction_type_id'),
                $request->input('direction_subject_id'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('publish_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id(),
                $request->input('direction_state_id', []),
                $request->input('direction_category_id', []),
                $request->input('direction_issued_to_id', [])
            );

            $direction = $this->directionService->create($directionDto);

            if (!$direction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving direction.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Direction created successfully!',
                'redirect_url' => route('direction.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Direction addition failed: ' . $e->getMessage());
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

    public function show($id)
    {
        $pageTitle = 'View Directions';
        $direction = $this->directionService->findById($id);

        return view('secure.direction.show', compact('direction', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Directions';
        $direction = $this->directionService->findById($id);

        $actTypes = $this->actTypeService->findAll();
        $types = $this->typeService->findAll();
        $subjects = $this->subjectService->findAll();
        $states = $this->stateService->findAll();
        $categories = $this->categoryService->findAll();
        $issuedTos = $this->issuedToService->findAll();

        // Convert comma separated strings to arrays for select2
        $selectedStates = $direction->direction_state_id ? explode(',', $direction->direction_state_id) : [];
        $selectedCategories = $direction->direction_category_id ? explode(',', $direction->direction_category_id) : [];
        $selectedIssuedTos = $direction->direction_issued_to_id ? explode(',', $direction->direction_issued_to_id) : [];

        return view('secure.direction.edit', compact(
            'direction',
            'pageTitle',
            'actTypes',
            'types',
            'subjects',
            'states',
            'categories',
            'issuedTos',
            'selectedStates',
            'selectedCategories',
            'selectedIssuedTos'
        ));
    }

    public function update(UpdateDirectionRequest $request, $id)
    {
        try {
            $direction = $this->directionService->findById($id);
            if (!$direction) {
                return response()->json(['success' => false, 'message' => 'Direction not found']);
            }

            $directionDto = new DirectionDto(
                $request->input('direction_act_type_id'),
                $request->input('direction_type_id'),
                $request->input('direction_subject_id'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('publish_date'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $direction->created_by,
                auth()->id(),
                $request->input('direction_state_id', []),
                $request->input('direction_category_id', []),
                $request->input('direction_issued_to_id', [])
            );

            $updated = $this->directionService->update($directionDto, $id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating direction.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Direction updated successfully!',
                'redirect_url' => route('direction.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Direction updates failed: ' . $e->getMessage());
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

    public function destroy($id)
    {
        try {
            $result = $this->directionService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting direction.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Direction moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Direction deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Direction $direction)
    {
        try {
            $updated = $this->directionService->approve(
                $direction->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $direction->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving direction.',
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Direction approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Direction $direction)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $direction->is_approved == 1 || $isPublished == 1 ? 1 : $direction->is_approved;
            $remarks = $direction->is_approved == 1
                ? $direction->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $direction->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directionService->publish(
                $direction->id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing direction.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Direction publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchDependencies(Request $request)
    {
        $actTypeId = $request->get('act_type_id');
        if (!$actTypeId) {
            return response()->json(['success' => false, 'message' => 'Act Type ID is required.'], 400);
        }

        try {
            $types = $this->typeService->findByActType($actTypeId);
            $subjects = $this->subjectService->findByActType($actTypeId);
            $categories = $this->categoryService->findByActType($actTypeId);
            $issuedTos = $this->issuedToService->findByActType($actTypeId);

            return response()->json([
                'success' => true,
                'types' => $types,
                'subjects' => $subjects,
                'categories' => $categories,
                'issued_tos' => $issuedTos,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
