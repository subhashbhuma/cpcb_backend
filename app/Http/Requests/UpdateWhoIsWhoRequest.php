<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateWhoIsWhoRequest extends FormRequest
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
            'order' => 'nullable|integer|min:0',
            'name' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'name_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'designation' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'designation_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'mobile_number' => 'nullable|string|max:15|regex:/^[0-9]{10,15}$/',
            'email_id' => 'nullable|email|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'address' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'address_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'show_on_homepage' => 'nullable|boolean',
            'hide_on_who_is_who' => 'nullable|boolean',
            'publish_remark' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
{
    return [
        'order.integer' => 'Display Order must be a valid number.',
        'order.min' => 'Display Order cannot be less than 0.',
        'name.required' => 'Please enter the Name.',
        'name.max' => 'The Name must not exceed 255 characters.',
        'name.regex' => 'Please enter a valid Name.',
        'name_hi.required' => 'Please enter the Hindi Name.',
        'name_hi.max' => 'The Hindi Name must not exceed 255 characters.',
        'name_hi.regex' => 'Please enter a valid Hindi Name.',
        'designation.max' => 'The Designation must not exceed 255 characters.',
        'designation.regex' => 'Please enter a valid Designation.',
        'designation_hi.max' => 'The Hindi Designation must not exceed 255 characters.',
        'designation_hi.regex' => 'Please enter a valid Hindi Designation.',
        'mobile_number.max' => 'The Mobile Number must not exceed 15 digits.',
        'mobile_number.regex' => 'Please enter a valid Mobile Number.',
        'email_id.email' => 'Please enter a valid Email Address.',
        'email_id.max' => 'The Email Address must not exceed 255 characters.',
        'division_id.exists' => 'Please select a valid Division.',
        'image.file' => 'Please select a valid image.',
        'image.mimes' => 'Please upload an image in JPG, JPEG, PNG, GIF, or WebP format.',
        'image.max' => 'The image size must not exceed 2 MB.',
        'address.max' => 'The Address must not exceed 255 characters.',
        'address.regex' => 'Please enter a valid Address.',
        'address_hi.max' => 'The Hindi Address must not exceed 255 characters.',
        'address_hi.regex' => 'Please enter a valid Hindi Address.',
        'publish_remark.max' => 'The Publish Remark must not exceed 500 characters.',
        'publish_remark.regex' => 'Please enter a valid Publish Remark.',
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
