<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeadOfficeRequest extends FormRequest
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
            'division_id' => 'required|integer|exists:divisions,id',
            'title' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'email' => [
                'nullable',
                'max:1000',
                'regex:/^\s*[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\s*(?:,\s*[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\s*)*$/',
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ext_number' => 'nullable|string|max:50|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'description' => 'nullable|string|max:65535',
            'description_hi' => 'nullable|string|max:65535',
            'order' => 'nullable|integer',
            'is_approved' => 'nullable|in:0,1,2',
            'is_published' => 'nullable|boolean',
            'remarks' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'publish_remark' => 'nullable|string|max:500',
            'personnels' => 'nullable|array',
            'personnels.*.id' => 'nullable|integer',
            'personnels.*.title' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'personnels.*.title_hi' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'personnels.*.designation' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'personnels.*.designation_hi' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'personnels.*.order' => 'nullable|integer|min:0',
            'personnels.*.record_status' => 'nullable|in:0,1',
            'profile_activities' => 'nullable|array',
            'profile_activities.*.id' => 'nullable|integer',
            'profile_activities.*.title' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'profile_activities.*.title_hi' => 'nullable|string|max:1000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'profile_activities.*.order' => 'nullable|integer|min:0',
            'profile_activities.*.record_status' => 'nullable|in:0,1',
        ];
    }
}
