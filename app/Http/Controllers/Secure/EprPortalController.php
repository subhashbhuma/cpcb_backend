<?php

namespace App\Http\Controllers\Secure;

use App\DTO\EprPortalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEprPortalRequest;
use App\Http\Requests\UpdateEprPortalRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\EprPortal;
use App\Services\EprPortalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\PublicEprPortalResource;

class EprPortalController extends Controller
{
    protected $eprPortalService;

    public function __construct()
    {
        $this->eprPortalService = new EprPortalService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'EPR Portals';
        return view('secure.epr_portals.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $portals = $this->eprPortalService->findAll();
            return DataTables::of($portals)
                ->addColumn('is_live_status', function ($portal) {
                    return $portal->is_live ? '<span class="badge bg-success">Online</span>' : '<span class="badge bg-danger">Offline</span>';
                })
                ->addColumn('action', function ($portal) {
                    $button = '';
                    if (auth()->user()->can('view epr portal')) {
                        $button .= '<a href="' . route('epr-portals.show', $portal->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit epr portal')) {
                        $button .= '<a href="' . route('epr-portals.edit', $portal->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete epr portal')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-epr-portal" data-id="' . $portal->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'file', 'is_live_status'])
                ->make(true);
        }
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add EPR Portal';
        return view('secure.epr_portals.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEprPortalRequest $request)
    {
        try {
            $isLive = $request->has('is_live') && $request->is_live ? 1 : 0;

            $portalDto = new EprPortalDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('link'),
                0,
                0,
                $isLive,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            $eprPortal = $this->eprPortalService->create($portalDto);

            if (!$eprPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving epr portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'EPR Portal created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('EPR Portal addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View EPR Portal';
        $eprPortal = $this->eprPortalService->findById($id);
        return view('secure.epr_portals.show', compact('eprPortal', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit EPR Portal';
        $eprPortal = $this->eprPortalService->findById($id);
        return view('secure.epr_portals.edit', compact('eprPortal', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEprPortalRequest $request, EprPortal $eprPortal)
    {
        try {
            $isLive = $request->has('is_live') && $request->is_live ? 1 : 0;

            $portalDto = new EprPortalDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('link'),
                0,
                0,
                $isLive,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );
            $eprPortalResult = $this->eprPortalService->update($portalDto, $eprPortal->id);

            if (!$eprPortalResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating epr portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'EPR Portal updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('EPR Portal updation failed: ' . $e->getMessage());
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
            $eprPortal = $this->eprPortalService->delete($id);
            if (!$eprPortal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting epr portal.',
                ], 500);
            }

            return response()->json(['message' => 'EPR Portal moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('EPR Portal deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, EprPortal $eprPortal)
    {
        try {
            $updated = $this->eprPortalService->approve(
                $eprPortal->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $eprPortal->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving epr portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('EPR Portal approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, EprPortal $eprPortal)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $eprPortal->is_approved == 1 || $isPublished == 1 ? 1 : $eprPortal->is_approved;
            $remarks = $eprPortal->is_approved == 1
                ? $eprPortal->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $eprPortal->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->eprPortalService->publish($eprPortal->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing epr portal.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('EPR Portal publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findForPublic()
    {
        try {
            $eprPortals = $this->eprPortalService->findForPublic();
            if (!$eprPortals) {
                return response()->json([
                    'success' => false,
                    'message' => 'No epr portals found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $eprPortals
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching epr portals for public: ' . $e->getMessage());
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
        $data = $this->eprPortalService->findForPublic($limit);
        return response()->json([
            'success' => true,
            'data' => PublicEprPortalResource::collection($data),
            'lastUpdatedOn' => EprPortal::getLastUpdatedOrCreatedAt(),
        ]);
    }
}
