<?php

namespace App\Services;

use App\DTO\PasswordDto;
use App\DTO\ProfileDto;
use App\DTO\UserDto;
use App\Repositories\UserRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class ProfileService
{
    use FileUploadTrait;
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function update(ProfileDto $profileDto, $id)
    {
        $updateData = [
            'name' => $profileDto->name,
            'mobile_number' => $profileDto->mobile_number,
        ];

        // Upload header logo
        if ($profileDto->profile_image) {
            $profileImage = $this->uploadFile($profileDto->profile_image, Config::get('file_paths')['USER_PROFILE_IMAGE_PATH']);
            $profileDto->profile_image = $profileImage['file_name'];
            $updateData['profile_image'] = $profileDto->profile_image;
        }
        
        $user = $this->userRepository->update($updateData, $id);

        if (!$user) {
            return false;
        }

        return $user;
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function changePassword(PasswordDto $passwordDto, $id)
    {
        $expiryDays = env('PASSWORD_EXPIRY_DAYS', 90);
        $user = $this->userRepository->update([
            'password' => $passwordDto->password,
            'password_changed_at' => now(),
            'password_expires_at' => now()->addDays($expiryDays),
            'force_password_change' => false,
        ], $id);

        if (!$user) {
            return false;
        }

        // Keep password history
        $userModel = \App\Models\User::find($id);
        if ($userModel) {
            $userModel->passwordHistories()->create([
                'password' => \Illuminate\Support\Facades\Hash::make($passwordDto->password)
            ]);
            
            // Clean up old histories (keep only last 3)
            $historiesToDelete = $userModel->passwordHistories()
                                           ->latest()
                                           ->skip(3)
                                           ->get();
            foreach ($historiesToDelete as $history) {
                $history->delete();
            }
        }

        return $user;
    }
}
