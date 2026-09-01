<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use App\DTO\EnvironmentalRegulationDetailDto;
use App\Http\Requests\CreateEnvironmentalRegulationDetailRequest;
use App\Http\Requests\UpdateEnvironmentalRegulationDetailRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\EnvRegDetailListResource;
use App\Models\EnvironmentalRegulationDetail;
use App\Services\EnvironmentalRegulationDetailService;
use App\Services\EnvironmentalRegulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class EnvironmentalRegulationDetailController extends Controller
{
    protected $environmentalRegulation;
    protected $detailService;

    public function __construct()
    {
        $this->environmentalRegulation = new EnvironmentalRegulationService();
        $this->detailService = new EnvironmentalRegulationDetailService();
    }

    /* ---------------------------------
     | Index
     |---------------------------------*/

    public function index()
    {
        $pageTitle = 'Environmental Regulation Details';
        return view('secure.home.environmental_regulation_details.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $records = $this->detailService->findAll();

            return DataTables::of($records)
                ->addColumn('action', function ($detail) {
                    $button = '';

                    if (auth()->user()->can('view environmental regulation detail')) {
                        $button .= '<a href="' . route('environmental-regulation-details.show', $detail->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit environmental regulation detail')) {
                        $button .= '<a href="' . route('environmental-regulation-details.edit', $detail->id) . '" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete environmental regulation detail')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-record" data-id="' . $detail->id . '">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }

                    return $button;
                })
                ->addColumn('tab_name', function ($detail) {
                    return $detail->environmentalRegulation->title ?? "";
                })
                ->addColumn('parent_title', function ($detail) {
                    return $detail->parent ? $detail->parent->title : '—';
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
        $pageTitle = 'Create Environmental Regulation Detail';
        $regulations = $this->environmentalRegulation->findAll();
        $parentItems = EnvironmentalRegulationDetail::orderBy('title')->get();
        return view('secure.home.environmental_regulation_details.create', compact('pageTitle', 'regulations', 'parentItems'));
    }

    public function store(CreateEnvironmentalRegulationDetailRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = new EnvironmentalRegulationDetailDto(
                $request->input('title'),
                $request->input('title_hi'),
                auth()->user()->id,
                $request->input('environmental_regulation_id'),
                $request->input('parent_id'),
                $request->input('order', 0),
                $request->input('type'),
                $request->input('url'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0,
                0,
                null,
                null,
                null
            );

            $detail = $this->detailService->create($dto);
            if (!$detail) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Error while saving record'], 500);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Record created successfully',
                'redirect_url' => route('environmental-regulation-details.index')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EnvironmentalRegulationDetail create failed', ['error' => $e->getMessage()]);
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Unexpected error'], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Detail';
        $detail = $this->detailService->findById($id);
        return view('secure.home.environmental_regulation_details.show', compact('detail', 'pageTitle'));
    }
    /* ---------------------------------
     | Edit / Update
     |---------------------------------*/

    public function edit(EnvironmentalRegulationDetail $environmentalRegulationDetail)
    {
        $pageTitle = 'Edit Environmental Regulation Detail';
        $detail = $this->detailService->findById($environmentalRegulationDetail->id);
        $regulations = $this->environmentalRegulation->findAll();
        $parentItems = EnvironmentalRegulationDetail::where('id', '!=', $environmentalRegulationDetail->id)
            ->orderBy('title')
            ->get();

        return view('secure.home.environmental_regulation_details.edit', compact('pageTitle', 'detail', 'regulations', 'parentItems'));
    }

    public function update(UpdateEnvironmentalRegulationDetailRequest $request, EnvironmentalRegulationDetail $environmentalRegulationDetail)
    {
        DB::beginTransaction();

        try {
            $dto = new EnvironmentalRegulationDetailDto(
                $request->input('title'),
                $request->input('title_hi'),
                $environmentalRegulationDetail->created_by,
                $request->input('environmental_regulation_id'),
                $request->input('parent_id'),
                $request->input('order', 0),
                $request->input('type'),
                $request->input('url'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                0,
                0,
                null,
                null,
                auth()->user()->id
            );

            $detail = $this->detailService->update($dto, $environmentalRegulationDetail->id);

            if (!$detail) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Update failed'], 500);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Record updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EnvironmentalRegulationDetail update failed', ['error' => $e->getMessage()]);
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Unexpected error'], 500);
        }
    }

    /* ---------------------------------
     | Approve / Publish
     |---------------------------------*/

    public function approve(ApproveRequest $request, EnvironmentalRegulationDetail $environmentalRegulationDetail)
    {
        try {
            $dto = new EnvironmentalRegulationDetailDto(
                $environmentalRegulationDetail->title,
                $environmentalRegulationDetail->title_hi,
                $environmentalRegulationDetail->created_by,
                $environmentalRegulationDetail->environmental_regulation_id,
                $environmentalRegulationDetail->parent_id,
                $environmentalRegulationDetail->order,
                $environmentalRegulationDetail->type,
                $environmentalRegulationDetail->url,
                $environmentalRegulationDetail->file_name,
                $environmentalRegulationDetail->file_name_hi,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                $environmentalRegulationDetail->publish_remark,
                auth()->user()->id
            );

            $this->detailService->approve($dto, $environmentalRegulationDetail->id);

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Environmental Regulation Detail approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while approving environmental regulation detail.',
            ], 500);
        }
    }

    public function publish(PublishRequest $request, EnvironmentalRegulationDetail $environmentalRegulationDetail)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $environmentalRegulationDetail->is_approved == 1 || $isPublished == 1 ? 1 : $environmentalRegulationDetail->is_approved;
            $remarks = $environmentalRegulationDetail->is_approved == 1
                ? $environmentalRegulationDetail->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $environmentalRegulationDetail->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $dto = new EnvironmentalRegulationDetailDto(
                $environmentalRegulationDetail->title,
                $environmentalRegulationDetail->title_hi,
                $environmentalRegulationDetail->created_by,
                $environmentalRegulationDetail->environmental_regulation_id,
                $environmentalRegulationDetail->parent_id,
                $environmentalRegulationDetail->order,
                $environmentalRegulationDetail->type,
                $environmentalRegulationDetail->url,
                $environmentalRegulationDetail->file_name,
                $environmentalRegulationDetail->file_name_hi,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                auth()->user()->id
            );

            $this->detailService->publish($dto, $environmentalRegulationDetail->id);

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Environmental Regulation Detail publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while publishing environmental regulation detail.',
            ], 500);
        }
    }

    /* ---------------------------------
     | Delete
     |---------------------------------*/

    public function destroy(EnvironmentalRegulationDetail $detail)
    {
        DB::beginTransaction();

        try {
            $this->detailService->delete($detail->id);
            DB::commit();
            return response()->json(['message' => 'Record deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Deletion failed'], 500);
        }
    }


    public function byRegulationId(Request $request)
    {
        $regulationId = $request->regulation_id;
        $limit = $request->limit;
        $result = $this->detailService->findForPublicByRegulationId($regulationId, $limit);
        return response()->json([
            'success' => true,
            'data' => EnvRegDetailListResource::collection($result),
        ]);
    }

    public function findByUrlForPublic($url)
    {
        $url = base64_decode($url);
        try {
            $pages = $this->detailService->findByUrlForPublic($url);
            return response()->json([
                'success' => true,
                'data' => $pages
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetching all pages failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching pages.',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
