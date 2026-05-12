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

    /* Tombol WhatsApp (Aman/Peringatan) */
    .btn-action-wa {
        background: #ECFDF5;
        color: #059669;
        border: 1px solid #A7F3D0;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-action-wa:hover {
        background: #D1FAE5;
        color: #047857;
        transform: translateY(-1px);
    }

    /* Tombol Edit Tagihan */
    .btn-action-bill {
        background: #F0F9FF;
        color: #0369A1;
        border: 1px solid #BAE6FD;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-action-bill:hover {
        background: #E0F2FE;
        color: #075985;
        transform: translateY(-1px);
    }

    /* Glassmorphism Modal */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-content {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        width: 90%;
        max-width: 450px;
        padding: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-backdrop.active {
        display: flex;
        opacity: 1;
    }
    .modal-backdrop.active .modal-content {
        transform: translateY(0);
    }
    .modal-header {
        margin-bottom: 1.5rem;
    }
    .modal-header h2 {
        font-size: 1.25rem;
        color: #1E293B;
        font-weight: 800;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748B;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        outline: none;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .modal-footer {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn-submit {
        flex-grow: 1;
        background: var(--primary);
        color: white;
        padding: 0.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-submit:hover {
        background: #1D4ED8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .btn-cancel {
        flex-grow: 1;
        background: #F1F5F9;
        color: #475569;
        padding: 0.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
    }
</style>

<div class="dashboard-title">
    <div>
        <h1>Manajemen Jatuh Tempo</h1>
        <p>Pantau masa sewa penghuni dan ingatkan pembayaran yang mendekati batas waktu.</p>
    </div>
</div>

{{-- Filter Widget --}}
<div class="widget" style="margin-bottom: 2rem;">
    <form action="{{ route('tempo.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex-grow: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Cari Penghuni</label>
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama penghuni..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem;">
            </div>
        </div>
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Status Tempo</label>
            <select name="status_tempo" onchange="this.form.submit()" style="padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white;">
                <option value="">Semua Status</option>
                <option value="Terlambat" {{ request('status_tempo') == 'Terlambat' ? 'selected' : '' }}>Terlambat 🔴</option>
                <option value="Mendatang" {{ request('status_tempo') == 'Mendatang' ? 'selected' : '' }}>Mendatang (7 Hari) 🟡</option>
                <option value="Aman" {{ request('status_tempo') == 'Aman' ? 'selected' : '' }}>Aman 🟢</option>
            </select>
        </div>
        <a href="{{ route('tempo.index') }}" class="btn-reset">
            <i class="fa-solid fa-rotate"></i> Reset
        </a>
    </form>
</div>

<div class="widget">
    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #E2E8F0;">
                    <th style="padding: 1rem 0.5rem;">UNIT</th>
                    <th style="padding: 1rem 0.5rem;">PENGHUNI</th>
                    <th style="padding: 1rem 0.5rem;">JATUH TEMPO</th>
                    <th style="padding: 1rem 0.5rem;">SISA HARI</th>
                    <th style="padding: 1rem 0.5rem;">TAGIHAN</th>
                    <th style="padding: 1rem 0.5rem;">STATUS</th>
                    <th style="text-align: center; padding: 1rem 0.5rem;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penghunis as $p)
                @php
                    $tempo = \Carbon\Carbon::parse($p->tgl_jatuh_tempo);
                    $daysLeft = (int) $now->diffInDays($tempo, false);
                    
                    $badgeClass = 'badge-success';
                    $statusText = 'Aman';
                    
                    if($daysLeft < 0) {
                        $badgeClass = 'badge-danger';
                        $statusText = 'Terlambat';
                    } elseif($daysLeft <= 7) {
                        $badgeClass = 'badge-warning';
                        $statusText = 'Mendatang';
                    }
                @endphp
                <tr style="border-bottom: 1px solid #F1F5F9;">
                    <td style="padding: 1rem 0.5rem;">
                        <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #2563EB;">
                            {{ $p->kamar->nomor_kamar ?? '?' }}
                        </div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 700; color: var(--text-main);">{{ $p->nama }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $p->no_hp }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 600;">{{ $tempo->translatedFormat('d F Y') }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        @if($daysLeft < 0)
                            <span style="color: #DC2626; font-weight: 700;">Lewat {{ abs($daysLeft) }} Hari</span>
                        @elseif($daysLeft == 0)
                            <span style="color: #D97706; font-weight: 700;">Hari Ini</span>
                        @else
                            <span style="font-weight: 600; color: var(--text-main);">{{ $daysLeft }} Hari Lagi</span>
                        @endif
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 700; color: var(--primary);">Rp {{ number_format($p->jumlah_tagihan ?? $p->kamar->harga_sewa, 0, ',', '.') }}</div>
                        @if($p->jumlah_tagihan)
                            <div style="font-size: 0.65rem; color: #059669; font-weight: 600; text-transform: uppercase;">Custom Rate</div>
                        @endif
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            <a href="https://wa.me/{{ $p->no_hp }}?text=Halo%20{{ $p->nama }},%20kami%20dari%20pengelola%20KOSQU%20mengingatkan%20bahwa%20masa%20sewa%20Anda%20unit%20{{ $p->kamar->nomor_kamar }}%20akan%20jatuh%20tempo%20pada%20{{ $tempo->format('d/m/Y') }}.%20Terima%20kasih." 
                               target="_blank" class="btn-action-wa" title="Kirim WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <button type="button" class="btn-action-bill" title="Atur Tagihan" 
                                    onclick="openBillModal('{{ $p->id }}', '{{ $p->nama }}', '{{ $p->tgl_jatuh_tempo }}', '{{ $p->jumlah_tagihan ?? $p->kamar->harga_sewa }}')">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </button>
                            {{-- <a href="{{ route('penghuni.edit', $p->id) }}" class="btn-action-edit" title="Edit Data">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a> --}}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                        <i class="fa-solid fa-calendar-day" style="font-size: 3rem; color: #CBD5E1; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
                        <p style="color: var(--text-muted); font-weight: 600;">Tidak ada data jatuh tempo ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

{{-- Modal Quick Edit Tagihan --}}
<div id="billModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Atur Tagihan & Tempo</h2>
            <p style="font-size: 0.85rem; color: #64748B;" id="modalPenghuniNama"></p>
        </div>
        <form id="billForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tgl_jatuh_tempo" id="modalTglTempo" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jumlah Tagihan (Rp)</label>
                <input type="number" name="jumlah_tagihan" id="modalJumlahTagihan" class="form-control" placeholder="Biarkan kosong untuk harga standar kamar" min="0">
                <p style="font-size: 0.7rem; color: #94A3B8; margin-top: 0.4rem;">Kosongkan jika ingin menggunakan harga standar kamar.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeBillModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBillModal(id, nama, tgl, jumlah) {
        const modal = document.getElementById('billModal');
        const form = document.getElementById('billForm');
        
        document.getElementById('modalPenghuniNama').innerText = 'Penghuni: ' + nama;
        document.getElementById('modalTglTempo').value = tgl;
        document.getElementById('modalJumlahTagihan').value = jumlah;
        
        // Update form action
        form.action = `/tempo/${id}/update-tagihan`;
        
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeBillModal() {
        const modal = document.getElementById('billModal');
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    // Close on backdrop click
    window.onclick = function(event) {
        const modal = document.getElementById('billModal');
        if (event.target == modal) {
            closeBillModal();
        }
    }
</script>