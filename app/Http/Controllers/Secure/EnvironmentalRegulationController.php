<?php

namespace App\Http\Controllers\Secure;

use App\DTO\EnvironmentalRegulationDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnvironmentalRegulationRequest;
use App\Http\Requests\UpdateEnvironmentalRegulationRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\EnvironmentalRegulationResource;
use App\Models\EnvironmentalRegulation;
use App\Services\EnvironmentalRegulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class EnvironmentalRegulationController extends Controller
{
    protected EnvironmentalRegulationService $environmentalRegulationService;

    public function __construct()
    {
        $this->environmentalRegulationService = new EnvironmentalRegulationService();
    }

    /**
     * Display regulation listing page
     */
    public function index()
    {
        $pageTitle = 'Environmental Regulation Setup';
        return view('secure.home.environmental_regulation.index', compact('pageTitle'));
    }

    /**
     * Fetch regulations for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $regulations = $this->environmentalRegulationService->findAll();

            return DataTables::of($regulations)
                ->addColumn('action', function ($regulation) {
                    $buttons = '';

                    if (auth()->user()->can('view environmental regulation')) {
                        $buttons .= '<a href="' . route('environmental-regulation.show', $regulation->id) . '"
                            class="btn btn-sm btn-primary" title="View">
                            <i class="fa fa-eye"></i>
                        </a> ';
                    }

                    if (auth()->user()->can('edit environmental regulation')) {
                        $buttons .= '<a href="' . route('environmental-regulation.edit', $regulation->id) . '"
                            class="btn btn-sm btn-warning" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a> ';
                    }

                    if (auth()->user()->can('delete environmental regulation')) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-regulation"
                            data-id="' . $regulation->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show create regulation form
     */
    public function create()
    {
        $pageTitle = 'Create Environmental Regulation';
        return view('secure.home.environmental_regulation.create', compact('pageTitle'));
    }

    /**
     * Store new regulation
     */
    public function store(StoreEnvironmentalRegulationRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = new EnvironmentalRegulationDto(
                $request->title,
                $request->title_hi,
                0,
                0,
                null,
                null,
                auth()->id(),
                auth()->id()
            );

            $result = $this->environmentalRegulationService->create($dto);

            if ($result) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Environmental Regulation created successfully!',
                    'redirect_url' => route('environmental-regulation.index')
                ], 201);
            }

            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error while saving regulation.'
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Environmental Regulation creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Display specific regulation
     */
    public function show(string $id)
    {
        $pageTitle = 'View Environmental Regulation';
        $regulation = $this->environmentalRegulationService->findById($id);

        return view(
            'secure.home.environmental_regulation.show',
            compact('regulation', 'pageTitle')
        );
    }

    /**
     * Show edit regulation form
     */
    public function edit(EnvironmentalRegulation $environmentalRegulation)
    {
        $pageTitle = 'Edit Environmental Regulation';
        return view(
            'secure.home.environmental_regulation.edit',
            compact('pageTitle', 'environmentalRegulation')
        );
    }

    /**
     * Update regulation
     */
    public function update(
        UpdateEnvironmentalRegulationRequest $request,
        EnvironmentalRegulation $environmentalRegulation
    ) {
        DB::beginTransaction();

        try {
            $dto = new EnvironmentalRegulationDto(
                $request->title,
                $request->title_hi,
                0,
                0,
                null,
                null,
                $environmentalRegulation->created_by,
                auth()->id()
            );

            $updated = $this->environmentalRegulationService
                ->update($dto, $environmentalRegulation->id);

            if ($updated) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Environmental Regulation updated successfully!',
                    'redirect_url' => route('environmental-regulation.index')
                ]);
            }

            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error while saving regulation.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Environmental Regulation update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /**
     * Delete regulation
     */
    public function destroy(EnvironmentalRegulation $environmentalRegulation)
    {
        DB::beginTransaction();

        try {
            $deleted = $this->environmentalRegulationService
                ->delete($environmentalRegulation->id);

            if (!$deleted) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting regulation.'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Environmental Regulation deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Approve / Reject regulation
     */
    public function approve(ApproveRequest $request, EnvironmentalRegulation $environmentalRegulation)
    {
        try {
            $dto = new EnvironmentalRegulationDto(
                $environmentalRegulation->title,
                $environmentalRegulation->title_hi,
                $request->is_approved,
                0,
                strip_tags($request->remarks) ?? null,
                $environmentalRegulation->publish_remark,
                $environmentalRegulation->created_by,
                auth()->id()
            );

            $this->environmentalRegulationService->approve($dto, $environmentalRegulation->id);

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Environmental Regulation approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while approving regulation.',
            ], 500);
        }
    }

    /**
     * Publish / Unpublish regulation
     */
    public function publish(PublishRequest $request, EnvironmentalRegulation $environmentalRegulation)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $environmentalRegulation->is_approved == 1 || $isPublished == 1 ? 1 : $environmentalRegulation->is_approved;
            $remarks = $environmentalRegulation->is_approved == 1
                ? $environmentalRegulation->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing' : $environmentalRegulation->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $dto = new EnvironmentalRegulationDto(
                $environmentalRegulation->title,
                $environmentalRegulation->title_hi,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $environmentalRegulation->created_by,
                auth()->id()
            );

            $this->environmentalRegulationService->publish($dto, $environmentalRegulation->id);

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Environmental Regulation publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error while publishing regulation.',
            ], 500);
        }
    }

    /**
     * Fetch published regulations for public API
     */
    // public function findAllforPublic()
    // {
    //     return response()->json([
    //         'success' => true,
    //         'data' => EnvironmentalRegulationResource::collection($this->environmentalRegulationService->findForPublic()),
    //     ]);
    // }

     public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => EnvironmentalRegulationResource::collection($this->environmentalRegulationService->findForPublic()),
        ]);
    }
}
