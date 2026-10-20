@php
    $classes = match ($status) {
        \App\Enums\StatusPengajuan::MENGAJUKAN => 'bg-gray-100 text-gray-800',
        \App\Enums\StatusPengajuan::MENUNGGU_NOMOR => 'bg-yellow-100 text-yellow-800',
        \App\Enums\StatusPengajuan::MENUNGGU_VERIFIKASI => 'bg-blue-100 text-blue-800',
        \App\Enums\StatusPengajuan::REVISI => 'bg-orange-100 text-orange-800',
        \App\Enums\StatusPengajuan::DITOLAK => 'bg-red-100 text-red-800',
        \App\Enums\StatusPengajuan::MENUNGGU_TTD => 'bg-purple-100 text-purple-800',
        \App\Enums\StatusPengajuan::SELESAI => 'bg-green-100 text-green-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $classes }}">
    {{ $status->label() }}
</span>