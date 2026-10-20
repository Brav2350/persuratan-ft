<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
class JenisSurat extends Model
{
     use SoftDeletes;

    protected $table = 'jenis_surat';
    
    protected $fillable = [
        'nama', 'kode', 'template_path', 'fields_json', 
        'ttd_x', 'ttd_y', 'qr_x', 'qr_y'
    ];

    protected $casts = [
        'fields_json' => 'array',
        'ttd_x' => 'integer',
        'ttd_y' => 'integer',
        'qr_x' => 'integer',
        'qr_y' => 'integer',
    ];

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}