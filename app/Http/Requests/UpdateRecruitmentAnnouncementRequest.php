<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRecruitmentAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_id' => 'required|exists:jobs,id',
            'job_post_id' => 'required|array',
            'job_post_id.*' => 'exists:job_posts,id',
            'type' => 'required|in:result,notification',
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'file_name' => 'nullable|file|mimes:pdf|max:51200',
            'file_name_hi' => 'nullable|file|mimes:pdf|max:51200',
            'remarks' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
{
    return [
        'job_id.required' => 'Please select the Job.',
        'job_id.exists' => 'Please select a valid Job.',

        'job_post_id.required' => 'Please select at least one Job Post.',
        'job_post_id.array' => 'Please select a valid Job Post.',
        'job_post_id.*.exists' => 'One or more selected Job Posts are invalid.',

        'type.required' => 'Please select the Type.',
        'type.in' => 'Please select a valid Type.',

        'title.required' => 'Please enter the Title.',
        'title.max' => 'The Title must not exceed 255 characters.',
        'title.regex' => 'Please enter a valid Title.',

        'title_hi.max' => 'The Hindi Title must not exceed 255 characters.',
        'title_hi.regex' => 'Please enter a valid Hindi Title.',

        'start_date.date' => 'Please enter a valid Issue Date.',

        'end_date.date' => 'Please enter a valid End Date.',
        'end_date.after_or_equal' => 'The End Date must be the same as or later than the Start Date.',

        'file_name.file' => 'Please select a valid PDF file.',
        'file_name.mimes' => 'Only PDF files are allowed.',
        'file_name.max' => 'The PDF file size must not exceed 50 MB.',

        'file_name_hi.file' => 'Please select a valid Hindi PDF file.',
        'file_name_hi.mimes' => 'Only PDF files are allowed for the Hindi document.',
        'file_name_hi.max' => 'The Hindi PDF file size must not exceed 50 MB.',

        'remarks.max' => 'The Remarks must not exceed 1000 characters.',
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
