@php
    $classes = match ($status) {
        \App\Enums\StatusPengajuan::MENGAJUKAN => 'bg-neutral-100 text-neutral-800',
        \App\Enums\StatusPengajuan::MENUNGGU_NOMOR => 'bg-warning-background text-warning-text',
        \App\Enums\StatusPengajuan::MENUNGGU_VERIFIKASI => 'bg-blue-100 text-blue-800',
        \App\Enums\StatusPengajuan::REVISI => 'bg-orange-100 text-warning-text',
        \App\Enums\StatusPengajuan::DITOLAK => 'bg-danger-background text-danger-text',
        \App\Enums\StatusPengajuan::MENUNGGU_TTD => 'bg-secondary-light text-secondary-pressed',
        \App\Enums\StatusPengajuan::SELESAI => 'bg-success-background text-success-text',
        default => 'bg-neutral-100 text-neutral-800',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $classes }}">
    {{ $status->label() }}
</span>