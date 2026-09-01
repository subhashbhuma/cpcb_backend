<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DirectionCategoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDirectionCategoryRequest;
use App\Http\Requests\UpdateDirectionCategoryRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\DirectionCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DirectionCategory;
use App\Models\DirectionActType;

class DirectionCategoryController extends Controller
{
    protected $directionCategoryService;

    public function __construct()
    {
        $this->directionCategoryService = new DirectionCategoryService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Direction Category List';
        return view('secure.direction_category.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->directionCategoryService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view direction category')) {
                        $btn .= '<a href="' . route('direction_category.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit direction category')) {
                        $btn .= '<a href="' . route('direction_category.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete direction category')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('direction_category.destroy', $row->id) . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $btn;
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
                ->rawColumns(['action', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Direction Category';
        $actTypes = DirectionActType::where('is_published', 1)->get();
        return view('secure.direction_category.create', compact('pageTitle', 'actTypes'));
    }

    public function store(StoreDirectionCategoryRequest $request)
    {
        try {
            $dto = new DirectionCategoryDto(
                $request->title,
                $request->title_hi,
                $request->direction_act_type_id,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                now(),
                auth()->id(),
                now()
            );

            $result = $this->directionCategoryService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record created successfully!',
                    'redirect_url' => route('direction_category.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving record.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Direction Category creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Direction Category';
        $directionCategory = $this->directionCategoryService->findById($id);
        return view('secure.direction_category.show', compact('directionCategory', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Direction Category';
        $directionCategory = $this->directionCategoryService->findById($id);
        $actTypes = DirectionActType::where('is_published', 1)->get();
        return view('secure.direction_category.edit', compact('directionCategory', 'pageTitle', 'actTypes'));
    }

    public function update(UpdateDirectionCategoryRequest $request, $id)
    {
        try {
            $directionCategory = $this->directionCategoryService->findById($id);
            if (!$directionCategory) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new DirectionCategoryDto(
                $request->title,
                $request->title_hi,
                $request->direction_act_type_id,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $directionCategory->created_by,
                $directionCategory->created_at,
                auth()->id(),
                now()
            );

            $result = $this->directionCategoryService->update($dto, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully!',
                    'redirect_url' => route('direction_category.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while updating record.'
            ]);
        } catch (\Exception $e) {
            Log::error('Direction Category update failed: ' . $e->getMessage());
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
            $result = $this->directionCategoryService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Direction Category deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, DirectionCategory $directionCategory)
    {
        try {
            $updated = $this->directionCategoryService->approve(
                $directionCategory->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $directionCategory->publish_remark
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
            Log::error('Direction Category approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, DirectionCategory $directionCategory)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $directionCategory->is_approved == 1 || $isPublished == 1 ? 1 : $directionCategory->is_approved;
            $remarks = $directionCategory->is_approved == 1
                ? $directionCategory->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $directionCategory->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directionCategoryService->publish(
                $directionCategory->id,
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
            Log::error('Direction Category publishing failed: ' . $e->getMessage());
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
        $data = $this->directionCategoryService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
