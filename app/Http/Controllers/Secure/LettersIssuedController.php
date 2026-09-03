<?php

namespace App\Http\Controllers\Secure;

use App\DTO\LettersIssuedDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLettersIssuedRequest;
use App\Http\Requests\UpdateLettersIssuedRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\LettersIssued;
use App\Services\LettersIssuedService;
use App\Services\DirectionStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LettersIssuedController extends Controller
{
    protected $service;
    protected $stateService;

    public function __construct()
    {
        $this->service = new LettersIssuedService();
        $this->stateService = new DirectionStateService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Letters issues';
        return view('secure.letters_issued.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = LettersIssued::query();

            if ($request->has('type_filter') && $request->type_filter) {
                $query->where('type', $request->type_filter);
            }

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $data = $query->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addColumn('file_name', function ($record) {
                    if ($record->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($record->file_url) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
                })
                ->addColumn('file_name_hi', function ($record) {
                    if ($record->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($record->file_url_hi) . " target='_BLANK' class='btn btn-xs btn-info'>View</a>";
                    }
                    return '';
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
                ->addColumn('action', function ($record) {
                    $button = '';
                    if (auth()->user()->can('view letters_issued')) {
                        $button .= '<a href="' . route('letters-issued.show', $record->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit letters_issued')) {
                        $button .= '<a href="' . route('letters-issued.edit', $record->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete letters_issued')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-letter" data-id="' . $record->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function fetchAllForPublicDataTable(Request $request)
    {
        try {
            $type = $request->input('type');
            $query = LettersIssued::where('is_published', 1);

            if ($type) {
                $query->where('type', $type);
            }

            // --- SEARCH LOGIC ---
            if ($request->filled('search')) {
                $search = $request->search;
                $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
                        ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
                });
            }

            // --- SORTING & PAGINATION ---
            $sortField = $request->get('sort', 'publish_date');
            $sortOrder = $request->get('order', 'desc');
            $perPage = (int) $request->get('per_page', 10);

            $results = $query->orderBy($sortField, $sortOrder)->paginate($perPage);

            // --- METADATA ---
            $lastUpdated = LettersIssued::getLastUpdatedOrCreatedAt();

            $totalQuery = LettersIssued::where('is_published', 1);
            if ($type) {
                $totalQuery->where('type', $type);
            }
            $totalRecords = $totalQuery->count();

            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalRecords,
                    'filtered_count' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => $lastUpdated,
                ],
                'data' => \App\Http\Resources\PublicLettersIssuedResource::collection($results),
            ], 200);

        } catch (\Exception $e) {
            \Log::error("LettersIssued DataTable Fetch Error: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Letters issues';
        $states = $this->stateService->findAll();
        $types = \Config::get('constants.LETTER_ISSUED_TYPE');
        return view('secure.letters_issued.create', compact('pageTitle', 'states', 'types'));
    }

    public function store(StoreLettersIssuedRequest $request)
    {
        try {
            $dto = new LettersIssuedDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('type'),
                $request->input('publish_date'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id(),
                $request->input('direction_state_id', [])
            );

            $result = $this->service->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving letter.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Letter issued created successfully!',
                'redirect_url' => route('letters-issued.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('LettersIssued addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Letters issues';
        $record = $this->service->findById($id);

        return view('secure.letters_issued.show', compact('record', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Letters issues';
        $record = $this->service->findById($id);
        $states = $this->stateService->findAll();
        $types = \Config::get('constants.LETTER_ISSUED_TYPE');

        $selectedStates = $record->direction_state_id ? explode(',', $record->direction_state_id) : [];

        return view('secure.letters_issued.edit', compact(
            'record',
            'pageTitle',
            'states',
            'selectedStates',
            'types'
        ));
    }

    public function update(UpdateLettersIssuedRequest $request, $id)
    {
        try {
            $record = $this->service->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new LettersIssuedDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('type'),
                $request->input('publish_date'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id(),
                $request->input('direction_state_id', [])
            );

            $updated = $this->service->update($dto, $id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating letter.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Letter issued updated successfully!',
                'redirect_url' => route('letters-issued.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('LettersIssued updates failed: ' . $e->getMessage());
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
            $result = $this->service->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting letter.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Letter moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Letter deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, LettersIssued $lettersIssued)
    {
        try {
            $updated = $this->service->approve(
                $lettersIssued->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $lettersIssued->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving letter.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('LettersIssued approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function publish(PublishRequest $request, LettersIssued $lettersIssued)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $lettersIssued->is_approved == 1 || $isPublished == 1 ? 1 : $lettersIssued->is_approved;
            $remarks = $lettersIssued->is_approved == 1
                ? $lettersIssued->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $lettersIssued->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->service->publish(
                $lettersIssued->id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing letter.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('LettersIssued publishing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
