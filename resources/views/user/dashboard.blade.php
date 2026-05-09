@extends('layouts.user')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Halo, {{ Auth::guard('penghuni')->user()->nama }}!</h1>
            <p>Berikut adalah ringkasan status hunian Anda bulan ini.</p>
        </div>
    </div>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #EFF6FF; color: #2563EB;">
            <i class="fa-solid fa-bed"></i>
        </div>
        <div class="stat-info">
            <h3>STATUS HUNIAN</h3>
            <div class="value">Kamar 104</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--success); font-weight: 600;">
                <i class="fa-regular fa-circle-check"></i> Tipe Superior
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #FFFBEB; color: #D97706;">
            <i class="fa-regular fa-calendar"></i>
        </div>
        <div class="stat-info">
            <h3>JATUH TEMPO BERIKUTNYA</h3>
            <div class="value">15 Nov 2023</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--text-muted); font-weight: 600;">
                <i class="fa-regular fa-clock"></i> 12 Hari Lagi
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #ECFDF5; color: #10B981;">
            <i class="fa-solid fa-check-double"></i>
        </div>
        <div class="stat-info">
            <h3>PEMBAYARAN BULAN INI</h3>
            <div class="value" style="color: #10B981;">Lunas</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--text-muted); font-weight: 600;">
                Terima kasih! Semua tagihan beres.
            </div>
        </div>
    </div>
</div>

<div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
    
    <div class="widget" style="flex: 2; min-width: 300px; display: flex; flex-direction: column;">
        <div class="widget-header">
            <div class="widget-title">Tagihan Aktif</div>
            <i class="fa-solid fa-receipt" style="color: var(--text-muted);"></i>
        </div>
        
        <div style="flex-grow: 1; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="margin: 0 0 0.2rem 0; font-size: 1.1rem; font-weight: 700; color: var(--primary);">Tagihan Sewa November</h3>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Periode: 15 Nov - 14 Des 2023</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary);">Rp 1.5M</div>
                    <div style="font-size: 0.75rem; font-weight: 600; color: #DC2626;">Belum Dibayar</div>
                </div>
            </div>

            <div style="background: #F8FAFC; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">Sewa Kamar Dasar</span>
                    <span style="font-weight: 600;">Rp 1.300.000</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">Biaya Tambahan (Listrik & Air)</span>
                    <span style="font-weight: 600;">Rp 200.000</span>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: auto;">
                <button class="btn-primary" style="flex: 2; justify-content: center; padding: 0.8rem 1rem;">
                    <i class="fa-solid fa-wallet"></i> Bayar Sekarang
                </button>
                <button style="flex: 1; background: #FFF; border: 1px solid #E2E8F0; color: var(--text-muted); font-weight: 600; border-radius: 8px; cursor: pointer; transition: 0.2s;">
                    Detail
                </button>
            </div>
        </div>
    </div>

    <div class="widget" style="flex: 3; min-width: 350px;">
        <div class="widget-header">
            <div class="widget-title">Pengumuman Kos</div>
            <i class="fa-solid fa-bullhorn" style="color: var(--text-muted);"></i>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; gap: 1rem; background: #F8FAFC; padding: 1rem; border-radius: 12px;">
                <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; items-center; justify-content: center; font-size: 1.1rem; color: #3B82F6; flex-shrink: 0;">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.2rem;">10 Nov 2023</div>
                    <h4 style="margin: 0 0 0.3rem 0; font-size: 0.95rem; font-weight: 700; color: var(--primary);">Pemeliharaan AC Rutin</h4>
                    <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;">
                        Diberitahukan kepada seluruh penghuni bahwa pemeliharaan AC rutin akan dilakukan pada tanggal 12-14 November. Teknisi akan mengunjungi kamar pada jam kerja (09:00 - 16:00). Mohon kerjasamanya.
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; background: #F8FAFC; padding: 1rem; border-radius: 12px;">
                <div style="width: 40px; height: 40px; background: #FFF7ED; border-radius: 8px; display: flex; items-center; justify-content: center; font-size: 1.1rem; color: #F97316; flex-shrink: 0;">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.2rem;">05 Nov 2023</div>
                    <h4 style="margin: 0 0 0.3rem 0; font-size: 0.95rem; font-weight: 700; color: var(--primary);">Peningkatan Jaringan Internet</h4>
                    <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;">
                        Jaringan Wi-Fi telah berhasil ditingkatkan ke bandwidth yang lebih tinggi. Silakan restart router di lorong jika Anda mengalami kendala koneksi.
                    </p>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid #E2E8F0; margin-top: 1.5rem; padding-top: 1rem; text-align: center;">
            <a href="#" style="font-size: 0.8rem; font-weight: 600; color: var(--secondary); text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                Lihat Semua Pengumuman <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
</div>
@endsection