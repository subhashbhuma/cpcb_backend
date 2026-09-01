<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DirectionActTypeDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDirectionActTypeRequest;
use App\Http\Requests\UpdateDirectionActTypeRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\DirectionActTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DirectionActType;

class DirectionActTypeController extends Controller
{
    protected $directionActTypeService;

    public function __construct()
    {
        $this->directionActTypeService = new DirectionActTypeService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Direction Act Type List';
        return view('secure.direction_act_type.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->directionActTypeService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view direction act type')) {
                        $btn .= '<a href="' . route('direction_act_type.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit direction act type')) {
                        $btn .= '<a href="' . route('direction_act_type.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete direction act type')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('direction_act_type.destroy', $row->id) . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $btn;
                })
                // ->addColumn('status', function ($row) {
                //     if ($row->is_published) {
                //         return '<span class="kt-badge kt-badge--success kt-badge--inline">Published</span>';
                //     } else {
                //         return '<span class="kt-badge kt-badge--warning kt-badge--inline">Draft</span>';
                //     }
                // })
                //  ->addColumn('approval_status', function ($row) {
                //     if ($row->is_approved == 1) {
                //          return '<span class="kt-badge kt-badge--success kt-badge--inline">Approved</span>';
                //     } elseif ($row->is_approved == 2) {
                //          return '<span class="kt-badge kt-badge--danger kt-badge--inline">Rejected</span>';
                //     }else{
                //         return '<span class="kt-badge kt-badge--warning kt-badge--inline">Pending</span>';
                //     }
                // })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Direction Act Type';
        return view('secure.direction_act_type.create', compact('pageTitle'));
    }

    public function store(StoreDirectionActTypeRequest $request)
    {
        try {
            $dto = new DirectionActTypeDto(
                $request->title,
                $request->title_hi,
                0, // Default is_approved
                0, // Default is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                now(),
                auth()->id(),
                now()
            );

            $result = $this->directionActTypeService->create($dto);

            if ($result) {
                return response()->json(['success' => true, 'message' => 'Record created successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            Log::error('Direction Act Type creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Direction Act Type';
        $directionActType = $this->directionActTypeService->findById($id);
        return view('secure.direction_act_type.show', compact('directionActType', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Direction Act Type';
        $directionActType = $this->directionActTypeService->findById($id);
        return view('secure.direction_act_type.edit', compact('directionActType', 'pageTitle'));
    }

    public function update(UpdateDirectionActTypeRequest $request, $id)
    {
        try {
            $directionActType = $this->directionActTypeService->findById($id);
            if (!$directionActType) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new DirectionActTypeDto(
                $request->title,
                $request->title_hi,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $directionActType->created_by,
                $directionActType->created_at,
                auth()->id(),
                now()
            );

            $result = $this->directionActTypeService->update($dto, $id);

            if ($result) {
                return response()->json(['success' => true, 'message' => 'Record updated successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            Log::error('Direction Act Type update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->directionActTypeService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Direction Act Type deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, DirectionActType $directionActType)
    {
        try {
            $updated = $this->directionActTypeService->approve(
                $directionActType->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $directionActType->publish_remark
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
            Log::error('Direction Act Type approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, DirectionActType $directionActType)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $directionActType->is_approved == 1 || $isPublished == 1 ? 1 : $directionActType->is_approved;
            $remarks = $directionActType->is_approved == 1
                ? $directionActType->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $directionActType->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directionActTypeService->publish(
                $directionActType->id,
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
            Log::error('Direction Act Type publishing failed: ' . $e->getMessage());
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
        $data = $this->directionActTypeService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

}
