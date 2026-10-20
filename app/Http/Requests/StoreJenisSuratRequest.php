<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJenisSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'kode' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                'unique:jenis_surat,kode',
            ],
            'fields_json' => [
                'required',
                'array',
                'min:1',
            ],
            'fields_json.*.name' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'distinct',
                Rule::notIn($this->systemPlaceholders()),
            ],
            'fields_json.*.label' => [
                'required',
                'string',
                'max:255',
            ],
            'fields_json.*.type' => [
                'required',
                Rule::in(['text', 'textarea', 'number', 'date', 'email', 'tel']),
            ],
            'fields_json.*.required' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fields_json.required' => 'Minimal satu field form harus dibuat.',
            'fields_json.min' => 'Minimal satu field form harus dibuat.',
            'fields_json.*.name.not_in' => 'Nama field tidak boleh menggunakan nama placeholder sistem.',
            'fields_json.*.name.distinct' => 'Nama field tidak boleh duplikat.',
        ];
    }

    protected function systemPlaceholders(): array
    {
        return [
            'nomor_surat',
            'no_tiket',
            'jenis_surat',
            'kode_surat',
            'tanggal',
            'tanggal_ttd',
            'penandatangan_nama',
            'penandatangan_nip',
            'signature',
            'tanda_tangan',
            'qr',
            'qr_code',
        ];
    }
}