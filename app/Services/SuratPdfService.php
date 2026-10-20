<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Throwable;

class SuratPdfService
{
    /**
     * Render PDF tanpa menyimpan file.
     * Digunakan untuk preview.
     */
    public function render(PengajuanSurat $pengajuan, User $signer): string
    {
        $html = $this->buildHtml($pengajuan, $signer);

        $pdf = Pdf::loadHtml($html);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    /**
     * Generate PDF final dan simpan ke storage.
     */
    public function finalize(PengajuanSurat $pengajuan, User $signer): string
    {
        $content = $this->render($pengajuan, $signer);

        $disk = Storage::disk(config('surat.pdf_disk', 'local'));

        $path = sprintf(
            'surat/final/%s/%s-%s.pdf',
            now()->format('Y/m'),
            $pengajuan->id,
            Str::slug($pengajuan->no_tiket)
        );

        $disk->put($path, $content);

        return $path;
    }

    protected function buildHtml(PengajuanSurat $pengajuan, User $signer): string
{
    $pengajuan->loadMissing(['jenisSurat']);

    if (blank($pengajuan->qr_token)) {
        $pengajuan->forceFill([
            'qr_token' => (string) Str::uuid(),
        ])->save();
    }

    $template = $this->getTemplate($pengajuan);

    // Kirim $pengajuan ke getSignatureHtml
    $signatureHtml = $this->getSignatureHtml($signer, $pengajuan);
    $qrHtml = $this->getQrHtml($pengajuan);

    $placeholders = $this->getPlaceholders(
        $pengajuan,
        $signer,
        $signatureHtml,
        $qrHtml
    );

    $content = strtr($template, $placeholders);

    $showSignatureBox = ! str_contains($template, '{{signature}}')
        && ! str_contains($template, '{{tanda_tangan}}');

    $showQrBox = ! str_contains($template, '{{qr}}')
        && ! str_contains($template, '{{qr_code}}');

    return view('surat.pdf-layout', [
        'content' => $content,
        'signatureHtml' => $signatureHtml,
        'qrHtml' => $qrHtml,
        'showSignatureBox' => $showSignatureBox,
        'showQrBox' => $showQrBox,
        'ttdX' => (int) $pengajuan->jenisSurat->ttd_x,
        'ttdY' => (int) $pengajuan->jenisSurat->ttd_y,
        'qrX' => (int) $pengajuan->jenisSurat->qr_x,
        'qrY' => (int) $pengajuan->jenisSurat->qr_y,
    ])->render();
}

    protected function getTemplate(PengajuanSurat $pengajuan): string
    {
        $templatePath = $pengajuan->jenisSurat->template_path;

        if ($templatePath) {
            $disk = Storage::disk(config('surat.template_disk', 'local'));

            if ($disk->exists($templatePath)) {
                $template = $disk->get($templatePath);

                return $this->sanitizeTemplate($template);
            }
        }

        return view('surat.default-template', [
            'pengajuan' => $pengajuan,
        ])->render();
    }

    protected function sanitizeTemplate(string $html): string
    {
        // Hapus tag PHP
        $html = preg_replace('/<\?php.*?\?>/is', '', $html) ?? $html;
$html = preg_replace('/<\?=.*?\?>/is', '', $html) ?? $html;

  // Hapus script
  $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? $html;

    // Hapus event handler HTML seperti onclick, onload, dll
    $html = preg_replace('/\son\w+\s*=\s*(\"[^\"]*\"|\'[^\']*\')/i', '', $html) ?? $html;

    // Hapus tag berisiko
    $html = preg_replace('#<(iframe|object|embed|form|link|meta|base)\b[^>]*>.*?</\1>#is', '', $html) ?? $html;
      $html = preg_replace('#<(iframe|object|embed|form|link|meta|base)\b[^>]*/?>#i', '', $html) ?? $html;

        return $html;
        }

        protected function getPlaceholders(
        PengajuanSurat $pengajuan,
        User $signer,
        string $signatureHtml,
        string $qrHtml
        ): array {
        $placeholders = [
        '{{logo}}' => $this->getLogoHtml(config('surat.logo_header'), 60),
        '{{logo_header}}' => $this->getLogoHtml(config('surat.logo_header'), 60),
        '{{logo_footer}}' => $this->getLogoHtml(config('surat.logo_footer'), 36),
        '{{nomor_surat}}' => e($pengajuan->nomor_surat ?? '-'),
        '{{no_tiket}}' => e($pengajuan->no_tiket),
        '{{jenis_surat}}' => e($pengajuan->jenisSurat->nama),
        '{{kode_surat}}' => e($pengajuan->jenisSurat->kode),
        '{{tanggal}}' => e(($pengajuan->tanggal_ttd ?? now())->translatedFormat('d F Y')),
        '{{tanggal_ttd}}' => e(optional($pengajuan->tanggal_ttd)->translatedFormat('d F Y') ?? '-'),
        '{{penandatangan_nama}}' => e($signer->name),
        '{{penandatangan_nip}}' => e($signer->nip ?? '-'),

        '{{signature}}' => $signatureHtml,
        '{{tanda_tangan}}' => $signatureHtml,
        '{{qr}}' => $qrHtml,
        '{{qr_code}}' => $qrHtml,
        ];

        foreach ($pengajuan->data_json ?? [] as $key => $value) {
        $placeholders['{{' . $key . '}}'] = $this->formatValue($value);
        }

        return $placeholders;
        }

        protected function formatValue(mixed $value): string
        {
        if (is_bool($value)) {
        return e($value ? 'Ya' : 'Tidak');
        }

        if (is_array($value)) {
        $value = implode(', ', array_filter($value, 'is_scalar'));
        }

        if (! is_scalar($value)) {
        $value = json_encode($value);
        }

        return e((string) $value);
        }

        protected function getSignatureHtml(User $signer, PengajuanSurat $pengajuan): string
        {
        // Jika mode manual, tidak ada signature image
        if ($pengajuan->mode_ttd === 'manual') {
        return '';
        }

        // Mode digital: gunakan signature image
        if (! $signer->signature_path) {
        return '';
        }

        $disk = Storage::disk(config('surat.signature_disk', 'local'));

        if (! $disk->exists($signer->signature_path)) {
        return '';
        }

        try {
        $manager = extension_loaded('imagick')
        ? ImageManager::imagick()
        : ImageManager::gd();

        $image = $manager->read($disk->path($signer->signature_path));
        $image->scale(height: 90);

        $base64 = base64_encode((string) $image->toPng());

        return sprintf(
        '<img src="data:image/png;base64,%s" style="height:90px;" alt="Tanda Tangan">',
        $base64
        );
        } catch (Throwable $e) {
        report($e);

        return '';
        }
        }

        protected function getQrHtml(PengajuanSurat $pengajuan): string
        {
        try {
        $url = route('verifikasi.publik', $pengajuan->qr_token, true);

        $size = (int) config('surat.qr_size', 90);

        $result = Builder::create()
        ->writer(new PngWriter())
        ->data($url)
        ->size($size)
        ->margin(0)
        ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
        ->encoding(new Encoding('UTF-8'))
        ->build();

        $png = $result->getString();

        return sprintf(
        '<img src="data:image/png;base64,%s" style="width:%dpx;height:%dpx;" alt="QR Verifikasi">',
        base64_encode($png),
        $size,
        $size
        );
        } catch (Throwable $e) {
        report($e);

        return '';
        }
        }
        /**
        * Baca file logo dari folder public dan embed sebagai base64 agar aman dirender DomPDF.
        */
        protected function getLogoHtml(?string $relativePath, int $height): string
        {
        if (! $relativePath) {
        return '';
        }

        $fullPath = public_path($relativePath);

        if (! is_file($fullPath)) {
        return '';
        }

        $data = file_get_contents($fullPath);

        if ($data === false) {
        return '';
        }

        $mime = match (strtolower(pathinfo($fullPath, PATHINFO_EXTENSION))) {
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        default => 'image/png',
        };

        return sprintf(
        '<img src="data:%s;base64,%s" style="height:%dpx;" alt="Logo">',
        $mime,
        base64_encode($data),
        $height
        );
        }
        }