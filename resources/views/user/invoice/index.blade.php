@extends('layouts.user')

@section('content')
<style>
    /* Hover untuk baris tabel */
    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .data-table tbody tr:hover {
        background-color: #F8FAFC;
    }

    /* Tombol View/Lihat */
    .btn-action-view {
        background: #EFF6FF;
        color: #2563EB;
        border: none;
        padding: 0.5rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    .btn-action-view:hover {
        background: #DBEAFE;
        color: #1D4ED8;
        transform: translateY(-1px);
    }

    /* Tombol Download/Cetak */
    .btn-action-download {
        background: #F8FAFC;
        color: #64748B;
        border: 1px solid #E2E8F0;
        padding: 0.5rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    .btn-action-download:hover {
        background: #F1F5F9;
        color: #475569;
        border-color: #CBD5E1;
        transform: translateY(-1px);
    }
</style>

<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Riwayat Invoice & Tagihan</h1>
            <p>Pantau status pembayaran dan unduh bukti transaksi Anda di sini.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <div class="stat-badge" style="background: white; padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid #E2E8F0; display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 8px; height: 8px; background: #22C55E; border-radius: 50%;"></div>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-main);">{{ $invoices->where('status_validasi', 'Valid')->count() }} Lunas</span>
            </div>
            <div class="stat-badge" style="background: white; padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid #E2E8F0; display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 8px; height: 8px; background: #F59E0B; border-radius: 50%;"></div>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-main);">{{ $invoices->where('status_validasi', 'Pending')->count() }} Menunggu</span>
            </div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header" style="margin-bottom: 1.5rem;">
        <div class="widget-title">Daftar Transaksi</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">{{ $invoices->count() }} Invoice Ditemukan</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #E2E8F0; text-align: left;">
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem;">ID INVOICE</th>
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem;">BULAN TAGIHAN</th>
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem;">TANGGAL BAYAR</th>
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem;">JUMLAH</th>
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem;">METODE</th>
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem;">STATUS</th>
                    <th style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.8rem; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr style="border-bottom: 1px solid #F1F5F9;">
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-weight: 700; color: var(--primary); font-family: 'Courier New', monospace;">#TRX-{{ $inv->id }}</span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 600;">{{ $inv->bulan_tagihan }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ $inv->tgl_bayar ? \Carbon\Carbon::parse($inv->tgl_bayar)->translatedFormat('d M Y') : '-' }}
                        </div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 700; color: var(--text-main);">Rp {{ number_format($inv->jumlah_bayar, 0, ',', '.') }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-size: 0.8rem; background: #F1F5F9; padding: 0.25rem 0.6rem; border-radius: 6px; color: #475569; font-weight: 600;">
                            {{ $inv->metode_bayar ?? 'Transfer' }}
                        </span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        @php
                            $badgeClass = 'badge-success';
                            $statusText = 'Lunas';
                            if($inv->status_validasi == 'Pending') {
                                $badgeClass = 'badge-warning';
                                $statusText = 'Menunggu';
                            } elseif($inv->status_validasi == 'Ditolak') {
                                $badgeClass = 'badge-danger';
                                $statusText = 'Ditolak';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            <button class="btn-action-view" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn-action-download" title="Cetak PDF">
                                <i class="fa-solid fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; opacity: 0.5;">
                            <i class="fa-solid fa-file-invoice-dollar" style="font-size: 3rem; color: #CBD5E1;"></i>
                            <div>
                                <div style="font-weight: 700; color: var(--primary);">Belum Ada Riwayat Transaksi</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">Transaksi Anda akan muncul di sini setelah Anda melakukan pembayaran.</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection