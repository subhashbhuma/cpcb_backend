<?php

namespace App\Http\Controllers\Secure;

use App\DTO\PublicationCategoryDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicationCategoryRequest;
use App\Http\Requests\UpdatePublicationCategoryRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\PublicationCategory;
use App\Services\PublicationCategoryServices;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class PublicationCategoryController extends Controller
{
    protected $publicationCategoryServices;
    public $carbon;
    public function __construct()
    {
        $this->publicationCategoryServices = new PublicationCategoryServices();
        $this->carbon = new Carbon();
    }

    public function index(Request $request)
    {
        $pageTitle = "Publication Category";
        return view('secure.publication_category.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $publication_category = $this->publicationCategoryServices->findAll();
            return DataTables::of($publication_category)
                ->addColumn('action', function ($publication_category) {
                    $button = '';
                    if (auth()->user()->can('view publication category')) {
                        $button .= '<a href="' . route('publication_category.show', $publication_category->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit publication category')) {
                        $button .= '<a href="' . route('publication_category.edit', $publication_category->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete publication category')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-publication_category" data-id="' . $publication_category->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })

                ->editColumn('code', function ($data) {
                    return $data->code . ' ' . '<br /><span dir="rtl">' . $data->code_hi . '</span>';
                })
                ->editColumn('title', function ($data) {
                    return $data->title . ' ' . '<br /><span dir="rtl">' . $data->title_hi . '</span>';
                })
                ->rawColumns(['action', 'code', 'title'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Publication Category';
        return view('secure.publication_category.create', compact('pageTitle'));
    }

    public function store(StorePublicationCategoryRequest $request)
    {

        try {
            $publicationCategoryDto = new PublicationCategoryDto(
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('code'))),
                strip_tags(html_entity_decode($request->input('code_hi'))),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                $this->carbon->now(),
                auth()->user()->id,
                $this->carbon->now()
            );

            $publication_category = $this->publicationCategoryServices->create($publicationCategoryDto);

            if (!$publication_category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving Publication Category.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publication Category created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Publication Category addition failed: ' . $e->getMessage());
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


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Publication Category';
        $publication_category = $this->publicationCategoryServices->findById($id);
        return view('secure.publication_category.show', compact('publication_category', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Publication Category';
        $publication_category = $this->publicationCategoryServices->findById($id);
        return view('secure.publication_category.edit', compact('publication_category', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicationCategoryRequest $request, $id)
    {
        try {
            $publicationCategory = $this->publicationCategoryServices->findById($id);
            if (!$publicationCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Publication Category not found.',
                ], 404);
            }

            $publicationCategoryDto = new PublicationCategoryDto(
                strip_tags(html_entity_decode($request->input('title'))),
                strip_tags(html_entity_decode($request->input('title_hi'))),
                strip_tags(html_entity_decode($request->input('code'))),
                strip_tags(html_entity_decode($request->input('code_hi'))),
                0, // reset is_approved
                0, // reset is_published
                null, // reset remarks
                null, // reset publish_remark
                $publicationCategory->created_by,
                $publicationCategory->created_at,
                auth()->user()->id,
                $this->carbon->now()
            );

            $updated = $this->publicationCategoryServices->update($publicationCategoryDto, $id);
            if (!$updated) {
                return response()->json([
                    'data' => $publicationCategoryDto,
                    'success' => false,
                    'message' => 'Error while updating Publication Category.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publication Category updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Publication Category updation failed: ' . $e->getMessage());
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $publication_category = $this->publicationCategoryServices->delete($id);
            if (!$publication_category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting Publication Category.',
                ], 500);
            }

            return response()->json(['message' => 'Publication Category moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Publication Category deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, $id)
    {
        try {
            $category = PublicationCategory::findOrFail($id);
            $updated = $this->publicationCategoryServices->approve(
                $category->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $category->publish_remark
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
            Log::error('Publication Category approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    public function publish(PublishRequest $request, $id)
    {
        try {
            $category = PublicationCategory::findOrFail($id);
            $isPublished = (int) $request->input('is_published');
            $isApproved = $category->is_approved == 1 || $isPublished == 1 ? 1 : $category->is_approved;
            $remarks = $category->is_approved == 1
                ? $category->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $category->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->publicationCategoryServices->publish(
                $category->id,
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
            Log::error('Publication Category publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $publication_category = $this->publicationCategoryServices->findForPublic();

        return response()->json([
            'success' => true,
            'data' => $publication_category,
            'lastUpdatedOn' => PublicationCategory::getLastUpdatedOrCreatedAt(),
        ]);
    }

    public function fetchByIdForPublic($id)
    {
        $publication_category = $this->publicationCategoryServices->fetchByIdForPublic($id);

        return response()->json([
            'success' => true,
            'data' => $publication_category
        ]);
    }
}
