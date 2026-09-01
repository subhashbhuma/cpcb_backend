<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateEnvironmentalRegulationDetailRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'environmental_regulation_id' => 'nullable|exists:environmental_regulations,id',
            'parent_id' => 'nullable|exists:environmental_regulation_details,id',
            'order' => 'nullable|integer|min:0',

            'type' => 'required|in:FILE,URL',
            'url' => 'nullable|string|max:2048|required_if:type,URL|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',

            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',

            'file_name' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'file_name_hi' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'publish_remark' => 'nullable|string|max:500',
        ];
    }

    /**
     * Handle failed validation response.
     */
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
