<div style="text-align: center;">
    <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">
        {{ $pengajuan->jenisSurat->nama }}
    </h2>

    <p style="margin: 4px 0 0; font-size: 12px;">
        Nomor: {{ $pengajuan->nomor_surat ?? '-' }}
    </p>
</div>

<div style="margin-top: 40px;">
    <p style="margin: 0;">
        Yang bertanda tangan di bawah ini menerangkan bahwa:
    </p>

    <table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
        @foreach ($pengajuan->data_json ?? [] as $key => $value)
            @php
                if (is_array($value)) {
                    $value = implode(', ', array_filter($value, 'is_scalar'));
                }
            @endphp

            <tr>
                <td style="width: 35%; padding: 4px 0; vertical-align: top;">
                    {{ ucwords(str_replace('_', ' ', $key)) }}
                </td>
                <td style="width: 5%; vertical-align: top;">:</td>
                <td style="padding: 4px 0;">
                    {{ $value }}
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div style="margin-top: 30px;">
    <p style="margin: 0;">
        Demikian surat ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
    </p>
</div>