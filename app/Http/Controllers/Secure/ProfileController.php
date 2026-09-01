<?php

namespace App\Http\Controllers\Secure;

use App\DTO\PasswordDto;
use App\DTO\ProfileDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Profile';
        return view('secure.profile.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id)
    {
        // IDOR Protection: Ensure user can only update their own profile
        abort_if($id != auth()->id(), 403, 'Unauthorized action.');

        try {
            if ($request->has('profile_image') && !$request->hasFile('profile_image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'The image was sent but PHP rejected it (possibly due to php.ini limits like upload_max_filesize or missing tmp folder).',
                ], 422);
            }

            $profileDto = new ProfileDto(
                $request->input('name'),
                '',
                $request->input('mobile_number'),
                $request->hasFile('profile_image') ? $request->file('profile_image') : null,
            );
            $result = $this->profileService->update($profileDto, $id);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating profile.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile details updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
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
            ], 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request, string $id)
    {

        // IDOR Protection: Ensure user can only change their own password
        abort_if($id != auth()->id(), 403, 'Unauthorized action.');

        try {
            $passwordDto = new PasswordDto(
                $request->input('new_password'),
            );
            $result = $this->profileService->changePassword($passwordDto, $id);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating password.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Password change failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function forcePasswordChange()
    {
        return view('secure.profile.force_password_change');
    }

    public function updateForcedPassword(ChangePasswordRequest $request)
    {
        try {
            $passwordDto = new PasswordDto(
                $request->input('new_password'),
            );
            $result = $this->profileService->changePassword($passwordDto, auth()->id());

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating password.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Forced password change failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
