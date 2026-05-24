@forelse($transaksis as $t)
    <tr style="border-bottom: 1px solid #F1F5F9;">
        <td style="padding: 1rem; font-weight: 600; color: #64748B;">
            #TRX-{{ $t->id }}
        </td>

        <td style="padding: 1rem;">
            {{ $t->created_at->format('d/m/Y') }}
        </td>

        <td style="padding: 1rem;">
            <div style="font-weight: 700; color: #1E293B;">
                {{ $t->penghuni->nama }}
            </div>
        </td>

        <td style="padding: 1rem;">
            <span
                style="font-weight: 600; color: #2563EB; background: #EFF6FF; padding: 0.2rem 0.5rem; border-radius: 6px;">
                {{ $t->penghuni->kamar->nomor_kamar }}
            </span>
        </td>

        <td style="padding: 1rem;">
            {{ $t->bulan_tagihan }}
        </td>

        <td style="padding: 1rem; font-weight: 700; color: #059669;">
            Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6"
            style="text-align: center; padding: 4rem 2rem; color: #94A3B8;">

            <i class="fa-solid fa-receipt"
                style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.2;"></i>

            <p>Tidak ada transaksi valid ditemukan untuk periode ini.</p>
        </td>
    </tr>
@endforelse
