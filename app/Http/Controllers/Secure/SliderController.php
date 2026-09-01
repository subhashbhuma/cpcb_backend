<?php

namespace App\Http\Controllers\Secure;

use Mews\Purifier\Facades\Purifier;
use App\DTO\SliderDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Slider;
use App\Services\SliderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    protected $sliderService;

    public function __construct()
    {
        $this->sliderService = new SliderService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Sliders';
        return view('secure.sliders.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $sliders = $this->sliderService->findAll();
            return DataTables::of($sliders)
                ->addColumn('image', function ($slider) {
                    if ($slider->file_name) {
                        return "<img src=" . generate_file_view_path_for_backend($slider->file_url) . " alt='Slider Image' class='img-fluid' style='max-height: 70px;'>";
                    }

                    return '';
                })
                ->addColumn('action', function ($slider) {
                    $button = '';
                    if (auth()->user()->can('view slider')) {
                        $button .= '<a href="' . route('sliders.show', $slider->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit slider')) {
                        $button .= '<a href="' . route('sliders.edit', $slider->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete slider')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-slider" data-id="' . $slider->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Sliders';
        return view('secure.sliders.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSliderRequest $request)
    {
        try {

            $sliderDto = new SliderDto(
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->input('description') ?? null,
                $request->input('description_hi') ?? null,
                $request->file('file_name'),
                0,
                0,
                null,
                strip_tags($request->input('publish_remark')) ?? null,
                strip_tags($request->input('link')) ?? null,
                auth()->user()->id,
                auth()->user()->id
            );

            $slider = $this->sliderService->create($sliderDto);

            if (!$slider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slider created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Slider addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Sliders';
        $slider = $this->sliderService->findById($id);
        return view('secure.sliders.show', compact('slider', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Sliders';
        $slider = $this->sliderService->findById($id);
        return view('secure.sliders.edit', compact('slider', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        try {
            $sliderDto = new SliderDto(
                strip_tags($request->input('title')),
                strip_tags($request->input('title_hi')),
                $request->input('description') ?? null,
                $request->input('description_hi') ?? null,
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                0,
                0,
                null,
                null,
                strip_tags($request->input('link')) ?? null,
                $slider->created_by,
                auth()->user()->id
            );
            $slider = $this->sliderService->update($sliderDto, $slider->id);

            if (!$slider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slider updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Slider updation failed: ' . $e->getMessage());
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
            $slider = $this->sliderService->delete($id);
            if (!$slider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting slider.',
                ], 500);
            }

            return response()->json(['message' => 'Slider moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Slider deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Slider $slider)
    {
        try {
            $sliderDto = new SliderDto(
                $slider->title,
                $slider->title_hi,
                $slider->description,
                $slider->description_hi,
                $slider->file_name,
                $request->input('is_approved'),
                0,
                strip_tags($request->input('remarks')) ?? null,
                $slider->publish_remark,
                $slider->link,
                $slider->created_by,
                auth()->user()->id,
            );

            $updated = $this->sliderService->approve($sliderDto, $slider->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Slider approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Slider $slider)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $slider->is_approved == 1 || $isPublished == 1 ? 1 : $slider->is_approved;
            $remarks = $slider->is_approved == 1
                ? $slider->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $slider->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $sliderDto = new SliderDto(
                $slider->title,
                $slider->title_hi,
                $slider->description,
                $slider->description_hi,
                $slider->file_name,
                $isApproved,
                $isPublished,
                $remarks,
                $publishRemark,
                $slider->link,
                $slider->created_by,
                auth()->user()->id,
            );

            $updated = $this->sliderService->publish($sliderDto, $slider->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Slider publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function findAllForPublic()
    {
        $slider = $this->sliderService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $slider
        ]);
    }
}
