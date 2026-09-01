<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCommentReportRequest extends FormRequest
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
            'title' => 'required|string|max:5000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:5000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'emails' => 'nullable|string|max:1000|regex:/^[\w\.\+\-]+@[\w\.\-]+(,\s*[\w\.\+\-]+@[\w\.\-]+)*$/',
            'published_date' => 'nullable|date',
            'file_name' => 'required|file|mimes:pdf|max:5120',
            'file_name_hi' => 'nullable|file|mimes:pdf|max:5120',
            ];
    }

    public function messages(): array
{
    return [
        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 5000 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.max' => 'The Hindi Title must not exceed 5000 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'emails.max' => 'The Email Address must not exceed 1000 characters.',
        'emails.regex' => 'Please enter valid Email Address(es). Separate multiple email addresses with commas.',

        'published_date.date' => 'Please enter a valid Published Date.',

        'file_name.required' => 'Please upload the PDF file.',
        'file_name.file' => 'Please select a valid PDF file.',
        'file_name.mimes' => 'Only PDF files are allowed.',
        'file_name.max' => 'The PDF file size must not exceed 5 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi PDF file.',
        'file_name_hi.mimes' => 'Only PDF files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi PDF file size must not exceed 5 MB.',
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
