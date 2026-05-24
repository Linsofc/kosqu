@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Detail & Riwayat Kamar {{ $kamar->nomor_kamar }}</h1>
            <p>Informasi detail kamar dan riwayat penghuni yang pernah menyewa.</p>
        </div>
        <a href="{{ route('kamar.index') }}" class="btn-secondary" style="text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

<div class="stats-grid" style="margin-bottom: 2rem; grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #0369A1;">
            <i class="fa-solid fa-bed"></i>
        </div>
        <div class="stat-info">
            <h3>NOMOR KAMAR</h3>
            <div class="value">Unit {{ $kamar->nomor_kamar }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <h3>HARGA SEWA</h3>
            <div class="value" style="color: #059669;">Rp {{ number_format($kamar->harga_sewa, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
            <i class="fa-solid fa-info-circle"></i>
        </div>
        <div class="stat-info">
            <h3>STATUS SAAT INI</h3>
            <div class="value" style="color: #D97706;">{{ $kamar->status }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #F3E8FF; color: #7E22CE;">
            <i class="fa-solid fa-couch"></i>
        </div>
        <div class="stat-info">
            <h3>FASILITAS</h3>
            <div class="value" style="font-size: 1rem; color: var(--text-muted); line-height: 1.2;">{{ $kamar->fasilitas ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header" style="margin-bottom: 2rem;">
        <div class="widget-title">Riwayat Penghuni Kamar</div>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>PENGHUNI</th>
                    <th>TANGGAL MASUK</th>
                    <th>TANGGAL KELUAR</th>
                    <th>STATUS HUNIAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $riwayat)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary);">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $riwayat->penghuni->nama ?? 'Penghuni Tidak Diketahui' }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $riwayat->penghuni->no_hp ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($riwayat->tgl_masuk)->format('d M Y') }}</div>
                    </td>
                    <td>
                        @if($riwayat->tgl_keluar)
                            <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($riwayat->tgl_keluar)->format('d M Y') }}</div>
                        @else
                            <span style="color: var(--text-muted); font-style: italic;">Belum Keluar</span>
                        @endif
                    </td>
                    <td>
                        @if(!$riwayat->tgl_keluar)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary" style="background: #F1F5F9; color: #64748B;">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        <i class="fa-solid fa-history" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                        Belum ada riwayat penghuni untuk kamar ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .btn-secondary {
        background: white;
        border: 1px solid #E2E8F0;
        color: #475569;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-secondary:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: #1E293B;
    }
</style>
@endsection
