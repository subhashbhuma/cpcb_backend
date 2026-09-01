<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DirectionSubjectDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDirectionSubjectRequest;
use App\Http\Requests\UpdateDirectionSubjectRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\DirectionSubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DirectionSubject;
use App\Models\DirectionActType;

class DirectionSubjectController extends Controller
{
    protected $directionSubjectService;

    public function __construct()
    {
        $this->directionSubjectService = new DirectionSubjectService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Direction Subject List';
        return view('secure.direction_subject.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->directionSubjectService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view direction subject')) {
                        $btn .= '<a href="' . route('direction_subject.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit direction subject')) {
                        $btn .= '<a href="' . route('direction_subject.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete direction subject')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('direction_subject.destroy', $row->id) . '" title="Delete Record">
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
        $pageTitle = 'Add Direction Subject';
        $actTypes = DirectionActType::where('is_published', 1)->get();
        return view('secure.direction_subject.create', compact('pageTitle', 'actTypes'));
    }

    public function store(StoreDirectionSubjectRequest $request)
    {
        try {
            $dto = new DirectionSubjectDto(
                $request->title,
                $request->title_hi,
                $request->direction_act_type_id,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                now()
            );

            $result = $this->directionSubjectService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record created successfully!',
                    'redirect_url' => route('direction_subject.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving record.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Direction Subject creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Direction Subject';
        $directionSubject = $this->directionSubjectService->findById($id);
        return view('secure.direction_subject.show', compact('directionSubject', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Direction Subject';
        $directionSubject = $this->directionSubjectService->findById($id);
        $actTypes = DirectionActType::where('is_published', 1)->get();
        return view('secure.direction_subject.edit', compact('directionSubject', 'pageTitle', 'actTypes'));
    }

    public function update(UpdateDirectionSubjectRequest $request, $id)
    {
        try {
            $directionSubject = $this->directionSubjectService->findById($id);
            if (!$directionSubject) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new DirectionSubjectDto(
                $request->title,
                $request->title_hi,
                $request->direction_act_type_id,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $directionSubject->created_by,
                $directionSubject->created_at,
                auth()->id(),
                now()
            );

            $result = $this->directionSubjectService->update($dto, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully!',
                    'redirect_url' => route('direction_subject.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while updating record.'
            ]);
        } catch (\Exception $e) {
            Log::error('Direction Subject update failed: ' . $e->getMessage());
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
            $result = $this->directionSubjectService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Direction Subject deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, DirectionSubject $directionSubject)
    {
        try {
            $updated = $this->directionSubjectService->approve(
                $directionSubject->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $directionSubject->publish_remark
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
            Log::error('Direction Subject approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, DirectionSubject $directionSubject)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $directionSubject->is_approved == 1 || $isPublished == 1 ? 1 : $directionSubject->is_approved;
            $remarks = $directionSubject->is_approved == 1
                ? $directionSubject->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $directionSubject->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directionSubjectService->publish(
                $directionSubject->id,
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
            Log::error('Direction Subject publishing failed: ' . $e->getMessage());
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
        $data = $this->directionSubjectService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
