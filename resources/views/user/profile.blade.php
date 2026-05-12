@extends('layouts.user')

@section('content')
<style>
    /* Card Container Utama */
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        /* Hapus max-width agar full mengikuti lebar container dashboard Anda */
        width: 100%; 
        box-sizing: border-box;
    }

    /* Bagian Header Profil (Foto & Nama) */
    .profile-header {
        text-align: center;
        padding-bottom: 2.5rem;
        border-bottom: 1px dashed #E2E8F0;
        margin-bottom: 2.5rem;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #E0F7FA;
        padding: 4px;
        background: white;
        margin-bottom: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .profile-name {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main, #1E293B);
        margin-bottom: 0.5rem;
    }
    .profile-role {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #F8FAFC;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--primary, #0088A8);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid #E2E8F0;
    }

    /* List Informasi Penghuni */
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #F1F5F9;
    }
    .info-item:last-child { 
        border-bottom: none; 
    }
    .info-label { 
        color: var(--text-muted, #64748B); 
        font-size: 0.9rem; 
        font-weight: 600;
    }
    .info-value { 
        color: var(--text-main, #1E293B); 
        font-weight: 700; 
        font-size: 0.95rem; 
    }

    /* Form Keamanan */
    .section-title {
        margin-bottom: 1.5rem;
    }
    .section-title h3 {
        font-weight: 800; 
        color: var(--text-main, #1E293B); 
        margin: 0 0 0.25rem 0; 
        font-size: 1.2rem;
    }
    .section-title p {
        margin: 0; 
        color: var(--text-muted, #64748B); 
        font-size: 0.85rem;
    }

    .form-group { 
        margin-bottom: 1.5rem; 
    }
    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-muted, #64748B);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 0.9rem 1.25rem;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        outline: none;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: #F8FAFC;
        color: var(--text-main, #1E293B);
    }
    .form-control:focus {
        background: white;
        border-color: var(--primary, #0088A8);
        box-shadow: 0 0 0 4px rgba(0, 136, 168, 0.1);
    }

    .password-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .password-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    /* Tombol Update */
    .btn-update {
        background: var(--primary, #0088A8);
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 136, 168, 0.2);
    }
    .btn-update:hover {
        background: var(--secondary, #0A9396);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 136, 168, 0.3);
    }
</style>

<div class="dashboard-title">
    <div>
        <h1>Profil Saya</h1>
        <p>Kelola informasi pribadi dan keamanan akun portal Anda.</p>
    </div>
</div>

{{-- Alert Messages --}}
@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #059669; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
        <span style="font-weight: 600; font-size: 0.9rem;">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.25rem;"></i>
        <span style="font-weight: 600; font-size: 0.9rem;">{{ session('error') }}</span>
    </div>
@endif

<div class="profile-card">

    <div class="section-title">
        <h3>Informasi Detail</h3>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Nama Lengkap</span>
            <span class="info-value">{{ $user->nama }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Nomor WhatsApp</span>
            <span class="info-value">
                <i class="fa-brands fa-whatsapp" style="color: #10B981; margin-right: 0.25rem;"></i>
                {{ $user->no_hp }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">NIK</span>
            <span class="info-value">{{ $user->nik }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Tanggal Masuk</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($user->tgl_masuk)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Tipe Kamar</span>
            <span class="info-value">{{ $user->kamar->tipe_kamar ?? 'Standar' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Harga Sewa</span>
            <span class="info-value" style="color: var(--primary);">Rp {{ number_format($user->kamar->harga_sewa ?? 0, 0, ',', '.') }}/bln</span>
        </div>
    </div>

    <form action="{{ route('user.profile.update') }}" method="POST" style="margin-top: 3rem;">
        @csrf
        @method('PUT')

        <div class="section-title">
            <h3>Keamanan Portal</h3>
            <p>Ubah password Anda secara berkala untuk menjaga kerahasiaan data.</p>
        </div>

        <div class="form-group" style="background: #FFFBEB; padding: 1.25rem; border-radius: 12px; border: 1px dashed #FCD34D;">
            <label style="color: #B45309;">Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control" style="background: white;" placeholder="Masukkan password lama untuk verifikasi">
            @error('current_password') <div style="color: #EF4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</div> @enderror
        </div>

        <div class="password-grid">
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="form-control" placeholder="Minimal 6 karakter">
                @error('new_password') <div style="color: #EF4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn-update">
                <i class="fa-solid fa-lock"></i> Perbarui Keamanan
            </button>
        </div>
    </form>
</div>
@endsection