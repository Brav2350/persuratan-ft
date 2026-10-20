<?php

namespace App\Http\Requests;

use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePengajuanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Otorisasi detail ditangani di Policy controller, bukan di sini.
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
            // Jenis surat wajib ada di database
            'jenis_surat_id' => ['required', 'exists:jenis_surat,id'],
            
            // Target penandatangan harus user aktif dengan role Penandatangan/Super Admin
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
            
            // Data dinamis (form fields per jenis surat) wajib array
            'data_json' => ['required', 'array'],
            
            // Lampiran opsional, tapi jika ada harus array file
            'lampiran' => ['nullable', 'array'],
            
            // Setiap file lampiran: PDF/JPG/PNG, max 2MB
            'lampiran.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            
            // Tombol submit: draft atau submit (wajib salah satu)
            'submit_action' => ['required', 'in:draft,submit'],
        ];
    }

    /**
     * Custom validation logic setelah rule dasar lolos.
     * Di sini kita validasi field dinamis per jenis surat.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $jenis = JenisSurat::find($this->input('jenis_surat_id'));

            if (! $jenis) {
                return;
            }

            $data = $this->input('data_json', []);

            // Loop setiap field yang didefinisikan di jenis surat
            foreach ($jenis->fields_json ?? [] as $field) {
                $key = $field['name'] ?? null;

                // Jika field required tapi kosong, tambahkan error
                if ($key && ($field['required'] ?? false) && blank($data[$key] ?? null)) {
                    $validator->errors()->add(
                        'data_json.'.$key,
                        ($field['label'] ?? $key).' wajib diisi.'
                    );
                }
            }
        });
    }

    /**
     * Custom error messages untuk rule tertentu.
     */
    public function messages(): array
    {
        return [
            'lampiran.*.mimes' => 'Lampiran harus berformat PDF, JPG, atau PNG.',
            'lampiran.*.max' => 'Ukuran maksimal lampiran 2 MB.',
        ];
    }
}