<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAgraAirQualityRequest extends FormRequest
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
            'quality_zone_id' => 'required|exists:quality_zones,id',
            'title' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'file_name' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_name_hi' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'for_date' => 'required|date',
        ];
    }

    public function messages(): array
{
    return [
        'quality_zone_id.required' => 'Please select the Quality Zone.',
        'quality_zone_id.exists' => 'Please select a valid Quality Zone.',

        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'file_name.required' => 'Please upload the Document.',
        'file_name.file' => 'Please select a valid document.',
        'file_name.mimes' => 'Only PDF, DOC, and DOCX files are allowed.',
        'file_name.max' => 'The document size must not exceed 10 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi document.',
        'file_name_hi.mimes' => 'Only PDF, DOC, and DOCX files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi document size must not exceed 10 MB.',

        'for_date.required' => 'Please select the Date.',
        'for_date.date' => 'Please enter a valid Date.',
    ];
}

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
