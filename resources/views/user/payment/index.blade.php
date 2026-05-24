@extends('layouts.user')

@section('content')
<div class="dashboard-title">
    <div>
        <h1>Laporan Pembayaran</h1>
        <p>Silakan lengkapi formulir di bawah ini setelah melakukan transfer sewa.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem; align-items: start;">
    
    {{-- Left Column --}}
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Payment Progress Card --}}
        <div class="widget" style="padding: 1.75rem;">
            <div class="widget-header" style="margin-bottom: 1.25rem;">
                <div class="widget-title">Progress Pembayaran</div>
                @if($statusPembayaran === 'Lunas')
                    <span class="badge badge-success">Lunas</span>
                @elseif($statusPembayaran === 'Cicilan')
                    <span class="badge badge-warning">Cicilan</span>
                @else
                    <span class="badge badge-danger">Belum Bayar</span>
                @endif
            </div>

            {{-- Progress Bar --}}
            <div style="height: 14px; background: #E2E8F0; border-radius: 7px; position: relative; margin-bottom: 1.25rem; overflow: hidden;">
                <div style="width: {{ $progressPersen }}%; height: 100%; background: linear-gradient(90deg, {{ $statusPembayaran === 'Lunas' ? '#059669, #10B981' : 'var(--primary), var(--secondary)' }}); border-radius: 7px; transition: width 0.5s;"></div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">Total Tagihan</span>
                    <span style="font-weight: 700; color: #1E293B;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">Sudah Dibayar</span>
                    <span style="font-weight: 700; color: #059669;">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; padding-top: 0.75rem; border-top: 1px dashed #E2E8F0;">
                    <span style="color: var(--text-muted); font-weight: 600;">Sisa Tagihan</span>
                    <span style="font-weight: 800; color: {{ $sisaTagihan > 0 ? '#D97706' : '#059669' }}; font-size: 1rem;">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            <div style="margin-top: 1rem; text-align: center; font-size: 0.75rem; color: #94A3B8;">
                <i class="fa-solid fa-chart-pie" style="margin-right: 0.25rem;"></i>
                {{ $progressPersen }}% terbayar
            </div>
        </div>

        {{-- Bank Info Card --}}
        <div class="widget" style="background: linear-gradient(135deg, var(--primary), #0A9396); color: white; border: none;">
            <div style="margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px;">Rekening Pembayaran</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach($bankAccounts as $bank)
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 32px; background: white; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #1E293B; font-weight: 800; font-size: 0.7rem;">{{ $bank['bank'] }}</div>
                    <div>
                        <div style="font-size: 1.1rem; font-weight: 700; letter-spacing: 1px;">{{ $bank['number'] }}</div>
                        <div style="font-size: 0.75rem; opacity: 0.8;">{{ $bank['holder'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.8rem; line-height: 1.6; opacity: 0.9;">
                <i class="fa-solid fa-circle-info" style="margin-right: 0.5rem;"></i>
                Anda bisa membayar cicilan atau langsung lunas. Transfer sesuai sisa tagihan.
            </div>
        </div>

        <div class="widget">
            <div class="widget-header">
                <div class="widget-title">Rincian Sewa</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Nomor Kamar</span>
                    <span style="font-weight: 700; color: var(--primary);">Unit {{ $kamar->nomor_kamar }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Harga Sewa/bulan</span>
                    <span style="font-weight: 700; color: var(--text-main);">Rp {{ number_format($kamar->harga_sewa, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Periode</span>
                    <span style="font-weight: 700; color: var(--text-main);">{{ $penghuni->tempo_periode ?? 1 }} Bulan</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Bulan Tagihan</span>
                    <span style="font-weight: 700; color: #D97706;">{{ $currentMonth }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="widget">
        @if($isPaid)
            <div style="text-align: center; padding: 4rem 2rem;">
                <div style="width: 80px; height: 80px; background: #ECFDF5; color: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem auto;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h2 style="color: var(--text-main); font-weight: 800; margin-bottom: 0.5rem;">Tagihan Sudah Lunas! 🎉</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">Semua pembayaran untuk periode ini sudah terpenuhi. Terima kasih!</p>
                <a href="{{ route('user.invoice') }}" class="btn-primary" style="display: inline-flex; margin-top: 2rem; text-decoration: none; padding: 0.8rem 2rem;">
                    Lihat Riwayat Invoice
                </a>
            </div>
        @else
            <form action="{{ route('user.payment.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem;">Catatan / Bulan Tagihan</label>
                        <input type="text" name="bulan_tagihan" value="{{ $currentMonth }}" style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #E2E8F0; background: white; color: #1E293B; font-weight: 600; outline: none;" placeholder="Contoh: Mei & Juni 2026">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem;">Jumlah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar" value="{{ $sisaTagihan }}" max="{{ $sisaTagihan }}" min="1" style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #E2E8F0; font-weight: 700; color: #1E293B; background: white; outline: none;" title="Sisa tagihan: Rp {{ number_format($sisaTagihan, 0, ',', '.') }}">
                        <div style="font-size: 0.7rem; color: #94A3B8; margin-top: 0.4rem;">
                            <i class="fa-solid fa-info-circle"></i> Sisa tagihan: <strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>. Boleh bayar cicilan.
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem;">Metode Pembayaran</label>
                        <select name="metode_bayar" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #E2E8F0; background: white; outline: none;">
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer BNI">Transfer BNI</option>
                            <option value="Tunai">Tunai ke Pengelola</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem;">Tanggal Transfer</label>
                        <input type="date" name="tgl_bayar" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem;">Unggah Bukti Transfer</label>
                    <div id="drop-zone" style="border: 2px dashed #CBD5E1; border-radius: 15px; padding: 2.5rem; text-align: center; background: #F8FAFC; transition: 0.3s; cursor: pointer;">
                        <input type="file" name="bukti_transfer" id="file-input" required accept="image/*" style="display: none;">
                        <div id="upload-icon" style="font-size: 2rem; color: #94A3B8; margin-bottom: 1rem;">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div style="font-size: 0.9rem; color: #475569; font-weight: 600;" id="file-label">Klik atau seret gambar di sini</div>
                        <div style="font-size: 0.75rem; color: #94A3B8; margin-top: 0.5rem;">Format JPG, PNG (Max 2MB)</div>
                    </div>
                    <div id="image-preview" style="display: none; margin-top: 1rem; position: relative; width: 100%; max-height: 300px; border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0;">
                        <img id="preview-img" src="" style="width: 100%; height: auto; display: block;">
                        <button type="button" onclick="resetUpload()" style="position: absolute; top: 10px; right: 10px; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 1rem; padding: 1rem; justify-content: center; font-size: 1rem; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Laporan Pembayaran
                </button>
            </form>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileLabel = document.getElementById('file-label');
    const previewContainer = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                    dropZone.style.display = 'none';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--primary)';
            dropZone.style.background = '#EFF6FF';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#CBD5E1';
            dropZone.style.background = '#F8FAFC';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#CBD5E1';
            dropZone.style.background = '#F8FAFC';
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
    }

    function resetUpload() {
        fileInput.value = '';
        previewContainer.style.display = 'none';
        dropZone.style.display = 'block';
    }
</script>
@endsection
