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
            <div class="value">50</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem;">
                <span style="color: var(--success); font-weight: 600;">30 TERISI</span>
                <span style="color: var(--text-muted); margin-left: 0.5rem;">20 TERSEDIA</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #0369A1;">
            <i class="fa-solid fa-user-group"></i>
        </div>
        <div class="stat-info">
            <h3>TOTAL PENGHUNI</h3>
            <div class="value">32</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem; color: var(--success);">
                <i class="fa-solid fa-circle-check"></i> Status Aktif
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF2F2; color: #DC2626;">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <div class="stat-info">
            <h3>VALIDASI TERTUNDA</h3>
            <div class="value" style="color: #DC2626;">5</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem; color: var(--text-muted);">Perlu tindakan segera</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFFBEB; color: #D97706;">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div class="stat-info">
            <h3>PENDAPATAN (BULAN INI)</h3>
            <div class="value">Rp 15.0M</div>
            <div style="font-size: 0.7rem; margin-top: 0.4rem; color: var(--success);">
                <i class="fa-solid fa-arrow-up"></i> +12% vs bulan lalu
            </div>
        </div>
    </div>
</div>

<div class="widgets-container">
    <div class="widget">
        <div class="widget-header">
            <div class="widget-title">Okupansi Kamar</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">60%</div>
        </div>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">Persentase hunian saat ini dibandingkan total kapasitas</p>
        
        <div style="height: 12px; background: #E2E8F0; border-radius: 6px; position: relative; margin-bottom: 2rem;">
            <div style="width: 60%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 6px;"></div>
        </div>

        <div style="display: flex; gap: 4rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Terisi</div>
                <div style="font-size: 1.1rem; font-weight: 700;">30 Unit</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Kosong</div>
                <div style="font-size: 1.1rem; font-weight: 700;">20 Unit</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Booking</div>
                <div style="font-size: 1.1rem; font-weight: 700;">4 Unit</div>
            </div>
        </div>
    </div>

    <div class="widget">
        <div class="widget-header">
            <div class="widget-title">Jatuh Tempo (3 Hari)</div>
            <span class="badge badge-warning">MENDATANG</span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 12px; background: #F8FAFC;">
                <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #2563EB;">102</div>
                <div style="flex-grow: 1;">
                    <div style="font-size: 0.9rem; font-weight: 600;">Andi Wijaya</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">24 Okt 2023</div>
                </div>
                <div style="font-weight: 700; font-size: 0.9rem;">Rp 1.2jt</div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 12px; background: #F8FAFC;">
                <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #2563EB;">205</div>
                <div style="flex-grow: 1;">
                    <div style="font-size: 0.9rem; font-weight: 600;">Siti Aminah</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">25 Okt 2023</div>
                </div>
                <div style="font-weight: 700; font-size: 0.9rem;">Rp 1.5jt</div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 12px; background: #F8FAFC;">
                <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #2563EB;">108</div>
                <div style="flex-grow: 1;">
                    <div style="font-size: 0.9rem; font-weight: 600;">Bambang S.</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">25 Okt 2023</div>
                </div>
                <div style="font-weight: 700; font-size: 0.9rem;">Rp 1.1jt</div>
            </div>
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
                <tr>
                    <td>10:24 WIB</td>
                    <td>
                        <div style="font-weight: 600;">Budi Santoso membayar sewa</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Kamar 301 - Transfer Bank BCA</div>
                    </td>
                    <td><span class="badge badge-success">Lunas</span></td>
                    <td><a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Detail</a></td>
                </tr>
                <tr>
                    <td>09:15 WIB</td>
                    <td>
                        <div style="font-weight: 600;">Kamar 102 tersedia</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Penghuni sebelumnya: Riko Pratama</div>
                    </td>
                    <td><span class="badge badge-info">Kosong</span></td>
                    <td><a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Pasarkan</a></td>
                </tr>
                <tr>
                    <td>Kemarin</td>
                    <td>
                        <div style="font-weight: 600;">Validasi Pembayaran Baru</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Doni Setiawan - Kamar 202</div>
                    </td>
                    <td><span class="badge badge-warning">Menunggu</span></td>
                    <td><a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Validasi</a></td>
                </tr>
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
