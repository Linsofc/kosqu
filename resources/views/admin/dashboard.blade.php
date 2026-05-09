@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Ringkasan Operasional</h1>
            <p>Selamat datang kembali, berikut pantauan properti Anda hari ini.</p>
        </div>
        <button class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            Tambah Kamar Baru
        </button>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #0369A1;">
            <i class="fa-solid fa-bed"></i>
        </div>
        <div class="stat-info">
            <h3>TOTAL KAMAR</h3>
            <div class="value">{{ $totalKamar }}</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem;">
                <span style="color: var(--success); font-weight: 600;">{{ $kamarTerisi }} TERISI</span>
                <span style="color: var(--text-muted); margin-left: 0.5rem;">{{ $kamarTersedia }} TERSEDIA</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #0369A1;">
            <i class="fa-solid fa-user-group"></i>
        </div>
        <div class="stat-info">
            <h3>TOTAL PENGHUNI</h3>
            <div class="value">{{ $totalPenghuni }}</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem;">
                <span style="color: var(--success); font-weight: 600;">{{ $penghuniAktif }} AKTIF</span>
                <span style="color: var(--danger); font-weight: 600; margin-left: 0.5rem;">{{ $penghuniKeluar }} KELUAR</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF2F2; color: #DC2626;">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <div class="stat-info">
            <h3>VALIDASI TERTUNDA</h3>
            <div class="value" style="color: #DC2626;">{{ $validasiTertunda }}</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem; color: var(--text-muted);">Perlu tindakan segera</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFFBEB; color: #D97706;">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div class="stat-info">
            <h3>PENDAPATAN (BULAN INI)</h3>
            <div class="value">Rp {{ number_format($pendapatanBulanIni / 1_000_000, 1) }}JT</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem; color: {{ $persenPerubahan >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                <i class="fa-solid fa-arrow-{{ $persenPerubahan >= 0 ? 'up' : 'down' }}"></i> {{ $persenPerubahan >= 0 ? '+' : '' }}{{ $persenPerubahan }}% vs bulan lalu
            </div>
        </div>
    </div>
</div>

<div class="widgets-container">
    <div class="widget">
        <div class="widget-header">
            <div class="widget-title">Okupansi Kamar</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">{{ $okupansi }}%</div>
        </div>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">Persentase hunian saat ini dibandingkan total kapasitas</p>
        
        <div style="height: 12px; background: #E2E8F0; border-radius: 6px; position: relative; margin-bottom: 2rem;">
            <div style="width: {{ $okupansi }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 6px;"></div>
        </div>

        <div style="display: flex; gap: 4rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Terisi</div>
                <div style="font-size: 1.1rem; font-weight: 700;">{{ $kamarTerisi }} Unit</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Kosong</div>
                <div style="font-size: 1.1rem; font-weight: 700;">{{ $kamarTersedia }} Unit</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Booking</div>
                <div style="font-size: 1.1rem; font-weight: 700;">{{ $bookingKamar }} Unit</div>
            </div>
        </div>
    </div>

    <div class="widget">
        <div class="widget-header">
            <div class="widget-title">Jatuh Tempo (3 Hari)</div>
            <span class="badge badge-warning">MENDATANG</span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($jatuhTempo as $jt)
            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 12px; background: #F8FAFC;">
                <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #2563EB;">
                    {{ $jt->kamar->nomor_kamar ?? '?' }}
                </div>
                <div style="flex-grow: 1;">
                    <div style="font-size: 0.9rem; font-weight: 600;">{{ $jt->nama }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($jt->tgl_jatuh_tempo)->format('d M Y') }}</div>
                </div>
                <div style="font-weight: 700; font-size: 0.9rem;">Rp {{ number_format($jt->kamar->harga_sewa / 1_000_000, 1) }}jt</div>
            </div>
            @empty
            <div style="text-align: center; padding: 1rem; color: var(--text-muted); font-size: 0.85rem;">
                Tidak ada jatuh tempo dalam 3 hari ke depan.
            </div>
            @endforelse
        </div>
        <a href="#" style="display: block; text-align: center; color: var(--secondary); font-size: 0.8rem; font-weight: 600; margin-top: 1.5rem; text-decoration: none;">Lihat Semua Penagihan</a>
    </div>
</div>

<div class="widgets-container" style="margin-top: 1.5rem;">
    <div class="widget recent-activity">
        <div class="widget-header">
            <div class="widget-title">Aktivitas Terbaru</div>
            <i class="fa-solid fa-rotate-right" style="color: var(--text-muted); cursor: pointer;"></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>WAKTU</th>
                    <th>AKTIVITAS</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aktivitasTerbaru as $at)
                <tr>
                    <td>{{ $at->created_at->translatedFormat('H:i') }} WIB</td>
                    <td>
                        <div style="font-weight: 600;">{{ $at->judul }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            {{ $at->deskripsi }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $at->warna_badge }}">{{ $at->status_badge }}</span>
                    </td>
                    <td><a href="{{ $at->url_aksi ?? '#' }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Detail</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        Belum ada aktivitas transaksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        <div class="help-card">
            <h3>Butuh Bantuan?</h3>
            <p>Hubungi tim support KOSQU jika Anda mengalami kendala dalam validasi transaksi atau manajemen data penghuni.</p>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.1); padding: 0.75rem; border-radius: 12px;">
                    <i class="fa-solid fa-headset" style="font-size: 1.2rem;"></i>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 600;">LIVE SUPPORT</div>
                        <div style="font-size: 0.7rem; opacity: 0.8;">Aktif (08:00 - 20:00)</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.1); padding: 0.75rem; border-radius: 12px;">
                    <i class="fa-solid fa-book" style="font-size: 1.2rem;"></i>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 600;">PANDUAN USER</div>
                        <div style="font-size: 0.7rem; opacity: 0.8;">Baca Dokumentasi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
