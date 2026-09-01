<?php

namespace App\Http\Requests\InformationCenter;

use Illuminate\Foundation\Http\FormRequest;

class CreateInformationCenterDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'information_center_id' => 'required|exists:information_centers,id',
            'type' => 'required|in:FILE,URL',
            'url' => 'nullable|max:2048|required_if:type,URL|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'title_hi' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'file_name' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt|max:10240|required_if:type,FILE',
            'file_name_hi' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt|max:10240',
            'is_approved' => 'nullable|in:0,1,2',
            'is_published' => 'nullable|in:0,1',
            'remarks' => 'nullable|string|max:500',
            'publish_remark' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'information_center_id.required' => 'Information Center is required',
            'information_center_id.exists' => 'Selected Information Center does not exist',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be FILE or URL',
            'url.required_if' => 'URL is required when type is URL',
            'url.url' => 'URL must be a valid URL',
            'title.required' => 'Title is required',
            'title.max' => 'Title cannot exceed 255 characters',
            'title_hi.max' => 'Hindi Title cannot exceed 255 characters',
            'file_name.required_if' => 'File is required when type is FILE',
            'file_name.mimes' => 'File must be pdf, doc, docx, xls, xlsx, or txt',
            'file_name.max' => 'File cannot exceed 10 MB',
            'file_name_hi.mimes' => 'Hindi File must be pdf, doc, docx, xls, xlsx, or txt',
            'file_name_hi.max' => 'Hindi File cannot exceed 10 MB',
        ];
    }
}
