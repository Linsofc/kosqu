<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tb_booking';

    protected $fillable = [
        'id_kamar',
        'nama',
        'nik',
        'no_hp',
        'password',
        'tgl_booking',
        'tgl_rencana_masuk',
        'catatan',
        'jumlah_dp',
        'status_dp',
        'tempo_periode',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }
}
