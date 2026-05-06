<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'tb_kamar';

    protected $fillable = [
        'nomor_kamar',
        'harga_sewa',
        'fasilitas',
        'status',
    ];

    public function penghuni()
    {
        return $this->hasOne(Penghuni::class, 'id_kamar');
    }
}
