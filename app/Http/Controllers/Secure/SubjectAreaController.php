<?php

namespace App\Http\Controllers\Secure;

use App\DTO\SubjectAreaDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectAreaRequest;
use App\Http\Requests\UpdateSubjectAreaRequest;
use App\Models\SubjectArea;
use App\Services\SubjectAreaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use Yajra\DataTables\Facades\DataTables;

class SubjectAreaController extends Controller
{
    protected SubjectAreaService $subjectAreaService;

    public function __construct()
    {
        $this->subjectAreaService = new SubjectAreaService();
    }

    /**
     * Display subject area listing page
     */
    public function index()
    {
        $pageTitle = 'Subject Area Setup';
        return view('secure.technical_report.subject_area.index', compact('pageTitle'));
    }

    /**
     * Fetch subject areas for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $subjectAreas = $this->subjectAreaService->findAll();

            return DataTables::of($subjectAreas)
                ->addColumn('action', function ($subjectArea) {
                    $buttons = '';

                    if (auth()->user()->can('view subject area')) {
                        $buttons .= '<a href="' . route('subject-area.show', $subjectArea->id) . '"
                                        class="btn btn-sm btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('edit subject area')) {
                        $buttons .= '<a href="' . route('subject-area.edit', $subjectArea->id) . '"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('delete subject area')) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-subject-area"
                                        data-id="' . $subjectArea->id . '" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show create subject area form
     */
    public function create()
    {
        $pageTitle = 'Create Subject Area';
        return view('secure.technical_report.subject_area.create', compact('pageTitle'));
    }

    /**
     * Store new subject area
     */
    public function store(StoreSubjectAreaRequest $request)
    {
        DB::beginTransaction();
        try {
            $dto = new SubjectAreaDto(
                $request->title,
                $request->title_hi,
                0,
                0,
                null,
                null,
                auth()->id()
            );

            $result = $this->subjectAreaService->create($dto);

            if ($result) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Subject area created successfully!',
                    'redirect_url' => route('subject-area.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving subject area.'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subject area creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Display specific subject area
     */
    public function show(string $id)
    {
        $pageTitle = 'View Subject Area';
        $subjectArea = $this->subjectAreaService->findById($id);

        return view('secure.technical_report.subject_area.show', compact('subjectArea', 'pageTitle'));
    }

    /**
     * Show edit subject area form
     */
    public function edit(SubjectArea $subjectArea)
    {
        $pageTitle = 'Edit Subject Area';
        return view('secure.technical_report.subject_area.edit', compact('pageTitle', 'subjectArea'));
    }

    /**
     * Update subject area
     */
    public function update(UpdateSubjectAreaRequest $request, SubjectArea $subjectArea)
    {
        DB::beginTransaction();
        try {
            $dto = new SubjectAreaDto(
                $request->title,
                $request->title_hi,
                0,
                0,
                null,
                null,
                $subjectArea->created_by,
                auth()->id()
            );

            $updated = $this->subjectAreaService->update($dto, $subjectArea->id);

            if ($updated) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Subject area updated successfully!',
                    'redirect_url' => route('subject-area.index')
                ]);
            }

            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error while saving subject area.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subject area update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Delete subject area
     */
    public function destroy(SubjectArea $subjectArea)
    {
        DB::beginTransaction();
        try {
            $deleted = $this->subjectAreaService->delete($subjectArea->id);

            if (!$deleted) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting subject area.'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Subject area deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    /**
     * Approve/Reject subject area
     */
    public function approve(ApproveRequest $request, SubjectArea $subjectArea)
    {
        try {
            $updated = $this->subjectAreaService->approve(
                $subjectArea->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $subjectArea->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving subject area.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Subject area approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Publish/Unpublish subject area
     */
    public function publish(PublishRequest $request, SubjectArea $subjectArea)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $subjectArea->is_approved == 1 || $isPublished == 1 ? 1 : $subjectArea->is_approved;
            $remarks = $subjectArea->is_approved == 1
                ? $subjectArea->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $subjectArea->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->subjectAreaService->publish($subjectArea->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing subject area.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Subject area publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Fetch published subject areas for public API
     */
    public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => $this->subjectAreaService->findForPublic()
        ]);
    }
}
