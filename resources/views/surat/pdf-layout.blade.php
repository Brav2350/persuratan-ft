<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #111;
        }

        .sheet {
            position: relative;
            width: 794px;
            min-height: 1123px;
        }

        .content {
            padding: 25px 55px 30px 55px;
        }

        .signature-box,
        .qr-box {
            position: absolute;
            z-index: 50;
        }

        .signature-box img {
            display: block;
        }

        .qr-box p {
            margin-top: 4px;
            font-size: 8px;
            color: #111;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="content">
            {!! $content !!}
        </div>

        @if ($showSignatureBox && $signatureHtml)
            <div
                class="signature-box"
                style="left: {{ $ttdX }}px; top: {{ $ttdY }}px;"
            >
                {!! $signatureHtml !!}
            </div>
        @endif

        @if ($showQrBox && $qrHtml)
            <div
                class="qr-box"
                style="left: {{ $qrX }}px; top: {{ $qrY }}px;"
            >
                {!! $qrHtml !!}
            </div>
        @endif
    </div>
</body>
</html>