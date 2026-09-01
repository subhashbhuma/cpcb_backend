<?php

namespace App\Http\Requests;

use App\Helpers\CustomHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateEmployeeRequest extends FormRequest
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
        $userId = $this->route('employee');
        $this->merge([
            'pan_no' => CustomHelper::decryptPassword($this->pan_no),
        ]);
        return [
            'name' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'email' => 'nullable|string|unique:users,email,' . $userId->id,
            'mobile_number' => 'nullable|string|size:10|regex:/^[0-9]{10}$/|unique:users,mobile_number,' . $userId->id,
            'designation_id' => 'required|integer|exists:designations,id',
            'emp_code' => 'required|string|max:50|regex:/^[A-Za-z0-9\-_\/]+$/',
            'level' => 'nullable|integer|min:0|max:100',
            'cell' => 'nullable|integer|min:0|max:100',
            'posted_at' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'pan_no' => 'nullable|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
        ];

    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
                'key' => CustomHelper::setEncryptionKey()
            ], 422)
        );
    }
}
