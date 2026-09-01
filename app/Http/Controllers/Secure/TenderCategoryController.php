<?php

namespace App\Http\Controllers\Secure;

use App\DTO\TenderCategoryDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenderCategoryRequest;
use App\Http\Requests\UpdateTenderCategoryRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\TenderCategory;
use App\Services\TenderCategoryServices;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class TenderCategoryController extends Controller
{
    protected $tenderCategoryServices;
    public $carbon;
    public function __construct()
    {
        $this->tenderCategoryServices = new TenderCategoryServices();
        $this->carbon = new Carbon();
    }

    public function index(Request $request)
    {
        $pageTitle = "Tender Category";
        return view('secure.tenders.tender_category.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $tender_category = $this->tenderCategoryServices->findAll();
            return DataTables::of($tender_category)
                ->addColumn('action', function ($tender_category) {
                    $button = '';
                    if (auth()->user()->can('view tender category')) {
                        $button .= '<a href="' . route('tender_category.show', $tender_category->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit tender category')) {
                        $button .= '<a href="' . route('tender_category.edit', $tender_category->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete tender category')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-tender_category" data-id="' . $tender_category->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Tender Category';
        return view('secure.tenders.tender_category.create', compact('pageTitle'));
    }

    public function store(StoreTenderCategoryRequest $request)
    {

        try {
            $tenderCategoryDto = new TenderCategoryDto(
                $request->input('title'),
                $request->input('title_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                $this->carbon->now(),
                auth()->user()->id,
                $this->carbon->now()
            );

            $tender_category = $this->tenderCategoryServices->create($tenderCategoryDto);

            if (!$tender_category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving Tender Category.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tender Category created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Tender Category addition failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Tender Category';
        $tender_category = $this->tenderCategoryServices->findById($id);
        return view('secure.tenders.tender_category.show', compact('tender_category', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Tender Category';
        $tender_category = $this->tenderCategoryServices->findById($id);
        return view('secure.tenders.tender_category.edit', compact('tender_category', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenderCategoryRequest $request, TenderCategory $tenderCategory)
    {
        try {
            $tenderCategoryDto = new TenderCategoryDto(

                $request->input('title'),
                $request->input('title_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                $tenderCategory->created_by,
                $tenderCategory->created_at,
                auth()->user()->id,
                $this->carbon->now()
            );

            $updated = $this->tenderCategoryServices->update($tenderCategoryDto, $tenderCategory->id);
            if (!$updated) {
                return response()->json([
                    'data' => $tenderCategoryDto,
                    'success' => false,
                    'message' => 'Error while updating Tender Category.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tender Category updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Tender Category updation failed: ' . $e->getMessage());
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
            $tender_category = $this->tenderCategoryServices->delete($id);
            if (!$tender_category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting Tender Category.',
                ], 500);
            }

            return response()->json(['message' => 'Tender Category moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Tender Category deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, TenderCategory $tenderCategory)
    {
        try {
            $updated = $this->tenderCategoryServices->approve(
                $tenderCategory->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $tenderCategory->publish_remark
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
            Log::error('Tender Category approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    public function publish(PublishRequest $request, TenderCategory $tenderCategory)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $tenderCategory->is_approved == 1 || $isPublished == 1 ? 1 : $tenderCategory->is_approved;
            $remarks = $tenderCategory->is_approved == 1
                ? $tenderCategory->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $tenderCategory->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->tenderCategoryServices->publish(
                $tenderCategory->id,
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
            Log::error('Tender Category publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $tender_category = $this->tenderCategoryServices->findForPublic();

        return response()->json([
            'success' => true,
            'data' => $tender_category,
            'lastUpdatedOn' => TenderCategory::getLastUpdatedOrCreatedAt(),
        ]);
    }

    public function fetchByIdForPublic($id)
    {
        $tender_category = $this->tenderCategoryServices->fetchByIdForPublic($id);

        return response()->json([
            'success' => true,
            'data' => $tender_category
        ]);
    }
}
