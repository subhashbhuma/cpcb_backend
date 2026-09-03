<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePageRequest extends FormRequest
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
            'type' => 'required|string|in:website,employee',
            'menu_id' => 'nullable|exists:menus,id',
            'title' => 'required|string|max:255',
            'title_hi' => 'required|string|max:255',
            'content' => 'nullable|string',
            'content_hi' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        
        // return [
        //     'type' => 'required|string|in:website,employee',
        //     'menu_id' => 'nullable|exists:menus,id',
        //     'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\-\(\)"\'’\/\.]*$/u',
        //     'title_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\-\(\)"\'’\/\.]*$/u',
        //     'content' => 'nullable|string',
        //     'content_hi' => 'nullable|string',
        //     'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ];
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
