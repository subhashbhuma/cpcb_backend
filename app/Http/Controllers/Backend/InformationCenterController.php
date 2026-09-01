<?php

namespace App\Http\Controllers\Backend;

use App\DTO\InformationCenterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\InformationCenter\StoreInformationCenterRequest;
use App\Http\Requests\InformationCenter\UpdateInformationCenterRequest;
use App\Models\InformationCenter;
use App\Services\InformationCenterService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InformationCenterController extends Controller
{
    public function __construct(
        private InformationCenterService $service
    ) {
        $this->middleware('permission:view information centers')->only(['index', 'show']);
        $this->middleware('permission:create information centers')->only(['create', 'store']);
        $this->middleware('permission:edit information centers')->only(['edit', 'update']);
        $this->middleware('permission:delete information centers')->only(['destroy']);
        $this->middleware('permission:approve information centers')->only(['approve']);
        $this->middleware('permission:publish information centers')->only(['publish']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $centers = InformationCenter::query();

            return DataTables::of($centers)
                ->addIndexColumn()
                ->addColumn('title', fn(InformationCenter $center) => $center->title)
                ->addColumn('is_approved', fn(InformationCenter $center) => $center->is_approved_desc ?? 'Pending')
                ->addColumn('is_published', fn(InformationCenter $center) => $center->is_published_desc ?? 'Draft')
                ->addColumn('details_count', fn(InformationCenter $center) => $center->details()->count())
                ->addColumn('created_by', fn(InformationCenter $center) => $center->createdBy->name ?? 'Unknown')
                ->addColumn('created_at', fn(InformationCenter $center) => $center->created_at->format('d-m-Y H:i'))
                ->addColumn('action', fn(InformationCenter $center) => view('backend.components.action-buttons', [
                    'model' => $center,
                    'editRoute' => 'information-centers.edit',
                    'showRoute' => 'information-centers.show',
                    'deleteRoute' => 'information-centers.destroy',
                ])->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.information-centers.index');
    }

    public function create()
    {
        return view('backend.information-centers.create');
    }

    public function store(StoreInformationCenterRequest $request)
    {
        try {
            $dto = new InformationCenterDto(
                title: $request->title,
                title_hi: $request->title_hi,
                is_approved: $request->is_approved ?? 0,
                is_published: $request->is_published ?? 0,
                remarks: $request->remarks,
                created_by: auth()->id(),
                updated_by: auth()->id(),
            );

            $center = $this->service->create($dto);

            return redirect()->route('information-centers.show', $center->id)
                ->with('success', 'Information Center created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create Information Center: ' . $e->getMessage());
        }
    }

    public function show(InformationCenter $informationCenter)
    {
        return view('backend.information-centers.show', ['center' => $informationCenter]);
    }

    public function edit(InformationCenter $informationCenter)
    {
        return view('backend.information-centers.edit', ['center' => $informationCenter]);
    }

    public function update(UpdateInformationCenterRequest $request, InformationCenter $informationCenter)
    {
        try {
            $dto = new InformationCenterDto(
                title: $request->title,
                title_hi: $request->title_hi,
                is_approved: $request->is_approved ?? $informationCenter->is_approved,
                is_published: $request->is_published ?? $informationCenter->is_published,
                remarks: $request->remarks,
                created_by: $informationCenter->created_by,
                updated_by: auth()->id(),
            );

            $this->service->update($informationCenter->id, $dto);

            return redirect()->route('information-centers.show', $informationCenter->id)
                ->with('success', 'Information Center updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update Information Center: ' . $e->getMessage());
        }
    }

    public function destroy(InformationCenter $informationCenter)
    {
        try {
            $this->service->delete($informationCenter->id);

            return redirect()->route('information-centers.index')
                ->with('success', 'Information Center deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete Information Center: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, InformationCenter $informationCenter)
    {
        try {
            $request->validate([
                'is_approved' => 'required|boolean',
                'remarks' => 'nullable|string|max:1000',
            ]);

            $this->service->approve(
                $informationCenter->id,
                $request->is_approved,
                $request->remarks
            );

            return redirect()->back()
                ->with('success', 'Information Center approval status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update approval status: ' . $e->getMessage());
        }
    }

    public function publish(Request $request, InformationCenter $informationCenter)
    {
        try {
            $request->validate(['is_published' => 'required|boolean']);

            $this->service->publish($informationCenter->id, $request->is_published);

            return redirect()->back()
                ->with('success', 'Information Center publish status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update publish status: ' . $e->getMessage());
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
}
