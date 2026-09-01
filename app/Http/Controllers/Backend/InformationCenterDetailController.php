<?php

namespace App\Http\Controllers\Backend;

use App\DTO\InformationCenterDetailDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\InformationCenter\CreateInformationCenterDetailRequest;
use App\Http\Requests\InformationCenter\UpdateInformationCenterDetailRequest;
use App\Models\InformationCenter;
use App\Models\InformationCenterDetail;
use App\Services\InformationCenterDetailService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InformationCenterDetailController extends Controller
{
    public function __construct(
        private InformationCenterDetailService $service
    ) {
        $this->middleware('permission:view information centers')->only(['index', 'show']);
        $this->middleware('permission:create information centers')->only(['create', 'store']);
        $this->middleware('permission:edit information centers')->only(['edit', 'update']);
        $this->middleware('permission:delete information centers')->only(['destroy']);
        $this->middleware('permission:approve information centers')->only(['approve']);
        $this->middleware('permission:publish information centers')->only(['publish']);
    }

    public function index(Request $request, InformationCenter $informationCenter)
    {
        if ($request->ajax()) {
            $details = $informationCenter->details();

            return DataTables::of($details)
                ->addIndexColumn()
                ->addColumn('title', fn(InformationCenterDetail $detail) => $detail->title)
                ->addColumn('type', fn(InformationCenterDetail $detail) => $detail->type)
                ->addColumn('is_approved', fn(InformationCenterDetail $detail) => $detail->is_approved_desc ?? 'Pending')
                ->addColumn('is_published', fn(InformationCenterDetail $detail) => $detail->is_published_desc ?? 'Draft')
                ->addColumn('created_by', fn(InformationCenterDetail $detail) => $detail->createdBy->name ?? 'Unknown')
                ->addColumn('created_at', fn(InformationCenterDetail $detail) => $detail->created_at->format('d-m-Y H:i'))
                ->addColumn('action', fn(InformationCenterDetail $detail) => view('backend.components.action-buttons', [
                    'model' => $detail,
                    'editRoute' => 'information-center-details.edit',
                    'showRoute' => 'information-center-details.show',
                    'deleteRoute' => 'information-center-details.destroy',
                ])->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.information-center-details.index', ['center' => $informationCenter]);
    }

    public function create(InformationCenter $informationCenter)
    {
        return view('backend.information-center-details.create', ['center' => $informationCenter]);
    }

    public function store(CreateInformationCenterDetailRequest $request, InformationCenter $informationCenter)
    {
        try {
            $dto = new InformationCenterDetailDto(
                information_center_id: $informationCenter->id,
                type: $request->type,
                url: $request->url,
                title: $request->title,
                title_hi: $request->title_hi,
                content: $request->content,
                content_hi: $request->content_hi,
                file_name: $request->file_name ? $request->file('file_name')->getClientOriginalName() : null,
                file_name_hi: $request->file_name_hi ? $request->file('file_name_hi')->getClientOriginalName() : null,
                featured_image: $request->featured_image ? $request->file('featured_image')->getClientOriginalName() : null,
                is_approved: $request->is_approved ?? 0,
                is_published: $request->is_published ?? 0,
                remarks: $request->remarks,
                created_by: auth()->id(),
                updated_by: auth()->id(),
            );

            $detail = $this->service->create($dto, $request);

            return redirect()->route('information-center-details.show', [$informationCenter->id, $detail->id])
                ->with('success', 'Information Center Detail created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create Information Center Detail: ' . $e->getMessage());
        }
    }

    public function show(InformationCenter $informationCenter, InformationCenterDetail $detail)
    {
        return view('backend.information-center-details.show', [
            'center' => $informationCenter,
            'detail' => $detail,
        ]);
    }

    public function edit(InformationCenter $informationCenter, InformationCenterDetail $detail)
    {
        return view('backend.information-center-details.edit', [
            'center' => $informationCenter,
            'detail' => $detail,
        ]);
    }

    public function update(UpdateInformationCenterDetailRequest $request, InformationCenter $informationCenter, InformationCenterDetail $detail)
    {
        try {
            $dto = new InformationCenterDetailDto(
                information_center_id: $informationCenter->id,
                type: $request->type ?? $detail->type,
                url: $request->url,
                title: $request->title ?? $detail->title,
                title_hi: $request->title_hi,
                content: $request->content,
                content_hi: $request->content_hi,
                file_name: $request->file_name ? $request->file('file_name')->getClientOriginalName() : $detail->file_name,
                file_name_hi: $request->file_name_hi ? $request->file('file_name_hi')->getClientOriginalName() : $detail->file_name_hi,
                featured_image: $request->featured_image ? $request->file('featured_image')->getClientOriginalName() : $detail->featured_image,
                is_approved: $request->is_approved ?? $detail->is_approved,
                is_published: $request->is_published ?? $detail->is_published,
                remarks: $request->remarks,
                created_by: $detail->created_by,
                updated_by: auth()->id(),
            );

            $this->service->update($detail->id, $dto, $request);

            return redirect()->route('information-center-details.show', [$informationCenter->id, $detail->id])
                ->with('success', 'Information Center Detail updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update Information Center Detail: ' . $e->getMessage());
        }
    }

    public function destroy(InformationCenter $informationCenter, InformationCenterDetail $detail)
    {
        try {
            $this->service->delete($detail->id);

            return redirect()->route('information-center-details.index', $informationCenter->id)
                ->with('success', 'Information Center Detail deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete Information Center Detail: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, InformationCenter $informationCenter, InformationCenterDetail $detail)
    {
        try {
            $request->validate([
                'is_approved' => 'required|boolean',
                'remarks' => 'nullable|string|max:1000',
            ]);

            $this->service->approve(
                $detail->id,
                $request->is_approved,
                $request->remarks
            );

            return redirect()->back()
                ->with('success', 'Information Center Detail approval status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update approval status: ' . $e->getMessage());
        }
    }

    public function publish(Request $request, InformationCenter $informationCenter, InformationCenterDetail $detail)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $detail->is_approved == 1 || $isPublished == 1 ? 1 : $detail->is_approved;
            $remarks = $detail->is_approved == 1
                ? $detail->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $detail->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $this->service->publish($detail->id, $isApproved, $remarks, $isPublished, $publishRemark);

            return redirect()->back()
                ->with('success', 'Information Center Detail publish status updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update publish status: ' . $e->getMessage());
        }
    }

    public function restore(InformationCenter $informationCenter, $detailId)
    {
        try {
            $detail = InformationCenterDetail::withTrashed()->findOrFail($detailId);
            $detail->restore();

            return redirect()->back()->with('success', 'Information Center Detail restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to restore Information Center Detail: ' . $e->getMessage());
        }
    }

    public function forceDelete(InformationCenter $informationCenter, $detailId)
    {
        try {
            $detail = InformationCenterDetail::withTrashed()->findOrFail($detailId);
            $this->service->delete($detail->id);
            $detail->forceDelete();

            return redirect()->route('information-center-details.index', $informationCenter->id)
                ->with('success', 'Information Center Detail permanently deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to permanently delete Information Center Detail: ' . $e->getMessage());
        }
    }
}
