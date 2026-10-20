<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PengajuanSurat;

class Lampiran extends Model
{
     use SoftDeletes;
    protected $table = 'lampiran';
    protected $fillable = ['pengajuan_id', 'file_path', 'jenis'];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }
}