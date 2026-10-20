<?php

namespace App\Services;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TemplateSuratService
{
    public function __construct(protected SuratPdfService $pdfService)
    {
    }

    /**
     * Simpan template HTML setelah divalidasi dan disanitasi.
     */
    public function storeTemplate(JenisSurat $jenisSurat, UploadedFile $file): string
    {
        $html = file_get_contents($file->getPathname());

        if ($html === false) {
            throw ValidationException::withMessages([
                'template' => 'File template tidak dapat dibaca.',
            ]);
        }

        $this->validateTemplateContent($html);

        $html = $this->sanitizeTemplateContent($html);

        $disk = Storage::disk(config('surat.template_disk', 'local'));

        if ($jenisSurat->template_path && $disk->exists($jenisSurat->template_path)) {
            $disk->delete($jenisSurat->template_path);
        }

        $path = sprintf(
            'templates/%s-%s.html',
            $jenisSurat->id,
            Str::slug($jenisSurat->nama)
        );

        $disk->put($path, $html);

        return $path;
    }

    /**
     * Hapus file template dari storage.
     */
    public function deleteTemplate(JenisSurat $jenisSurat): void
    {
        if (! $jenisSurat->template_path) {
            return;
        }

        $disk = Storage::disk(config('surat.template_disk', 'local'));

        if ($disk->exists($jenisSurat->template_path)) {
            $disk->delete($jenisSurat->template_path);
        }
    }

    /**
     * Ambil isi template.
     */
    public function content(JenisSurat $jenisSurat): ?string
    {
        if (! $jenisSurat->template_path) {
            return null;
        }

        $disk = Storage::disk(config('surat.template_disk', 'local'));

        if (! $disk->exists($jenisSurat->template_path)) {
            return null;
        }

        return $disk->get($jenisSurat->template_path);
    }

    /**
     * Daftar placeholder yang dikenali sistem.
     */
    public function knownPlaceholders(JenisSurat $jenisSurat): array
    {
        $system = [
            'logo',
'logo_header',
'logo_footer',
            'nomor_surat',
            'no_tiket',
            'jenis_surat',
            'kode_surat',
            'tanggal',
            'tanggal_ttd',
            'penandatangan_nama',
            'penandatangan_nip',
            'signature',
            'tanda_tangan',
            'qr',
            'qr_code',
        ];

        $fields = collect($jenisSurat->fields_json ?? [])
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        return array_values(array_unique(array_merge($system, $fields)));
    }

    /**
     * Ekstrak semua placeholder {{...}} dari template.
     */
    public function extractPlaceholders(string $html): array
    {
        preg_match_all('/{{\s*([a-zA-Z0-9_]+)\s*}}/', $html, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    /**
     * Placeholder yang ada di template tetapi tidak dikenali.
     */
    public function unknownPlaceholders(JenisSurat $jenisSurat, string $html): array
    {
        $extracted = $this->extractPlaceholders($html);
        $known = $this->knownPlaceholders($jenisSurat);

        return array_values(array_diff($extracted, $known));
    }

    /**
     * Preview PDF menggunakan data dummy.
     */
    public function previewPdf(JenisSurat $jenisSurat, User $user): string
    {
        $pengajuan = new PengajuanSurat([
            'no_tiket' => 'PREVIEW-' . strtoupper(Str::random(6)),
            'nomor_surat' => $this->previewNomor($jenisSurat),
            'qr_token' => (string) Str::uuid(),
            'tanggal_ttd' => now(),
            'data_json' => $this->dummyData($jenisSurat),
        ]);

        $pengajuan->setRelation('jenisSurat', $jenisSurat);
        $pengajuan->setRelation('penandatangan', $user);

        return $this->pdfService->render($pengajuan, $user);
    }

    protected function previewNomor(JenisSurat $jenisSurat): string
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ][now()->month];

        return sprintf(
            '001/%s/%s/%s/%s',
            $jenisSurat->kode,
            config('surat.unit_code', 'FT'),
            $romawi,
            now()->year
        );
    }

    protected function dummyData(JenisSurat $jenisSurat): array
    {
        return collect($jenisSurat->fields_json ?? [])
            ->mapWithKeys(function (array $field) {
                $name = $field['name'] ?? '';
                $label = $field['label'] ?? $name;
                $type = $field['type'] ?? 'text';

                if (in_array($name, ['nama', 'nama_lengkap', 'nama_mahasiswa', 'nama_dosen'])) {
                    $value = 'Nama Lengkap Contoh';
                } elseif ($name === 'nim') {
                    $value = '1234567890';
                } else {
                    $value = match ($type) {
                        'date' => now()->format('Y-m-d'),
                        'number' => '1',
                        'email' => 'contoh@ft.ac.id',
                        'tel' => '081234567890',
                        'textarea' => 'Contoh isi ' . strtolower($label),
                        default => 'Contoh ' . strtolower($label),
                    };
                }

                return [$name => $value];
            })
            ->filter(fn ($value, $key) => $key !== '')
            ->all();
    }

    protected function validateTemplateContent(string $html): void
    {
        if (preg_match('/<\?php|<\?=|<\?/i', $html)) {
            throw ValidationException::withMessages([
                'template' => 'Template tidak boleh mengandung kode PHP.',
            ]);
        }

        if (preg_match('#<script\b[^>]*>#i', $html)) {
            throw ValidationException::withMessages([
                'template' => 'Template tidak boleh mengandung tag script.',
            ]);
        }

        if (preg_match('/\son\w+\s*=/i', $html)) {
            throw ValidationException::withMessages([
                'template' => 'Template tidak boleh mengandung event handler HTML.',
            ]);
        }

        if (preg_match('#<(iframe|object|embed|form|link|meta|base)\b#i', $html)) {
            throw ValidationException::withMessages([
                'template' => 'Template mengandung tag yang tidak diizinkan.',
            ]);
        }
    }

    protected function sanitizeTemplateContent(string $html): string
    {
        $html = preg_replace('/<\?php.*?\?>/is', '', $html) ?? $html;
$html = preg_replace('/<\?=.*?\?>/is', '', $html) ?? $html;
  $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? $html;
    $html = preg_replace('/\son\w+\s*=\s*(\"[^\"]*\"|\'[^\']*\')/i', '', $html) ?? $html;

    return $html;
    }
    /**
    * Simpan konten template dari editor online.
    */
    public function saveContent(JenisSurat $jenisSurat, string $html): string
    {
    $this->validateTemplateContent($html);

    $html = $this->sanitizeTemplateContent($html);

    $disk = Storage::disk(config('surat.template_disk', 'local'));

    if (! $jenisSurat->template_path) {
    $jenisSurat->template_path = sprintf(
    'templates/%s-%s.html',
    $jenisSurat->id,
    Str::slug($jenisSurat->nama)
    );
    $jenisSurat->save();
    }

    $disk->put($jenisSurat->template_path, $html);

    return $jenisSurat->template_path;
    }
    }