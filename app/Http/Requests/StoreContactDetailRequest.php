<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'department' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'department_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'address' => 'nullable|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'address_hi' => 'nullable|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'phone_numbers' => 'nullable|string|max:255|regex:/^[0-9,\s\-\+\(\)]+$/',
            'email_ids' => 'nullable|string|max:500|regex:/^[\w\.\+\-]+@[\w\.\-]+(,\s*[\w\.\+\-]+@[\w\.\-]+)*$/',
            'profile_image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'myorder' => 'nullable|integer',
        ];
    }

    public function messages(): array
{
    return [
        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.required' => 'Please enter the Hindi Title.',
        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'department.max' => 'The Department must not exceed 255 characters.',
        'department.regex' => 'Please enter a valid Department.',

        'department_hi.max' => 'The Hindi Department must not exceed 255 characters.',
        'department_hi.regex' => 'Please enter a valid Hindi Department.',

        'address.regex' => 'Please enter a valid Address.',
        'address_hi.regex' => 'Please enter a valid Hindi Address.',

        'phone_numbers.max' => 'The Phone Number must not exceed 255 characters.',
        'phone_numbers.regex' => 'Please enter valid Phone Number(s).',

        'email_ids.max' => 'The Email Address must not exceed 500 characters.',
        'email_ids.regex' => 'Please enter valid Email Address(es).',

        'profile_image.file' => 'Please select a valid image.',
        'profile_image.mimes' => 'Only JPG, JPEG, PNG, and WebP image formats are allowed.',
        'profile_image.max' => 'The image size must not exceed 2 MB.',

        'myorder.integer' => 'Display Order must be a valid number.',
    ];
}


    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
