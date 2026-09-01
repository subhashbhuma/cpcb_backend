<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DesignationDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Designation;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    protected DesignationService $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
    }

    /**
     * Display designation listing page
     */
    public function index()
    {
        $pageTitle = 'Designation Setup';
        return view('secure.designation.index', compact('pageTitle'));
    }

    /**
     * Fetch designations for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $designations = $this->designationService->findAll();

            return DataTables::of($designations)
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
                ->addColumn('action', function ($designation) {
                    $buttons = '';

                    if (auth()->user()->can('view designation')) {
                        $buttons .= '<a href="' . route('designation.show', $designation->id) . '"
                                        class="btn btn-sm btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('edit designation')) {
                        $buttons .= '<a href="' . route('designation.edit', $designation->id) . '"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('delete designation')) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-designation"
                                        data-id="' . $designation->id . '" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action', 'status', 'approval_status'])
                ->make(true);
        }
    }

    /**
     * Show create designation form
     */
    public function create()
    {
        $pageTitle = 'Create Designation';
        return view('secure.designation.create', compact('pageTitle'));
    }

    /**
     * Store new designation
     */
    public function store(StoreDesignationRequest $request)
    {
        try {
            $dto = new DesignationDto(
                $request->title,
                $request->title_hi,
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->designationService->create($dto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Designation created successfully!',
                    'redirect_url' => route('designation.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving designation.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Designation creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Display specific designation
     */
    public function show(string $id)
    {
        $pageTitle = 'View Designation';
        $record = $this->designationService->findById($id);
        return view('secure.designation.show', compact('record', 'pageTitle'));
    }

    /**
     * Show edit designation form
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Designation';
        $record = $this->designationService->findById($id);
        return view('secure.designation.edit', compact('pageTitle', 'record'));
    }

    /**
     * Update designation
     */
    public function update(UpdateDesignationRequest $request, string $id)
    {
        try {
            $record = $this->designationService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Designation not found']);
            }

            $dto = new DesignationDto(
                $request->title,
                $request->title_hi,
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $updated = $this->designationService->update($dto, $id);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Designation updated successfully!',
                    'redirect_url' => route('designation.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving designation.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Designation update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Delete designation
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->designationService->delete($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting designation.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Designation deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Designation deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    /**
     * Approve/Reject designation
     */
    public function approve(ApproveRequest $request, string $id)
    {
        try {
            $record = $this->designationService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Designation not found']);
            }

            $updated = $this->designationService->approve(
                $id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $record->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving designation.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Publish/Unpublish designation
     */
    public function publish(PublishRequest $request, string $id)
    {
        try {
            $record = $this->designationService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Designation not found']);
            }

            $isPublished = (int) $request->input('is_published');
            $isApproved = $record->is_approved == 1 || $isPublished == 1 ? 1 : $record->is_approved;
            $remarks = $record->is_approved == 1
                ? $record->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $record->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->designationService->publish(
                $id,
                $isApproved,
                $remarks,
                $isPublished,
                $publishRemark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing designation.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Fetch published designations for public API
     */
    public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => $this->designationService->findForPublic()
        ]);
    }
}
