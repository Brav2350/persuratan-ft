<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomorSuratCounter extends Model
{
    protected $table = 'nomor_surat_counter';
    protected $fillable = ['jenis_surat_id', 'tahun', 'counter'];

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }
}