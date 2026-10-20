<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            [
                'nama' => 'Surat Keterangan Aktif Kuliah',
                'kode' => 'SAK',
                'fields_json' => [
                    ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['name' => 'nim', 'label' => 'NIM', 'type' => 'text', 'required' => true],
                    ['name' => 'semester', 'label' => 'Semester', 'type' => 'number', 'required' => true],
                    ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'textarea', 'required' => true],
                ]
            ],
            [
                'nama' => 'Surat Tugas',
                'kode' => 'ST',
                'fields_json' => [
                    ['name' => 'nama', 'label' => 'Nama Pelaksana', 'type' => 'text', 'required' => true],
                    ['name' => 'nip_nim', 'label' => 'NIP/NIM', 'type' => 'text', 'required' => true],
                    ['name' => 'kegiatan', 'label' => 'Nama Kegiatan', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'date', 'required' => true],
                    ['name' => 'tanggal_selesai', 'label' => 'Tanggal Selesai', 'type' => 'date', 'required' => true],
                ]
            ],
            // Tambahkan sisanya (Rekomendasi, Cuti, SKL, dll) sesuai kebutuhan
        ];

        foreach ($jenisSurat as $data) {
            JenisSurat::create($data);
        }
    }
}