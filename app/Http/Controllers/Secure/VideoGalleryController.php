<?php

namespace App\Http\Controllers\Secure;

use Mews\Purifier\Facades\Purifier;
use App\DTO\VideoGalleryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoGalleryRequest;
use App\Http\Requests\UpdateVideoGalleryRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\VideoGallery;
use App\Services\GalleryEventService;
use Illuminate\Http\Request;
use App\Services\VideoGalleryService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VideoGalleryController extends Controller
{
    protected $videoGalleryService;
    protected GalleryEventService $galleryEventService;

    public function __construct()
    {
        $this->videoGalleryService = new VideoGalleryService();
        $this->galleryEventService = new GalleryEventService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Video Gallery';
        return view('secure.video_galleries.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->videoGalleryService->findAll();
            return DataTables::of($users)
                ->addColumn('action', function ($videoGallery) {
                    $button = '';
                    if (auth()->user()->can('view video gallery')) {
                        $button .= '<a href="' . route('video-gallery.show', $videoGallery->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit video gallery')) {
                        $button .= '<a href="' . route('video-gallery.edit', $videoGallery->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete video gallery')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-video-gallery" data-id="' . $videoGallery->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })
                ->addColumn('thumbnail_image_desc', function ($videoGallery) {
                    if ($videoGallery->thumbnail_image) {
                        return "<img src=" . generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH'] . '/' . $videoGallery->thumbnail_image)) . " alt='Featured Image' class='img-fluid' style='max-height: 70px;'>";
                    }

                    return '';
                })
                ->editColumn('date', function ($videoGallery) {
                    return $videoGallery->date ? date('d-m-Y', strtotime($videoGallery->date)) : '';
                })
                ->rawColumns(['thumbnail_image_desc', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Video Gallery';
        $events = $this->galleryEventService->findAll();
        return view('secure.video_galleries.create', compact('pageTitle', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoGalleryRequest $request)
    {
        DB::beginTransaction();
        try {
            // Create a VideoGalleryDto instance with validated request data
            $videoGalleryDto = new VideoGalleryDto(
                $request->input('gallery_event_id'),
                $request->file('thumbnail_image'),
                $request->input('type'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->input('youtube_embed_code') ?? null,
                $request->input('url') ?? null,
                $request->input('title'),
                $request->input('title_hi'),
                Purifier::clean(html_entity_decode($request->input('description') ?? null)),
                Purifier::clean(html_entity_decode($request->input('description_hi') ?? null)),
                $request->input('date') ?? null,
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            // Use the VideoGalleryService to create a new page
            $videoGallery = $this->videoGalleryService->create($videoGalleryDto);

            if (!$videoGallery) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Video Gallery created successfully!',
                'redirect_url' => route('video-gallery.index', $videoGallery->id),
            ], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Video Gallery creation failed: ' . $e->getMessage());

            DB::rollBack();
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
        $pageTitle = 'View Video Gallery';
        $videoGallery = $this->videoGalleryService->findById($id);
        return view('secure.video_galleries.show', compact('videoGallery', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoGallery $videoGallery)
    {
        $pageTitle = 'Edit Video Gallery';
        $events = $this->galleryEventService->findAll();
        return view('secure.video_galleries.edit', compact('pageTitle', 'videoGallery', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoGalleryRequest $request, VideoGallery $videoGallery)
    {
        DB::beginTransaction();
        try {
            // Create a VideoGalleryDto instance with validated request data
            $videoGalleryDto = new VideoGalleryDto(
                $request->input('gallery_event_id') ?? $videoGallery->gallery_event_id,
                $request->hasFile('thumbnail_image') ? $request->file('thumbnail_image') : null,
                $request->input('type') ?? $videoGallery->type,
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->input('youtube_embed_code') ?? $videoGallery->youtube_embed_code,
                $request->input('url') ?? $videoGallery->url,
                $request->input('title') ?? $videoGallery->title,
                $request->input('title_hi') ?? $videoGallery->title_hi,
                Purifier::clean(html_entity_decode($request->input('description'))) ?? $videoGallery->description,
                Purifier::clean(html_entity_decode($request->input('description_hi'))) ?? $videoGallery->description_hi,
                $request->input('date') ?? $videoGallery->date,
                0,
                0,
                null,
                null,
                $videoGallery->created_by,
                auth()->user()->id
            );

            // Use the VideoGalleryService to create a new page
            $videoGallery = $this->videoGalleryService->update($videoGalleryDto, $videoGallery->id);

            if (!$videoGallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving page.',
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Video Gallery updated successfully!',
                'redirect_url' => route('video-gallery.index', $videoGallery->id),
            ], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Video Gallery updation failed: ' . $e->getMessage());

            DB::rollBack();
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
    public function destroy(VideoGallery $videoGallery)
    {
        DB::beginTransaction();
        try {
            $result = $this->videoGalleryService->delete($videoGallery->id);
            if (!$result) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting video gallery.',
                ], 500);
            }

            DB::commit();
            return response()->json(['message' => 'Video Gallery deleted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, VideoGallery $videoGallery)
    {
        try {
            $updated = $this->videoGalleryService->approve(
                $videoGallery->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $videoGallery->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving video gallery.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Video Gallery approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, VideoGallery $videoGallery)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $videoGallery->is_approved == 1 || $isPublished == 1 ? 1 : $videoGallery->is_approved;
            $remarks = $videoGallery->is_approved == 1
                ? $videoGallery->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $videoGallery->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->videoGalleryService->publish($videoGallery->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing video gallery.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Video Gallery publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findAllforPublic()
    {
        $videoGallery = $this->videoGalleryService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $videoGallery,
            'lastUpdatedOn' => $this->videoGalleryService->getLastUpdatedOrCreatedAt(),
        ]);
    }

    public function findforPublicbyid(Request $request)
    {

        $video_gallery = $this->videoGalleryService->findById($request->id);
        return response()->json([
            'success' => true,
            'data' => $video_gallery, //VideoGalleryByEventResource::collection($video_gallery),
            'lastUpdatedOn' => $this->videoGalleryService->getLastUpdatedOrCreatedAt(),
        ]);
    }


    public function byEvent(Request $request)
    {
        $eventId = $request->event_id;
        $limit = $request->limit;
        $video_gallery = $this->videoGalleryService->findForPublicByEventId($eventId, $limit);
        return response()->json([
            'success' => true,
            'data' => $video_gallery, //VideoGalleryByEventResource::collection($video_gallery),
            'lastUpdatedOn' => $this->videoGalleryService->getLastUpdatedOrCreatedAt(),
            'category' => $this->galleryEventService->findById($eventId),
        ]);
    }


    // public function byEvent($galleryEvent)
    // {
    //     $videos = VideoGallery::where('gallery_event_id', $galleryEvent)
    //         ->where('is_approved', 1)
    //         ->where('is_published', 1)
    //         ->orderBy('date', 'desc')
    //         ->get([
    //             'id',
    //             'type',
    //             'file_name',
    //             'youtube_embed_code',
    //             'url',
    //             'title'
    //         ]);

    //     return response()->json([
    //         'success' => true,
    //         'data' => $videos
    //     ]);
    // }
}
