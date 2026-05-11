<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files'         => 'required|array|min:1|max:5',
            'files.*'       => 'file|mimes:jpg,jpeg,png,mp4,mov|max:51200',
            'keterangan.*'  => 'nullable|string|max:255',
        ];
    }
}
