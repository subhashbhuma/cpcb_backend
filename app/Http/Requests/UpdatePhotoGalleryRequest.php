<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePhotoGalleryRequest extends FormRequest
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
            'gallery_event_id' => 'required|exists:gallery_events,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'description' => 'nullable|string|max:65535',
            'description_hi' => 'nullable|string|max:65535',
            'date' => 'required|date',
            'is_approved' => 'nullable|in:0,1,2',
            'is_published' => 'nullable|in:0,1',
            'remarks' => 'nullable|string|max:500',
            'publish_remark' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
{
    return [
        'gallery_event_id.required' => 'Please select the Gallery Event.',
        'gallery_event_id.exists' => 'Please select a valid Gallery Event.',

        'featured_image.image' => 'Please select a valid image.',
        'featured_image.mimes' => 'Only JPG, JPEG, PNG, GIF, and WebP image formats are allowed.',
        'featured_image.max' => 'The image size must not exceed 2 MB.',

        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.required' => 'Please enter the Hindi Title.',
        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'description.max' => 'The Description must not exceed the allowed length.',
        'description_hi.max' => 'The Hindi Description must not exceed the allowed length.',

        'date.required' => 'Please select the Date.',
        'date.date' => 'Please enter a valid Date.',

        'is_approved.in' => 'Please select a valid Approval Status.',
        'is_published.in' => 'Please select a valid Publication Status.',

        'remarks.max' => 'The Remarks must not exceed 500 characters.',
        'publish_remark.max' => 'The Publish Remark must not exceed 500 characters.',
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
