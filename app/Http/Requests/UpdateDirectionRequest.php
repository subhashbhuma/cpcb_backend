<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectionRequest extends FormRequest
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
            'direction_act_type_id' => 'required|integer|exists:direction_act_types,id',
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'publish_date' => 'required|date',
            'file_name' => [
                'nullable',
                'file',
                'mimetypes:application/pdf,application/octet-stream',
            ],

            'file_name_hi' => [
                'nullable',
                'file',
                'mimetypes:application/pdf,application/octet-stream',
            ],
            'direction_state_id' => 'required|array|min:1',
            'direction_state_id.*' => 'required|integer|exists:direction_states,id',
            'direction_category_id' => 'required|integer|exists:direction_categories,id',
            'direction_issued_to_id' => 'required|array|min:1',
            'direction_issued_to_id.*' => 'required|integer|exists:direction_issued_tos,id',
        ];
    }

    public function messages(): array
{
    return [
        'direction_act_type_id.required' => 'Please select the Direction Act Type.',
        'direction_act_type_id.integer' => 'Please select a valid Direction Act Type.',
        'direction_act_type_id.exists' => 'Please select a valid Direction Act Type.',

        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.required' => 'Please enter the Hindi Title.',
        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'publish_date.required' => 'Please select the Publish Date.',
        'publish_date.date' => 'Please enter a valid Publish Date.',

        'file_name.required' => 'Please upload the PDF file.',
        'file_name.file' => 'Please select a valid PDF file.',
        'file_name.mimes' => 'Only PDF files are allowed.',
        'file_name.max' => 'The PDF file size must not exceed 50 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi PDF file.',
        'file_name_hi.mimes' => 'Only PDF files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi PDF file size must not exceed 50 MB.',

        'direction_state_id.required' => 'Please select at least one State.',
        'direction_state_id.array' => 'Please select a valid State.',
        'direction_state_id.min' => 'Please select at least one State.',
        'direction_state_id.*.required' => 'Please select a State.',
        'direction_state_id.*.integer' => 'Please select a valid State.',
        'direction_state_id.*.exists' => 'One or more selected States are invalid.',

        'direction_category_id.required' => 'Please select the Direction Category.',
        'direction_category_id.integer' => 'Please select a valid Direction Category.',
        'direction_category_id.exists' => 'Please select a valid Direction Category.',

        'direction_issued_to_id.required' => 'Please select at least one Issued To option.',
        'direction_issued_to_id.array' => 'Please select a valid Issued To option.',
        'direction_issued_to_id.min' => 'Please select at least one Issued To option.',
        'direction_issued_to_id.*.required' => 'Please select an Issued To option.',
        'direction_issued_to_id.*.integer' => 'Please select a valid Issued To option.',
        'direction_issued_to_id.*.exists' => 'One or more selected Issued To options are invalid.',
    ];
}
}
