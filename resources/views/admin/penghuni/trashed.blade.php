@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('penghuni.index') }}" style="width: 40px; height: 40px; background: #F1F5F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #475569; transition: all 0.2s;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1>Arsip Penghuni Terhapus</h1>
                <p>Penghuni yang sudah dihapus. Anda bisa mengembalikan atau menghapus permanen.</p>
            </div>
        </div>
    </div>
</div>

{{-- Info Banner --}}
<div style="background: linear-gradient(135deg, #FFFBEB, #FEF3C7); border: 1px solid #FDE68A; border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
    <div style="width: 40px; height: 40px; background: #FEF2F2; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <i class="fa-solid fa-circle-info" style="color: #D97706;"></i>
    </div>
    <div>
        <div style="font-weight: 700; color: #92400E; font-size: 0.9rem;">Tentang Arsip</div>
        <div style="font-size: 0.8rem; color: #B45309; line-height: 1.5;">
            Data di sini menggunakan <strong>soft delete</strong> — penghuni belum benar-benar hilang dari database.
            Klik <strong>Pulihkan</strong> untuk mengembalikan, atau <strong>Hapus Permanen</strong> untuk menghapus selamanya.
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <div class="widget-title" style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 36px; height: 36px; background: #FEF2F2; color: #DC2626; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            Data Terhapus
        </div>
        <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
            {{ $trashedPenghunis->count() }} data
        </span>
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
                    <th>Dihapus Pada</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trashedPenghunis as $index => $p)
                <tr>
                    <td>
                        <span style="font-weight: 600; color: var(--text-muted); font-size: 0.85rem;">
                            {{ $index + 1 }}
                        </span>
                    </td>

                    {{-- Kamar --}}
                    <td>
                        <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #FEF2F2, #FECACA); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #DC2626; font-size: 0.85rem;">
                            {{ $p->kamar->nomor_kamar ?? '-' }}
                        </div>
                    </td>

                    {{-- Nama --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; background: linear-gradient(135deg, #94A3B8, #64748B); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem; flex-shrink: 0; opacity: 0.7;">
                                {{ strtoupper(substr($p->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.9rem; color: #64748B; text-decoration: line-through;">{{ $p->nama }}</div>
                                <div style="font-size: 0.7rem; color: #DC2626; font-weight: 600;">Dihapus</div>
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

                    {{-- Dihapus Pada --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 28px; height: 28px; background: #FEF2F2; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-regular fa-trash-can" style="color: #DC2626; font-size: 0.75rem;"></i>
                            </div>
                            <span style="font-size: 0.85rem; color: #DC2626; font-weight: 500;">
                                {{ \Carbon\Carbon::parse($p->deleted_at)->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            <button type="button" onclick="restoreUser('{{ route('penghuni.restore', $p->id) }}', '{{ $p->nama }}')" class="action-btn" style="background: #ECFDF5; color: #059669; border: none; cursor: pointer;" title="Pulihkan">
                                <i class="fa-solid fa-rotate-left" style="font-size: 0.85rem;"></i>
                            </button>
                            <button type="button" onclick="forceDeleteUser('{{ route('penghuni.force-delete', $p->id) }}', '{{ $p->nama }}')" class="action-btn delete" style="border: none; cursor: pointer;" title="Hapus Permanen">
                                <i class="fa-solid fa-trash-can" style="font-size: 0.85rem;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem 2rem; text-align: center; border: none;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <div style="width: 80px; height: 80px; background: #ECFDF5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-check-circle" style="font-size: 2rem; color: #10B981;"></i>
                            </div>
                            <div>
                                <p style="font-weight: 600; color: var(--text-main); font-size: 1rem; margin-bottom: 0.25rem;">Arsip Kosong 🎉</p>
                                <p style="color: var(--text-muted); font-size: 0.85rem;">Tidak ada penghuni yang terhapus saat ini.</p>
                            </div>
                            <a href="{{ route('penghuni.index') }}" class="btn-primary" style="text-decoration: none; margin-top: 0.5rem;">
                                <i class="fa-solid fa-arrow-left"></i>
                                Kembali ke Data Penghuni
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

@section('scripts')
<script>
    function restoreUser(actionUrl, name) {
        Swal.fire({
            title: 'Pulihkan Penghuni?',
            html: `
                <div style="text-align: left; font-size: 0.9rem; color: #475569; line-height: 1.6;">
                    <p>Anda akan memulihkan data penghuni <strong>"${name}"</strong>.</p>
                    <p style="margin-top: 0.5rem; color: #059669;"><i class="fa-solid fa-circle-check"></i> Data akan kembali muncul di daftar penghuni aktif.</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="fa-solid fa-rotate-left"></i> Ya, Pulihkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function forceDeleteUser(actionUrl, name) {
        Swal.fire({
            title: 'Hapus Permanen?',
            html: `
                <div style="text-align: left; font-size: 0.9rem; color: #475569; line-height: 1.6;">
                    <p>Anda akan menghapus <strong>"${name}"</strong> secara <strong style="color: #DC2626;">PERMANEN</strong>.</p>
                    <p style="margin-top: 0.5rem; color: #DC2626;"><i class="fa-solid fa-triangle-exclamation"></i> Tindakan ini TIDAK BISA dibatalkan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="fa-solid fa-trash-can"></i> Hapus Permanen',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
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
