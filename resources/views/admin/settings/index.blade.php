@extends('layouts.app')

@section('content')
<style>
    .settings-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
    }
    @media (max-width: 992px) {
        .settings-container {
            grid-template-columns: 1fr;
        }
    }
    .settings-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .settings-header {
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .settings-header i {
        width: 40px;
        height: 40px;
        background: #EFF6FF;
        color: #2563EB;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .settings-header h3 {
        font-weight: 800;
        color: #1E293B;
        font-size: 1.1rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748B;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        outline: none;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: #F8FAFC;
    }
    .form-control:focus {
        background: white;
        border-color: #2563EB;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .btn-save {
        background: #1E293B;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-save:hover {
        background: #0F172A;
        transform: translateY(-1px);
    }
    .profile-info {
        text-align: center;
        padding-bottom: 2rem;
        border-bottom: 1px solid #F1F5F9;
        margin-bottom: 2rem;
    }
    .profile-info img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #EFF6FF;
        margin-bottom: 1rem;
    }
    .profile-info h4 {
        font-weight: 800;
        color: #1E293B;
    }
    .profile-info p {
        color: #94A3B8;
        font-size: 0.85rem;
    }
</style>

<div class="dashboard-title">
    <div>
        <h1>Pengaturan Akun</h1>
        <p>Kelola profil admin dan keamanan akun Anda.</p>
    </div>
</div>

<div class="settings-container">
    <div>
        <div class="settings-card">
            <div class="profile-info">
                <img src="{{ asset('images/admin-profile.jpg') }}" alt="Profile">
                <h4>{{ $admin->nama_admin }}</h4>
                <p>{{ $admin->username }} • Admin Utama</p>
            </div>
            <div style="color: #64748B; font-size: 0.85rem; line-height: 1.6;">
                <p><i class="fa-solid fa-circle-info" style="margin-right: 0.5rem; color: #2563EB;"></i> Pastikan data Anda selalu mutakhir untuk menjaga keamanan akses dashboard.</p>
            </div>
        </div>
    </div>

    <div>
        <form action="{{ route('settings.update') }}" method="POST" class="settings-card">
            @csrf
            @method('PUT')
            
            <div class="settings-header">
                <i class="fa-solid fa-user-gear"></i>
                <h3>Profil Admin</h3>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_admin" value="{{ old('nama_admin', $admin->nama_admin) }}" class="form-control" required>
                    @error('nama_admin') <span style="color: #EF4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username', $admin->username) }}" class="form-control" required>
                    @error('username') <span style="color: #EF4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #F1F5F9; margin: 1rem 0 2rem 0;">

            <div class="settings-header">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Keamanan & Password</h3>
            </div>

            <div class="form-group">
                <label>Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control" placeholder="Isi jika ingin mengubah password">
                @error('current_password') <span style="color: #EF4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Minimal 6 karakter">
                    @error('new_password') <span style="color: #EF4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-check-double"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
