@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('booking.index') }}" style="width: 40px; height: 40px; background: #F1F5F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #475569; transition: all 0.2s;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Detail Booking</h1>
            <p>Informasi lengkap reservasi kamar.</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
    {{-- Detail Info --}}
    <div class="widget" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-weight: 800; color: #1E293B;">
                <i class="fa-solid fa-user" style="color: var(--primary); margin-right: 0.5rem;"></i>
                Data Calon Penghuni
            </h3>
            @if($booking->status == 'Pending')
                <span class="badge badge-warning" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Menunggu Konfirmasi</span>
            @elseif($booking->status == 'Dikonfirmasi')
                <span class="badge badge-success" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Dikonfirmasi</span>
            @else
                <span class="badge badge-danger" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Dibatalkan</span>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.3rem;">Nama Lengkap</div>
                <div style="font-weight: 700; color: #1E293B; font-size: 1.1rem;">{{ $booking->nama }}</div>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.3rem;">NIK</div>
                <div style="font-weight: 600; color: #1E293B; font-family: monospace; font-size: 1rem;">{{ $booking->nik }}</div>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.3rem;">No. HP / WhatsApp</div>
                <div style="font-weight: 600; color: #1E293B;">
                    {{ $booking->no_hp }}
                    <a href="https://wa.me/{{ $booking->no_hp }}" target="_blank" style="color: #059669; margin-left: 0.5rem;">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.3rem;">Password Login</div>
                <div style="font-weight: 600; color: #1E293B; font-family: monospace;">{{ $booking->password ?? '••••••' }}</div>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.3rem;">Tanggal Booking</div>
                <div style="font-weight: 600; color: #1E293B;">{{ \Carbon\Carbon::parse($booking->tgl_booking)->translatedFormat('d F Y') }}</div>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.3rem;">Rencana Masuk</div>
                <div style="font-weight: 700; color: var(--primary); font-size: 1.05rem;">{{ \Carbon\Carbon::parse($booking->tgl_rencana_masuk)->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        @if($booking->catatan)
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #F1F5F9;">
            <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.5rem;">Catatan</div>
            <div style="color: #475569; line-height: 1.6; font-size: 0.9rem; background: #F8FAFC; padding: 1rem; border-radius: 10px;">{{ $booking->catatan }}</div>
        </div>
        @endif

        {{-- DP Info Section --}}
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #F1F5F9;">
            <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 1rem;">Informasi Pembayaran</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div style="background: #F8FAFC; border-radius: 12px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.7rem; color: #94A3B8; font-weight: 600; text-transform: uppercase;">Total Sewa</div>
                    <div style="font-size: 1.1rem; font-weight: 800; color: #1E293B; margin-top: 0.3rem;">Rp {{ number_format($booking->kamar->harga_sewa ?? 0, 0, ',', '.') }}</div>
                </div>
                <div style="background: {{ $booking->jumlah_dp > 0 ? '#ECFDF5' : '#FEF2F2' }}; border-radius: 12px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.7rem; color: #94A3B8; font-weight: 600; text-transform: uppercase;">DP / Uang Muka</div>
                    <div style="font-size: 1.1rem; font-weight: 800; color: {{ $booking->jumlah_dp > 0 ? '#059669' : '#DC2626' }}; margin-top: 0.3rem;">Rp {{ number_format($booking->jumlah_dp, 0, ',', '.') }}</div>
                    <span class="badge {{ $booking->status_dp == 'Lunas' ? 'badge-success' : 'badge-warning' }}" style="margin-top: 0.5rem; font-size: 0.65rem;">{{ $booking->status_dp }}</span>
                </div>
                <div style="background: #FFFBEB; border-radius: 12px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.7rem; color: #94A3B8; font-weight: 600; text-transform: uppercase;">Sisa Pelunasan</div>
                    @php $sisaPelunasan = max(0, ($booking->kamar->harga_sewa ?? 0) - $booking->jumlah_dp); @endphp
                    <div style="font-size: 1.1rem; font-weight: 800; color: {{ $sisaPelunasan > 0 ? '#D97706' : '#059669' }}; margin-top: 0.3rem;">Rp {{ number_format($sisaPelunasan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if($booking->status == 'Pending')
        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #F1F5F9;">
            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="cancel-booking-form" style="flex: 1;">
                @csrf
                <button type="submit" style="width: 100%; padding: 0.85rem; background: #FEF2F2; color: #DC2626; border-radius: 12px; border: 1px solid #FECACA; font-weight: 700; cursor: pointer; transition: all 0.2s; font-size: 0.95rem;">
                    <i class="fa-solid fa-xmark"></i> Batalkan Booking
                </button>
            </form>
            <form action="{{ route('booking.confirm', $booking->id) }}" method="POST" class="confirm-booking-form" style="flex: 2;">
                @csrf
                <button type="submit" style="width: 100%; padding: 0.85rem; background: linear-gradient(135deg, #059669, #10B981); color: white; border-radius: 12px; border: none; font-weight: 700; cursor: pointer; transition: all 0.2s; font-size: 0.95rem;">
                    <i class="fa-solid fa-check"></i> Konfirmasi & Daftarkan Penghuni
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Room Card --}}
    <div>
        <div class="widget" style="padding: 2rem;">
            <h3 style="font-weight: 800; color: #1E293B; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-bed" style="color: var(--primary); margin-right: 0.5rem;"></i>
                Informasi Kamar
            </h3>

            <div style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #EFF6FF, #F0F9FF); border-radius: 16px; margin-bottom: 1.5rem;">
                <div style="font-size: 3rem; font-weight: 800; color: var(--primary);">{{ $booking->kamar->nomor_kamar ?? '?' }}</div>
                <div style="font-size: 0.85rem; color: #64748B; font-weight: 600; margin-top: 0.25rem;">Unit Kamar</div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: #64748B;">Harga Sewa</span>
                    <span style="font-weight: 700; color: #059669;">Rp {{ number_format($booking->kamar->harga_sewa ?? 0, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: #64748B;">Status Kamar</span>
                    @if($booking->kamar->status == 'Booking')
                        <span class="badge badge-warning">Booking</span>
                    @elseif($booking->kamar->status == 'Terisi')
                        <span class="badge badge-success">Terisi</span>
                    @else
                        <span class="badge" style="background: #F1F5F9; color: #64748B;">Tersedia</span>
                    @endif
                </div>
                @if($booking->kamar->fasilitas)
                <div style="border-top: 1px solid #F1F5F9; padding-top: 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.5rem;">Fasilitas</div>
                    <div style="font-size: 0.85rem; color: #475569; line-height: 1.6;">{{ $booking->kamar->fasilitas }}</div>
                </div>
                @endif
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ECFDF5, #F0FDF4); border: 1px solid #A7F3D0; border-radius: 16px; padding: 1.25rem; margin-top: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <i class="fa-solid fa-circle-info" style="color: #059669;"></i>
                <span style="font-weight: 700; color: #065F46; font-size: 0.85rem;">Info Booking</span>
            </div>
            <p style="font-size: 0.8rem; color: #047857; line-height: 1.5;">
                Setelah booking dikonfirmasi, calon penghuni akan otomatis terdaftar sebagai penghuni aktif dan menerima pesan WhatsApp selamat datang.
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.confirm-booking-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formEl = this;
            Swal.fire({
                title: 'Konfirmasi Booking?',
                html: `
                    <div style="text-align: left; font-size: 0.9rem; color: #475569; line-height: 1.6;">
                        <p>Tindakan ini akan:</p>
                        <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
                            <li>Mendaftarkan <strong>{{ $booking->nama }}</strong> sebagai penghuni aktif</li>
                            <li>Membuat transaksi pembayaran awal</li>
                            <li>Mengirim pesan WhatsApp selamat datang</li>
                        </ul>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748B',
                confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Konfirmasi',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) formEl.submit();
            });
        });
    });

    document.querySelectorAll('.cancel-booking-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formEl = this;
            Swal.fire({
                title: 'Batalkan Booking?',
                text: 'Kamar akan kembali tersedia untuk booking lain.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#64748B',
                confirmButtonText: '<i class="fa-solid fa-xmark"></i> Ya, Batalkan',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) formEl.submit();
            });
        });
    });
</script>
@endsection
