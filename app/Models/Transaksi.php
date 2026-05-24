<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';

    protected $fillable = [
        'id_penghuni',
        'bulan_tagihan',
        'periode_tagihan',
        'jumlah_bayar',
        'bukti_transfer',
        'tgl_bayar',
        'metode_bayar',
        'status_validasi',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni')->withTrashed();
    }
}
