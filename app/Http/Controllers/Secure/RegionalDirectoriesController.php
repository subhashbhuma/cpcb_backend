<?php

namespace App\Http\Controllers\Secure;

use App\DTO\RegionalDirectoriesDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegionalDirectoriesRequest;
use App\Http\Requests\UpdateRegionalDirectoriesRequest;
use App\Models\RegionalDirectories;
use App\Services\RegionalDirectoriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RegionalDirectoriesController extends Controller
{
    protected $regionalDirectoriesService;

    public function __construct()
    {
        $this->regionalDirectoriesService = new RegionalDirectoriesService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Regional Directories';
        return view('secure.regional_directories.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request){
        if ($request->ajax()) {
            $regionalDirectories = $this->regionalDirectoriesService->findAll();
            return DataTables::of($regionalDirectories)
                ->addColumn('action', function ($regionalDirectories) {
                    $button = '';
                    if (auth()->user()->can('view regional directory')) {
                        $button .= '<a href="' . route('regional_directories.show', $regionalDirectories->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit regional directory')) {
                        $button .= '<a href="' . route('regional_directories.edit', $regionalDirectories->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete regional directory')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-regional_directories" data-id="' . $regionalDirectories->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Regional Directories';
        return view('secure.regional_directories.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionalDirectoriesRequest $request)
    {
        try {

            $regionalDirectoriesDto = new RegionalDirectoriesDto(
                $request->input('zone'),
                $request->input('state'),
                $request->input('address'),
                $request->input('phone_numbers'),
                $request->input('email_ids'),
                $request->input('jurdiction'),
                $request->input('location_link'),
                0,
                0,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            $regionalDirectories = $this->regionalDirectoriesService->create($regionalDirectoriesDto);

            if (!$regionalDirectories) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving Regional Directories.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Regional Directories created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Regional Directories addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Regional Directories';
        $regional_directories = $this->regionalDirectoriesService->findById($id);
        return view('secure.regional_directories.show', compact('regional_directories', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Regional Directories';
        $regional_directories = $this->regionalDirectoriesService->findById($id);
        return view('secure.regional_directories.edit', compact('regional_directories', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionalDirectoriesRequest $request, RegionalDirectories $regionalDirectories)
    {
        try {
            $regionalDirectoriesDto = new RegionalDirectoriesDto(
                $request->input('zone'),
                $request->input('state'),
                $request->input('address'),
                $request->input('phone_numbers'),
                $request->input('email_ids'),
                $request->input('jurdiction'),
                $request->input('location_link'),
                0,
                0,
                $request->input('remarks'),
                auth()->user()->id,
                auth()->user()->id
            );
            $regional_directories = $this->regionalDirectoriesService->update($regionalDirectoriesDto, $regionalDirectories->id);

            if (!$regional_directories) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating Regional Directories.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Regional Directories updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directories updation failed: ' . $e->getMessage());
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
            $regional_directories = $this->regionalDirectoriesService->delete($id);
            if (!$regional_directories) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting quicklink.',
                ], 500);
            }

            return response()->json(['message' => 'Regional Directories moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Regional Directories deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(Request $request, RegionalDirectories $regionalDirectories)
    {
        try {
            $regionalDirectoriesDto = new RegionalDirectoriesDto(
                $regionalDirectories->zone,
                $regionalDirectories->state,
                $regionalDirectories->address,
                $regionalDirectories->phone_number,
                $regionalDirectories->email_ids,
                $regionalDirectories->jurdiction,
                $regionalDirectories->location_link,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                $regionalDirectories->created_by,
                auth()->user()->id,
            );

            $updated = $this->regionalDirectoriesService->approve($regionalDirectoriesDto, $regionalDirectories->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving Regional Directories.',
                    'data' => $regionalDirectories
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Regional Directories decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directories approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(Request $request, RegionalDirectories $regionalDirectories)
    {
        try {
            $regionalDirectoriesDto = new RegionalDirectoriesDto(
                $regionalDirectories->zone,
                $regionalDirectories->state,
                $regionalDirectories->address,
                $regionalDirectories->phone_number,
                $regionalDirectories->email_ids,
                $regionalDirectories->jurdiction,
                $regionalDirectories->location_link,
                $regionalDirectories->is_approved == 1 ? $regionalDirectories->is_approved : 1,
                $request->input('is_published'),
                $regionalDirectories->is_approved == 1 ? $regionalDirectories->remarks : 'Automatically approved while publishing the content',
                $regionalDirectories->created_by,
                auth()->user()->id,
            );

            $updated = $this->regionalDirectoriesService->publish($regionalDirectoriesDto, $regionalDirectories->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing Regional Directories.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Regional Directories published successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directories publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        try {
            $regionalDirectories = $this->regionalDirectoriesService->findAllForPublic();
            if ($regionalDirectories->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No Regional Directories found.'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $regionalDirectories
            ], 200);
        } catch (\Exception $e) {
            Log::error('Regional Directories fetch for public failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
