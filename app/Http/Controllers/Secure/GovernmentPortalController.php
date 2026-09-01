<?php

namespace App\Http\Controllers\Secure;

use App\DTO\GovernmentPortalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGovernmentPortalRequest;
use App\Http\Requests\UpdateGovernmentPortalRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\GovernmentPortal;
use App\Services\GovernmentPortalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class GovernmentPortalController extends Controller
{
    protected $governmentPortalService;

    public function __construct()
    {
        $this->governmentPortalService = new GovernmentPortalService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Government Portals';
        return view('secure.government_portals.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $governmentPortals = $this->governmentPortalService->findAll();
            return DataTables::of($governmentPortals)
                ->addColumn('image', function ($governmentPortal) {
                    if ($governmentPortal->file_name) {
                        return "<img src=" . generate_file_view_path_for_backend($governmentPortal->file_name_full_path) . " alt='Government Portal Image' class='img-fluid' style='height: 60px; object-fit: contain;'>";
                    }

                    return '';
                })
                ->addColumn('action', function ($governmentPortal) {
                    $button = '';
                    if (auth()->user()->can('view government portal')) {
                        $button .= '<a href="' . route('government-portals.show', $governmentPortal->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit government portal')) {
                        $button .= '<a href="' . route('government-portals.edit', $governmentPortal->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete government portal')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-government-portal" data-id="' . $governmentPortal->id . '" title="Delete">
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
        $pageTitle = 'Add Government Portal';
        return view('secure.government_portals.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGovernmentPortalRequest $request)
    {
        try {

            $governmentPortalDto = new GovernmentPortalDto(
                $request->file('file_name'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('link'),
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            $governmentPortal = $this->governmentPortalService->create($governmentPortalDto);

            if (!$governmentPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving government portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Government Portal created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Government Portal addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Government Portal';
        $governmentPortal = $this->governmentPortalService->findById($id);
        return view('secure.government_portals.show', compact('governmentPortal', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Government Portal';
        $governmentPortal = $this->governmentPortalService->findById($id);
        return view('secure.government_portals.edit', compact('governmentPortal', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGovernmentPortalRequest $request, GovernmentPortal $governmentPortal)
    {
        try {
            $governmentPortalDto = new GovernmentPortalDto(
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('link'),
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );
            $governmentPortal = $this->governmentPortalService->update($governmentPortalDto, $governmentPortal->id);

            if (!$governmentPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating government portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Government Portal updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Government Portal updation failed: ' . $e->getMessage());
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
            $governmentPortal = $this->governmentPortalService->delete($id);
            if (!$governmentPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting government portal.',
                ], 500);
            }

            return response()->json(['message' => 'Government Portal moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Government Portal deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, GovernmentPortal $governmentPortal)
    {
        try {
            $updated = $this->governmentPortalService->approve(
                $governmentPortal->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $governmentPortal->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving government portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Government Portal approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, GovernmentPortal $governmentPortal)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $governmentPortal->is_approved == 1 || $isPublished == 1 ? 1 : $governmentPortal->is_approved;
            $remarks = $governmentPortal->is_approved == 1
                ? $governmentPortal->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $governmentPortal->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->governmentPortalService->publish($governmentPortal->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing government portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Government Portal publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findForPublic()
    {
        try {
            $governmentPortals = $this->governmentPortalService->findForPublic();
            if (!$governmentPortals) {
                return response()->json([
                    'success' => false,
                    'message' => 'No government portals found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $governmentPortals
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching government portals for public: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

}