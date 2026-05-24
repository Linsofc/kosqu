<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Penghuni extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'tb_penghuni';

    protected $fillable = [
        'id_kamar',
        'nama',
        'nik',
        'password',
        'no_hp',
        'tgl_masuk',
        'tgl_jatuh_tempo',
        'jumlah_tagihan',
        'tempo_periode',
        'last_reminder_sent_at',
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

    // ===== PAYMENT HELPER METHODS =====

    /**
     * Get the current billing period key (e.g. "2026-06").
     * Based on tgl_jatuh_tempo: the period is from (tgl_jatuh_tempo - tempo_periode months) to tgl_jatuh_tempo.
     */
    public function periodeAktif(): string
    {
        if ($this->tgl_jatuh_tempo) {
            if (Carbon::parse($this->tgl_jatuh_tempo)->startOfDay() <= Carbon::parse($this->tgl_masuk)->startOfDay()) {
                return Carbon::parse($this->tgl_masuk)->format('Y-m');
            }

            $tempo = Carbon::parse($this->tgl_jatuh_tempo);
            $periodeMonths = max(1, (int)($this->tempo_periode ?? 1));
            // Period start = jatuh tempo - tempo months
            $start = $tempo->copy()->subMonths($periodeMonths);
            return $start->format('Y-m');
        }
        return Carbon::now()->format('Y-m');
    }

    /**
     * Total tagihan for the current billing cycle.
     * = jumlah_tagihan (monthly rate) * tempo_periode (number of months)
     */
    public function totalTagihanPeriode(): int
    {
        $hargaPerBulan = (int)($this->jumlah_tagihan ?? $this->kamar->harga_sewa ?? 0);
        $periode = max(1, (int)($this->tempo_periode ?? 1));
        return $hargaPerBulan * $periode;
    }

    /**
     * Total amount already paid (Valid only) for the current billing period.
     */
    public function totalDibayarPeriode(): int
    {
        $periode = $this->periodeAktif();
        return (int) Transaksi::where('id_penghuni', $this->id)
            ->where('status_validasi', 'Valid')
            ->where('periode_tagihan', $periode)
            ->sum('jumlah_bayar');
    }

    /**
     * Remaining balance for the current billing period.
     */
    public function sisaTagihan(): int
    {
        $sisa = $this->totalTagihanPeriode() - $this->totalDibayarPeriode();
        return max(0, $sisa);
    }

    /**
     * Payment status for the current period.
     * Returns: 'Lunas' | 'Cicilan' | 'Belum Bayar'
     */
    public function statusPembayaran(): string
    {
        $dibayar = $this->totalDibayarPeriode();
        $total = $this->totalTagihanPeriode();

        if ($dibayar >= $total) {
            return 'Lunas';
        }
        if ($dibayar > 0) {
            return 'Cicilan';
        }
        
        if ($this->tgl_jatuh_tempo && 
            Carbon::parse($this->tgl_jatuh_tempo)->startOfDay() > Carbon::parse($this->tgl_masuk)->startOfDay() &&
            Carbon::parse($this->tgl_jatuh_tempo)->startOfDay() >= Carbon::now()->startOfDay()
        ) {
            return 'Lunas';
        }

        return 'Belum Bayar';
    }

    /**
     * Payment progress percentage (0-100).
     */
    public function progressPembayaran(): int
    {
        $total = $this->totalTagihanPeriode();
        if ($total <= 0) return 100;
        $dibayar = $this->totalDibayarPeriode();
        return min(100, (int) round($dibayar / $total * 100));
    }
}
