<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    protected $table = 'tb_aktivitas';

    protected $fillable = [
        'id_penghuni',
        'judul',
        'deskripsi',
        'tipe',
        'status_badge',
        'warna_badge',
        'url_aksi',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni')->withTrashed();
    }
}
