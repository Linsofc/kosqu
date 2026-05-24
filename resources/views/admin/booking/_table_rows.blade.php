@forelse($bookings as $b)
<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
    <td style="padding: 1rem 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #2563EB;">
                {{ $b->kamar->nomor_kamar ?? '?' }}
            </div>
            <div>
                <div style="font-weight: 700; color: var(--primary);">Unit {{ $b->kamar->nomor_kamar ?? '-' }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Rp {{ number_format($b->kamar->harga_sewa ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </td>
    <td style="padding: 1rem 0.75rem;">
        <div style="font-weight: 700; color: #1E293B;">{{ $b->nama }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $b->no_hp }}</div>
    </td>
    <td style="padding: 1rem 0.75rem;">
        <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($b->tgl_booking)->translatedFormat('d M Y') }}</div>
    </td>
    <td style="padding: 1rem 0.75rem;">
        <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($b->tgl_rencana_masuk)->translatedFormat('d M Y') }}</div>
    </td>
    <td style="padding: 1rem 0.75rem;">
        @if($b->status == 'Pending')
            <span class="badge badge-warning">Pending</span>
        @elseif($b->status == 'Dikonfirmasi')
            <span class="badge badge-success">Dikonfirmasi</span>
        @else
            <span class="badge badge-danger">Dibatalkan</span>
        @endif
    </td>
    <td style="padding: 1rem 0.75rem;">
        <div style="display: flex; justify-content: center; gap: 0.5rem;">
            <a href="{{ route('booking.show', $b->id) }}" style="width: 32px; height: 32px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563EB; text-decoration: none; transition: all 0.2s;" title="Detail">
                <i class="fa-solid fa-eye" style="font-size: 0.8rem;"></i>
            </a>

            @if($b->status == 'Pending')
            <form action="{{ route('booking.confirm', $b->id) }}" method="POST" class="confirm-booking-form" style="display: inline;">
                @csrf
                <button type="submit" style="width: 32px; height: 32px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #059669; cursor: pointer; transition: all 0.2s;" title="Konfirmasi">
                    <i class="fa-solid fa-check" style="font-size: 0.8rem;"></i>
                </button>
            </form>
            <form action="{{ route('booking.cancel', $b->id) }}" method="POST" class="cancel-booking-form" style="display: inline;">
                @csrf
                <button type="submit" style="width: 32px; height: 32px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #DC2626; cursor: pointer; transition: all 0.2s;" title="Batalkan">
                    <i class="fa-solid fa-xmark" style="font-size: 0.8rem;"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" style="text-align: center; padding: 4rem 2rem;">
        <i class="fa-solid fa-calendar-plus" style="font-size: 3rem; color: #CBD5E1; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
        <p style="color: var(--text-muted); font-weight: 600;">Belum ada booking.</p>
        <p style="color: #CBD5E1; font-size: 0.85rem;">Buat booking pertama atau ubah kata kunci pencarian.</p>
    </td>
</tr>
@endforelse
