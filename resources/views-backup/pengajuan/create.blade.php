@extends('layouts.admin')

@section('title', 'Buat Pengajuan Surat')

@section('content')
    @php
        $jenisListData = $jenisSurat->map(function ($item) {
            return [
                'id' => (int) $item->id,
                'nama' => $item->nama,
                'fields' => $item->fields_json ?? [],
            ];
        })->values()->all();
    @endphp

    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Buat Pengajuan Surat
            </h2>
            <p class="text-sm text-gray-500">
                Pilih jenis surat, lalu lengkapi form dinamis yang muncul.
            </p>
        </div>

        <div x-data="pengajuanForm()" x-cloak>
            <form
                method="POST"
                action="{{ route('pengajuan.store') }}"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                {{-- Pilih Jenis Surat --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Informasi Dasar</h3>

                    <div class="mt-4">
                        <label for="jenis_surat_id" class="block text-sm font-medium text-gray-700">
                            Jenis Surat <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="jenis_surat_id"
                            name="jenis_surat_id"
                            x-model="jenisId"
                            required
                            class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Jenis Surat --</option>
                            <template x-for="jenis in jenisList" :key="jenis.id">
                                <option :value="jenis.id" x-text="jenis.nama"></option>
                            </template>
                        </select>

                        @error('jenis_surat_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Dinamis --}}
                <div
                    x-show="selectedJenis"
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm"
                >
                    <h3 class="text-sm font-semibold text-gray-900">Data Surat</h3>

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <template x-for="field in selectedJenis.fields" :key="field.name">
                            <div :class="field.type === 'textarea' ? 'md:col-span-2' : ''">
                                <label :for="field.name" class="block text-sm font-medium text-gray-700">
                                    <span x-text="field.label"></span>
                                    <span x-show="field.required" class="text-red-500">*</span>
                                </label>

                                {{-- Textarea --}}
                                <template x-if="field.type === 'textarea'">
                                    <textarea
                                        :id="field.name"
                                        :name="'data_json[' + field.name + ']'"
                                        :required="field.required"
                                        x-model="formData[field.name]"
                                        rows="3"
                                        class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                </template>

                                {{-- Input biasa --}}
                                <template x-if="field.type !== 'textarea'">
                                    <input
                                        :id="field.name"
                                        :type="field.type || 'text'"
                                        :name="'data_json[' + field.name + ']'"
                                        :required="field.required"
                                        x-model="formData[field.name]"
                                        class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Lampiran Pendukung</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Format: PDF, JPG, JPEG, PNG. Maksimal 2 MB per file.
                    </p>

                    <input
                        type="file"
                        name="lampiran[]"
                        multiple
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="mt-4 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-800"
                    >

                    @error('lampiran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('lampiran.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Submit --}}
                <div class="flex items-center justify-end gap-3">
                    <a
                        href="{{ route('pengajuan.index') }}"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                    >
                        Simpan Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function pengajuanForm() {
            return {
                jenisList: @json($jenisListData),
                jenisId: @json((int) old('jenis_surat_id', '')),
                formData: @json((object) old('data_json', [])),

                get selectedJenis() {
                    if (!this.jenisId) {
                        return null;
                    }
                    return this.jenisList.find(jenis => jenis.id === Number(this.jenisId)) ?? null;
                },

                init() {
                    console.log('jenisList:', this.jenisList);
                    console.log('jenisId:', this.jenisId);
                    console.log('selectedJenis:', this.selectedJenis);
                }
            };
        }
    </script>
@endsection