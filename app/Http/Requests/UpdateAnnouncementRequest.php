<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAnnouncementRequest extends FormRequest
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
        $rules = [
            'title' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u'],
            'title_hi' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u'],
            'file_or_link' => ['required', 'in:file,link'],
            'page_link' => ['nullable', 'string', 'max:2048', 'regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u'],
            'status' => ['required', 'in:0,1'],
            'file_name' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'file_name_hi' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'published_date' => ['nullable', 'date'],
        ];

        if ($this->input('file_or_link') === 'file') {
            $rules['file_name'] = ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'];
        } elseif ($this->input('file_or_link') === 'link') {
            $rules['page_link'] = ['required', 'string', 'max:2048', 'regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u'];
        }

        return $rules;
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

        'description.max' => 'The Description must not exceed the allowed length.',
        'description_hi.max' => 'The Hindi Description must not exceed the allowed length.',

        'file_or_link.required' => 'Please select File or Link.',
        'file_or_link.in' => 'Please select a valid option.',

        'page_link.required' => 'Please enter the Page Link.',
        'page_link.max' => 'The Page Link must not exceed 2048 characters.',
        'page_link.regex' => 'Please enter a valid Page Link.',

        'status.required' => 'Please select the Status.',
        'status.in' => 'Please select a valid Status.',

        'file_name.required' => 'Please upload the Document.',
        'file_name.file' => 'Please select a valid document.',
        'file_name.mimes' => 'Only PDF, DOC, and DOCX files are allowed.',
        'file_name.max' => 'The document size must not exceed 2 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi document.',
        'file_name_hi.mimes' => 'Only PDF, DOC, and DOCX files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi document size must not exceed 2 MB.',

        'published_date.date' => 'Please enter a valid Published Date.',
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
