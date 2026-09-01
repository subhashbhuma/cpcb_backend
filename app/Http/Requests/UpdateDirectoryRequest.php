<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'name_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'cpcb_no' => 'nullable|string|max:30|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'designation' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'designation_hi' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'email' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'mobile_no' => 'nullable|string|max:20|regex:/^[0-9+\-\s]{0,20}$/',
            'office_ph_no' => 'nullable|string|max:20|regex:/^[0-9+\-\s]{0,20}$/',
            'ext_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s]{0,20}$/',
            'assigned_work' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'assigned_work_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'order_no' => 'required|integer|min:1',
            'show_order' => 'nullable|integer|min:1',
            'remarks' => 'nullable|string|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'Please enter the Name.',
        'name.max' => 'The Name must not exceed 255 characters.',
        'name.regex' => 'Please enter a valid Name.',

        'name_hi.required' => 'Please enter the Hindi Name.',
        'name_hi.max' => 'The Hindi Name must not exceed 255 characters.',
        'name_hi.regex' => 'Please enter a valid Hindi Name.',

        'cpcb_no.max' => 'The CPCB Number must not exceed 30 characters.',
        'cpcb_no.regex' => 'Please enter a valid CPCB Number.',

        'designation.required' => 'Please enter the Designation.',
        'designation.max' => 'The Designation must not exceed 255 characters.',
        'designation.regex' => 'Please enter a valid Designation.',

        'designation_hi.required' => 'Please enter the Hindi Designation.',
        'designation_hi.max' => 'The Hindi Designation must not exceed 255 characters.',
        'designation_hi.regex' => 'Please enter a valid Hindi Designation.',

        'division_id.integer' => 'Please select a valid Division.',
        'division_id.exists' => 'Please select a valid Division.',

        'email.max' => 'The Email Address must not exceed 255 characters.',
        'email.email' => 'Please enter a valid Email Address.',

        'image.image' => 'Please select a valid image.',
        'image.mimes' => 'Only JPG, JPEG, PNG, GIF, and WebP image formats are allowed.',
        'image.max' => 'The image size must not exceed 5 MB.',

        'mobile_no.max' => 'The Mobile Number must not exceed 20 characters.',
        'mobile_no.regex' => 'Please enter a valid Mobile Number.',

        'office_ph_no.max' => 'The Office Phone Number must not exceed 20 characters.',
        'office_ph_no.regex' => 'Please enter a valid Office Phone Number.',

        'ext_number.max' => 'The Extension Number must not exceed 20 characters.',
        'ext_number.regex' => 'Please enter a valid Extension Number.',

        'assigned_work.max' => 'The Assigned Work must not exceed 255 characters.',
        'assigned_work.regex' => 'Please enter valid Assigned Work.',

        'assigned_work_hi.max' => 'The Hindi Assigned Work must not exceed 255 characters.',
        'assigned_work_hi.regex' => 'Please enter valid Hindi Assigned Work.',

        'order_no.required' => 'Please enter the Display Order.',
        'order_no.integer' => 'The Display Order must be a valid number.',
        'order_no.min' => 'The Display Order must be at least 1.',

        'show_order.integer' => 'The Show Order must be a valid number.',
        'show_order.min' => 'The Show Order must be at least 1.',

        'remarks.regex' => 'Please enter valid Remarks.',
    ];
}
}
