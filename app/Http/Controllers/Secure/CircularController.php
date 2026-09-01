<?php

namespace App\Http\Controllers\Secure;

use App\DTO\CircularDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCircularRequest;
use App\Http\Requests\UpdateCircularRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicCircularResource;
use App\Models\Circular;
use App\Services\CircularCategoryService;
use App\Services\CircularService;
use App\Services\DivisionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CircularController extends Controller
{
    protected $circularService;
    protected $circularCategoryService;
    protected $divisionService;

    public function __construct()
    {
        $this->circularService = new CircularService();
        $this->circularCategoryService = new CircularCategoryService();
        $this->divisionService = new DivisionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Circulars';
        return view('secure.circulars.index', compact('pageTitle'));
    }

    public function employeeIndex(Request $request, $category)
    {
        $categoryId = '';
        switch ($category) {
            case 'memorandum':
                $categoryId = 2;
                $pageTitle = 'Memorandum';
                break;
            case 'office_order':
                $categoryId = 3;
                $pageTitle = 'Office Order';
                break;
            case 'circular':
                $categoryId = 1;
                $pageTitle = 'Circular';
                break;

            default:
                $categoryId = 1;
                $pageTitle = 'Circular';
                break;
        }
        return view('secure.circulars.employee-circular', compact('pageTitle', 'categoryId'));
    }




    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $archiveStatus = $request->input('archive_status', null);
            $category = $request->input('category', null);
            $query = $this->circularService->fetchForDatatable($archiveStatus, $category);

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            return DataTables::of($query)
                ->addColumn('circular_category_name', function ($circular) {
                    return $circular->circularCategory ? $circular->circularCategory->name . ' (' . $circular->circularCategory->name_hi . ')' : '';
                })
                ->addColumn('file', function ($circular) {
                    if ($circular->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($circular->file_url) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('file_hi', function ($circular) {
                    if ($circular->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($circular->file_url_hi) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->editColumn('published_date', function ($circular) {
                    return $circular->published_date ? $circular->published_date->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($circular) {
                    $button = '';
                    if (auth()->user()->can('view circular')) {
                        $button .= '<a href="' . route('circulars.show', $circular->id) . '" class="btn btn-sm btn-primary" title="View">
                    ';

                        if (!Auth::user()->hasRole('EMPLOYEE')) {
                            $button .= '<i class="fa fa-eye"></i></a> ';
                        } else {
                            $button .= ' <i class="fa fa-eye"></i> View Detail</a> ';
                        }


                    }

                    if (auth()->user()->can('edit circular')) {
                        $button .= '<a href="' . route('circulars.edit', $circular->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete circular')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-circular" data-id="' . $circular->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file', 'file_hi'])
                ->make(true);
        }
    }

    public function fetchForDatatableBackend(Request $request)
    {
        if ($request->ajax()) {
            $query = Circular::with(['circularCategory', 'division'])->orderBy('id', 'desc');

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            return DataTables::of($query)
                ->addColumn('circular_category_name', function ($circular) {
                    return $circular->circularCategory ? $circular->circularCategory->name . ' (' . $circular->circularCategory->name_hi . ')' : '';
                })
                ->addColumn('file', function ($circular) {
                    if ($circular->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($circular->file_url) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->addColumn('file_hi', function ($circular) {
                    if ($circular->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($circular->file_url_hi) . " target='_BLANK'>View Document</a>";
                    }

                    return '';
                })
                ->editColumn('published_date', function ($circular) {
                    return $circular->published_date ? $circular->published_date->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($circular) {
                    $button = '';
                    if (auth()->user()->can('view circular')) {
                        $button .= '<a href="' . route('circulars.show', $circular->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>';
                    }

                    if (auth()->user()->can('edit circular')) {
                        $button .= '<a href="' . route('circulars.edit', $circular->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete circular')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-circular" data-id="' . $circular->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file', 'file_hi'])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Circulars';
        $circularCategories = $this->circularCategoryService->findAll();
        $divisions = $this->divisionService->findPublished(1);
        return view('secure.circulars.create', compact('pageTitle', 'circularCategories', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCircularRequest $request)
    {
        try {

            $circularDto = new CircularDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('division_id'),
                $request->input('category'),
                $request->input('published_date'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                auth()->user()->id
            );

            $circular = $this->circularService->create($circularDto);

            if (!$circular) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving circular.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Circular created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Circular addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Circulars';
        $circular = $this->circularService->findById($id);
        return view('secure.circulars.show', compact('circular', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Circulars';
        $circular = $this->circularService->findById($id);
        $circularCategories = $this->circularCategoryService->findAll();
        $divisions = $this->divisionService->findPublished();
        return view('secure.circulars.edit', compact('circular', 'pageTitle', 'circularCategories', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCircularRequest $request, Circular $circular)
    {
        try {
            $circularDto = new CircularDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('division_id') ?? $circular->division_id,
                $request->input('category') ?? $circular->category,
                $request->input('published_date') ?? $circular->published_date,
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                $circular->created_by,
                auth()->user()->id
            );


            $circular = $this->circularService->update($circularDto, $circular->id);

            if (!$circular) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating circular.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Circular updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Circular updation failed: ' . $e->getMessage());
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
            $circular = $this->circularService->delete($id);
            if (!$circular) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting circular.',
                ], 500);
            }

            return response()->json(['message' => 'Circular moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Circular deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Circular $circular)
    {
        try {
            $updated = $this->circularService->approve(
                $circular->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $circular->publish_remark
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
            Log::error('Circular approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Circular $circular)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $circular->is_approved == 1 || $isPublished == 1 ? 1 : $circular->is_approved;
            $remarks = $circular->is_approved == 1
                ? $circular->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $circular->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->circularService->publish(
                $circular->id,
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
            Log::error('Circular publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $circulars = $this->circularService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $circulars
        ]);
    }

    public function fetchAllForPublicDataTable(Request $request, $type = 'latest')
    {
        try {
            $categoryFilter = $request->category;
            $baseQuery = Circular::with('circularCategory')->where('is_published', 1);


            $threeMonthsAgo = Carbon::now()->subDays(90)->startOfDay();
            $query = (clone $baseQuery);


            if ($type === 'latest') {
                $query->where('published_date', '>=', $threeMonthsAgo);
            } elseif ($type === 'archive') {
                $query->where('published_date', '<=', $threeMonthsAgo);
            }

            if ($categoryFilter) {
                $query->where('category', $categoryFilter);
            }
            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
                        ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
                    // ->orWhere('division', 'LIKE', "%{$search}%")
                    // ->orWhere('division_hi', 'LIKE', "%{$search}%");
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
                    'lastUpdatedOn' => Circular::getLastUpdatedOrCreatedAt(),
                ],
                'data' => PublicCircularResource::collection($results),
            ], 200);

        } catch (\Exception $e) {
            Log::error("Circular DataTable Fetch Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }


    public function fetchAllCategory()
    {
        try {
            $categories = $this->circularCategoryService->findAll();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error("Circular Category Fetch Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}
