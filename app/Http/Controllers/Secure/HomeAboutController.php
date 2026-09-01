<?php

namespace App\Http\Controllers\Secure;

use App\DTO\HomeAboutDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomeAboutRequest;
use App\Http\Requests\UpdateHomeAboutRequest;
use App\Models\HomeAbout;
use App\Services\HomeAboutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class HomeAboutController extends Controller
{
    protected $homeAboutService;

    public function __construct()
    {
        $this->homeAboutService = new HomeAboutService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Home About';
        $homeAbout = $this->homeAboutService->findFirst();
        return view('secure.home_about.index', compact('pageTitle', 'homeAbout'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Home About';
        $homeAbout = $this->homeAboutService->findFirst();
        return view('secure.home_about.edit', compact('homeAbout', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomeAboutRequest $request, HomeAbout $homeAbout)
    {
        try {
            $homeAboutDto = new HomeAboutDto(
                $request->input('title'),
                $request->input('title_hi'),
                $request->input('description'),
                $request->input('description_hi'),
                $request->input('button_link'),
                $request->hasFile('image') ? $request->file('image') : null,
                $homeAbout->is_approved,
                $homeAbout->is_published,
                $homeAbout->remarks,
                $homeAbout->created_by,
                auth()->user()->id
            );

            $homeAbout = $this->homeAboutService->update($homeAboutDto, $homeAbout->id);

            if (!$homeAbout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Home About updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Home About updation failed: ' . $e->getMessage());
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
            $homeAbout = $this->homeAboutService->delete($id);
            if (!$homeAbout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting slider.',
                ], 500);
            }

            return response()->json(['message' => 'HomeAbout moved to trash successfully!']);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('HomeAbout deletion failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function approve(Request $request, HomeAbout $homeAbout)
    {
        try {
            $homeAboutDto = new HomeAboutDto(
                $homeAbout->title,
                $homeAbout->title_hi,
                $homeAbout->description,
                $homeAbout->description_hi,
                $homeAbout->file_name,
                $request->input('is_approved'),
                0,
                $request->input('remarks'),
                $homeAbout->created_by,
                auth()->user()->id,
            );

            $updated = $this->homeAboutService->approve($homeAboutDto, $homeAbout->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while approving slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'HomeAbout decision submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('HomeAbout approval failed: ' . $e->getMessage());
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

    public function publish(Request $request, HomeAbout $homeAbout)
    {
        try {
            $homeAboutDto = new HomeAboutDto(
                $homeAbout->title,
                $homeAbout->title_hi,
                $homeAbout->description,
                $homeAbout->description_hi,
                $homeAbout->file_name,
                $homeAbout->is_approved == 1 ? $homeAbout->is_approved : 1,
                $request->input('is_published'),
                $homeAbout->is_approved == 1 ? $homeAbout->remarks : 'Automatically approved while publishing the content',
                $homeAbout->created_by,
                auth()->user()->id,
            );

            $updated = $this->homeAboutService->publish($homeAboutDto, $homeAbout->id);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while publishing slider.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'HomeAbout published successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('HomeAbout publishing failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    public function fetchAllForPublic()
    {
        $homeAbout = $this->homeAboutService->findforPublic();
        return response()->json($homeAbout[0]);
    }
}
