<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penghuni extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_penghuni';

    protected $fillable = [
        'id_kamar',
        'nama',
        'nik',
        'password',
        'no_hp',
        'tgl_masuk',
        'tgl_jatuh_tempo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'id_penghuni');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_penghuni');
    }
}
