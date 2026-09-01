<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCircularRequest extends FormRequest
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
            'division_id' => 'nullable|integer|exists:divisions,id',
            'category' => 'required|exists:circular_categories,id',
            'published_date' => 'required|date',
            'file_name' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'file_name_hi' => 'nullable|file|mimes:pdf,doc,docx|max:5120'

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

        'division_id.integer' => 'Please select a valid Division.',
        'division_id.exists' => 'Please select a valid Division.',

        'category.required' => 'Please select the Category.',
        'category.exists' => 'Please select a valid Category.',

        'published_date.required' => 'Please select the Published Date.',
        'published_date.date' => 'Please enter a valid Published Date.',

        'file_name.required' => 'Please upload the Document.',
        'file_name.file' => 'Please select a valid document.',
        'file_name.mimes' => 'Only PDF, DOC, and DOCX files are allowed.',
        'file_name.max' => 'The document size must not exceed 5 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi document.',
        'file_name_hi.mimes' => 'Only PDF, DOC, and DOCX files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi document size must not exceed 5 MB.',
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
