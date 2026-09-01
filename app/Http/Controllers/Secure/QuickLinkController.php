<?php

namespace App\Http\Controllers\Secure;

use App\DTO\QuickLinkDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuickLinkRequest;
use App\Http\Requests\UpdateQuickLinkRequest;
use App\Models\QuickLink;
use App\Services\QuickLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class QuickLinkController extends Controller
{
    protected $quickLinkService;

    public function __construct()
    {
        $this->quickLinkService = new QuickLinkService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Quick Links';
        return view('secure.quick_links.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $quickLinks = $this->quickLinkService->findAll();
            return DataTables::of($quickLinks)
                ->addColumn('action', function ($quickLinks) {
                    $button = '';
                    if (auth()->user()->can('view quick link')) {
                        $button .= '<a href="' . route('quick-links.show', $quickLinks->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit quick link')) {
                        $button .= '<a href="' . route('quick-links.edit', $quickLinks->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete quick link')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-quickLinks" data-id="' . $quickLinks->id . '" title="Delete">
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
        $pageTitle = 'Add Quick Links';
        return view('secure.quick_links.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuickLinkRequest $request)
    {
        try {

            $quickLinkDto = new QuickLinkDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('link'),
                0,
                0,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            $quickLink = $this->quickLinkService->create($quickLinkDto);

            if (!$quickLink) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving quicklink.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'QuickLink created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('QuickLink addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Quick Links';
        $quickLink = $this->quickLinkService->findById($id);
        return view('secure.quick_links.show', compact('quickLink', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Quick Links';
        $quickLink = $this->quickLinkService->findById($id);
        return view('secure.quick_links.edit', compact('quickLink', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuickLinkRequest $request, QuickLink $quickLink)
    {
        try {
            $quickLinkDto = new QuickLinkDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('link'),
                $quickLink->is_approved,
                $quickLink->is_published,
                $quickLink->remarks,
                auth()->user()->id,
                auth()->user()->id
            );
            $quickLink = $this->quickLinkService->update($quickLinkDto, $quickLink->id);

            if (!$quickLink) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating quicklink.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'QuickLink updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('QuickLink updation failed: ' . $e->getMessage());
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
            $quickLink = $this->quickLinkService->delete($id);
            if (!$quickLink) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting quicklink.',
                ], 500);
            }

            return response()->json(['message' => 'QuickLink moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('QuickLink deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(Request $request, QuickLink $quickLink)
    {
        try {
            $quickLinkDto = new QuickLinkDto(
                $quickLink->title,
                $quickLink->title_hi,
                $quickLink->description,
                $quickLink->description_hi,
                $quickLink->file_name,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                $quickLink->created_by,
                auth()->user()->id,
            );

            $updated = $this->quickLinkService->approve($quickLinkDto, $quickLink->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving quicklink.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'QuickLink decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('QuickLink approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(Request $request, QuickLink $quickLink)
    {
        try {
            $quickLinkDto = new QuickLinkDto(
                $quickLink->title,
                $quickLink->title_hi,
                $quickLink->description,
                $quickLink->description_hi,
                $quickLink->file_name,
                $quickLink->is_approved == 1 ? $quickLink->is_approved : 1,
                $request->input('is_published'),
                $quickLink->is_approved == 1 ? $quickLink->remarks : 'Automatically approved while publishing the content',
                $quickLink->created_by,
                auth()->user()->id,
            );

            $updated = $this->quickLinkService->publish($quickLinkDto, $quickLink->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing quicklink.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'QuickLink published successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('QuickLink publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        try {
            $quickLinks = $this->quickLinkService->findAllForPublic();
            if ($quickLinks->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No quick links found.'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $quickLinks
            ], 200);
        } catch (\Exception $e) {
            Log::error('QuickLink fetch for public failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
