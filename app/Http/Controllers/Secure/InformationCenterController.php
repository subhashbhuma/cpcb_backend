<?php

namespace App\Http\Controllers\Secure;

use App\DTO\InformationCenterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\InformationCenter\StoreInformationCenterRequest;
use App\Http\Requests\InformationCenter\UpdateInformationCenterRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\InformationCenterResource;
use App\Models\InformationCenter;
use App\Services\InformationCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InformationCenterController extends Controller
{
    protected $service;

    public function __construct(
    ) {
        $this->service = new InformationCenterService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->fetchForDatatable($request);
        }

        return view('secure.home.information_centers.index', ['pageTitle' => 'Information Centers']);
    }

    /**
     * Fetch centers for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $centers = InformationCenter::query();

            return DataTables::of($centers)
                ->addIndexColumn()
                ->addColumn('title', fn(InformationCenter $center) => $center->title)
                ->addColumn('description', fn(InformationCenter $center) => substr($center->description, 0, 50) . '...')
                ->addColumn('is_approved', fn(InformationCenter $center) => $center->is_approved_desc ?? 'Pending')
                ->addColumn('is_approved_desc', fn(InformationCenter $center) => $center->is_approved_desc ?? 'Pending')
                ->addColumn('is_published', fn(InformationCenter $center) => $center->is_published_desc ?? 'Draft')
                ->addColumn('is_published_desc', fn(InformationCenter $center) => $center->is_published_desc ?? 'Draft')
                ->addColumn('action', function ($informationCenter) {
                    $buttons = '';

                    if (auth()->user()->can('view information center')) {
                        $buttons .= '<a href="' . route('information-centers.show', $informationCenter->id) . '"
                        class="btn btn-sm btn-primary" title="View">
                        <i class="fa fa-eye"></i>
                    </a> ';
                    }

                    if (auth()->user()->can('edit information center')) {
                        $buttons .= '<a href="' . route('information-centers.edit', $informationCenter->id) . '"
                        class="btn btn-sm btn-warning" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a> ';
                    }

                    if (auth()->user()->can('delete information center')) {
                        $buttons .= '<button class="btn btn-sm btn-danger delete-information-center"
                        data-id="' . $informationCenter->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);


        }
    }

    public function create()
    {
        return view('secure.home.information_centers.create', ['pageTitle' => 'Create Information Center']);
    }

    public function store(StoreInformationCenterRequest $request)
    {
        try {
            $dto = new InformationCenterDto(
                title: $request->title,
                title_hi: $request->title_hi,
                is_approved: 0,
                is_published: 0,
                remarks: null,
                publish_remark: null,
                created_by: auth()->id(),
                updated_by: auth()->id(),
            );

            $this->service->create($dto);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Information Center created successfully!',
                    'redirect_url' => route('information-centers.index')
                ], 201);
            }

            return redirect()->route('information-centers.index')
                ->with('success', 'Information Center created successfully!');
        } catch (\Exception $e) {
            Log::error('Information Center creation failed: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
            }
            return back()->with('error', 'An unexpected error occurred.');
        }
    }

    public function show(InformationCenter $informationCenter)
    {
        return view('secure.home.information_centers.show', ['center' => $informationCenter, 'pageTitle' => 'View Information Center']);
    }

    public function edit(InformationCenter $informationCenter)
    {
        return view('secure.home.information_centers.edit', ['center' => $informationCenter, 'pageTitle' => 'Edit Information Center']);
    }

    public function update(UpdateInformationCenterRequest $request, InformationCenter $informationCenter)
    {
        try {
            $dto = new InformationCenterDto(
                title: $request->title,
                title_hi: $request->title_hi,
                is_approved: 0,
                is_published: 0,
                remarks: null,
                publish_remark: null,
                created_by: $informationCenter->created_by,
                updated_by: auth()->id(),
            );

            $this->service->update($informationCenter->id, $dto);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Information Center updated successfully!',
                    'redirect_url' => route('information-centers.index')
                ]);
            }

            return redirect()->route('information-centers.show', $informationCenter->id)
                ->with('success', 'Information Center updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Information Center: ' . $e->getMessage()
                ], 422);
            }
            return back()->with('error', 'Failed to update Information Center: ' . $e->getMessage());
        }
    }

    public function destroy(InformationCenter $informationCenter)
    {
        try {
            $this->service->delete($informationCenter->id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Information Center deleted successfully!'
                ]);
            }

            return redirect()->route('information-centers.index')
                ->with('success', 'Information Center deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Information Center deletion failed: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function approve(ApproveRequest $request, InformationCenter $informationCenter)
    {
        try {
            $this->service->approve(
                $informationCenter->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $informationCenter->publish_remark
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
            Log::error('Information Center approval failed: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function publish(PublishRequest $request, InformationCenter $informationCenter)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $informationCenter->is_approved == 1 || $isPublished == 1 ? 1 : $informationCenter->is_approved;
            $remarks = $informationCenter->is_approved == 1
                ? $informationCenter->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $informationCenter->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $this->service->publish($informationCenter->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Publish decision submitted successfully!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Publish decision submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Information Center publish failed: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
            }
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function restore($id)
    {
        try {
            $center = InformationCenter::withTrashed()->findOrFail($id);
            $center->restore();

            return redirect()->back()->with('success', 'Information Center restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to restore Information Center: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $center = InformationCenter::withTrashed()->findOrFail($id);
            $center->forceDelete();

            return redirect()->route('information-centers.index')
                ->with('success', 'Information Center permanently deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to permanently delete Information Center: ' . $e->getMessage());
        }
    }

    /**
     * Fetch published information centers for public API
     */
    public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => InformationCenterResource::collection($this->service->getForPublic()),
        ]);
    }
}
