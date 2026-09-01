<?php

namespace App\Http\Controllers\Secure;
use Mews\Purifier\Facades\Purifier;
use App\DTO\PhotoGalleryDto;
use App\DTO\PhotoGalleryFileDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoGalleryRequest;
use App\Http\Requests\UpdatePhotoGalleryRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Resources\PhotoGalleryByEventResource;
use App\Models\GalleryEvent;
use App\Models\PhotoGallery;
use App\Models\PhotoGalleryFile;
use App\Services\GalleryEventService;
use Illuminate\Http\Request;
use App\Services\PhotoGalleryFileService;
use App\Services\PhotoGalleryService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PhotoGalleryController extends Controller
{
    protected PhotoGalleryService $photoGalleryService;
    protected PhotoGalleryFileService $photoGalleryFileService;
    protected GalleryEventService $galleryEventService;

    public function __construct()
    {
        $this->photoGalleryService = new PhotoGalleryService();
        $this->photoGalleryFileService = new PhotoGalleryFileService();
        $this->galleryEventService = new GalleryEventService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Photo Gallery';
        return view('secure.photo_galleries.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->photoGalleryService->findAll();
            return DataTables::of($users)
                ->addColumn('action', function ($photoGallery) {
                    $button = '';
                    if (auth()->user()->can('view photo gallery')) {
                        $button .= '<a href="' . route('photo-gallery.show', $photoGallery->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit photo gallery')) {
                        $button .= '<a href="' . route('photo-gallery.edit', $photoGallery->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete photo gallery')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-photo-gallery" data-id="' . $photoGallery->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->addColumn('event_title', function ($gallery) {
                    return $gallery->event?->title ?? '-';
                })
                ->addColumn('featured_image_desc', function ($photoGallery) {
                    if ($photoGallery->featured_image) {
                        return "<img src=" . generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH'] . '/' . $photoGallery->featured_image)) . " alt='Featured Image' class='img-fluid' style='max-height: 70px;'>";
                    }

                    return '';
                })
                ->rawColumns(['featured_image_desc', 'action', 'event_title'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Photo Gallery';
        $events = $this->galleryEventService->findAll();
        return view('secure.photo_galleries.create', compact('pageTitle', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoGalleryRequest $request)
    {
        DB::beginTransaction();
        try {
            // Create a PhotoGalleryDto instance with validated request data
            $photoGalleryDto = new PhotoGalleryDto(
                $request->input('gallery_event_id'),
                $request->file('featured_image'),
                $request->input('title'),
                $request->input('title_hi'),
                Purifier::clean(html_entity_decode($request->input('description') ?? null)),
                Purifier::clean(html_entity_decode($request->input('description_hi') ?? null)),
                $request->input('date') ? date('Y-m-d', strtotime($request->input('date'))) : null,
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            // Use the PhotoGalleryService to create a new page
            $photoGallery = $this->photoGalleryService->create($photoGalleryDto);

            if (!$photoGallery) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                ], 500);
            }

            // Add the page files
            if (count(json_decode($request->fileCountArray)) > 0) {
                foreach (json_decode($request->fileCountArray) as $key => $count) {
                    $photoGalleryFileDto = new PhotoGalleryFileDto(
                        $photoGallery->id,
                        $request->file('file_name_' . $count),
                        $request->input('title_' . $count) ?? null,
                        $request->input('title_hi_' . $count) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->photoGalleryFileService->create($photoGalleryFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while saving page files.',
                        ], 500);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Photo Gallery created successfully!',
                'redirect_url' => route('photo-gallery.index'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the exception for debugging purposes
            Log::error('Photo Gallery creation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'View Photo Gallery';
        $photoGallery = $this->photoGalleryService->findById($id);
        return view('secure.photo_galleries.show', compact('photoGallery', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhotoGallery $photoGallery)
    {

        $photoGallery->load(['event', 'images']);
        $pageTitle = 'Edit Photo Gallery';
        $events = $this->galleryEventService->findAll();
        return view('secure.photo_galleries.edit', compact('pageTitle', 'photoGallery', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoGalleryRequest $request, PhotoGallery $photoGallery)
    {
        DB::beginTransaction();
        try {
            // Create a PhotoGalleryDto instance with validated request data
            $photoGalleryDto = new PhotoGalleryDto(
                $request->input('gallery_event_id'),
                $request->hasFile('featured_image') ? $request->file('featured_image') : null,
                $request->input('title'),
                $request->input('title_hi'),
                Purifier::clean(html_entity_decode($request->input('description') ?? null)),
                Purifier::clean(html_entity_decode($request->input('description_hi') ?? null)),
                $request->input('date') ? date('Y-m-d', strtotime($request->input('date'))) : null,
                0,
                0,
                null,
                null,
                $photoGallery->created_by,
                auth()->user()->id
            );

            // Use the PhotoGalleryService to create a new page
            $photoGallery = $this->photoGalleryService->update($photoGalleryDto, $photoGallery->id);

            if (!$photoGallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                ], 500);
            }

            // Add the page files
            if (count(json_decode($request->fileCountArray)) > 0) {
                foreach (json_decode($request->fileCountArray) as $key => $count) {
                    $photoGalleryFileDto = new PhotoGalleryFileDto(
                        $photoGallery->id,
                        $request->file('file_name_' . $count),
                        $request->input('title_' . $count) ?? null,
                        $request->input('title_hi_' . $count) ?? null,
                        auth()->user()->id,
                        auth()->user()->id
                    );
                    $result = $this->photoGalleryFileService->create($photoGalleryFileDto);
                    if (!$result) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while saving page files.',
                        ], 500);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Photo Gallery updated successfully!',
                'redirect_url' => route('photo-gallery.index'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the exception for debugging purposes
            Log::error('Photo Gallery updation failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhotoGallery $photoGallery)
    {
        DB::beginTransaction();
        try {
            $result = $this->photoGalleryService->delete($photoGallery->id);
            if (!$result) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting photo gallery.',
                ], 500);
            }

            // Delete files
            $files = $photoGallery->images;
            foreach ($files as $file) {
                $result = $this->photoGalleryFileService->delete($file->id);
                if (!$result) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error while deleting photo gallery files.',
                    ], 500);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Photo Gallery deleted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyFile(PhotoGalleryFile $photoGalleryFile)
    {
        try {
            $result = $this->photoGalleryFileService->delete($photoGalleryFile->id);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting page.',
                ], 500);
            }

            return response()->json(['message' => 'Photo Gallery file deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, PhotoGallery $photoGallery)
    {
        try {
            $updated = $this->photoGalleryService->approve(
                $photoGallery->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $photoGallery->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving photo gallery.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('PhotoGallery approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, PhotoGallery $photoGallery)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $photoGallery->is_approved == 1 || $isPublished == 1 ? 1 : $photoGallery->is_approved;
            $remarks = $photoGallery->is_approved == 1
                ? $photoGallery->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $photoGallery->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;


            $updated = $this->photoGalleryService->publish($photoGallery->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing photo gallery.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('PhotoGallery publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findAllforPublic()
    {
        return response()->json([
            'success' => true,
            'data' => $this->photoGalleryService->findForPublic(100),
            'lastUpdatedOn' => $this->photoGalleryService->getLastUpdatedOrCreatedAt(),
        ]);
    }

    public function findByIdWithImages($id)
    {
        $photo_gallery = $this->photoGalleryService->findByIdWithImages($id);
        if (!$photo_gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Photo Gallery not found.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $photo_gallery,
            'lastUpdatedOn' => $this->photoGalleryService->getLastUpdatedOrCreatedAt(),
        ]);
    }

    public function byEvent(Request $request)
    {
        $eventId = $request->event_id;
        $limit = $request->limit;
        $photo_gallery = $this->photoGalleryService->findForPublicByEventId($eventId, $limit);
        return response()->json([
            'success' => true,
            'data' => PhotoGalleryByEventResource::collection($photo_gallery),
            'lastUpdatedOn' => $this->photoGalleryService->getLastUpdatedOrCreatedAt(),
            'category' => $this->galleryEventService->findById($eventId),
        ]);
    }
}
