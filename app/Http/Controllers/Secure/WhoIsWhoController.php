<?php

namespace App\Http\Controllers\Secure;
use App\DTO\WhoIsWhoDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWhoIsWhoRequest;
use App\Http\Requests\UpdateWhoIsWhoRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\WhoIsWho;
use App\Services\DivisionService;
use App\Services\WhoIsWhoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\PublicWhoIsWhoHomePageResource;

class WhoIsWhoController extends Controller
{
    protected $whoIsWhoService;
    protected $divisionService;

    public function __construct()
    {
        $this->whoIsWhoService = new WhoIsWhoService();
        $this->divisionService = new DivisionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Who Is Who';
        return view('secure.who_is_who.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $whoIsWhos = $this->whoIsWhoService->findAll();

            return DataTables::of($whoIsWhos)
                ->addColumn('image', function ($whoIsWho) {
                    if ($whoIsWho->image) {
                        return "<img src=" . generate_file_view_path_for_backend($whoIsWho->image_full_path) . " alt='" . $whoIsWho->name . " Image' class='img-fluid' style='max-height: 70px;'>";
                    }

                    return '';
                })
                ->addColumn('action', function ($whoIsWho) {
                    $button = '';
                    if (auth()->user()->can('view who is who')) {
                        $button .= '<a href="' . route('who-is-who.show', $whoIsWho->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit who is who')) {
                        $button .= '<a href="' . route('who-is-who.edit', $whoIsWho->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete who is who')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-who-is-who" data-id="' . $whoIsWho->id . '" title="Delete">
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
        $pageTitle = 'Add Who Is Who';
        $divisions = $this->divisionService->findPublished();
        return view('secure.who_is_who.create', compact('pageTitle', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWhoIsWhoRequest $request)
    {
        try {

            $whoIsWhoDto = new WhoIsWhoDto(
                $request->input('order'),
                $request->input('name'),
                $request->input('name_hi'),
                $request->input('designation'),
                $request->input('designation_hi'),
                $request->input('mobile_number'),
                $request->input('email_id'),
                $request->input('division_id'),
                $request->hasFile('image') ? $request->file('image') : null,
                $request->input('address'),
                $request->input('address_hi'),
                $request->input('show_on_homepage'),
                $request->input('hide_on_who_is_who'),
                $request->input('is_approved', false),
                $request->input('is_published', false),
                $request->input('remarks'),
                $request->input('publish_remark'),
                auth()->user()->id,
                auth()->user()->id
            );


            $whoIsWho = $this->whoIsWhoService->create($whoIsWhoDto);

            if (!$whoIsWho) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving who is who.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Who is who created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Who is who addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Who Is Who';
        $whoIsWho = $this->whoIsWhoService->findById($id);
        return view('secure.who_is_who.show', compact('whoIsWho', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Who Is Who';
        $whoIsWho = $this->whoIsWhoService->findById($id);
        $divisions = $this->divisionService->findAll();
        return view('secure.who_is_who.edit', compact('whoIsWho', 'pageTitle', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWhoIsWhoRequest $request, WhoIsWho $whoIsWho)
    {
        try {
            $whoIsWhoDto = new WhoIsWhoDto(
                $request->input('order'),
                $request->input('name'),
                $request->input('name_hi'),
                $request->input('designation'),
                $request->input('designation_hi'),
                $request->input('mobile_number'),
                $request->input('email_id'),
                $request->input('division_id'),
                $request->hasFile('image') ? $request->file('image') : null,
                $request->input('address'),
                $request->input('address_hi'),
                $request->input('show_on_homepage'),
                $request->input('hide_on_who_is_who'),
                0,
                0,
                null,
                null,
                $whoIsWho->created_by,
                auth()->user()->id
            );

            $whoIsWho = $this->whoIsWhoService->update($whoIsWhoDto, $whoIsWho->id);

            if (!$whoIsWho) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating who is who.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Who is who updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Who is who updation failed: ' . $e->getMessage());
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
            $whoIsWho = $this->whoIsWhoService->delete($id);
            if (!$whoIsWho) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting who is who.',
                ], 500);
            }

            return response()->json(['message' => 'Who is who moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Who is who deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, WhoIsWho $whoIsWho)
    {
        try {
            $whoIsWhoDto = new WhoIsWhoDto(
                $whoIsWho->order,
                $whoIsWho->name,
                $whoIsWho->name_hi,
                $whoIsWho->designation,
                $whoIsWho->designation_hi,
                $whoIsWho->mobile_number,
                $whoIsWho->email_id,
                $whoIsWho->division_id,
                $whoIsWho->image,
                $whoIsWho->address,
                $whoIsWho->address_hi,
                $whoIsWho->show_on_homepage,
                $whoIsWho->hide_on_who_is_who,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                $whoIsWho->publish_remark,
                $whoIsWho->created_by,
                auth()->user()->id
            );

            $updated = $this->whoIsWhoService->approve($whoIsWhoDto, $whoIsWho->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving who is who.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('WhoIsWho approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, WhoIsWho $whoIsWho)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $whoIsWho->is_approved == 1 || $isPublished == 1 ? 1 : $whoIsWho->is_approved;
            $remarks = $whoIsWho->is_approved == 1
                ? $whoIsWho->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $whoIsWho->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $whoIsWhoDto = new WhoIsWhoDto(
                $whoIsWho->order,
                $whoIsWho->name,
                $whoIsWho->name_hi,
                $whoIsWho->designation,
                $whoIsWho->designation_hi,
                $whoIsWho->mobile_number,
                $whoIsWho->email_id,
                $whoIsWho->division_id,
                $whoIsWho->image,
                $whoIsWho->address,
                $whoIsWho->address_hi,
                $whoIsWho->show_on_homepage,
                $whoIsWho->hide_on_who_is_who,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $whoIsWho->created_by,
                auth()->user()->id
            );

            $updated = $this->whoIsWhoService->publish($whoIsWhoDto, $whoIsWho->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing who is who.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Who is who publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function findAllForPublic()
    {
        $whoIsWhos = $this->whoIsWhoService->findAllForWhoIsWho();
        if ($whoIsWhos->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }
        return response()->json($whoIsWhos);
    }

    public function findByDesignation($designation)
    {
        $designation_f = str_replace('_', ' ', $designation);
        $whoIsWhos = $this->whoIsWhoService->findByDesignation($designation_f);
        if ($whoIsWhos->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }
        return response()->json($whoIsWhos[0]);
    }


    public function whoIsWhoHomePage()
    {
        $whoIsWhos = $this->whoIsWhoService->whoIsWhoHomePage();
        if ($whoIsWhos->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No data found'
            ], 200);
        }
        return response()->json([
            'status' => true,
            'data' => PublicWhoIsWhoHomePageResource::collection($whoIsWhos),
        ], 200);
    }
}
