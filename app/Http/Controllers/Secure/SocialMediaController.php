<?php

namespace App\Http\Controllers\Secure;
use Mews\Purifier\Facades\Purifier;
use App\DTO\SocialMediaDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocialMediaRequest;
use App\Http\Requests\UpdateSocialMediaRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\SocialMedia;
use App\Services\SocialMediaPlatformService;
use App\Services\SocialMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SocialMediaController extends Controller
{
    protected $socialMediaService;
    protected $socialMediaPlatformService;

    public function __construct()
    {
        $this->socialMediaService = new SocialMediaService();
        $this->socialMediaPlatformService = new SocialMediaPlatformService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Social Medias';
        return view('secure.social_medias.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $socialMedias = $this->socialMediaService->findAll();

            return DataTables::of($socialMedias)
                ->addColumn('action', function ($socialMedia) {
                    $button = '';
                    if (auth()->user()->can('view social media')) {
                        $button .= '<a href="' . route('social-medias.show', $socialMedia->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit social media')) {
                        $button .= '<a href="' . route('social-medias.edit', $socialMedia->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete social media')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-social-media" data-id="' . $socialMedia->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add Social Medias';
        $socialMediaPlatforms = $this->socialMediaPlatformService->findAll();
        return view('secure.social_medias.create', compact('pageTitle', 'socialMediaPlatforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialMediaRequest $request)
    {
        try {

            $socialMediaDto = new SocialMediaDto(
                $request->input('type'),
                $request->input('name'),
                $request->input('url'),
                $request->input('embed_code'),
                $request->input('icon_class'),
                0,
                0,
                null,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            $socialMedia = $this->socialMediaService->create($socialMediaDto);

            if (!$socialMedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving social media.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Social media created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Social media addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Social Medias';
        $socialMedia = $this->socialMediaService->findById($id);
        return view('secure.social_medias.show', compact('socialMedia', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Social Medias';
        $socialMedia = $this->socialMediaService->findById($id);
        $socialMediaPlatforms = $this->socialMediaPlatformService->findAll();
        return view('secure.social_medias.edit', compact('socialMedia', 'pageTitle', 'socialMediaPlatforms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialMediaRequest $request, SocialMedia $socialMedia)
    {
        try {
            $socialMediaDto = new SocialMediaDto(
                $request->input('type'),
                $request->input('name'),
                $request->input('url'),
                $request->input('embed_code'),
                $request->input('icon_class'),
                0,
                0,
                null,
                null,
                $socialMedia->created_by,
                auth()->user()->id
            );

            $socialMedia = $this->socialMediaService->update($socialMediaDto, $socialMedia->id);

            if (!$socialMedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating social media.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Social media updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Social media updation failed: ' . $e->getMessage());
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
            $socialMedia = $this->socialMediaService->delete($id);
            if (!$socialMedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting social media.',
                ], 500);
            }

            return response()->json(['message' => 'Social media moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Social media deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, SocialMedia $socialMedia)
    {
        try {
            $updated = $this->socialMediaService->approve(
                $socialMedia->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $socialMedia->publish_remark
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving social media.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('SocialMedia approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, SocialMedia $socialMedia)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $socialMedia->is_approved == 1 || $isPublished == 1 ? 1 : $socialMedia->is_approved;
            $remarks = $socialMedia->is_approved == 1
                ? $socialMedia->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $socialMedia->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->socialMediaService->publish($socialMedia->id, $isApproved, $remarks, $isPublished, $publishRemark);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing social media.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publish decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Social media publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function findForPublic()
    {
        $data = $this->socialMediaService->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
