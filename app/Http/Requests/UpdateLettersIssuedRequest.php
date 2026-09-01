<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLettersIssuedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'type' => 'nullable|string|max:100|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'publish_date' => 'required|date',
            'file_name' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file_name_hi' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'direction_state_id' => 'nullable|array',
            'direction_state_id.*' => 'integer|exists:direction_states,id',
        ];
    }

    public function messages(): array
{
    return [
        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'type.max' => 'The Type must not exceed 100 characters.',
        'type.regex' => 'Please enter a valid Type.',

        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'publish_date.required' => 'Please select the Publish Date.',
        'publish_date.date' => 'Please enter a valid Publish Date.',

        'file_name.file' => 'Please select a valid document.',
        'file_name.mimes' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.',
        'file_name.max' => 'The document size must not exceed 10 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi document.',
        'file_name_hi.mimes' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi document size must not exceed 10 MB.',

        'direction_state_id.array' => 'Please select a valid State.',
        'direction_state_id.*.integer' => 'Please select a valid State.',
        'direction_state_id.*.exists' => 'One or more selected States are invalid.',
    ];
}
}

