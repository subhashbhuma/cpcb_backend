<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSliderRequest extends FormRequest
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
            'title' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'description' => 'nullable|string|max:65535|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'description_hi' => 'nullable|string|max:65535|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'file_name' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|string|max:2048|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
        ];
    }

   public function messages(): array
{
    return [
        'file_name.file' => 'Please select a valid image file.',
        'file_name.mimes' => 'Please upload an image in JPG, JPEG, PNG, or WebP format.',
        'file_name.max' => 'The image size must be 2 MB or less.',

        'title.regex' => 'The Title contains invalid characters. Please enter a valid title.',
        'title_hi.regex' => 'The Hindi Title contains invalid characters. Please enter a valid title.',
        'description.regex' => 'The Description contains invalid characters. Please enter a valid description.',
        'description_hi.regex' => 'The Hindi Description contains invalid characters. Please enter a valid description.',
        'link.regex' => 'Please enter a valid link.',
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
