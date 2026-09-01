<?php

namespace App\Http\Controllers\Secure;

use Mews\Purifier\Facades\Purifier;
use App\DTO\FaqDto;
use App\DTO\QuickLinkDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\StoreQuickLinkRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\PublishRequest;
use App\Models\Faq;
use App\Models\QuickLink;
use App\Services\FaqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\PublicFaqResource;

class FaqController extends Controller
{
    protected $faqService;

    public function __construct()
    {
        $this->faqService = new FaqService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'FAQ';
        return view('secure.faq.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $faq = $this->faqService->findAll();
            return DataTables::of($faq)
                ->addColumn('action', function ($faq) {
                    $button = '';
                    if (auth()->user()->can('view faq')) {
                        $button .= '<a href="' . route('faq.show', $faq->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit faq')) {
                        $button .= '<a href="' . route('faq.edit', $faq->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete faq')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-faq" data-id="' . $faq->id . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>';
                    }
                    return $button;
                })

                ->editColumn('answer', function ($faq) {
                    return strip_tags($faq->answer);
                })
                ->editColumn('answer_hi', function ($faq) {
                    return strip_tags($faq->answer_hi);
                })

                ->rawColumns(['action', 'answer', 'answer_hi'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add FAQ';
        return view('secure.faq.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request)
    {
        try {

            $faqDto = new FaqDto(
                $request->input('question'),
                $request->input('question_hi'),
                Purifier::clean(html_entity_decode($request->input('answer'))),
                Purifier::clean(html_entity_decode($request->input('answer_hi'))),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                auth()->user()->id,
                auth()->user()->id
            );

            $faq = $this->faqService->create($faqDto);

            if (!$faq) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving faq.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('FAQ addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View FAQ';
        $faq = $this->faqService->findById($id);
        return view('secure.faq.show', compact('faq', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Faq';
        $faq = $this->faqService->findById($id);
        return view('secure.faq.edit', compact('faq', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        try {
            $faqDto = new FaqDto(
                $request->input('question'),
                $request->input('question_hi'),
                Purifier::clean(html_entity_decode($request->input('answer'))),
                Purifier::clean(html_entity_decode($request->input('answer_hi'))),
                0, // is_approved
                0, // is_published
                null, // remarks
                null, // publish_remark
                $faq->created_by,
                auth()->user()->id
            );
            $faq_dto = $this->faqService->update($faqDto, $faq->id);

            if (!$faq_dto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating faq.',
                    'da' => $faq->id
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Faq updated successfully!',
                'da' => $faq
            ], 200);
        } catch (\Exception $e) {
            Log::error('FAQ updation failed: ' . $e->getMessage());
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
            $faq = $this->faqService->delete($id);
            if (!$faq) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting faq.',
                ], 500);
            }

            return response()->json(['message' => 'FAQ moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('FAQ deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(ApproveRequest $request, Faq $faq)
    {
        try {
            $updated = $this->faqService->approve(
                $faq->id,
                $request->is_approved,
                strip_tags($request->remarks) ?? null,
                $faq->publish_remark
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
            Log::error('FAQ approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(PublishRequest $request, Faq $faq)
    {
        try {
            $isPublished = (int) $request->input('is_published');
            $isApproved = $faq->is_approved == 1 || $isPublished == 1 ? 1 : $faq->is_approved;
            $remarks = $faq->is_approved == 1
                ? $faq->remarks
                : ($isPublished == 1 ? 'Automatically approved while publishing the content' : $faq->remarks);
            $publishRemark = $request->filled('publish_remark') ? strip_tags($request->input('publish_remark')) : null;

            $updated = $this->faqService->publish(
                $faq->id,
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
            Log::error('FAQ publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic(Request $request)
    {
        $search = $request->input('search');
        try {
            $faq = $this->faqService->findAllForPublic($search);
            if ($faq->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No FAQ found.'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => PublicFaqResource::collection($faq),
                'lastUpdatedOn' => Faq::getLastUpdatedOrCreatedAt(),
            ], 200);
        } catch (\Exception $e) {
            Log::error('FAQ fetch for public failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
