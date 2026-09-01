<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DirectionStateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDirectionStateRequest;
use App\Http\Requests\UpdateDirectionStateRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\DirectionStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DirectionState;

class DirectionStateController extends Controller
{
    protected $directionStateService;

    public function __construct()
    {
        $this->directionStateService = new DirectionStateService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Direction State List';
        return view('secure.direction_state.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->directionStateService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view direction state')) {
                        $btn .= '<a href="' . route('direction_state.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit direction state')) {
                        $btn .= '<a href="' . route('direction_state.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete direction state')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('direction_state.destroy', $row->id) . '" title="Delete Record">
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
        $pageTitle = 'Add Direction State';
        return view('secure.direction_state.create', compact('pageTitle'));
    }

    public function store(StoreDirectionStateRequest $request)
    {
        try {
            $dto = new DirectionStateDto(
                $request->title,
                $request->title_hi,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                now()
            );

            $result = $this->directionStateService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record created successfully!',
                    'redirect_url' => route('direction_state.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving record.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Direction State creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Direction State';
        $directionState = $this->directionStateService->findById($id);
        return view('secure.direction_state.show', compact('directionState', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Direction State';
        $directionState = $this->directionStateService->findById($id);
        return view('secure.direction_state.edit', compact('directionState', 'pageTitle'));
    }

    public function update(UpdateDirectionStateRequest $request, $id)
    {
        try {
            $directionState = $this->directionStateService->findById($id);
            if (!$directionState) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new DirectionStateDto(
                $request->title,
                $request->title_hi,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $directionState->created_by,
                $directionState->created_at,
                auth()->id(),
                now()
            );

            $result = $this->directionStateService->update($dto, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully!',
                    'redirect_url' => route('direction_state.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while updating record.'
            ]);
        } catch (\Exception $e) {
            Log::error('Direction State update failed: ' . $e->getMessage());
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
            $result = $this->directionStateService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Direction State deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, DirectionState $directionState)
    {
        try {
            $updated = $this->directionStateService->approve(
                $directionState->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $directionState->publish_remark
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
            Log::error('Direction State approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, DirectionState $directionState)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $directionState->is_approved == 1 || $isPublished == 1 ? 1 : $directionState->is_approved;
            $remarks = $directionState->is_approved == 1
                ? $directionState->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $directionState->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directionStateService->publish(
                $directionState->id,
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
            Log::error('Direction State publishing failed: ' . $e->getMessage());
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
        $data = $this->directionStateService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
