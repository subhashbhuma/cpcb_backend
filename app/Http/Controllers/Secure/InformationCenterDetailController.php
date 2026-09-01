<?php

namespace App\Http\Controllers\Secure;

use App\DTO\InformationCenterDetailDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\InformationCenter\CreateInformationCenterDetailRequest;
use App\Http\Requests\InformationCenter\UpdateInformationCenterDetailRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\InformationCenterDetailResource;
use App\Models\InformationCenter;
use App\Models\InformationCenterDetail;
use App\Services\InformationCenterDetailService;
use App\Services\InformationCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InformationCenterDetailController extends Controller
{
    protected $service;
    protected $informationCenterService;

    public function __construct()
    {
        $this->service = new InformationCenterDetailService();
        $this->informationCenterService = new InformationCenterService();
    }

    /* ---------------------------------
     | Index
     |---------------------------------*/

    public function index()
    {
        $pageTitle = 'Information Center Details';
        return view('secure.home.information_center_details.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $records = $this->service->getAll();

            return DataTables::of($records)
                ->addColumn('action', function ($detail) {
                    $button = '';

                    if (auth()->user()->can('view information center detail')) {
                        $button .= '<a href="' . route('information-center-details.show', $detail->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit information center detail')) {
                        $button .= '<a href="' . route('information-center-details.edit', $detail->id) . '" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete information center detail')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-record" data-id="' . $detail->id . '">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }

                    return $button;
                })
                ->addColumn('tab_name', function ($detail) {
                    return $detail->informationCenter->title ?? "";
                })
                ->rawColumns(['action', 'tab_name'])
                ->make(true);
        }
    }

    /* ---------------------------------
     | Create
     |---------------------------------*/

    public function create()
    {
        $pageTitle = 'Create Information Center Detail';
        $centers = $this->informationCenterService->getAll('backend');
        return view('secure.home.information_center_details.create', compact('pageTitle', 'centers'));
    }

    public function store(CreateInformationCenterDetailRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = new InformationCenterDetailDto(
                information_center_id: $request->input('information_center_id'),
                type: $request->input('type'),
                url: $request->input('url'),
                title: $request->input('title'),
                title_hi: $request->input('title_hi'),
                file_name: null,
                file_name_hi: null,
                is_approved: 0,
                is_published: 0,
                remarks: null,
                publish_remark: null,
                created_by: auth()->id(),
                updated_by: auth()->id(),
            );

            $detail = $this->service->create($dto, $request);
            if (!$detail) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Error while saving record'], 500);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Record created successfully',
                'redirect_url' => route('information-center-details.index')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('InformationCenterDetail create failed', ['error' => $e->getMessage()]);
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    /* ---------------------------------
     | Show
     |---------------------------------*/

    public function show(InformationCenterDetail $informationCenterDetail)
    {
        return view('secure.home.information_center_details.show', [
            'detail' => $informationCenterDetail,
            'center' => $informationCenterDetail->informationCenter,
            'pageTitle' => 'View Information Center Detail'
        ]);
    }

    /* ---------------------------------
     | Edit
     |---------------------------------*/

    public function edit(InformationCenterDetail $informationCenterDetail)
    {
        $centers = InformationCenter::all();
        return view('secure.home.information_center_details.edit', [
            'detail' => $informationCenterDetail,
            'center' => $informationCenterDetail->informationCenter,
            'centers' => $centers,
            'pageTitle' => 'Edit Information Center Detail'
        ]);
    }

    /* ---------------------------------
     | Update
     |---------------------------------*/

    public function update(UpdateInformationCenterDetailRequest $request, InformationCenterDetail $informationCenterDetail)
    {
        DB::beginTransaction();

        try {
            $dto = new InformationCenterDetailDto(
                information_center_id: $request->input('information_center_id', $informationCenterDetail->information_center_id),
                type: $request->type ?? $informationCenterDetail->type,
                url: $request->url,
                title: $request->title ?? $informationCenterDetail->title,
                title_hi: $request->title_hi,
                file_name: $informationCenterDetail->file_name,
                file_name_hi: $informationCenterDetail->file_name_hi,
                is_approved: 0,
                is_published: 0,
                remarks: null,
                publish_remark: null,
                created_by: $informationCenterDetail->created_by,
                updated_by: auth()->id(),
            );

            $this->service->update($informationCenterDetail->id, $dto, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Information Center Detail updated successfully!',
                'redirect_url' => route('information-center-details.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Information Center Detail: ' . $e->getMessage()
                ], 422);
            }
            return back()->with('error', 'Failed to update Information Center Detail: ' . $e->getMessage());
        }
    }

    public function destroy(InformationCenterDetail $informationCenterDetail)
    {
        try {
            $this->service->delete($informationCenterDetail->id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Information Center Detail deleted successfully!'
                ]);
            }

            return redirect()->route('information-center-details.index')
                ->with('success', 'Information Center Detail deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Information Center Detail deletion failed: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Something went wrong!');
        }
    }

    /* ---------------------------------
     | Approve
     |---------------------------------*/

    public function approve(ApproveRequest $request, InformationCenterDetail $informationCenterDetail)
    {
        try {
            $this->service->approve(
                $informationCenterDetail->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $informationCenterDetail->publish_remark
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Approval decision submitted successfully!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Approval decision submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Information Center Detail approval failed: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Something went wrong!');
        }
    }

    /* ---------------------------------
     | Publish
     |---------------------------------*/

    public function publish(PublishRequest $request, InformationCenterDetail $informationCenterDetail)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $informationCenterDetail->is_approved == 1 || $isPublished == 1 ? 1 : $informationCenterDetail->is_approved;
            $remarks = $informationCenterDetail->is_approved == 1
                ? $informationCenterDetail->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $informationCenterDetail->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $this->service->publish($informationCenterDetail->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Publish decision submitted successfully!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Publish decision submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Information Center Detail publish failed: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Something went wrong!');
        }
    }

    /* ---------------------------------
     | Restore
     |---------------------------------*/

    public function restore($detailId)
    {
        try {
            $detail = InformationCenterDetail::withTrashed()->findOrFail($detailId);
            $detail->restore();

            return redirect()->back()->with('success', 'Information Center Detail restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to restore Information Center Detail: ' . $e->getMessage());
        }
    }

    /* ---------------------------------
     | Force Delete
     |---------------------------------*/

    public function forceDelete($detailId)
    {
        try {
            $detail = InformationCenterDetail::withTrashed()->findOrFail($detailId);
            $this->service->delete($detail->id);
            $detail->forceDelete();

            return redirect()->route('information-center-details.index')
                ->with('success', 'Information Center Detail permanently deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to permanently delete Information Center Detail: ' . $e->getMessage());
        }
    }

    /* ---------------------------------
     | Public API Methods
     |---------------------------------*/

    /**
     * Fetch details by information center ID for public API
     */
    public function byCenterId(Request $request)
    {
        $centerId = $request->center_id;
        // $limit = $request->limit;
        $result = $this->service->getByInformationCenterIdForPublic($centerId);
        // if ($limit) {
        //     $result = $result->take($limit);
        // }
        return response()->json([
            'success' => true,
            'data' => InformationCenterDetailResource::collection($result),
        ]);
    }

    /**
     * Find details by URL for public
     */
    public function findByUrlForPublic($url)
    {
        try {
            $decodedUrl = base64_decode($url);
            $details = InformationCenterDetail::where('url', $decodedUrl)
                ->where('is_published', 1)
                ->where('is_approved', 1)
                ->get();
            return response()->json([
                'success' => true,
                'data' => $details
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetching information center details by URL failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching information center details.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
