@php
    $jenisSurat =$jenisSurat ?? null;

    // Ambil data field dari old input atau database
    $rawFields = old('fields_json',$jenisSurat?->fields_json ?? []);

    // Pastikan setiap elemen adalah array, lalu format ulang
    $initialFields = collect($rawFields)
        ->filter(function ($field) {
            return is_array($field);
        })
        ->map(function ($field) {
            return [
                'name' => $field['name'] ?? '',
                'label' => $field['label'] ?? '',
                'type' => $field['type'] ?? 'text',
                'required' => filter_var($field['required'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];
        })
        ->values()
        ->all();
@endphp

<div x-data="jenisSuratForm()" class="space-y-6" x-cloak>
    {{-- Informasi Jenis Surat --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Informasi Jenis Surat</h3>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Jenis Surat <span class="text-rose-600">*</span>
                </label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $jenisSurat?->nama) }}"
                    required
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                >
                @error('nama')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kode" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kode Surat <span class="text-rose-600">*</span>
                </label>
                <input
                    type="text"
                    id="kode"
                    name="kode"
                    value="{{ old('kode', $jenisSurat?->kode) }}"
                    required
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                >
                @error('kode')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Field Form Dinamis --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Field Form Dinamis</h3>
                <p class="mt-1 text-xs text-slate-500">
                    Field ini akan muncul saat admin membuat pengajuan surat.
                </p>
            </div>

            <button
                type="button"
                @click="addField()"
                class="rounded-xl bg-[#517085] px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] transition duration-150"
            >
                + Tambah Field
            </button>
        </div>

        <div class="mt-4 space-y-3">
            <template x-for="(field, index) in fields" :key="index">
                <div class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200/80 bg-slate-50/50 p-4 md:grid-cols-12 items-end">
                    <div class="md:col-span-3">
                        <label class="mb-1 block text-[11px] font-semibold uppercase text-slate-500">Nama Field</label>
                        <input
                            type="text"
                            x-model="field.name"
                            :name="`fields_json[${index}][name]`"
                            placeholder="contoh: nim"
                            class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                        >
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-[11px] font-semibold uppercase text-slate-500">Label</label>
                        <input
                            type="text"
                            x-model="field.label"
                            :name="`fields_json[${index}][label]`"
                            placeholder="contoh: NIM"
                            class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                        >
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-[11px] font-semibold uppercase text-slate-500">Tipe Input</label>
                        <select
                            x-model="field.type"
                            :name="`fields_json[${index}][type]`"
                            class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                        >
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="email">Email</option>
                            <option value="tel">Telepon</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between md:col-span-3 pb-1">
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                            <input
                                type="hidden"
                                :name="`fields_json[${index}][required]`"
                                value="0"
                            >
                            <input
                                type="checkbox"
                                x-model="field.required"
                                :name="`fields_json[${index}][required]`"
                                value="1"
                                class="rounded border-slate-300 text-[#517085] focus:ring-[#517085]"
                            >
                            Wajib
                        </label>

                        <button
                            type="button"
                            @click="removeField(index)"
                            class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition duration-150"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="fields.length === 0" class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-xs text-slate-400">
                Belum ada field dinamis. Klik tombol "Tambah Field" di atas.
            </div>
        </div>

        @error('fields_json')
            <p class="mt-3 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
    function jenisSuratForm() {
        return {
            fields: @json($initialFields ?? []),

            init() {
                console.log('Alpine initialized. Fields:', this.fields);
            },

            addField() {
                this.fields.push({
                    name: '',
                    label: '',
                    type: 'text',
                    required: false
                });
            },

            removeField(index) {
                this.fields.splice(index, 1);
            }
        };
    }
</script>