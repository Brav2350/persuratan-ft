<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PengajuanSurat extends Model
{
     use SoftDeletes;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'no_tiket', 'jenis_surat_id', 'user_id', 'data_json', 
        'status', 'catatan_revisi', 'nomor_surat', 'file_pdf', 
        'qr_token', 'penandatangan_id', 'tanggal_ttd','target_signer_id',
        'mode_ttd',
    ];

    protected $casts = [
        'data_json' => 'array',
        'status' => StatusPengajuan::class,
        'tanggal_ttd' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($pengajuan) {
            if (empty($pengajuan->no_tiket)) {
                // Format Tiket: TGL-TIME-RANDOM
                $pengajuan->no_tiket = strtoupper(Str::random(10));
            }
            if (empty($pengajuan->qr_token)) {
                $pengajuan->qr_token = (string) Str::uuid();
            }
        });
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penandatangan_id');
    }

    public function lampiran(): HasMany
    {
        return $this->hasMany(Lampiran::class, 'pengajuan_id');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'pengajuan_id');
    }

    public function nomorCounter()
    {
        return $this->hasOne(NomorSuratCounter::class, 'jenis_surat_id', 'jenis_surat_id')
            ->where('tahun', date('Y'));
    }
public function targetSigner(): BelongsTo
{
    return $this->belongsTo(User::class, 'target_signer_id');
}
}