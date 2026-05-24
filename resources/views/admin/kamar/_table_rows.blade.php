@forelse($kamars as $kamar)
<tr>
    <td>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 40px; height: 40px; background: #F1F5F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary);">
                {{ $kamar->nomor_kamar }}
            </div>
            <span style="font-weight: 600;">Unit {{ $kamar->nomor_kamar }}</span>
        </div>
    </td>
    <td>
        <div style="font-weight: 700; color: var(--primary);">Rp {{ number_format($kamar->harga_sewa, 0, ',', '.') }}</div>
    </td>
    <td>
        <div style="font-size: 0.85rem; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $kamar->fasilitas }}">
            {{ $kamar->fasilitas ?? 'Tidak ada data fasilitas' }}
        </div>
    </td>
    <td>
        @php
            $badgeClass = 'badge-success';
            if($kamar->status == 'Terisi') $badgeClass = 'badge-warning';
        @endphp
        <span class="badge {{ $badgeClass }}">
            {{ $kamar->status }}
        </span>
    </td>
    <td>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('kamar.show', $kamar) }}" class="action-btn" title="Riwayat Kamar" style="background: #E0F2FE; color: #0369A1; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </a>
            <button class="action-btn edit" onclick='openEditModal(@json($kamar))' title="Edit Kamar">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button type="button" class="action-btn delete" title="Hapus Kamar" 
                onclick="confirmDeleteKamar('{{ route('kamar.destroy', $kamar) }}', '{{ $kamar->nomor_kamar }}')"
                {{ $kamar->status == 'Terisi' ? 'disabled style=opacity:0.5;cursor:not-allowed' : '' }}>
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
        <i class="fa-solid fa-bed" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
        Belum ada data kamar yang sesuai.
    </td>
</tr>
@endforelse
