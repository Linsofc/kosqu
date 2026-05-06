<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;

    protected $table = 'tb_penghuni';

    protected $fillable = [
        'id_kamar',
        'nama',
        'nik',
        'no_hp',
        'tgl_masuk',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_penghuni');
    }
}
