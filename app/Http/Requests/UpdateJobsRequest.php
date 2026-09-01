<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateJobsRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'advertisement_file_name' => 'nullable|file|mimes:pdf|max:51200',
            'advertisement_file_hi_name' => 'nullable|file|mimes:pdf|max:51200',
            'direct_application_form_name' => 'nullable|file|mimes:pdf|max:51200',
            'direct_application_form_hi_name' => 'nullable|file|mimes:pdf|max:51200',
            'deputation_application_form_name' => 'nullable|file|mimes:pdf|max:51200',
            'deputation_application_form_hi_name' => 'nullable|file|mimes:pdf|max:51200',
            'job_type' => 'required|in:regular,contract',
            'direct_application' => 'required|in:online,offline',
            'deputation_application' => 'required|in:online,offline',
            'direct_application_url' => 'nullable|url|max:500',
            'deputation_application_url' => 'nullable|url|max:500',
            'online_form_url' => 'nullable|url|max:500',
            'walk_in_interview_date' => 'nullable|required_if:job_type,contract|date',
            'posts' => 'nullable|array',
            'posts.*.title' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'posts.*.title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'is_approved' => 'nullable|in:0,1,2',
            'is_published' => 'nullable|in:0,1',
            'remarks' => 'nullable|string|max:500',
            'publish_remark' => 'nullable|string|max:500',
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

        'start_date.required' => 'Please select the Start Date.',
        'start_date.date' => 'Please enter a valid Start Date.',

        'end_date.date' => 'Please enter a valid End Date.',
        'end_date.after_or_equal' => 'The End Date must be the same as or later than the Start Date.',

        'advertisement_file_name.file' => 'Please select a valid Advertisement PDF.',
        'advertisement_file_name.mimes' => 'Only PDF files are allowed for the Advertisement.',
        'advertisement_file_name.max' => 'The Advertisement PDF size must not exceed 50 MB.',

        'advertisement_file_hi_name.file' => 'Please select a valid Hindi Advertisement PDF.',
        'advertisement_file_hi_name.mimes' => 'Only PDF files are allowed for the Hindi Advertisement.',
        'advertisement_file_hi_name.max' => 'The Hindi Advertisement PDF size must not exceed 50 MB.',

        'direct_application_form_name.file' => 'Please select a valid Direct Application Form PDF.',
        'direct_application_form_name.mimes' => 'Only PDF files are allowed for the Direct Application Form.',
        'direct_application_form_name.max' => 'The Direct Application Form PDF size must not exceed 50 MB.',

        'direct_application_form_hi_name.file' => 'Please select a valid Hindi Direct Application Form PDF.',
        'direct_application_form_hi_name.mimes' => 'Only PDF files are allowed for the Hindi Direct Application Form.',
        'direct_application_form_hi_name.max' => 'The Hindi Direct Application Form PDF size must not exceed 50 MB.',

        'deputation_application_form_name.file' => 'Please select a valid Deputation Application Form PDF.',
        'deputation_application_form_name.mimes' => 'Only PDF files are allowed for the Deputation Application Form.',
        'deputation_application_form_name.max' => 'The Deputation Application Form PDF size must not exceed 50 MB.',

        'deputation_application_form_hi_name.file' => 'Please select a valid Hindi Deputation Application Form PDF.',
        'deputation_application_form_hi_name.mimes' => 'Only PDF files are allowed for the Hindi Deputation Application Form.',
        'deputation_application_form_hi_name.max' => 'The Hindi Deputation Application Form PDF size must not exceed 50 MB.',

        'job_type.required' => 'Please select the Job Type.',
        'job_type.in' => 'Please select a valid Job Type.',

        'direct_application.required' => 'Please select the Direct Application Mode.',
        'direct_application.in' => 'Please select a valid Direct Application Mode.',

        'deputation_application.required' => 'Please select the Deputation Application Mode.',
        'deputation_application.in' => 'Please select a valid Deputation Application Mode.',

        'direct_application_url.url' => 'Please enter a valid Direct Application URL.',
        'direct_application_url.max' => 'The Direct Application URL must not exceed 500 characters.',

        'deputation_application_url.url' => 'Please enter a valid Deputation Application URL.',
        'deputation_application_url.max' => 'The Deputation Application URL must not exceed 500 characters.',

        'online_form_url.url' => 'Please enter a valid Online Form URL.',
        'online_form_url.max' => 'The Online Form URL must not exceed 500 characters.',

        'walk_in_interview_date.required_if' => 'Please select the Walk-in Interview Date.',
        'walk_in_interview_date.date' => 'Please enter a valid Walk-in Interview Date.',

        'posts.array' => 'Please provide valid Post details.',

        'posts.*.title.max' => 'The Post Title must not exceed 255 characters.',
        'posts.*.title.regex' => 'Please enter a valid Post Title.',

        'posts.*.title_hi.max' => 'The Hindi Post Title must not exceed 255 characters.',
        'posts.*.title_hi.regex' => 'Please enter a valid Hindi Post Title.',

        'is_approved.in' => 'Please select a valid Approval Status.',
        'is_published.in' => 'Please select a valid Publication Status.',

        'remarks.max' => 'The Remarks must not exceed 500 characters.',
        'publish_remark.max' => 'The Publish Remark must not exceed 500 characters.',
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
