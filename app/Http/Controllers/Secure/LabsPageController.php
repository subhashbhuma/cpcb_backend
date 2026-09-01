<?php

namespace App\Http\Controllers\Secure;

use App\DTO\LabsPageDto;
use App\DTO\LabsPageFilesDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabsPageRequest;
use App\Models\LaboratoriesFile;
use App\Models\LaboratoriesPage;
use App\Services\LaboratoriesCategoryService;
use App\Services\LaboratoriesFileService;
use App\Services\LaboratoriesPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class LabsPageController extends Controller
{
    private $laboratoriesCategoryService;
    private $laboratoriesPageService;
    private $laboratoriesFileService;

    public function __construct()
    {
        $this->laboratoriesCategoryService = new LaboratoriesCategoryService();
        $this->laboratoriesPageService = new LaboratoriesPageService();
        $this->laboratoriesFileService = new LaboratoriesFileService();
    }

    public function index(int $laboratoriesCategory)
    {
        $pageTitle = 'Laboratories';
        $categoryies = $this->laboratoriesCategoryService->findAllForPublic();
        $page_CatId = $laboratoriesCategory;
        return view('secure.labs.index', compact('pageTitle', 'categoryies', 'page_CatId'));
    }

    public function air_index()
    {
        $pageTitle = 'Air Laboratories';
        $page_CatId = 1;
        $categoryies = $this->laboratoriesPageService->findAllByLab($page_CatId);
        return view('secure.labs.air-index', compact( 'pageTitle', 'categoryies', 'page_CatId'));
    }

    public function water_index()
    {
        $pageTitle = 'Water Laboratories';
        $page_CatId = 2;
        $categoryies = $this->laboratoriesPageService->findAllByLab($page_CatId);
        return view('secure.labs.water-index', compact( 'pageTitle', 'categoryies', 'page_CatId'));
    }

    public function fetchForDatatable(Request $request, int $laboratoriesCategory)
    {
        if ($request->ajax()) {
            $labs = $this->laboratoriesPageService->findAllByLab($laboratoriesCategory);
            return DataTables::of($labs)
                ->addColumn('action', function ($labs) {
                    $button = '';
                    if (auth()->user()->can('view laboratories page')) {
                        $button .= '<a href="' . route('labs.show', $labs->id) . '" class="btn btn-primary btn-sm" title="View"><i class="fa fa-eye"></i></a>';
                    }
                    if (auth()->user()->can('edit laboratories page')) {
                        $button .= '<a href="' . route('labs.edit', $labs->id) . '" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-edit"></i></a>';
                    }
                    if (auth()->user()->can('delete laboratories page')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-labs" data-id="' . $labs->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function airFetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $labs = $this->laboratoriesPageService->findAllByLab(1);
            return DataTables::of($labs)
                ->addColumn('action', function ($labs) {
                    $button = '';
                    if (auth()->user()->can('view laboratories page')) {
                        $button .= '<a href="' . route('labs.show', $labs->id) . '" class="btn btn-primary btn-sm" title="View"><i class="fa fa-eye"></i></a>';
                    }
                    if (auth()->user()->can('edit laboratories page')) {
                        $button .= '<a href="' . route('labs.edit', $labs->id) . '" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-edit"></i></a>';
                    }
                    if (auth()->user()->can('delete laboratories page')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-labs" data-id="' . $labs->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function waterFetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $labs = $this->laboratoriesPageService->findAllByLab(2);
            return DataTables::of($labs)
                ->addColumn('action', function ($labs) {
                    $button = '';
                    if (auth()->user()->can('view laboratories page')) {
                        $button .= '<a href="' . route('labs.show', $labs->id) . '" class="btn btn-primary btn-sm" title="View"><i class="fa fa-eye"></i></a>';
                    }
                    if (auth()->user()->can('edit laboratories page')) {
                        $button .= '<a href="' . route('labs.edit', $labs->id) . '" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-edit"></i></a>';
                    }
                    if (auth()->user()->can('delete laboratories page')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-labs" data-id="' . $labs->id . '" title="Delete">
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
        $pageTitle = 'Create Laboratory';
        $categories = $this->laboratoriesCategoryService->findAllForPublic();
        return view('secure.labs.create', compact('pageTitle', 'categories'));
    }

    public function store(StoreLabsPageRequest $request)
    {
        DB::beginTransaction();
        try {

            $labsPageDto = new LabsPageDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('category_id'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->input('public_comments'),
                $request->input('public_comments_hi'),
                $request->input('public_comments_url'),
                $request->input('content'),
                $request->input('content_hi'),
                0,
                0,
                $request->input('remarks'),
                auth()->user()->id,
                auth()->user()->id
            );
            $labs = $this->laboratoriesPageService->create($labsPageDto);
            if (!$labs) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                    'data' => $labs
                ], 500);
            }
            if (count(json_decode($request->fileCountArray)) > 0) {
                foreach (json_decode($request->fileCountArray) as $key => $count) {
                    $labPageFileDto = new LabsPageFilesDto(
                        $labs->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        $request->input('title_' . $count),
                        $request->input('title_hi_' . $count),
                        $request->input('date_' . $count) ?? null,
                        $request->input('type_' . $count) ?? null,
                        $request->input('description_' . $count) ?? null,
                        $request->input('description_hi_' . $count) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->laboratoriesFileService->create($labPageFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Failed to upload files.'
                        ], 500);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'labs' => $labs->id,
                'redirect_url' => route('labs.index'),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Page creation failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
                'data' => $request->all(),
            ], 500);
        }
    }

    public function air_store(StoreLabsPageRequest $request)
    {
        DB::beginTransaction();
        try {

            $labsPageDto = new LabsPageDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('category_id'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->input('public_comments'),
                $request->input('public_comments_hi'),
                $request->input('public_comments_url'),
                $request->input('content'),
                $request->input('content_hi'),
                0,
                0,
                $request->input('remarks'),
                auth()->user()->id,
                auth()->user()->id
            );
            $labs = $this->laboratoriesPageService->create($labsPageDto);
            if (!$labs) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                    'data' => $labs
                ], 500);
            }
            if (count(json_decode($request->fileCountArray)) > 0) {
                foreach (json_decode($request->fileCountArray) as $key => $count) {
                    $labPageFileDto = new LabsPageFilesDto(
                        $labs->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        $request->input('title_' . $count),
                        $request->input('title_hi_' . $count),
                        $request->input('date_' . $count) ?? null,
                        $request->input('type_' . $count) ?? null,
                        $request->input('description_' . $count) ?? null,
                        $request->input('description_hi_' . $count) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->laboratoriesFileService->create($labPageFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Failed to upload files.'
                        ], 500);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'labs' => $labs->id,
                'redirect_url' => route('air-labs.index'),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Page creation failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
                'data' => $request->all(),
            ], 500);
        }
    }
    public function water_store(StoreLabsPageRequest $request)
    {
        DB::beginTransaction();
        try {

            $labsPageDto = new LabsPageDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('category_id'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->input('public_comments'),
                $request->input('public_comments_hi'),
                $request->input('public_comments_url'),
                $request->input('content'),
                $request->input('content_hi'),
                0,
                0,
                $request->input('remarks'),
                auth()->user()->id,
                auth()->user()->id
            );
            $labs = $this->laboratoriesPageService->create($labsPageDto);
            if (!$labs) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                    'data' => $labs
                ], 500);
            }
            if (count(json_decode($request->fileCountArray)) > 0) {
                foreach (json_decode($request->fileCountArray) as $key => $count) {
                    $labPageFileDto = new LabsPageFilesDto(
                        $labs->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        $request->input('title_' . $count),
                        $request->input('title_hi_' . $count),
                        $request->input('date_' . $count) ?? null,
                        $request->input('type_' . $count) ?? null,
                        $request->input('description_' . $count) ?? null,
                        $request->input('description_hi_' . $count) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->laboratoriesFileService->create($labPageFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Failed to upload files.'
                        ], 500);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'labs' => $labs->id,
                'redirect_url' => route('water-labs.index'),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Page creation failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
                'data' => $request->all(),
            ], 500);
        }
    }

    public function show(LaboratoriesPage $laboratoriesPage)
    {
        $pageTitle = 'View Laboratory';
        $labs = $laboratoriesPage;
        return view('secure.labs.show', compact('pageTitle', 'labs'));
    }

    public function edit(LaboratoriesPage $laboratoriesPage)
    {
        $pageTitle = 'Edit Laboratory';
        $labs = $laboratoriesPage;
        $categories = $this->laboratoriesCategoryService->findAllForPublic();
        return view('secure.labs.edit', compact('pageTitle', 'categories', 'labs'));
    }
    public function update(StoreLabsPageRequest $request, LaboratoriesPage $laboratoriesPage)
    {
        DB::beginTransaction();
        try {
            $labsPageDto = new LabsPageDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('category_id'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->input('public_comments'),
                $request->input('public_comments_hi'),
                $request->input('public_comments_url'),
                $request->input('content'),
                $request->input('content_hi'),
                0,
                0,
                $request->input('remarks'),
                auth()->user()->id,
                auth()->user()->id
            );
            $result = $this->laboratoriesPageService->update($labsPageDto, $laboratoriesPage->id);

            if (!$result) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update laboratory.',
                    'error' => $laboratoriesPage->id,
                ], 500);
            }
            if (count(json_decode($request->fileCountArray)) > 0) {
                foreach (json_decode($request->fileCountArray) as $key => $count) {
                    $labPageFileDto = new LabsPageFilesDto(
                        $laboratoriesPage->id,
                        $request->file('file_name_' . $count),
                        $request->file('file_name_hi_' . $count),
                        $request->input('title_' . $count),
                        $request->input('title_hi_' . $count),
                        $request->input('date_' . $count) ?? null,
                        $request->input('type_' . $count) ?? null,
                        $request->input('description_' . $count) ?? null,
                        $request->input('description_hi_' . $count) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->laboratoriesFileService->create($labPageFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Failed to upload files.'
                        ], 500);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Laboratory updated successfully!',
                'redirect_url' => route('labs.index'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Laboratory update failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
    public function destroy(LaboratoriesPage $laboratoriesPage)
    {
        DB::beginTransaction();
        try {
            $result = $this->laboratoriesPageService->delete($laboratoriesPage);
            if (!$result) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete laboratory.'
                ], 500);
            }
            // Delete associated files
            $files = $result->files;
            foreach ($files as $file) {
                $fileResult = $this->laboratoriesFileService->delete($file->id);
                if (!$fileResult) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to delete associated files.'
                    ], 500);
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Laboratory deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Laboratory deletion failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
    public function destroyFile(LaboratoriesFile $laboratoriesFile)
    {
        try {
            $result = $this->laboratoriesFileService->delete($laboratoriesFile->id);
            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete file.',
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
    public function approve(Request $request, LaboratoriesPage $laboratoriesPage)
    {
        try {
            $labsPage = new LabsPageDto(
                $laboratoriesPage->title,
                $laboratoriesPage->title_hi,
                $laboratoriesPage->category_id,
                $laboratoriesPage->featured_image,
                $laboratoriesPage->public_comments,
                $laboratoriesPage->public_comments_hi,
                $laboratoriesPage->public_comments_url,
                $laboratoriesPage->content,
                $laboratoriesPage->content_hi,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                auth()->user()->id,
                auth()->user()->id
            );
            $result = $this->laboratoriesPageService->approve($labsPage, $laboratoriesPage->id);
            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to approve laboratory.',
                    'data' => $laboratoriesPage,
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Laboratory approved successfully!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Laboratory approval failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
    public function publish(Request $request, LaboratoriesPage $laboratoriesPage)
    {
        try {
            $labsPageDto = new LabsPageDto(
                $laboratoriesPage->title,
                $laboratoriesPage->title_hi,
                $laboratoriesPage->category_id,
                $laboratoriesPage->featured_image,
                $laboratoriesPage->public_comments,
                $laboratoriesPage->public_comments_hi,
                $laboratoriesPage->public_comments_url,
                $laboratoriesPage->content,
                $laboratoriesPage->content_hi,
                $laboratoriesPage->is_approved ? $laboratoriesPage->is_approved : 1,
                $request->input('is_published'),
                $laboratoriesPage->is_approved == 1
                    ? $laboratoriesPage->remarks
                    : 'Automatically approved while publishing the content',
                auth()->user()->id,
                auth()->user()->id
            );
            $result = $this->laboratoriesPageService->publish($labsPageDto, $laboratoriesPage->id);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to publish laboratory.',
                    'data' => $laboratoriesPage->all(),
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Laboratory published successfully!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Laboratory publishing failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
    public function fetchAllLabsPage()
    {
        $labs = $this->laboratoriesPageService->findAll();
        return response()->json([
            'success' => true,
            'data' => $labs
        ]);
    }
    public function fetchLabsPageById($id)
    {
        $labs = $this->laboratoriesPageService->findById($id);
        if (!$labs) {
            return response()->json([
                'success' => false,
                'message' => 'Laboratory not found.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $labs
        ]);
    }
}
