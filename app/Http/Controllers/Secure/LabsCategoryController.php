<?php

namespace App\Http\Controllers\Secure;

use App\DTO\LabsCategoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabsCategoryRequest;
use App\Http\Requests\StoreLabsPageRequest;
use App\Http\Requests\UpdateLabsCategoryRequest;
use App\Http\Requests\UpdateLabsPageRequest;
use App\Models\LaboratoriesCategory;
use App\Models\LaboratoriesPage;
use App\Services\LaboratoriesCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class LabsCategoryController extends Controller {
    private $laboratoriesCategoryService;

    public function __construct() {
        $this->laboratoriesCategoryService = new LaboratoriesCategoryService();
    }

    public function index(Request $request) {
        $pageTitle = 'View Laboratories Category';
        return view('secure.labs_category.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request){
        if($request->ajax()){
            $labs_cat = $this->laboratoriesCategoryService->findAll();
            return DataTables::of($labs_cat)
            ->addColumn('action', function($cat){
                $button = '';
                if(auth()->user()->can('view laboratories category')) {
                    $button .= '<a href="'.route('labs_category.show', $cat->id).'" class="btn btn-primary btn-sm" title="View"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit laboratories category')) {
                    $button .= '<a href="'.route('labs_category.edit', $cat->id).'" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete laboratories category')) {
                    $button .= '<button class="btn btn-sm btn-danger delete-labs_category" data-id="' . $cat->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                }
                return $button;
            })
            ->addColumn('view_page', function ($cat) {
                $button = '';
                if(auth()->user()->can('view laboratories page')) {
                    $button .= '<a href="'.route('labs.index', ['laboratoriesCategory' => $cat->id]).'" class="btn btn-primary btn-sm" title="View Pages"><i class="fa fa-eye"></i>View</a>';
                }
                return $button;
            })
            ->rawColumns(['action', 'view_page'])
            ->make(true);
        }
    }
    public function create() {
        $pageTitle = 'Create Laboratories Category';
        return view('secure.labs_category.create', compact('pageTitle'));
    }
    public function store(StoreLabsCategoryRequest $request) {
        DB::beginTransaction();
        try {
            $labsCategoryDto = new LabsCategoryDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('slogan'),
                $request->input('slogan_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->input('permission_group') . " Page",
                0,
                0,
                null,
                auth()->user()->id,
                auth()->user()->id
            );
            $result = $this->laboratoriesCategoryService->create($labsCategoryDto);
            $permissionGroup = (string) $request->input('permission_group') . " Page";
            $permissions = [
                'view ' . strtolower($permissionGroup),
                'add ' . strtolower($permissionGroup),
                'edit ' . strtolower($permissionGroup),
                'delete ' . strtolower($permissionGroup),
                'publish ' . strtolower($permissionGroup),
                'approve ' . strtolower($permissionGroup)
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate([
                    'name' => $perm,
                    'group' => $permissionGroup
                ]);
            }
            if(!$result){
            DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving Regional Directories.',
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Regional Directories created successfully!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Laboratories Category addition failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating Laboratories Category: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id) {
        $pageTitle = 'View Laboratories Category';
        $labCategory = $this->laboratoriesCategoryService->findById($id);
        return view('secure.labs_category.show', compact('labCategory','pageTitle'));
    }
    public function edit($id) {
        $pageTitle = 'Edit Laboratories Category';
        $labCategory = $this->laboratoriesCategoryService->findById($id);
        return view('secure.labs_category.edit', compact('labCategory', 'pageTitle'));
    }
    public function update(UpdateLabsCategoryRequest $request, LaboratoriesCategory $laboratoriesCategory) {
        try {
            DB::beginTransaction();
            $labsCategoryDto = new LabsCategoryDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('slogan'),
                $request->input('slogan_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $laboratoriesCategory->permission_group,
                0,
                0,
                null,
                auth()->user()->id,
                auth()->user()->id
            );
            $result = $this->laboratoriesCategoryService->update($labsCategoryDto, $laboratoriesCategory->id);
            if(!$result){
            DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Laboratories Category.',
                ], 500);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Laboratories Category updated successfully.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Laboratories Category addition failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating Laboratories Category: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id) {
        try {
            $result = $this->laboratoriesCategoryService->delete($id);
            if($result){
                return response()->json([
                    'success' => true,
                    'message' => 'Laboratories Category deleted successfully.',
                ], 201);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Laboratories Category.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Laboratories Category addition failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting Laboratories Category: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function approve(Request $request, LaboratoriesCategory $laboratoriesCategory) {
        try {
            $labsCategoryDto = new LabsCategoryDto(
                $laboratoriesCategory->title,
                $laboratoriesCategory->title_hi,
                $laboratoriesCategory->slogan,
                $laboratoriesCategory->slogan_hi,
                $laboratoriesCategory->description,
                $laboratoriesCategory->description_hi,
                $laboratoriesCategory->featured_image,
                $laboratoriesCategory->permission_group,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                $laboratoriesCategory->created_by,
                $laboratoriesCategory->updated_by,
            );

            $updated = $this->laboratoriesCategoryService->approve($labsCategoryDto, $laboratoriesCategory->id);

            if($updated){
                return response()->json([
                    'success' => true,
                    'message' => 'Laboratories Category approved successfully.',
                ], 201);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve Laboratories Category.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Laboratories Category addition failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving Laboratories Category: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function publish(Request $request, LaboratoriesCategory $laboratoriesCategory) {
        try {
            $labsCategoryDto = new LabsCategoryDto(
                $laboratoriesCategory->title,
                $laboratoriesCategory->title_hi,
                $laboratoriesCategory->slogan,
                $laboratoriesCategory->slogan_hi,
                $laboratoriesCategory->description,
                $laboratoriesCategory->description_hi,
                $laboratoriesCategory->featured_image,
                $laboratoriesCategory->permission_group,
                $laboratoriesCategory->is_approved == 1 ? $laboratoriesCategory->is_approved : 1,
                $request->input('is_published'),
                $laboratoriesCategory->is_approved == 1
                    ? $laboratoriesCategory->remarks
                    : 'Automatically approved while publishing the content',
                $laboratoriesCategory->created_by,
                $laboratoriesCategory->updated_by,
            );
            $updated = $this->laboratoriesCategoryService->publish($labsCategoryDto, $laboratoriesCategory->id);

            if($updated){
                return response()->json([
                    'success' => true,
                    'message' => 'Laboratories Category published successfully.',
                ], 201);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish Laboratories Category.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Laboratories Category addition failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while publishing Laboratories Category: ' . $e->getMessage(),
            ], 500);
        }
    }
}
