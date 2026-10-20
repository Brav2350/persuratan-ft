@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                Notifikasi
            </h2>
            <p class="text-sm text-gray-500">
                Daftar notifikasi perubahan status pengajuan.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('notifications.read-all') }}"
        >
            @csrf

            <button
                type="submit"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
            >
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse ($notifications as $notification)
            @php
                $data = $notification->data ?? [];
            @endphp

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm {{ $notification->read_at ? 'opacity-70' : 'border-blue-200 bg-blue-50/30' }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $data['title'] ?? 'Notifikasi' }}
                        </p>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ $data['jenis_surat'] ?? 'Jenis surat tidak diketahui' }}
                            - No Tiket:
                            {{ $data['no_tiket'] ?? '-' }}
                        </p>

                        @if (! empty($data['old_status']) && ! empty($data['new_status']))
                            <p class="mt-1 text-sm text-gray-600">
                                Status berubah dari
                                <span class="font-medium">{{ $data['old_status'] }}</span>
                                menjadi
                                <span class="font-medium">{{ $data['new_status'] }}</span>.
                            </p>
                        @endif

                        <p class="mt-2 text-xs text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="shrink-0">
                        <form
                            method="POST"
                            action="{{ route('notifications.mark-read', $notification->id) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="rounded-md bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-800"
                            >
                                Buka
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
                Belum ada notifikasi.
            </div>
        @endforelse
    </div>

    @if ($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection