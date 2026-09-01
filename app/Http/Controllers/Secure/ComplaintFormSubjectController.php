<?php

namespace App\Http\Controllers\Secure;

use App\DTO\ComplaintFormSubjectDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintFormSubjectRequest;
use App\Http\Requests\UpdateComplaintFormSubjectRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\ComplaintFormSubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Config;

class ComplaintFormSubjectController extends Controller
{
    protected $complaintFormSubjectService;

    public function __construct(ComplaintFormSubjectService $complaintFormSubjectService)
    {
        $this->complaintFormSubjectService = $complaintFormSubjectService;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Complaint Form Subject List';
        return view('secure.complaint_form_subject.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->complaintFormSubjectService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
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
                    if (auth()->user()->can('view complaint form subject')) {
                        $btn .= '<a href="' . route('complaint_form_subject.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit complaint form subject')) {
                        $btn .= '<a href="' . route('complaint_form_subject.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete complaint form subject')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('complaint_form_subject.destroy', $row->id) . '" title="Delete Record">
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
        $pageTitle = 'Add Complaint Form Subject';
        return view('secure.complaint_form_subject.create', compact('pageTitle'));
    }

    public function store(StoreComplaintFormSubjectRequest $request)
    {
        try {
            $dto = new ComplaintFormSubjectDto(
                $request->title,
                $request->title_hi,
                0, // Default is_approved
                0, // is_published (reset on update/store)
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->complaintFormSubjectService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Complaint Form Subject created successfully!',
                    'redirect_url' => route('complaint_form_subject.index')
                ], 201);
            }

            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        } catch (\Exception $e) {
            Log::error('Complaint Form Subject creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Complaint Form Subject';
        $record = $this->complaintFormSubjectService->findById($id);
        return view('secure.complaint_form_subject.show', compact('record', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Complaint Form Subject';
        $record = $this->complaintFormSubjectService->findById($id);
        return view('secure.complaint_form_subject.edit', compact('record', 'pageTitle'));
    }

    public function update(UpdateComplaintFormSubjectRequest $request, $id)
    {
        try {
            $record = $this->complaintFormSubjectService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Complaint Form Subject not found']);
            }

            $dto = new ComplaintFormSubjectDto(
                $request->title,
                $request->title_hi,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $result = $this->complaintFormSubjectService->update($dto, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Complaint Form Subject updated successfully!',
                    'redirect_url' => route('complaint_form_subject.index')
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        } catch (\Exception $e) {
            Log::error('Complaint Form Subject update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->complaintFormSubjectService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Complaint Form Subject deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $record = $this->complaintFormSubjectService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $updated = $this->complaintFormSubjectService->approve(
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
            Log::error('Complaint Form Subject approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, string $id)
    {
        try {
            $record = $this->complaintFormSubjectService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $isPublished = (int) $request->input('is_published');
            $isApproved = $record->is_approved == 1 || $isPublished == 1 ? 1 : $record->is_approved;
            $remarks = $record->is_approved == 1
                ? $record->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $record->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->complaintFormSubjectService->publish(
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
            Log::error('Complaint Form Subject publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $limit = $request->get('limit', null);
        $data = $this->complaintFormSubjectService->findForPublic($limit);
        $file_name_en = Config::get('constants.GUIDELINES_FOR_COMPLAIN_FILE_EN_NAME');
        $file_name_hi = Config::get('constants.GUIDELINES_FOR_COMPLAIN_FILE_HI_NAME');
        return response()->json([
            'success' => true,
            'data' => $data,
            'file_paths' => [
                'file_path_en' => $file_name_en ? base64_encode(Config::get('file_paths')['GUIDELINES_FOR_COMPLAIN_FILE_EN_PATH'] . '/' . $file_name_en) : null,
                'file_path_hi' => $file_name_hi ? base64_encode(Config::get('file_paths')['GUIDELINES_FOR_COMPLAIN_FILE_HI_PATH'] . '/' . $file_name_hi) : null
            ]
        ]);
    }
}
