<?php

namespace App\Http\Controllers\Secure;

use App\DTO\QueryFormSubjectDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQueryFormSubjectRequest;
use App\Http\Requests\UpdateQueryFormSubjectRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicQueryFormSubjectResource;
use App\Services\QueryFormSubjectService;
use App\Services\DivisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class QueryFormSubjectController extends Controller
{
    protected $queryFormSubjectService;
    protected $divisionService;

    public function __construct(QueryFormSubjectService $queryFormSubjectService, DivisionService $divisionService)
    {
        $this->queryFormSubjectService = $queryFormSubjectService;
        $this->divisionService = $divisionService;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Query Form Subject List';
        return view('secure.query_form_subject.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->queryFormSubjectService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('division_name', function ($row) {
                    return $row->division ? $row->division->title : '—';
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
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view query form subject')) {
                        $btn .= '<a href="' . route('query_form_subject.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit query form subject')) {
                        $btn .= '<a href="' . route('query_form_subject.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete query form subject')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('query_form_subject.destroy', $row->id) . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Query Form Subject';
        $divisions = $this->divisionService->findPublished();
        return view('secure.query_form_subject.create', compact('pageTitle', 'divisions'));
    }

    public function store(StoreQueryFormSubjectRequest $request)
    {
        try {
            $dto = new QueryFormSubjectDto(
                $request->title,
                $request->title_hi,
                $request->division_id,
                $request->name,
                $request->name_hi,
                $request->email_id,
                0, // Default is_approved
                0, // Reset on update/store
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->queryFormSubjectService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Query Form Subject created successfully!',
                    'redirect_url' => route('query_form_subject.index')
                ], 201);
            }

            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        } catch (\Exception $e) {
            Log::error('Query Form Subject creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Query Form Subject';
        $record = $this->queryFormSubjectService->findById($id);
        return view('secure.query_form_subject.show', compact('record', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Query Form Subject';
        $record = $this->queryFormSubjectService->findById($id);
        $divisions = $this->divisionService->findPublished();
        return view('secure.query_form_subject.edit', compact('record', 'pageTitle', 'divisions'));
    }

    public function update(UpdateQueryFormSubjectRequest $request, $id)
    {
        try {
            $record = $this->queryFormSubjectService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Query Form Subject not found']);
            }

            $dto = new QueryFormSubjectDto(
                $request->title,
                $request->title_hi,
                $request->division_id,
                $request->name,
                $request->name_hi,
                $request->email_id,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $result = $this->queryFormSubjectService->update($dto, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Query Form Subject updated successfully!',
                    'redirect_url' => route('query_form_subject.index')
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        } catch (\Exception $e) {
            Log::error('Query Form Subject update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->queryFormSubjectService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Query Form Subject deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $record = $this->queryFormSubjectService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $updated = $this->queryFormSubjectService->approve(
                $id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $record->publish_remark
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
            ]);
        } catch (\Exception $e) {
            Log::error('Query Form Subject approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, string $id)
    {
        try {
            $record = $this->queryFormSubjectService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $isPublished = (int) $request->input('is_published');
            $isApproved = $record->is_approved == 1 || $isPublished == 1 ? 1 : $record->is_approved;
            $remarks = $record->is_approved == 1
                ? $record->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $record->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->queryFormSubjectService->publish(
                $id,
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
            ]);
        } catch (\Exception $e) {
            Log::error('Query Form Subject publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $limit = $request->get('limit', null);
        $data = $this->queryFormSubjectService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => PublicQueryFormSubjectResource::collection($data),
        ]);
    }
}
