@extends('layouts.app')

@section('content')
<style>
    /* Transisi untuk baris tabel */
    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .data-table tbody tr:hover {
        background-color: #F8FAFC;
    }

    /* Tombol Reset Filter */
    .btn-reset {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #F1F5F9;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-reset:hover {
        background: #E2E8F0;
        color: #334155;
    }

    /* Tombol Lihat Bukti */
    .btn-proof {
        padding: 0.4rem 0.75rem;
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #DBEAFE;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-proof:hover {
        background: #DBEAFE;
        border-color: #BFDBFE;
    }

    /* Tombol Terima Pembayaran (Valid) */
    .btn-accept {
        background: #ECFDF5;
        color: #059669;
        border: 1px solid #A7F3D0;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-accept:hover {
        background: #D1FAE5;
        color: #047857;
        transform: translateY(-1px);
    }

    /* Tombol Tolak Pembayaran (Invalid) */
    .btn-reject {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FECACA;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-reject:hover {
        background: #FEE2E2;
        color: #B91C1C;
        transform: translateY(-1px);
    }
</style>

<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1>Transaksi & Validasi</h1>
            <p>Verifikasi bukti pembayaran penghuni dan kelola status tagihan.</p>
        </div>
    </div>
</div>

{{-- Filter Widget --}}
<div class="widget" style="margin-bottom: 2rem;">
    <form action="{{ route('transaksi.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex-grow: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Cari Transaksi</label>
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ID Transaksi atau Nama Penghuni..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem;">
            </div>
        </div>
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Status</label>
            <select name="status" onchange="this.form.submit()" style="padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white;">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                <option value="Valid" {{ request('status') == 'Valid' ? 'selected' : '' }}>Valid (Diterima)</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 10px;">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        <a href="{{ route('transaksi.index') }}" class="btn-reset">
            <i class="fa-solid fa-rotate"></i> Reset
        </a>
    </form>
</div>

{{-- Table Widget --}}
<div class="widget">
    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #E2E8F0;">
                    <th style="padding: 1rem 0.5rem;">ID TRX</th>
                    <th style="padding: 1rem 0.5rem;">PENGHUNI</th>
                    <th style="padding: 1rem 0.5rem;">TAGIHAN</th>
                    <th style="padding: 1rem 0.5rem;">JUMLAH</th>
                    <th style="padding: 1rem 0.5rem;">BUKTI</th>
                    <th style="padding: 1rem 0.5rem;">STATUS</th>
                    <th style="text-align: center; padding: 1rem 0.5rem;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $trx)
                <tr style="border-bottom: 1px solid #F1F5F9;">
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-weight: 700; color: var(--primary); font-family: 'Courier New', monospace;">#TRX-{{ $trx->id }}</span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 600; color: var(--text-main);">{{ $trx->penghuni->nama ?? 'N/A' }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">Kamar {{ $trx->penghuni->kamar->nomor_kamar ?? '-' }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-size: 0.9rem; font-weight: 500;">{{ $trx->bulan_tagihan }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">Dibuat: {{ $trx->created_at->format('d/m/Y') }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 700; color: #10B981;">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        @if($trx->bukti_transfer)
                            <button onclick="viewProof('{{ asset('storage/' . $trx->bukti_transfer) }}', '#TRX-{{ $trx->id }}')" class="btn-proof">
                                <i class="fa-solid fa-image"></i> Lihat Bukti
                            </button>
                        @else
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">No Proof</span>
                        @endif
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        @php
                            $badgeClass = 'badge-warning';
                            if($trx->status_validasi == 'Valid') $badgeClass = 'badge-success';
                            if($trx->status_validasi == 'Ditolak') $badgeClass = 'badge-danger';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $trx->status_validasi }}</span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            @if($trx->status_validasi == 'Pending')
                                <form action="{{ route('transaksi.validasi', $trx->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="Valid">
                                    <button type="submit" class="btn-accept" title="Terima Pembayaran">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('transaksi.validasi', $trx->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="action-btn" style="background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" title="Tolak Pembayaran">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Selesai</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                        <i class="fa-solid fa-receipt" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                        <p style="color: var(--text-muted); font-weight: 500;">Tidak ada data transaksi ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $transaksis->links() }}
    </div>
</div>

{{-- Proof Modal --}}
<div id="proofModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
    <div style="background: white; padding: 1rem; border-radius: 20px; position: relative; max-width: 90%; max-height: 90%;">
        <button onclick="closeProofModal()" style="position: absolute; top: -15px; right: -15px; width: 35px; height: 35px; background: white; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-size: 1.2rem; z-index: 10000; transition: 0.2s;">&times;</button>
        <div style="text-align: center; margin-bottom: 1rem; padding-top: 0.5rem;">
            <h3 id="proofTitle" style="margin: 0; font-size: 1rem; color: var(--primary);">Bukti Transfer</h3>
        </div>
        <img id="proofImage" src="" alt="Bukti Transfer" style="max-width: 100%; max-height: 70vh; border-radius: 12px; display: block; margin: 0 auto; object-fit: contain;">
        <div style="margin-top: 1rem; text-align: center;">
            <a id="downloadProof" href="" download class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.85rem;">
                <i class="fa-solid fa-download"></i> Unduh Bukti
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function viewProof(url, trxId) {
        document.getElementById('proofImage').src = url;
        document.getElementById('proofTitle').innerText = 'Bukti Transfer ' + trxId;
        document.getElementById('downloadProof').href = url;
        document.getElementById('proofModal').style.display = 'flex';
    }

    function closeProofModal() {
        document.getElementById('proofModal').style.display = 'none';
        document.getElementById('proofImage').src = '';
    }

    // Close on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('proofModal');
        if (event.target == modal) {
            closeProofModal();
        }
    }
</script>
@endsection