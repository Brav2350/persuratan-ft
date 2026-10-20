<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>Verifikasi Surat - Fakultas Teknik</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-full items-center justify-center px-4 py-10">
    <div class="w-full max-w-xl">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="border-b border-gray-100 pb-5">
                <h1 class="text-lg font-semibold text-gray-900">
                    Verifikasi Surat
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Fakultas Teknik
                </p>
            </div>

            @if ($isValid)
                <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                    <p class="text-sm font-semibold text-green-800">
                        VALID
                    </p>

                    <p class="mt-1 text-sm text-green-700">
                        Surat ini terdaftar dan telah ditandatangani secara digital.
                    </p>
                </div>

                <dl class="mt-6 space-y-4">
                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">
                            Nomor Surat
                        </dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $pengajuan->nomor_surat }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">
                            Jenis Surat
                        </dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $pengajuan->jenisSurat?->nama ?? '-' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">
                            Nama
                        </dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $namaPemohon }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">
                            Tanggal Tanda Tangan
                        </dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $pengajuan->tanggal_ttd?->translatedFormat('d F Y H:i') }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">
                            Penandatangan
                        </dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $pengajuan->penandatangan?->name ?? '-' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">
                            NIP Penandatangan
                        </dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $pengajuan->penandatangan?->nip ?? '-' }}
                        </dd>
                    </div>
                </dl>
            @else
                <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                    <p class="text-sm font-semibold text-red-800">
                        TIDAK VALID
                    </p>

                    <p class="mt-1 text-sm text-red-700">
                        Surat tidak ditemukan, belum ditandatangani, atau tidak valid.
                    </p>
                </div>

                <p class="mt-4 text-sm text-gray-600">
                    Pastikan QR code yang Anda pindai berasal dari dokumen resmi Sistem Persuratan Fakultas Teknik.
                </p>
            @endif

            <div class="mt-8 border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-400">
                    Halaman verifikasi ini bersifat publik dan tidak menampilkan seluruh data dokumen.
                </p>
            </div>
        </div>
    </div>
</body>
</html>