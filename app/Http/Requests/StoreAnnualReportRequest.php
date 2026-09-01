<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAnnualReportRequest extends FormRequest
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
            'release_date' => 'required|date|after_or_equal:publish_date',
            'file_name' => 'nullable|file|mimes:pdf|max:51200',
            'file_name_hi' => 'nullable|file|mimes:pdf|max:51200',
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

        'release_date.required' => 'Please select the Release Date.',
        'release_date.date' => 'Please enter a valid Release Date.',
        'release_date.after_or_equal' => 'The Release Date must be the same as or later than the Publish Date.',

        'file_name.file' => 'Please select a valid PDF file.',
        'file_name.mimes' => 'Only PDF files are allowed.',
        'file_name.max' => 'The PDF file size must not exceed 50 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi PDF file.',
        'file_name_hi.mimes' => 'Only PDF files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi PDF file size must not exceed 50 MB.',
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
