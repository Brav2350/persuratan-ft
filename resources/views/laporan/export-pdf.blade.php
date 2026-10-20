<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
        }

        h1 {
            font-size: 15px;
            margin-bottom: 4px;
        }

        p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            vertical-align: top;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Pengajuan Surat</h1>

    <p>
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>

    @if (! empty($filters['dari']) || ! empty($filters['sampai']))
        <p>
            Rentang tanggal:
            {{ $filters['dari'] ?? '-' }}
            s/d
            {{ $filters['sampai'] ?? '-' }}
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No Tiket</th>
                <th>Nomor Surat</th>
                <th>Jenis Surat</th>
                <th>Pemohon</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
                <th>Tanggal TTD</th>
                <th>Penandatangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporan as $item)
                <tr>
                    <td>{{ $item->no_tiket }}</td>
                    <td>{{ $item->nomor_surat ?? '-' }}</td>
                    <td>{{ $item->jenisSurat?->nama }}</td>
                    <td>{{ $item->pemohon?->name }}</td>
                    <td>{{ $item->status->label() }}</td>
                    <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $item->tanggal_ttd?->format('d M Y H:i') ?? '-' }}</td>
                    <td>{{ $item->penandatangan?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">
                        Tidak ada data sesuai filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>