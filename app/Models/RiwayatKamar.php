<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKamar extends Model
{
    use HasFactory;

    protected $table = 'tb_riwayat_kamar';

    protected $fillable = [
        'id_penghuni',
        'id_kamar',
        'tgl_masuk',
        'tgl_keluar',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni')->withTrashed();
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }
}
