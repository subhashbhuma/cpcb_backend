<?php

namespace App\Http\Controllers\Secure;

use App\DTO\ComplaintDto;
use App\DTO\ComplaintHistoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateComplaintRequest;
use App\Http\Requests\StoreComplaintHistoryRequest;
use App\Services\ComplaintService;
use App\Services\ComplaintHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ComplaintController extends Controller
{
    protected $ComplaintService;
    protected $ComplaintHistoryService;

    public function __construct()
    {
        $this->ComplaintService = new ComplaintService();
        $this->ComplaintHistoryService = new ComplaintHistoryService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Complaint List';
        return view('secure.complaint.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->ComplaintService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subject', function ($row) {
                    return $row->complaintSubject->title ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view complaint')) {
                        $btn .= '<a href="' . route('complaint.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit complaint')) {
                        $btn .= '<a href="' . route('complaint.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete complaint')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('complaint.destroy', $row->id) . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Complaint';
        $Complaint = $this->ComplaintService->findById($id);
        return view('secure.complaint.show', compact('Complaint', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Complaint';
        $Complaint = $this->ComplaintService->findById($id);
        return view('secure.complaint.edit', compact('Complaint', 'pageTitle'));
    }




    public function update(UpdateComplaintRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $Complaint = $this->ComplaintService->findById($id);
            if (!$Complaint) {
                return response()->json(['success' => false, 'message' => 'Complaint not found']);
            }

            $dto = new ComplaintDto(
                $request->complaint_subject_id,
                $request->full_name,
                $request->email,
                $request->phone,
                $request->message,
                $Complaint->file_name,
                $request->status
            );

            $result = $this->ComplaintService->update($dto, $id);

            if ($result) {
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Complaint Updated Successfully']);
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complaint update failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->ComplaintService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Complaint deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function sendRevert(StoreComplaintHistoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $Complaint = $this->ComplaintService->findById($id);
            if (!$Complaint) {
                return response()->json(['success' => false, 'message' => 'Complaint not found']);
            }

            $dto = new ComplaintHistoryDto(
                $id,
                $request->revert_message,
                auth()->id()
            );

            $this->ComplaintHistoryService->create($dto, $Complaint);

            // Update status if provided
            if ($request->status) {
                $this->ComplaintService->updateStatus($id, $request->status);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Response sent successfully and email notification sent!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complaint revert failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
