<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFortnightlyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'file_name' => 'nullable|file|mimes:pdf|max:10240',
            'file_name_hi' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'file_name.mimes' => 'File must be a PDF',
            'file_name.max' => 'File size must not exceed 10MB',
            'file_name_hi.mimes' => 'Hindi file must be a PDF',
            'file_name_hi.max' => 'Hindi file size must not exceed 10MB',
        ];
    }
}
