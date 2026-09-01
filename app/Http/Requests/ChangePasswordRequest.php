<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Decrypt passwords before validation
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'old_password' => CustomHelper::decryptPassword($this->old_password),
            'new_password' => CustomHelper::decryptPassword($this->new_password),
            'new_password_confirmation' => CustomHelper::decryptPassword($this->new_password_confirmation),
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The old password is incorrect.');
                    }
                }
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
                function ($attribute, $value, $fail) {
                    $user = Auth::user();
                    
                    // Check against current password
                    if (Hash::check($value, $user->password)) {
                        $fail("Your new password cannot be the same as your current password or any of your last 3 passwords.");
                        return;
                    }

                    // Check against last 3 passwords
                    $histories = $user->passwordHistories()->latest()->take(3)->get();
                    foreach ($histories as $history) {
                        if (Hash::check($value, $history->password)) {
                            $fail("Your new password cannot be the same as your current password or any of your last 3 passwords.");
                            return;
                        }
                    }
                }
            ],
            'new_password_confirmation' => 'required|same:new_password',
        ];
    }

    /**
     * Custom messages
     */
    public function messages()
    {
        return [
            'new_password.regex' => 'The new password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'new_password_confirmation.same' => 'The confirm password does not match.',
        ];
    }
}