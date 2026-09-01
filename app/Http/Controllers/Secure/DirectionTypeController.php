<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use App\Models\DirectionType;
use App\Models\DirectionActType;
use App\Http\Requests\StoreDirectionTypeRequest;
use App\Http\Requests\UpdateDirectionTypeRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Services\DirectionTypeService;
use App\DTO\DirectionTypeDto;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DirectionTypeController extends Controller
{
    protected $directionTypeService;

    public function __construct(DirectionTypeService $directionTypeService)
    {
        $this->directionTypeService = $directionTypeService;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Direction Types';
        return view('secure.direction_types.index', compact('pageTitle'));
    }


    public function fetchForDatatable(Request $request)
    {
        $directionTypes = $this->directionTypeService->findAll();
        return DataTables::of($directionTypes)
            ->addColumn('action', function ($row) {
                $editBtn = $deleteBtn = $viewBtn = '';

                if (auth()->user()->can('edit direction type')) {
                    $editBtn = '<a href="' . route('direction-types.edit', $row->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete direction type')) {
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                }
                if (auth()->user()->can('view direction type')) {
                    $viewBtn = '<a href="' . route('direction-types.show', $row->id) . '" class="btn btn-sm btn-info text-white"><i class="fa fa-eye"></i></a>';
                }
                return '<div class="btn-group">' . $viewBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Create Direction Type';
        $actTypes = DirectionActType::where('is_published', 1)->get();
        return view('secure.direction_types.create', compact('pageTitle', 'actTypes'));
    }

    public function store(StoreDirectionTypeRequest $request)
    {
        try {
            $directionTypeDto = new DirectionTypeDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('direction_act_type_id'),
                0, // is_approved default 0
                0, // is_published default 0
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->directionTypeService->create($directionTypeDto);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record created successfully!',
                    'redirect_url' => route('direction-types.index')
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while saving direction type.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Direction Type creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function edit(DirectionType $directionType)
    {
        $actTypes = DirectionActType::where('is_published', 1)->get();
        return view('secure.direction_types.edit', compact('directionType', 'actTypes'));
    }

    public function update(UpdateDirectionTypeRequest $request, DirectionType $directionType)
    {
        try {
            $directionTypeDto = new DirectionTypeDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('direction_act_type_id'),
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $directionType->created_by,
                auth()->id()
            );

            $updated = $this->directionTypeService->update($directionTypeDto, $directionType->id);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully!',
                    'redirect_url' => route('direction-types.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error while updating direction type.'
            ]);
        } catch (\Exception $e) {
            Log::error('Direction Type update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show(DirectionType $directionType)
    {
        return view('secure.direction_types.show', compact('directionType'));
    }

    public function destroy(DirectionType $directionType)
    {
        try {
            $deleted = $this->directionTypeService->delete($directionType->id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting direction type.'
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Direction Type deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, DirectionType $directionType)
    {
        try {
            $updated = $this->directionTypeService->approve(
                $directionType->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $directionType->publish_remark
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
            Log::error('Direction Type approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, DirectionType $directionType)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $directionType->is_approved == 1 || $isPublished == 1 ? 1 : $directionType->is_approved;
            $remarks = $directionType->is_approved == 1
                ? $directionType->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $directionType->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->directionTypeService->publish(
                $directionType->id,
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
            Log::error('Direction Type publish failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
