<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSocialMediaRequest extends FormRequest
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
            'type' => 'required|string|in:1,2,3,4,5,6',
            'name' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'url' => 'required|url|max:255',
            'embed_code' => 'required|string',
            'icon_class' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-_]+$/',
            'is_approved' => 'nullable|in:0,1,2',
            'is_published' => 'nullable|in:0,1',
            'remarks' => 'nullable|string|max:500',
            'publish_remark' => 'nullable|string|max:500',
        ];

        return $rules;
    }

    public function messages(): array
{
    return [
        'type.required' => 'Please select the Type.',
        'type.in' => 'Please select a valid Type.',

        'name.required' => 'Please enter the Name.',
        'name.max' => 'The Name must not exceed 255 characters.',
        'name.regex' => 'Please enter a valid Name.',

        'url.required' => 'Please enter the URL.',
        'url.url' => 'Please enter a valid URL.',
        'url.max' => 'The URL must not exceed 255 characters.',

        'embed_code.required' => 'Please enter the Embed Code.',

        'icon_class.max' => 'The Icon Class must not exceed 255 characters.',
        'icon_class.regex' => 'Please enter a valid Icon Class.',

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
