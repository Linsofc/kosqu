@forelse($penghunis as $index => $p)
<tr>
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
                <p style="font-weight: 600; color: var(--text-main); font-size: 1rem; margin-bottom: 0.25rem;">Tidak Ada Data Ditemukan</p>
                <p style="color: var(--text-muted); font-size: 0.85rem;">Coba ubah kata kunci atau filter pencarian.</p>
            </div>
        </div>
    </td>
</tr>
@endforelse
