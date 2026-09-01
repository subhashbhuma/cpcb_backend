<?php

namespace App\Http\Controllers\Secure;

use App\DTO\DirectoryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDirectoryRequest;
use App\Http\Requests\UpdateDirectoryRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PublicDownloadDirectoryResource;
use App\Models\Directory;
use App\Models\DivisionOrder;
use App\Models\SiteSetting;
use App\Services\DirectoryService;
use App\Services\DivisionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\PublicDirectoryResource;

class DirectoryController extends Controller
{
    protected $directoryService;
    protected $divisionService;

    public function __construct()
    {
        $this->directoryService = new DirectoryService();
        $this->divisionService = new DivisionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Directory Management';
        return view('secure.directories.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource for Datatable.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Directory::with('division', 'division_order')
                ->select('id', 'cpcb_no', 'name', 'designation', 'division_id', 'order_no', 'show_order', 'is_published')
                ->selectRaw('ROW_NUMBER() OVER (PARTITION BY order_no ORDER BY show_order ASC, id ASC) as section_serial')
                ->orderBy('order_no', 'ASC')
                ->orderBy('show_order', 'ASC');
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $button = '';
                    if (auth()->user()->can('view directory')) {
                        $button .= '<a href="' . route('directories.show', $row->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit directory')) {
                        $button .= '<a href="' . route('directories.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete directory')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Directory Entry';
        $divisions = $this->divisionService->findPublished();
        $divisionOrders = DivisionOrder::orderBy('order_no')->get();
        return view('secure.directories.create', compact('pageTitle', 'divisions', 'divisionOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectoryRequest $request)
    {
        try {
            $dto = new DirectoryDto(
                $request->input('name'),
                $request->input('name_hi'),
                $request->input('designation'),
                $request->input('designation_hi'),
                $request->input('division_id'),
                $request->input('office_ph_no'),
                $request->input('mobile_no'),
                $request->input('email'),
                $request->hasFile('image') ? $request->file('image') : null,
                $request->input('ext_number'),
                $request->input('order_no') ?? 1,
                $request->input('show_order') ?? 1,
                null, // remarks
                $request->input('cpcb_no'),
                $request->input('assigned_work'),
                $request->input('assigned_work_hi'),
                0, // is_approved
                0, // is_published
                null, // publish_remark
                auth()->user()->id,
                auth()->user()->id
            );

            $result = $this->directoryService->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Directory addition failed: ' . $e->getMessage());
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Directory Details';
        $directory = $this->directoryService->findById($id);
        return view('secure.directories.show', compact('directory', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Directory Entry';
        $directory = $this->directoryService->findById($id);
        $divisions = $this->divisionService->findPublished();
        $divisionOrders = DivisionOrder::orderBy('order_no')->get();
        return view('secure.directories.edit', compact('directory', 'pageTitle', 'divisions', 'divisionOrders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectoryRequest $request, Directory $directory)
    {
        try {
            $dto = new DirectoryDto(
                $request->input('name'),
                $request->input('name_hi'),
                $request->input('designation'),
                $request->input('designation_hi'),
                $request->input('division_id'),
                $request->input('office_ph_no'),
                $request->input('mobile_no'),
                $request->input('email'),
                $request->hasFile('image') ? $request->file('image') : null,
                $request->input('ext_number'),
                $request->input('order_no') ?? 1,
                $request->input('show_order') ?? 1,
                null, // remarks
                $request->input('cpcb_no'),
                $request->input('assigned_work'),
                $request->input('assigned_work_hi'),
                0, // is_approved
                0, // is_published
                null, // publish_remark
                $directory->created_by,
                auth()->user()->id
            );

            $updated = $this->directoryService->update($dto, $directory->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Directory updation failed: ' . $e->getMessage());
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
            $result = $this->directoryService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Directory deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Directory $directory)
    {
        try {
            $updated = $this->directoryService->approve(
                $directory->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $directory->publish_remark
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
            Log::error('Directory approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Directory $directory)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $directory->is_approved == 1 || $isPublished == 1 ? 1 : $directory->is_approved;
            $remarks = $directory->is_approved == 1
                ? $directory->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing' : $directory->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directoryService->publish(
                $directory->id,
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
            Log::error('Directory publishing failed: ' . $e->getMessage());
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
        $data = $this->directoryService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    public function fetchAllForPublicDataTable(Request $request)
    {
        try {
            $query = Directory::with(['division', 'division_order'])->where('is_published', 1)->where('is_approved', 1);
            if ($request->filled('search')) {
                $searchString = strtolower($request->search);
                $searchString = str_replace(['%', '_'], ['\\%', '\\_'], $searchString);
                $keywords = array_filter(explode(' ', $searchString));

                foreach ($keywords as $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"])
                            ->orWhereRaw('LOWER(name_hi) LIKE ?', ["%{$keyword}%"])
                            ->orWhereRaw('LOWER(designation) LIKE ?', ["%{$keyword}%"])
                            ->orWhereRaw('LOWER(designation_hi) LIKE ?', ["%{$keyword}%"])
                            ->orWhereRaw('LOWER(office_ph_no) LIKE ?', ["%{$keyword}%"])
                            ->orWhereRaw('LOWER(mobile_no) LIKE ?', ["%{$keyword}%"])
                            ->orWhereRaw('LOWER(ext_number) LIKE ?', ["%{$keyword}%"])
                            ->orWhereHas('division', function ($q2) use ($keyword) {
                                $q2->whereRaw('LOWER(title) LIKE ?', ["%{$keyword}%"])
                                   ->orWhereRaw('LOWER(title_hi) LIKE ?', ["%{$keyword}%"]);
                            })
                            ->orWhereRaw('LOWER(email) LIKE ?', ["%{$keyword}%"]);
                    });
                }
            }


            $query->orderBy('order_no', 'ASC');
            $query->orderBy('show_order', 'ASC');

            $perPage = (int) $request->get('per_page', 10);
            $results = $query->paginate($perPage);

            $totalRecords = Directory::where('is_published', 1)
                ->where('is_approved', 1)
                ->count();

            $pageCategory = Directory::where('is_published', 1)->where('is_approved', 1)->first()?->pageCategory;
            return response()->json([
                'status' => true,
                'meta' => [
                    'total_records' => $totalRecords,
                    'filtered_count' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'lastUpdatedOn' => Directory::getLastUpdatedOrCreatedAt(),
                ],
                'data' => PublicDirectoryResource::collection($results),
                'pageCategory' => [
                    'file_path_en' => $pageCategory?->file_path_en,
                    'file_path_hi' => $pageCategory?->file_path_hi,
                    'title' => $pageCategory?->title,
                    'title_hi' => $pageCategory?->title_hi,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error("Public Directory DataTable Error: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch directory data',
                $e->getMessage()
            ], 500);
        }
    }

    public function downloadDirectoryForPublic(Request $request)
    {
        try {
            $query = Directory::with(['division', 'division_order'])->where('is_published', 1)->where('is_approved', 1);
            $query->orderBy('order_no', 'ASC');
            $query->orderBy('show_order', 'ASC');
            $directories = $query->get();
            $lastUpdatedOn = Directory::getLastUpdatedOrCreatedAt();

            // Fetch and encode the logo
            $logoBase64 = '';
            $siteSettings = SiteSetting::select('admin_panel_logo')->first();

            $logoPaths = [
                public_path('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $siteSettings->admin_panel_logo)
            ];
            foreach ($logoPaths as $path) {
                if (file_exists($path)) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                    break;
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('pdf.directory', compact('directories', 'lastUpdatedOn', 'logoBase64'));

            // Define file path
            $fileName = 'Tel_Directory.pdf';
            $filePath = 'directories/' . $fileName;

            // Save PDF to public storage
            Storage::disk('public')->put($filePath, $pdf->output());

            return response()->json([
                'status' => true,
                'file_path' => base64_encode($filePath),
            ], 200);
        } catch (\Exception $e) {
            Log::error("Public Directory PDF Error: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch directory data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
