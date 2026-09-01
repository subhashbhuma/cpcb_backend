<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVideoGalleryRequest extends FormRequest
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
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'thumbnail_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'type' => 'required|in:1,2,3',
            'file_name' => 'nullable|file|mimes:mp4,avi,mov,wmv,webm|max:102400|required_if:type,1',
            'youtube_embed_code' => 'nullable|required_if:type,2|string|max:255|regex:/^[a-zA-Z0-9_\-]+$/',
            'url' => 'nullable|required_if:type,3|url|max:2048',
            'date' => 'nullable|date',
            'description' => 'nullable|string|max:5000',
            'description_hi' => 'nullable|string|max:5000',
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

        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.required' => 'Please enter the Hindi Title.',
        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'thumbnail_image.required' => 'Please upload the Thumbnail Image.',
        'thumbnail_image.image' => 'Please select a valid image.',
        'thumbnail_image.mimes' => 'Only JPG, JPEG, PNG, and GIF image formats are allowed.',
        'thumbnail_image.max' => 'The image size must not exceed 2 MB.',

        'type.required' => 'Please select the Video Type.',
        'type.in' => 'Please select a valid Video Type.',

        'file_name.required_if' => 'Please upload the Video File.',
        'file_name.file' => 'Please select a valid video file.',
        'file_name.mimes' => 'Only MP4, AVI, MOV, WMV, and WebM video formats are allowed.',
        'file_name.max' => 'The video file size must not exceed 100 MB.',

        'youtube_embed_code.required_if' => 'Please enter the YouTube Video ID.',
        'youtube_embed_code.max' => 'The YouTube Video ID must not exceed 255 characters.',
        'youtube_embed_code.regex' => 'Please enter a valid YouTube Video ID.',

        'url.required_if' => 'Please enter the Video URL.',
        'url.url' => 'Please enter a valid Video URL.',
        'url.max' => 'The Video URL must not exceed 2048 characters.',

        'date.date' => 'Please enter a valid Date.',

        'description.max' => 'The Description must not exceed 5000 characters.',
        'description_hi.max' => 'The Hindi Description must not exceed 5000 characters.',

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
