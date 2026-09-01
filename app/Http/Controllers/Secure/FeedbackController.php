<?php

namespace App\Http\Controllers\Secure;

use App\DTO\FeedbackDto;
use App\DTO\FeedbackHistoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Http\Requests\StoreFeedbackHistoryRequest;
use App\Services\FeedbackService;
use App\Services\FeedbackHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    protected $feedbackService;
    protected $feedbackHistoryService;

    public function __construct()
    {
        $this->feedbackService = new FeedbackService();
        $this->feedbackHistoryService = new FeedbackHistoryService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Feedback List';
        return view('secure.feedback.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->feedbackService->findAll();
            return DataTables::of($data)
                 ->addIndexColumn()
                 ->addColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view feedback')) {
                        $btn .= '<a href="' . route('feedback.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit feedback')) {
                        $btn .= '<a href="' . route('feedback.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete feedback')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('feedback.destroy', $row->id) . '" title="Delete Record">
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
        $pageTitle = 'View Feedback';
        $feedback = $this->feedbackService->findById($id);
        return view('secure.feedback.show', compact('feedback', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Feedback';
        $feedback = $this->feedbackService->findById($id);
        return view('secure.feedback.edit', compact('feedback', 'pageTitle'));
    }




    public function update(UpdateFeedbackRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $feedback = $this->feedbackService->findById($id);
            if (!$feedback) {
                return response()->json(['success' => false, 'message' => 'Feedback not found']);
            }

            $dto = new FeedbackDto(
                $request->full_name,
                $request->email,
                $request->phone,
                $request->message,
                $feedback->file_name,
                $request->status
            );

            $result = $this->feedbackService->update($dto, $id);

            if ($result) {
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Feedback Updated Successfully']);
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Feedback update failed: ' . $e->getMessage());
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
            $result = $this->feedbackService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Feedback deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function sendRevert(StoreFeedbackHistoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $feedback = $this->feedbackService->findById($id);
            if (!$feedback) {
                return response()->json(['success' => false, 'message' => 'Feedback not found']);
            }

            $dto = new FeedbackHistoryDto(
                $id,
                $request->revert_message,
                auth()->id()
            );

            $this->feedbackHistoryService->create($dto, $feedback);

            // Update status if provided
            if ($request->status) {
                $this->feedbackService->updateStatus($id, $request->status);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Response sent successfully and email notification sent!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Feedback revert failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
