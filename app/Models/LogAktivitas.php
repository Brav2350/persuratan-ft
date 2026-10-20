<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $fillable = ['pengajuan_id', 'user_id', 'aksi', 'keterangan'];

    public function pengajuan(): BelongsTo 
    { 
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }
}