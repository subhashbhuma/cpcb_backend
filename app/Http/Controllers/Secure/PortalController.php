<?php

namespace App\Http\Controllers\Secure;

use App\DTO\PortalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortalRequest;
use App\Http\Requests\UpdatePortalRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Portal;
use App\Services\PortalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\PublicCpcbPortalResource;
class PortalController extends Controller
{
    protected $portalService;

    public function __construct()
    {
        $this->portalService = new PortalService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'CPCB Portals';
        return view('secure.cpcb_portals.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $portals = $this->portalService->findAll();
            return DataTables::of($portals)
                ->addColumn('file', function ($portal) {
                    if ($portal->file_name) {
                        return "<a href=" . $portal->file_name_full_path . "><i class='fa fa-file'></i> </a>";
                    }

                    return '';
                })
                ->addColumn('action', function ($portal) {
                    $button = '';
                    if (auth()->user()->can('view cpcb portal')) {
                        $button .= '<a href="' . route('cpcb-portals.show', $portal->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit cpcb portal')) {
                        $button .= '<a href="' . route('cpcb-portals.edit', $portal->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete cpcb portal')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-cpcb-portal" data-id="' . $portal->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add CPCB Portal';
        return view('secure.cpcb_portals.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortalRequest $request)
    {
        try {

            $portalDto = new PortalDto(
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

            $cpcbPortal = $this->portalService->create($portalDto);

            if (!$cpcbPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving cpcb portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cpcb Portal created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Cpcb Portal addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View CPCB Portal';
        $cpcbPortal = $this->portalService->findById($id);
        return view('secure.cpcb_portals.show', compact('cpcbPortal', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit CPCB Portal';
        $cpcbPortal = $this->portalService->findById($id);
        return view('secure.cpcb_portals.edit', compact('cpcbPortal', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortalRequest $request, Portal $portal)
    {
        try {
            $portalDto = new PortalDto(
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
            $cpcbPortal = $this->portalService->update($portalDto, $portal->id);

            if (!$cpcbPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating cpcb portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cpcb Portal updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Cpcb Portal updation failed: ' . $e->getMessage());
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
            $cpcbPortal = $this->portalService->delete($id);
            if (!$cpcbPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting cpcb portal.',
                ], 500);
            }

            return response()->json(['message' => 'Cpcb Portal moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Cpcb Portal deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Portal $portal)
    {
        try {
            $updated = $this->portalService->approve(
                $portal->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $portal->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving cpcb portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Cpcb Portal approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Portal $portal)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $portal->is_approved == 1 || $isPublished == 1 ? 1 : $portal->is_approved;
            $remarks = $portal->is_approved == 1
                ? $portal->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $portal->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->portalService->publish($portal->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing cpcb portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Cpcb Portal publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findForPublic()
    {
        try {
            $cpcbPortals = $this->portalService->findForPublic();
            if (!$cpcbPortals) {
                return response()->json([
                    'success' => false,
                    'message' => 'No cpcb portals found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $cpcbPortals
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching cpcb portals for public: ' . $e->getMessage());
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
        $data = $this->portalService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => PublicCpcbPortalResource::collection($data),
            'lastUpdatedOn' => Portal::getLastUpdatedOrCreatedAt(),
        ]);
    }
}

