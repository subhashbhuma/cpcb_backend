<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMediaRequest extends FormRequest
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
            'file_name' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,mp4|max:102400',
            'alt_text' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\-\(\)"\'’\/\.]*$/u',
            'alt_text_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\-\(\)"\'’\/\.]*$/u',
        ];
    }

    public function messages(): array
    {
        return [
            'file_name.required' => 'Please select a file to upload.',
            'file_name.file' => 'Please select a valid file.',
            'file_name.mimes' => 'Only JPG, JPEG, PNG, GIF, WebP, PDF, DOC, DOCX, XLS, XLSX, and MP4 files are allowed.',
            'file_name.max' => 'The file size must not exceed 100 MB.',

            'alt_text.max' => 'Title Text must not exceed 255 characters.',
            'alt_text.regex' => 'Please enter a valid Title Text.',

            'alt_text_hi.max' => 'Hindi Title Text must not exceed 255 characters.',
            'alt_text_hi.regex' => 'Please enter valid Hindi Title Text.',
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
