@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1>Data Penghuni</h1>
            <p>Kelola informasi seluruh penghuni Wisma AAM secara efisien.</p>
        </div>
        <a href="{{ route('penghuni.create') }}" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-user-plus"></i>
            Tambah Penghuni
        </a>
    </div>
</div>

{{-- Stats Summary --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #EFF6FF; color: #2563EB;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>TOTAL PENGHUNI</h3>
            <div class="value">{{ $penghunis->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ECFDF5; color: #10B981;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <h3>STATUS AKTIF</h3>
            <div class="value" style="color: #10B981;">{{ $penghunis->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFF7ED; color: #F97316;">
            <i class="fa-solid fa-bed"></i>
        </div>
        <div class="stat-info">
            <h3>KAMAR TERISI</h3>
            <div class="value">{{ $penghunis->count() }}</div>
        </div>
    </div>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div id="success-alert" style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; animation: slideDown 0.3s ease;">
        <div style="width: 32px; height: 32px; background: #D1FAE5; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fa-solid fa-circle-check" style="color: #10B981;"></i>
        </div>
        <span style="font-weight: 600; font-size: 0.9rem; flex-grow: 1;">{{ session('success') }}</span>
        <button onclick="document.getElementById('success-alert').style.display='none'" style="background: none; border: none; color: #065F46; cursor: pointer; opacity: 0.5; font-size: 1.1rem;">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
@endif

{{-- Data Table --}}
<div class="widget">
    <div class="widget-header">
        <div class="widget-title" style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 36px; height: 36px; background: var(--primary-light); color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-address-book"></i>
            </div>
            Daftar Penghuni
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                {{ $penghunis->count() }} data
            </span>
        </div>
    </div>

    <div style="overflow-x: auto; margin: 0 -1.5rem; padding: 0 1.5rem;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kamar</th>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>Kontak</th>
                    <th>Tgl. Masuk</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penghunis as $index => $p)
                <tr>
                    {{-- No --}}
                    <td>
                        <span style="font-weight: 600; color: var(--text-muted); font-size: 0.85rem;">
                            {{ $index + 1 }}
                        </span>
                    </td>

                    {{-- Kamar --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #2563EB; font-size: 0.85rem;">
                                {{ $p->kamar->nomor_kamar ?? '-' }}
                            </div>
                        </div>
                    </td>

                    {{-- Nama --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                {{ strtoupper(substr($p->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main);">{{ $p->nama }}</div>
                                <div style="display: flex; align-items: center; gap: 0.35rem; margin-top: 0.2rem;">
                                    <span style="width: 6px; height: 6px; background: #22C55E; border-radius: 50; display: inline-block;"></span>
                                    <span style="font-size: 0.7rem; color: #22C55E; font-weight: 600;">Aktif</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- NIK --}}
                    <td>
                        <span style="background: #F1F5F9; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8rem; color: #475569; font-weight: 500; font-family: 'Courier New', monospace; letter-spacing: 0.5px;">
                            {{ $p->nik }}
                        </span>
                    </td>

                    {{-- Kontak --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 28px; height: 28px; background: #ECFDF5; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-brands fa-whatsapp" style="color: #22C55E; font-size: 0.85rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem; color: var(--text-main); font-weight: 500;">{{ $p->no_hp }}</span>
                        </div>
                    </td>

                    {{-- Tgl Masuk --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 28px; height: 28px; background: #F0F9FF; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-regular fa-calendar" style="color: #0EA5E9; font-size: 0.75rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                                {{ \Carbon\Carbon::parse($p->tgl_masuk)->format('d M Y') }}
                            </span>
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            <a href="{{ route('penghuni.edit', $p->id) }}" class="action-btn edit" title="Edit Data">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 0.85rem;"></i>
                            </a>
                            <button type="button" onclick="openDeleteModal('{{ route('penghuni.destroy', $p->id) }}', '{{ $p->nama }}')" class="action-btn delete" title="Hapus Data" style="border: none; cursor: pointer;">
                                <i class="fa-solid fa-trash-can" style="font-size: 0.85rem;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem 2rem; text-align: center; border: none;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <div style="width: 80px; height: 80px; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-users-slash" style="font-size: 2rem; color: #CBD5E1;"></i>
                            </div>
                            <div>
                                <p style="font-weight: 600; color: var(--text-main); font-size: 1rem; margin-bottom: 0.25rem;">Belum Ada Data Penghuni</p>
                                <p style="color: var(--text-muted); font-size: 0.85rem;">Mulai dengan menambahkan penghuni baru ke dalam sistem.</p>
                            </div>
                            <a href="{{ route('penghuni.create') }}" class="btn-primary" style="text-decoration: none; margin-top: 0.5rem;">
                                <i class="fa-solid fa-user-plus"></i>
                                Tambah Penghuni Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: all 0.3s ease;">
    <div style="background: #fff; padding: 2.5rem; border-radius: 20px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.15); animation: modalIn 0.3s ease;">
        <div style="width: 64px; height: 64px; background: #FEF2F2; color: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1.5rem auto;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 style="margin: 0 0 0.5rem 0; color: #1E293B; font-size: 1.25rem; font-weight: 700;">Konfirmasi Hapus</h3>
        <p style="color: #64748B; font-size: 0.9rem; margin-bottom: 0.25rem; line-height: 1.6;">Anda akan menghapus data penghuni:</p>
        <p id="deleteTargetName" style="color: var(--primary); font-weight: 700; font-size: 1rem; margin-bottom: 1.5rem;"></p>
        <p style="color: #94A3B8; font-size: 0.8rem; margin-bottom: 2rem;">
            <i class="fa-solid fa-circle-info" style="margin-right: 0.25rem;"></i>
            Tindakan ini tidak dapat dibatalkan.
        </p>
        
        <div style="display: flex; gap: 0.75rem;">
            <button type="button" onclick="closeDeleteModal()" style="flex: 1; padding: 0.85rem 1.5rem; background: #F1F5F9; color: #475569; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: all 0.2s ease;">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="margin: 0; flex: 1; display: flex;">
                @csrf
                @method('DELETE')
                <button type="submit" style="width: 100%; padding: 0.85rem 1.5rem; background: #EF4444; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fa-solid fa-trash-can"></i>
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.9) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Responsive table adjustments */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function openDeleteModal(actionUrl, name) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteTargetName').textContent = name;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.getElementById('deleteForm').action = '';
        document.getElementById('deleteTargetName').textContent = '';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeDeleteModal();
        }
    }

    // Auto-dismiss success alert after 5 seconds
    const alert = document.getElementById('success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'all 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
</script>
@endsection