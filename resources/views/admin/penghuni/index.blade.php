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
            <div class="value" style="color: #10B981;">{{ $penghunis->where('status', 'Aktif')->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF2F2; color: #DC2626;">
            <i class="fa-solid fa-person-walking-arrow-right"></i>
        </div>
        <div class="stat-info">
            <h3>STATUS KELUAR</h3>
            <div class="value" style="color: #DC2626;">{{ $penghunis->where('status', 'Keluar')->count() }}</div>
        </div>
    </div>
</div>



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
                                    <span style="width: 6px; height: 6px; background: {{ $p->status == 'Aktif' ? '#22C55E' : '#EF4444' }}; border-radius: 50%; display: inline-block;"></span>
                                    <span style="font-size: 0.7rem; color: {{ $p->status == 'Aktif' ? '#22C55E' : '#EF4444' }}; font-weight: 600;">{{ $p->status }}</span>
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
        confirmAction({
            title: 'Hapus Penghuni?',
            text: `Anda akan menghapus data penghuni "${name}". Tindakan ini tidak dapat dibatalkan!`,
            confirmText: 'Ya, Hapus Data',
            callback: function() {
                // Create a dynamic form to submit the delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection