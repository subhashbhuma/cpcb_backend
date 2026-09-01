<?php

namespace App\Http\Controllers\Secure;

use App\DTO\PublicationDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePublicationRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Publication;
use App\Services\PublicationCategoryServices;
use App\Services\PublicationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PublicationController extends Controller
{
    protected $publicationServices;
    protected $publicationCategoryServices;
    protected $carbon;
    public function __construct()
    {
        $this->publicationServices = new PublicationService();
        $this->publicationCategoryServices = new PublicationCategoryServices();
        $this->carbon = new Carbon();
    }

    public function index()
    {
        $pageTitle = 'Publications';
        return view('secure.publication.index', compact('pageTitle'));
    }

    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $query = Publication::query();

            if ($request->has('status')) {
                if ($request->status == 'published') {
                    $query->where('is_published', 1);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            $publications = $query->orderBy('id', 'DESC');
            return DataTables::of($publications)
                ->addColumn('file_name', function ($publication) {
                    if ($publication->file_name) {
                        return "<a href=" . generate_file_view_path_for_backend($publication->file_url) . " target='_BLANK'>View Document</a>";
                    };
                })
                ->addColumn('file_name_hi', function ($publication) {
                    if ($publication->file_name_hi) {
                        return "<a href=" . generate_file_view_path_for_backend($publication->file_url_hi) . " target='_BLANK'>View Document</a>";
                    };
                })
                ->addColumn('action', function ($publication) {
                    $button = '';
                    if (auth()->user()->can('view publication')) {
                        $button .= '<a href="' . route('publication.show', $publication->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit publication')) {
                        $button .= '<a href="' . route('publication.edit', $publication->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete publication')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-publication" data-id="' . $publication->id . '" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';
                    }
                    return $button;
                })
                ->addColumn('category', function ($publication) {
                    return $publication->category->title ?? '-';
                })
                ->rawColumns(['action', 'file_name', 'file_name_hi'])
                ->make(true);
        }
    }

    public function create()
    {
        $pageTitle = 'Create Publication';
        $publication_categories = $this->publicationCategoryServices->findAll();
        return view('secure.publication.create', compact('pageTitle', 'publication_categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $createdAt = Carbon::now();
            $publicationDto = new PublicationDto(
                $request->input('category_id'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('price'),
                $request->file('file_name'),
                $request->file('file_name_hi'),
                $request->input('published_date'),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                $createdAt,
                auth()->user()->id,
                $createdAt
            );

            $publication = $this->publicationServices->create($publicationDto);

            if (!$publication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving publication.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publication created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Publication addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Publication';
        $publication = $this->publicationServices->findById($id);
        return view('secure.publication.show', compact('publication', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Publication';
        $publication = $this->publicationServices->findById($id);
        $publication_categories = $this->publicationCategoryServices->findAll();
        return view('secure.publication.edit', compact('publication', 'pageTitle', 'publication_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicationRequest $request, Publication $publication)
    {
        try {
            $publicationDto = new PublicationDto(

                $request->input('category_id'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('price'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $request->hasFile('file_name_hi') ? $request->file('file_name_hi') : null,
                $request->input('published_date'),
                0, // reset is_approved
                0, // reset is_published
                null, // reset remarks
                null, // reset publish_remark
                $publication->created_by,
                $publication->created_at,
                auth()->user()->id,
                $this->carbon->now()
            );

            $updated = $this->publicationServices->update($publicationDto, $publication->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating publication.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publication updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Publication updation failed: ' . $e->getMessage());
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
            $jobs = $this->publicationServices->delete($id);
            if (!$jobs) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting publication.',
                ], 500);
            }

            return response()->json(['message' => 'Publication moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Publication deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Publication $publication)
    {
        try {
            $updated = $this->publicationServices->approve(
                $publication->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $publication->publish_remark
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
            Log::error('Publication approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    public function publish(PublishRequest $request, Publication $publication)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $publication->is_approved == 1 || $isPublished == 1 ? 1 : $publication->is_approved;
            $remarks = $publication->is_approved == 1
                ? $publication->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $publication->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->publicationServices->publish(
                $publication->id,
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
            Log::error('Publication publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllforPublic()
    {
        $publication = $this->publicationServices->findForPublic();
        return response()->json([
            'success' => true,
            'data' => $publication
        ]);
    }

    public function fetchByCategoryForPublic($categories)
    {
        $publication = $this->publicationServices->findByCategoryForPublic($categories);
        return response()->json([
            'success' => true,
            'data' => $publication,
            'lastUpdatedOn' => Publication::getLastUpdatedOrCreatedAt(),
        ]);
    }
}
