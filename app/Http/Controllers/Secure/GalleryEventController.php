<?php

namespace App\Http\Controllers\Secure;

use App\DTO\GalleryEventDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryEventRequest;
use App\Http\Requests\UpdateGalleryEventRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\GalleryEvent;
use App\Services\GalleryEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class GalleryEventController extends Controller
{
    /**
     *  Gallery Event service instance
     */
    protected $galleryEventService;

    /**
     * Constructor
     * Initialize service class
     */
    public function __construct()
    {
        $this->galleryEventService = new GalleryEventService();
    }

    /* =====================================================
     * INDEX
     * ===================================================== */

    /**
     * Display  gallery event listing page
     */
    public function index()
    {
        $pageTitle = 'Gallery Events';
        return view('secure.gallery_events.index', compact('pageTitle'));
    }

    /* =====================================================
     * DATATABLE FETCH
     * ===================================================== */

    /**
     * Fetch  gallery events for DataTable (AJAX)
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $events = $this->galleryEventService->findAll();

            return DataTables::of($events)

                // Featured Image Column
                ->addColumn('featured_image_desc', function ($photoGallery) {
                    if ($photoGallery->featured_image) {
                        return "<img src='" .
                            generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['GALLERY_EVENT_FEATURED_IMAGE_PATH'] . '/' . $photoGallery->featured_image)) .
                            "' class='img-fluid' style='max-height:70px'>";
                    }
                    return '';
                })

                // Action Buttons Column
                ->addColumn('action', function ($photoGallery) {
                    $button = '';

                    if (auth()->user()->can('view gallery event')) {
                        $button .= '<a href="' . route('gallery-event.show', $photoGallery->id) . '" class="btn btn-sm btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('edit gallery event')) {
                        $button .= '<a href="' . route('gallery-event.edit', $photoGallery->id) . '" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a> ';
                    }

                    if (auth()->user()->can('delete gallery event')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-gallery-event"
                                            data-id="' . $photoGallery->id . '" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>';
                    }

                    return $button;
                })

                ->rawColumns(['featured_image_desc', 'action'])
                ->make(true);
        }
    }

    /* =====================================================
     * CREATE
     * ===================================================== */

    /**
     * Show create  gallery event form
     */
    public function create()
    {
        $pageTitle = 'Create  Gallery Event';
        return view('secure.gallery_events.create', compact('pageTitle'));
    }

    /* =====================================================
     * STORE
     * ===================================================== */

    /**
     * Store newly created  gallery event
     */
    public function store(StoreGalleryEventRequest $request)
    {
        DB::beginTransaction();
        try {
            // Prepare DTO from validated request
            $dto = new GalleryEventDto(
                $request->file('featured_image'),
                $request->title,
                $request->title_hi,
                0,
                0,
                null,
                null,
                auth()->id(),
                auth()->id()
            );

            // Create record via service
            $result = $this->galleryEventService->create($dto);

            if ($result) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Gallery Event created successfully!',
                    'redirect_url' => route('gallery-event.index')
                ], 201);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving gallery event.'
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(' Gallery Event creation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /* =====================================================
     * SHOW
     * ===================================================== */

    /**
     * Display a specific  gallery event
     */
    public function show(string $id)
    {
        $pageTitle = 'View  Gallery Event';
        $photoGallery = $this->galleryEventService->findById($id);

        return view('secure.gallery_events.show', compact('photoGallery', 'pageTitle'));
    }

    /* =====================================================
     * EDIT
     * ===================================================== */

    /**
     * Show edit form for  gallery event
     */
    public function edit(GalleryEvent $galleryEvent)
    {
        $pageTitle = 'Edit Gallery Event';
        return view('secure.gallery_events.edit', compact('pageTitle', 'galleryEvent'));
    }

    /* =====================================================
     * UPDATE
     * ===================================================== */

    /**
     * Update  gallery event
     */
    public function update(UpdateGalleryEventRequest $request, GalleryEvent $galleryEvent)
    {
        DB::beginTransaction();
        try {
            $dto = new GalleryEventDto(
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->title,
                $request->title_hi,
                0,
                0,
                null,
                null,
                $galleryEvent->created_by,
                auth()->id()
            );

            $updated = $this->galleryEventService->update($dto, $galleryEvent->id);

            if ($updated) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Gallery Event updated successfully!',
                    'redirect_url' => route('gallery-event.index')
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving gallery event.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(' Gallery Event updation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    /* =====================================================
     * DELETE
     * ===================================================== */

    /**
     * Delete  gallery event
     */
    public function destroy(GalleryEvent $galleryEvent)
    {
        DB::beginTransaction();
        try {
            $deleted = $this->galleryEventService->delete($galleryEvent->id);

            if (!$deleted) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting  gallery event.'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ' Gallery Event deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    /* =====================================================
     * APPROVAL
     * ===================================================== */

    /**
     * Approve or reject  gallery event
     */
    public function approve(ApproveRequest $request, GalleryEvent $galleryEvent)
    {
        try {
            $updated = $this->galleryEventService->approve(
                $galleryEvent->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $galleryEvent->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving gallery event.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Gallery Event approval failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /* =====================================================
     * PUBLISH
     * ===================================================== */

    /**
     * Publish  gallery event
     */
    public function publish(PublishRequest $request, GalleryEvent $galleryEvent)
    {
        try {

            $isPublished = (int) $request->input('is_published');
            $isApproved = $galleryEvent->is_approved == 1 || $isPublished == 1 ? 1 : $galleryEvent->is_approved;
            $remarks = $galleryEvent->is_approved == 1
                ? $galleryEvent->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $galleryEvent->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;


            $updated = $this->galleryEventService->publish($galleryEvent->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing gallery event.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Gallery Event publishing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /* =====================================================
     * PUBLIC API
     * ===================================================== */

    /**
     * Fetch published  gallery events for public website
     */
    public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => $this->galleryEventService->findForPublic()
        ]);
    }
}
