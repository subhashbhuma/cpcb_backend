<?php

namespace App\Http\Controllers\Secure;

use App\DTO\PageCategoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageCategoryRequest;
use App\Http\Requests\UpdatePageCategoryRequest;
use App\Services\PageCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PageCategory;

class PageCategoryController extends Controller
{
    protected $pageCategoryService;

    public function __construct()
    {
        $this->pageCategoryService = new PageCategoryService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Page Category List';
        return view('secure.page_category.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->pageCategoryService->findAll();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view page category')) {
                        $btn .= '<a href="' . route('page_category.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit page category')) {
                        $btn .= '<a href="' . route('page_category.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete page category')) {
                        $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-url="' . route('page_category.destroy', $row->id) . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_published) {
                        return '<span class="kt-badge kt-badge--success kt-badge--inline">Published</span>';
                    } else {
                        return '<span class="kt-badge kt-badge--warning kt-badge--inline">Draft</span>';
                    }
                })
                 ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved == 1) {
                         return '<span class="kt-badge kt-badge--success kt-badge--inline">Approved</span>';
                    } elseif ($row->is_approved == 2) {
                         return '<span class="kt-badge kt-badge--danger kt-badge--inline">Rejected</span>';
                    }else{
                        return '<span class="kt-badge kt-badge--warning kt-badge--inline">Pending</span>';
                    }
                })
                ->rawColumns(['action', 'status', 'approval_status'])
                ->make(true);
        }
    }



    

    public function create()
    {
        $pageTitle = 'Add Page Category';
        return view('secure.page_category.create', compact('pageTitle'));
    }

    public function store(StorePageCategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = new PageCategoryDto(
                $request->title,
                $request->title_hi,
                $request->file('file_name'),
                $request->file('file_name_hi'),
                0, // Default is_approved
                $request->is_published ?? 0,
                $request->remarks,
                auth()->id(),
                now(),
                auth()->id(),
                now()
            );

            $result = $this->pageCategoryService->create($dto);

            if ($result) {
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Page Category Created Successfully']);
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page Category creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function show($id)
    {
        $pageTitle = 'View Page Category';
        $pageCategory = $this->pageCategoryService->findById($id);
        return view('secure.page_category.show', compact('pageCategory', 'pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Page Category';
        $pageCategory = $this->pageCategoryService->findById($id);
        return view('secure.page_category.edit', compact('pageCategory', 'pageTitle'));
    }

    public function update(UpdatePageCategoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $pageCategory = $this->pageCategoryService->findById($id);
            if (!$pageCategory) {
                 return response()->json(['success' => false, 'message' => 'Page Category not found']);
            }
            
            $dto = new PageCategoryDto(
                $request->title,
                $request->title_hi,
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                $pageCategory->is_approved, // Keep existing approval status
                $pageCategory->is_published, // Keep existing publish status
                $request->remarks,
                $pageCategory->created_by,
                $pageCategory->created_at,
                auth()->id(),
                now()
            );

            $result = $this->pageCategoryService->update($dto, $id);

            if ($result) {
                DB::commit();
                 return response()->json(['success' => true, 'message' => 'Page Category Updated Successfully']);
            } else {
                DB::rollBack();
               return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page Category update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->pageCategoryService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Page Category deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(Request $request, PageCategory $pageCategory)
    {
        try {

            $dto = new PageCategoryDto(
                $pageCategory->title,
                $pageCategory->title_hi,
                $pageCategory->file_name,
                $pageCategory->file_name_hi,
                $request->input('is_approved'), // status -> is_approved
                $pageCategory->is_published,
                $request->input('remarks'),
                $pageCategory->created_by,
                $pageCategory->created_at,
                auth()->id(),
                now()
            );

            $updated = $this->pageCategoryService->approve($dto, $pageCategory->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Page Category approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
    
    public function publish(Request $request, PageCategory $pageCategory) {
         try {

             $isAlreadyApproved = $pageCategory->is_approved == 1;

             $dto = new PageCategoryDto(
                $pageCategory->title,
                $pageCategory->title_hi,
                $pageCategory->file_name,
                $pageCategory->file_name_hi,
                 $isAlreadyApproved ? $pageCategory->is_approved : 1,
                 $request->input('is_published'), // status -> is_published
                $isAlreadyApproved ? $pageCategory->remarks : 'Automatically approved while publishing the content',
                $pageCategory->created_by,
                $pageCategory->created_at,
                auth()->id(),
                now()
            );

            $updated = $this->pageCategoryService->publish($dto, $pageCategory->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record published successfully!'
            ], 200);
         } catch (\Exception $e) {
            Log::error('Page Category publishing failed: ' . $e->getMessage());
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
        $data = $this->pageCategoryService->findForPublic($limit);
        return response()->json([
             'success' => true,
             'data' => $data
         ]);
    }

}
