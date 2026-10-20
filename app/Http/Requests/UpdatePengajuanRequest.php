<?php

namespace App\Http\Requests;

use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_surat_id' => ['required', 'exists:jenis_surat,id'],
            'target_signer_id' => [
                'required',
                'exists:users,id',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $calon = User::find($value);

                    if (! $calon || ! $calon->hasRole(['Penandatangan', 'Super Admin']) || ! $calon->is_active) {
                        $fail('User yang dipilih bukan penandatangan aktif.');
                    }
                },
            ],
            'data_json' => ['required', 'array'],
            'lampiran' => ['nullable', 'array'],
            'lampiran.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'submit_action' => ['required', 'in:draft,submit'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $jenis = JenisSurat::find($this->input('jenis_surat_id'));

            if (! $jenis) {
                return;
            }

            $data = $this->input('data_json', []);

            foreach ($jenis->fields_json ?? [] as $field) {
                $key = $field['name'] ?? null;

                if ($key && ($field['required'] ?? false) && blank($data[$key] ?? null)) {
                    $validator->errors()->add(
                        'data_json.'.$key,
                        ($field['label'] ?? $key).' wajib diisi.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'lampiran.*.mimes' => 'Lampiran harus berformat PDF, JPG, atau PNG.',
            'lampiran.*.max' => 'Ukuran maksimal lampiran 2 MB.',
        ];
    }
}