<?php

namespace App\Http\Controllers\Secure;

use App\DTO\QualityZoneDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQualityZoneRequest;
use App\Http\Requests\UpdateQualityZoneRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\QualityZone;
use App\Services\QualityZoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class QualityZoneController extends Controller
{
    protected $qualityZoneService;

    public function __construct()
    {
        $this->qualityZoneService = new QualityZoneService();
    }

    public function index(Request $request)
    {
        $pageTitle = 'Quality Zones';
        return view('secure.quality_zone.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = QualityZone::query();
            $data = $query->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    if ($row->is_published == 1) {
                        return '<span class="badge bg-success">' . $row->is_published_desc . '</span>';
                    } else {
                        return '<span class="badge bg-warning">' . $row->is_published_desc . '</span>';
                    }
                })
                ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved == 1) {
                        return '<span class="badge bg-success">' . $row->is_approved_desc . '</span>';
                    } elseif ($row->is_approved == 2) {
                        return '<span class="badge bg-danger">' . $row->is_approved_desc . '</span>';
                    } else {
                        return '<span class="badge bg-warning">' . $row->is_approved_desc . '</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = '';
                    if (auth()->user()->can('view quality zone')) {
                        $button .= '<a href="' . route('quality-zones.show', $data->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }
                    if (auth()->user()->can('edit quality zone')) {
                        $button .= '<a href="' . route('quality-zones.edit', $data->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }
                    if (auth()->user()->can('delete quality zone')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-record" data-id="' . $data->id . '" title="Delete Record">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'status', 'approval_status'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Add Quality Zone';
        return view('secure.quality_zone.create', compact('pageTitle'));
    }

    public function store(StoreQualityZoneRequest $request)
    {
        try {
            $dto = new QualityZoneDto(
                $request->input('title'),
                $request->input('title_hi'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->id(),
                auth()->id()
            );

            $result = $this->qualityZoneService->create($dto);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!',
                'redirect_url' => route('quality-zones.index')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Quality Zone addition failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $pageTitle = 'View Quality Zone';
        $record = $this->qualityZoneService->findById($id);
        return view('secure.quality_zone.show', compact('record', 'pageTitle'));
    }

    public function edit(string $id)
    {
        $pageTitle = 'Edit Quality Zone';
        $record = $this->qualityZoneService->findById($id);
        return view('secure.quality_zone.edit', compact('record', 'pageTitle'));
    }

    public function update(UpdateQualityZoneRequest $request, $id)
    {
        try {
            $record = $this->qualityZoneService->findById($id);
            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $dto = new QualityZoneDto(
                $request->input('title'),
                $request->input('title_hi'),
                0, // Reset is_approved
                0, // Reset is_published
                null, // Reset remarks
                null, // Reset publish_remark
                $record->created_by,
                auth()->id()
            );

            $updated = $this->qualityZoneService->update($dto, $id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating record.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully!',
                'redirect_url' => route('quality-zones.index')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Quality Zone updating failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->qualityZoneService->delete($id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting record.',
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'Record moved to trash successfully!']);
        } catch (\Exception $e) {
            Log::error('Quality Zone deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, QualityZone $qualityZone)
    {
        try {
            $updated = $this->qualityZoneService->approve(
                $qualityZone->id,
                $request->is_approved,
                $request->remarks ? strip_tags($request->remarks) : null,
                $qualityZone->publish_remark
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
            Log::error('Quality Zone approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function publish(PublishRequest $request, QualityZone $qualityZone)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $qualityZone->is_approved == 1 || $isPublished == 1 ? 1 : $qualityZone->is_approved;
            $remarks = $qualityZone->is_approved == 1
                ? $qualityZone->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $qualityZone->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->qualityZoneService->publish(
                $qualityZone->id,
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
            Log::error('Quality Zone publishing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
