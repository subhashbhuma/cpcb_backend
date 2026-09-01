<?php

namespace App\Http\Controllers\Secure;

use App\DTO\AdditionalLogoDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdditionalLogoRequest;
use App\Http\Requests\UpdateAdditionalLogoRequest;
use App\Models\AdditionalLogo;
use App\Services\AdditionalLogoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AdditionalLogoController extends Controller
{
    protected $additionalLogoService;

    public function __construct()
    {
        $this->additionalLogoService = new AdditionalLogoService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Additional Logos';
        return view('secure.additional_logos.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $additionalLogos = $this->additionalLogoService->findAll();
            return DataTables::of($additionalLogos)
                ->addColumn('image', function ($additionalLogo) {
                    if ($additionalLogo->file_name) {
                        return "<img src=" . $additionalLogo->file_url . " alt='Additional Logo Image' class='img-fluid' style='max-height: 70px;'>";
                    }

                    return '';
                })
                ->addColumn('action', function ($additionalLogo) {
                    $button = '';
                    if (auth()->user()->can('view additional logo')) {
                        $button .= '<a href="' . route('additional-logos.show', $additionalLogo->id) . '" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a> ';
                    }

                    if (auth()->user()->can('edit additional logo')) {
                        $button .= '<a href="' . route('additional-logos.edit', $additionalLogo->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a> ';
                    }

                    if (auth()->user()->can('delete additional logo')) {
                        $button .= '<button class="btn btn-sm btn-danger delete-additional-logo" data-id="' . $additionalLogo->id . '" title="Delete">
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
        $pageTitle = 'Add Additional Logos';
        return view('secure.additional_logos.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdditionalLogoRequest $request)
    {
        try {

            $additionalLogoDto = new AdditionalLogoDto(
                $request->input('link'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->file('file_name'),
                0,
                0,
                null,
                auth()->user()->id,
                auth()->user()->id
            );

            $additionalLogo = $this->additionalLogoService->create($additionalLogoDto);

            if (!$additionalLogo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while saving additional logo.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Additional Logo created successfully!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Additional Logo addition failed: ' . $e->getMessage());
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
        $pageTitle = 'View Additional Logos';
        $additionalLogo = $this->additionalLogoService->findById($id);
        return view('secure.additional_logos.show', compact('additionalLogo', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit AdditionalLogos';
        $additionalLogo = $this->additionalLogoService->findById($id);
        return view('secure.additional_logos.edit', compact('additionalLogo', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdditionalLogoRequest $request, AdditionalLogo $additionalLogo)
    {
        try {
            $additionalLogoDto = new AdditionalLogoDto(
                $request->input('link'),
                $request->input('title'),
                $request->input('title_hi'),
                $request->hasFile('file_name') ? $request->file('file_name') : null,
                $additionalLogo->is_approved,
                $additionalLogo->is_published,
                $additionalLogo->remarks,
                auth()->user()->id,
                auth()->user()->id
            );
            $additionalLogo = $this->additionalLogoService->update($additionalLogoDto, $additionalLogo->id);

            if (!$additionalLogo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating additional logo.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Additional Logo updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Additional Logo updation failed: ' . $e->getMessage());
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
            $additionalLogo = $this->additionalLogoService->delete($id);
            if (!$additionalLogo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting additional logo.',
                ], 500);
            }

            return response()->json(['message' => 'Additional Logo moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Additional Logo deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(Request $request, AdditionalLogo $additionalLogo)
    {
        try {
            $additionalLogoDto = new AdditionalLogoDto(
                $additionalLogo->link,
                $additionalLogo->title,
                $additionalLogo->title_hi,
                $additionalLogo->file_name,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                $additionalLogo->created_by,
                auth()->user()->id,
            );

            $updated = $this->additionalLogoService->approve($additionalLogoDto, $additionalLogo->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving additional logo.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Additional Logo decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Additional Logo approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function publish(Request $request, AdditionalLogo $additionalLogo)
    {
        try {
            $additionalLogoDto = new AdditionalLogoDto(
                $additionalLogo->link,
                $additionalLogo->title,
                $additionalLogo->title_hi,
                $additionalLogo->file_name,
                $additionalLogo->is_approved == 1 ? $additionalLogo->is_approved : 1,
                $request->input('is_published'),
                $additionalLogo->is_approved == 1 ? $additionalLogo->remarks : 'Automatically approved while publishing the content',
                $additionalLogo->created_by,
                auth()->user()->id,
            );

            $updated = $this->additionalLogoService->publish($additionalLogoDto, $additionalLogo->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing additional logo.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Additional Logo published successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Additional Logo publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
