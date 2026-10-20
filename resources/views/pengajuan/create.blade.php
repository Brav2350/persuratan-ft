```blade
@extends('layouts.admin')

@section('title', 'Buat Pengajuan Surat')

@section('content')
<div
    x-data="pengajuanForm(@js($jenisListData), @js(old('data_json', [])), @js(old('jenis_surat_id', '')))"
    class="mx-auto max-w-4xl"
>
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Buat Pengajuan Surat</h2>
        <p class="mt-1 text-xs text-slate-500">
            Isi data sesuai jenis surat. Simpan sebagai draft untuk dilanjutkan nanti, atau langsung ajukan ke BAAK.
        </p>
    </div>

    @php $dataErrors = Arr::flatten($errors->get('data_json.*')); @endphp
    @if (count($dataErrors))
        <div class="mb-6 rounded-2xl border border-rose-200/80 bg-rose-50/80 p-4 text-xs text-rose-800 shadow-sm">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($dataErrors as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('pengajuan.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf

        {{-- Informasi Dasar --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Informasi Dasar</h3>

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="jenis_surat_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Surat <span class="text-rose-600">*</span>
                    </label>
                    <select
                        id="jenis_surat_id"
                        name="jenis_surat_id"
                        x-model="jenisId"
                        required
                        class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                    >
                        <option value="">-- Pilih Jenis Surat --</option>
                        <template x-for="jenis in jenisList" :key="jenis.id">
                            <option :value="jenis.id" x-text="jenis.nama"></option>
                        </template>
                    </select>
                    @error('jenis_surat_id')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="target_signer_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Penandatangan <span class="text-rose-600">*</span>
                    </label>
                    <select
                        id="target_signer_id"
                        name="target_signer_id"
                        required
                        class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                    >
                        <option value="">-- Pilih Penandatangan --</option>
                        @foreach ($penandatangan as $calon)
                            <option value="{{ $calon->id }}" @selected(old('target_signer_id') == $calon->id)>
                                {{ $calon->name }} — NIP: {{ $calon->nip ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('target_signer_id')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Field Dinamis --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Data Isian Surat</h3>

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2" x-show="selectedJenis" x-cloak>
                <template x-for="field in (selectedJenis ? selectedJenis.fields : [])" :key="field.name">
                    <div :class="field.type === 'textarea' ? 'md:col-span-2' : ''">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            <span x-text="field.label"></span>
                            <span x-show="field.required" class="text-rose-600">*</span>
                        </label>

                        <textarea
                            x-show="field.type === 'textarea'"
                            :name="`data_json[${field.name}]`"
                            x-model="data[field.name]"
                            rows="3"
                            class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                        ></textarea>

                        <input
                            x-show="field.type !== 'textarea'"
                            :type="field.type === 'number' ? 'number' : (field.type === 'date' ? 'date' : 'text')"
                            :name="`data_json[${field.name}]`"
                            x-model="data[field.name]"
                            class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                        >
                    </div>
                </template>
            </div>

            <p x-show="!selectedJenis" class="mt-2 text-xs text-slate-400">
                Pilih jenis surat terlebih dahulu untuk menampilkan form isian.
            </p>
        </div>

        {{-- Lampiran --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Lampiran Pendukung (Opsional)</h3>
            <input
                type="file"
                name="lampiran[]"
                multiple
                accept=".pdf,.jpg,.jpeg,.png"
                class="mt-3 w-full text-xs text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-[#517085] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-[#425d70] transition duration-150"
            >
            <p class="mt-2 text-[11px] text-slate-400">Format PDF/JPG/PNG, maksimal 2 MB per file.</p>
            @error('lampiran.*')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200/80">
            <a
                href="{{ route('pengajuan.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
            >
                Batal
            </a>
            <button
                type="submit"
                name="submit_action"
                value="draft"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-[#517085] hover:bg-slate-50 transition duration-150"
            >
                Simpan Draft
            </button>
            <button
                type="submit"
                name="submit_action"
                value="submit"
                onclick="return confirm('Ajukan surat ini untuk diproses BAAK?')"
                class="rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
            >
                Ajukan Surat
            </button>
        </div>
    </form>
</div>

<script>
    function pengajuanForm(jenisList, initialData, initialJenisId) {
        return {
            jenisList: jenisList,
            data: initialData || {},
            jenisId: initialJenisId || '',
            get selectedJenis() {
                return this.jenisList.find(j => String(j.id) === String(this.jenisId));
            },
        };
    }
</script>
@endsection
```