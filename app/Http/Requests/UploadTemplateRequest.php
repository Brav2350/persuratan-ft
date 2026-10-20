<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template' => [
                'nullable',
                'file',
                'extensions:html,htm',
                'max:1024',
            ],
            'ttd_x' => [
                'required',
                'integer',
                'min:0',
                'max:2000',
            ],
            'ttd_y' => [
                'required',
                'integer',
                'min:0',
                'max:2000',
            ],
            'qr_x' => [
                'required',
                'integer',
                'min:0',
                'max:2000',
            ],
            'qr_y' => [
                'required',
                'integer',
                'min:0',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'template.extensions' => 'Template harus berupa file HTML.',
            'template.max' => 'Ukuran template maksimal 1 MB.',
        ];
    }
}