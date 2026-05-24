@extends('layouts.app')

@section('content')
<style>
    /* Mengatur Palet Warna Dasar */
    :root {
        --primary: #2563EB;
        --primary-hover: #1D4ED8;
        --primary-light: #EFF6FF;
        --dark-slate: #0F172A;
        --slate: #1E293B;
        --text-muted: #64748B;
        --border-light: #E2E8F0;
        --bg-body: #F8FAFC;
        --success: #10B981;
        --danger: #EF4444;
        --warning: #F59E0B;
    }

    .dashboard-title {
        margin-bottom: 2rem;
    }
    .dashboard-title h1 {
        font-weight: 800;
        color: var(--dark-slate);
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
    }
    .dashboard-title p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .settings-container {
        display: grid;
        grid-template-columns: 280px 1fr;
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
        padding: 2.5rem;
        border: 1px solid var(--border-light);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        transition: box-shadow 0.3s ease;
    }
    .settings-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
    }

    .settings-header {
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .settings-header i {
        width: 48px;
        height: 48px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.1);
    }
    .settings-header h3 {
        font-weight: 800;
        color: var(--slate);
        font-size: 1.2rem;
        margin: 0;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 0.6rem;
        letter-spacing: 0.025em;
    }
    .form-control {
        width: 100%;
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        outline: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--bg-body);
        color: var(--dark-slate);
    }
    .form-control:hover {
        border-color: #CBD5E1;
    }
    .form-control:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }
    textarea.form-control {
        resize: vertical;
        font-family: inherit;
        min-height: 100px;
        line-height: 1.5;
    }

    /* Buttons */
    .btn-save {
        background: linear-gradient(135deg, var(--slate), var(--dark-slate));
        color: white;
        padding: 0.85rem 2.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3);
    }

    /* Sidebar Profile */
    .profile-info {
        text-align: center;
        padding-bottom: 2rem;
        border-bottom: 1px dashed var(--border-light);
        margin-bottom: 2rem;
    }
    .profile-info img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        margin-bottom: 1.25rem;
        transition: transform 0.3s ease;
    }
    .profile-info img:hover {
        transform: scale(1.05);
    }
    .profile-info h4 {
        font-weight: 800;
        color: var(--dark-slate);
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }
    .profile-info p {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Tab Navigation */
    .settings-tabs {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .tab-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: left;
        position: relative;
        overflow: hidden;
    }
    .tab-btn:hover {
        background: var(--bg-body);
        color: var(--slate);
        transform: translateX(4px);
    }
    .tab-btn.active {
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 700;
    }
    .tab-btn.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary);
        border-radius: 0 4px 4px 0;
    }
    .tab-btn i {
        width: 24px;
        text-align: center;
        font-size: 1.1rem;
    }

    /* Tab Content Animation */
    .tab-content {
        display: none;
        opacity: 0;
    }
    .tab-content.active {
        display: block;
        animation: slideFadeIn 0.4s ease forwards;
    }
    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Toggle Switch */
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .toggle-switch {
        position: relative;
        width: 52px;
        height: 28px;
        appearance: none;
        background: #CBD5E1;
        border-radius: 14px;
        cursor: pointer;
        transition: background 0.3s ease;
        outline: none;
        border: none;
        flex-shrink: 0;
    }
    .toggle-switch:checked {
        background: var(--success);
    }
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-switch:checked::before {
        transform: translateX(24px);
    }

    /* Variable Tags */
    .variable-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    .variable-tag {
        background: white;
        color: var(--primary);
        padding: 0.35rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--primary-light);
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .variable-tag:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    /* Test WA Section */
    .test-wa-box {
        background: linear-gradient(to right, #ECFDF5, #F0FDF4);
        border: 1px solid #A7F3D0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 2rem;
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.05);
    }
    .test-wa-box h4 {
        font-size: 0.95rem;
        font-weight: 800;
        color: #065F46;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-test-wa {
        background: var(--success);
        color: white;
        padding: 0.85rem 1.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
    }
    .btn-test-wa:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 10px -2px rgba(16, 185, 129, 0.3);
    }

    /* Hint text */
    .hint-text {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        display: block;
    }

    /* Status indicator */
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(255,255,255,0.8);
    }
    .status-dot.active { 
        background: var(--success); 
        animation: pulse-green 2s infinite;
    }
    .status-dot.inactive { 
        background: var(--danger); 
    }

    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Info Cards Tempo */
    .info-card-grid {
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 1.25rem; 
        margin-top: 2rem;
    }
    .info-card {
        border-radius: 16px; 
        padding: 1.5rem; 
        text-align: center;
        transition: transform 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-4px);
    }
</style>

<div class="dashboard-title">
    <div>
        <h1>Pengaturan Sistem</h1>
        <p>Kelola profil administrator, konfigurasi tempo pembayaran, dan integrasi WhatsApp.</p>
    </div>
</div>

<div class="settings-container">
    {{-- Left Sidebar --}}
    <div>
        <div class="settings-card" style="padding: 2rem 1.5rem;">
            <div class="profile-info">
                <img src="{{ asset('images/admin-profile.jpg') }}" alt="Profile Admin">
                <h4>{{ $admin->nama_admin }}</h4>
                <p>{{ $admin->username }} &bull; Admin Utama</p>
            </div>

            <nav class="settings-tabs">
                <button class="tab-btn active" onclick="switchTab('profil')" id="tab-btn-profil">
                    <i class="fa-solid fa-user-gear"></i>
                    Profil Admin
                </button>
                <button class="tab-btn" onclick="switchTab('tempo')" id="tab-btn-tempo">
                    <i class="fa-solid fa-calendar-check"></i>
                    Tempo Tagihan
                </button>
                <button class="tab-btn" onclick="switchTab('whatsapp')" id="tab-btn-whatsapp">
                    <i class="fa-brands fa-whatsapp"></i>
                    WhatsApp Fonnte
                </button>
                <button class="tab-btn" onclick="switchTab('walog')" id="tab-btn-walog">
                    <i class="fa-solid fa-list-check"></i>
                    Log WhatsApp
                </button>
            </nav>
        </div>
    </div>

    {{-- Right Content --}}
    <div>
        {{-- ====== TAB 1: PROFIL ADMIN ====== --}}
        <div class="tab-content active" id="tab-profil">
            <form action="{{ route('settings.update') }}" method="POST" class="settings-card">
                @csrf
                @method('PUT')
                
                <div class="settings-header">
                    <i class="fa-solid fa-user-gear"></i>
                    <div>
                        <h3>Profil Administrator</h3>
                        <span class="hint-text" style="margin-top: 0;">Perbarui informasi pribadi dan kredensial login.</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_admin" value="{{ old('nama_admin', $admin->nama_admin) }}" class="form-control" required>
                        @error('nama_admin') <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.4rem; display:block;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="{{ old('username', $admin->username) }}" class="form-control" required>
                        @error('username') <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.4rem; display:block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr style="border: none; border-top: 1px dashed var(--border-light); margin: 2rem 0;">

                <div class="settings-header">
                    <i class="fa-solid fa-shield-halved" style="background: #FEE2E2; color: #DC2626; box-shadow: inset 0 0 0 1px rgba(220,38,38,0.1);"></i>
                    <div>
                        <h3>Keamanan & Password</h3>
                        <span class="hint-text" style="margin-top: 0;">Biarkan kosong jika tidak ingin mengubah password.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini untuk verifikasi">
                    @error('current_password') <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.4rem; display:block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter">
                        @error('new_password') <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.4rem; display:block;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        {{-- ====== TAB 2: TEMPO PEMBAYARAN ====== --}}
        <div class="tab-content" id="tab-tempo">
            <form action="{{ route('settings.update-tempo') }}" method="POST" class="settings-card">
                @csrf
                @method('PUT')

                <div class="settings-header">
                    <i class="fa-solid fa-calendar-check" style="background: #FEF3C7; color: #D97706; box-shadow: inset 0 0 0 1px rgba(217,119,6,0.1);"></i>
                    <div>
                        <h3>Tempo Pembayaran Default</h3>
                        <span class="hint-text" style="margin-top: 0;">Atur periode penagihan standar saat penghuni baru didaftarkan.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Periode Tempo Tagihan</label>
                    <select name="tempo_periode" class="form-control" style="cursor: pointer;">
                        @foreach([1 => '1 Bulan', 2 => '2 Bulan', 3 => '3 Bulan', 4 => '4 Bulan', 5 => '5 Bulan', 6 => '6 Bulan (1 Semester)', 7 => '7 Bulan', 8 => '8 Bulan', 9 => '9 Bulan', 10 => '10 Bulan', 11 => '11 Bulan', 12 => '12 Bulan (1 Tahun)'] as $val => $label)
                            <option value="{{ $val }}" {{ ($settings['tempo_periode'] ?? 1) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="hint-text"><i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> Setiap penghuni tetap bisa disesuaikan temponya secara individu pada halaman Data Penghuni.</span>
                </div>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="apply_to_all" value="1" style="width: 16px; height: 16px;">
                        Terapkan ke seluruh penghuni aktif saat ini
                    </label>
                    <span class="hint-text"><i class="fa-solid fa-triangle-exclamation" style="color: var(--warning);"></i> Jika dicentang, tempo tagihan seluruh penghuni aktif akan ikut berubah (overwrite). Biarkan kosong jika pengaturan ini hanya untuk penghuni baru.</span>
                </div>

                {{-- Visual Info Cards --}}
                <div class="info-card-grid">
                    <div class="info-card" style="background: #F0F9FF; border: 1px solid #BAE6FD;">
                        <div style="font-size: 2.2rem; font-weight: 800; color: #0284C7; line-height: 1;">1</div>
                        <div style="font-size: 0.85rem; color: #0369A1; font-weight: 700; margin-top: 0.25rem;">Bulan</div>
                        <div style="font-size: 0.75rem; color: #38BDF8; margin-top: 0.5rem; background: white; padding: 0.25rem; border-radius: 6px;">Opsi Bulanan</div>
                    </div>
                    <div class="info-card" style="background: #FEF3C7; border: 1px solid #FDE68A;">
                        <div style="font-size: 2.2rem; font-weight: 800; color: #D97706; line-height: 1;">6</div>
                        <div style="font-size: 0.85rem; color: #B45309; font-weight: 700; margin-top: 0.25rem;">Bulan</div>
                        <div style="font-size: 0.75rem; color: #F59E0B; margin-top: 0.5rem; background: white; padding: 0.25rem; border-radius: 6px;">Opsi Semesteran</div>
                    </div>
                    <div class="info-card" style="background: #ECFDF5; border: 1px solid #A7F3D0;">
                        <div style="font-size: 2.2rem; font-weight: 800; color: #059669; line-height: 1;">12</div>
                        <div style="font-size: 0.85rem; color: #047857; font-weight: 700; margin-top: 0.25rem;">Bulan</div>
                        <div style="font-size: 0.75rem; color: #10B981; margin-top: 0.5rem; background: white; padding: 0.25rem; border-radius: 6px;">Opsi Tahunan</div>
                    </div>
                </div>

                <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Tempo
                    </button>
                </div>
            </form>
        </div>

        {{-- ====== TAB 3: WHATSAPP FONNTE ====== --}}
        <div class="tab-content" id="tab-whatsapp">
            <form action="{{ route('settings.update-whatsapp') }}" method="POST" class="settings-card">
                @csrf
                @method('PUT')

                <div class="settings-header">
                    <i class="fa-brands fa-whatsapp" style="background: #ECFDF5; color: #059669; box-shadow: inset 0 0 0 1px rgba(5,150,105,0.1);"></i>
                    <div style="flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3>Integrasi WhatsApp</h3>
                            <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--bg-body); padding: 0.35rem 0.85rem; border-radius: 20px; border: 1px solid var(--border-light);">
                                <span class="status-dot {{ !empty($settings['fonnte_token']) ? 'active' : 'inactive' }}"></span>
                                <span style="font-size: 0.75rem; font-weight: 700; color: {{ !empty($settings['fonnte_token']) ? 'var(--success)' : 'var(--danger)' }}; text-transform: uppercase; letter-spacing: 0.5px;">
                                    {{ !empty($settings['fonnte_token']) ? 'Terhubung' : 'Terputus' }}
                                </span>
                            </div>
                        </div>
                        <span class="hint-text" style="margin-top: 0.25rem;">Kirim notifikasi tagihan otomatis melalui layanan Fonnte API.</span>
                    </div>
                </div>

                {{-- Token --}}
                <div class="form-group">
                    <label>API Token Fonnte</label>
                    <div style="position: relative;">
                        <input type="password" name="fonnte_token" id="fonnteTokenInput" 
                               value="{{ old('fonnte_token', $settings['fonnte_token']) }}" 
                               class="form-control" placeholder="Masukkan token API..."
                               style="padding-right: 3.5rem; font-family: monospace; letter-spacing: 1px;">
                        <button type="button" onclick="toggleTokenVisibility()" 
                                style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: white; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.1rem; padding: 0.2rem;">
                            <i class="fa-solid fa-eye" id="tokenToggleIcon"></i>
                        </button>
                    </div>
                    @error('fonnte_token') <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.4rem; display:block;">{{ $message }}</span> @enderror
                    <span class="hint-text">Dapatkan token dari <a href="https://fonnte.com" target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: none;">dashboard fonnte.com</a> &rarr; Device &rarr; API Token</span>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label>Nomor WA Admin (Pusat Bantuan)</label>
                    <input type="text" name="admin_phone" 
                           value="{{ old('admin_phone', $settings['admin_phone'] ?? '') }}" 
                           class="form-control" placeholder="Contoh: 6281234567890">
                    @error('admin_phone') <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.4rem; display:block;">{{ $message }}</span> @enderror
                    <span class="hint-text">Nomor ini akan dihubungi oleh penghuni saat mereka mengklik tombol Bantuan di Portal mereka. Pastikan gunakan format 62.</span>
                </div>

                <hr style="border: none; border-top: 1px dashed var(--border-light); margin: 2rem 0;">

                {{-- Reminder Config --}}
                <div class="settings-header" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-bell-concierge" style="background: #F3E8FF; color: #9333EA; box-shadow: inset 0 0 0 1px rgba(147,51,234,0.1);"></i>
                    <div>
                        <h3>Aturan Pengiriman</h3>
                        <span class="hint-text" style="margin-top: 0;">Atur jadwal pengingat tagihan otomatis ke penghuni.</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; background: var(--bg-body); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border-light);">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--slate); margin-bottom: 0.75rem;">Status Fitur</label>
                        <div class="toggle-container">
                            <input type="checkbox" name="wa_reminder_enabled" class="toggle-switch" 
                                   {{ ($settings['wa_reminder_enabled'] ?? 'true') === 'true' ? 'checked' : '' }}>
                            <span style="font-size: 0.9rem; font-weight: 600; color: var(--dark-slate);">Aktifkan Pengingat Otomatis</span>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--slate); margin-bottom: 0.75rem;">Waktu Pengiriman</label>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.9rem; font-weight: 600; color: var(--dark-slate);">Kirim H -</span>
                            <input type="number" name="wa_reminder_days_before" 
                                   value="{{ old('wa_reminder_days_before', $settings['wa_reminder_days_before'] ?? 7) }}" 
                                   class="form-control" min="1" max="30" style="width: 80px; text-align: center; padding: 0.5rem;">
                            <span style="font-size: 0.9rem; color: var(--text-muted);">hari sebelum tempo</span>
                        </div>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px dashed var(--border-light); margin: 2rem 0;">

                {{-- Templates --}}
                <div class="settings-header" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-message" style="background: #E0F2FE; color: #0284C7; box-shadow: inset 0 0 0 1px rgba(2,132,199,0.1);"></i>
                    <div>
                        <h3>Personalisasi Pesan</h3>
                        <span class="hint-text" style="margin-top: 0;">Klik tag biru di bawah form untuk memasukkan variabel dinamis.</span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label>Template Pengingat <span style="color: var(--warning);">(Sebelum Jatuh Tempo)</span></label>
                    <textarea name="wa_reminder_template" class="form-control" rows="4">{{ old('wa_reminder_template', $settings['wa_reminder_template']) }}</textarea>
                    <div class="variable-tags">
                        <span class="variable-tag" onclick="insertVariable('wa_reminder_template', '{nama}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> nama</span>
                        <span class="variable-tag" onclick="insertVariable('wa_reminder_template', '{kamar}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> kamar</span>
                        <span class="variable-tag" onclick="insertVariable('wa_reminder_template', '{tanggal}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> tanggal</span>
                        <span class="variable-tag" onclick="insertVariable('wa_reminder_template', '{tagihan}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> tagihan</span>
                        <span class="variable-tag" onclick="insertVariable('wa_reminder_template', '{sisa_hari}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> sisa_hari</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Template Keterlambatan <span style="color: var(--danger);">(Setelah Jatuh Tempo)</span></label>
                    <textarea name="wa_overdue_template" class="form-control" rows="4">{{ old('wa_overdue_template', $settings['wa_overdue_template']) }}</textarea>
                    <div class="variable-tags">
                        <span class="variable-tag" onclick="insertVariable('wa_overdue_template', '{nama}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> nama</span>
                        <span class="variable-tag" onclick="insertVariable('wa_overdue_template', '{kamar}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> kamar</span>
                        <span class="variable-tag" onclick="insertVariable('wa_overdue_template', '{tanggal}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> tanggal</span>
                        <span class="variable-tag" onclick="insertVariable('wa_overdue_template', '{tagihan}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> tagihan</span>
                        <span class="variable-tag" onclick="insertVariable('wa_overdue_template', '{sisa_hari}')"><i class="fa-solid fa-plus" style="font-size:0.6rem; margin-right:3px;"></i> sisa_hari</span>
                    </div>
                </div>

                <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi WA
                    </button>
                </div>
            </form>

            {{-- Test WhatsApp --}}
            <form action="{{ route('settings.test-whatsapp') }}" method="POST" class="test-wa-box">
                @csrf
                <h4><i class="fa-solid fa-paper-plane"></i> Test Kirim WhatsApp</h4>
                <div style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="flex-grow: 1; min-width: 200px;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #047857; margin-bottom: 0.5rem; letter-spacing: 0.5px;">Nomor Tujuan (Gunakan awalan 08 / 62)</label>
                        <input type="text" name="test_phone" class="form-control" placeholder="Contoh: 081234567890" 
                               style="background: white; border: 2px solid #A7F3D0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    </div>
                    <button type="submit" class="btn-test-wa">
                        <i class="fa-brands fa-whatsapp" style="font-size: 1.1rem;"></i> Kirim Pesan Uji Coba
                    </button>
                </div>
                <span style="font-size: 0.8rem; color: #047857; margin-top: 0.75rem; display: block; opacity: 0.85;">Pesan otomatis akan dikirim ke nomor di atas untuk memverifikasi apakah token Fonnte berfungsi dengan baik.</span>
            </form>
        </div>

        {{-- ====== TAB 4: LOG WHATSAPP ====== --}}
        <div class="tab-content" id="tab-walog">
            <div class="settings-card">
                <div class="settings-header" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-list-check" style="background: #F1F5F9; color: #475569; box-shadow: inset 0 0 0 1px rgba(71,85,105,0.1);"></i>
                    <div>
                        <h3>Log Riwayat Pengiriman</h3>
                        <span class="hint-text" style="margin-top: 0;">Pantau status pengiriman pesan WhatsApp ke penghuni.</span>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-light); text-align: left;">
                                <th style="padding: 1rem 0.5rem; color: var(--text-muted);">TANGGAL</th>
                                <th style="padding: 1rem 0.5rem; color: var(--text-muted);">PENGHUNI</th>
                                <th style="padding: 1rem 0.5rem; color: var(--text-muted);">PESAN</th>
                                <th style="padding: 1rem 0.5rem; color: var(--text-muted);">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($whatsappLogs ?? [] as $log)
                            <tr style="border-bottom: 1px solid var(--border-light);">
                                <td style="padding: 1rem 0.5rem; white-space: nowrap;">
                                    <div style="font-weight: 600;">{{ $log->created_at->translatedFormat('d M Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $log->created_at->format('H:i') }}</div>
                                </td>
                                <td style="padding: 1rem 0.5rem;">
                                    @if($log->penghuni)
                                        <div style="font-weight: 600; color: var(--primary);">{{ $log->penghuni->nama }}</div>
                                    @else
                                        <div style="font-weight: 600; color: var(--text-muted);">-</div>
                                    @endif
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $log->no_hp }}</div>
                                </td>
                                <td style="padding: 1rem 0.5rem;">
                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--slate);" title="{{ $log->pesan }}">
                                        {{ $log->pesan }}
                                    </div>
                                    @if($log->status === 'Failed' && $log->response_api)
                                        <div style="font-size: 0.7rem; color: var(--danger); margin-top: 0.25rem;">
                                            Error: {{ \Illuminate\Support\Str::limit($log->response_api, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 1rem 0.5rem;">
                                    @if($log->status === 'Success')
                                        <span style="background: #D1FAE5; color: #065F46; padding: 0.25rem 0.5rem; border-radius: 6px; font-weight: 700; font-size: 0.7rem;">Sukses</span>
                                    @else
                                        <span style="background: #FEE2E2; color: #991B1B; padding: 0.25rem 0.5rem; border-radius: 6px; font-weight: 700; font-size: 0.7rem;">Gagal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada riwayat pengiriman pesan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Hilangkan class active dari semua tab dan tombol
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

        // Tampilkan yang dipilih
        document.getElementById('tab-' + tabId).classList.add('active');
        document.getElementById('tab-btn-' + tabId).classList.add('active');
    }

    function toggleTokenVisibility() {
        const input = document.getElementById('fonnteTokenInput');
        const icon = document.getElementById('tokenToggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function insertVariable(fieldName, variable) {
        const textarea = document.querySelector(`textarea[name="${fieldName}"]`);
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        
        // Memasukkan variabel di posisi kursor
        textarea.value = text.substring(0, start) + variable + text.substring(end);
        
        // Memindahkan kursor setelah variabel yang dimasukkan
        textarea.selectionStart = textarea.selectionEnd = start + variable.length;
        textarea.focus();
        
        // Opsional: berikan feedback visual pada textarea (flash)
        textarea.style.backgroundColor = '#EFF6FF';
        setTimeout(() => { textarea.style.backgroundColor = '#F8FAFC'; }, 200);
    }

    // Auto-switch tab berdasarkan validation errors atau session
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('_tab'))
            switchTab('{{ session("_tab") }}');
        @elseif($errors->has('fonnte_token') || $errors->has('wa_reminder_template') || $errors->has('wa_overdue_template') || $errors->has('wa_reminder_days_before') || $errors->has('test_phone'))
            switchTab('whatsapp');
        @elseif($errors->has('tempo_periode'))
            switchTab('tempo');
        @endif
    });
</script>
@endsection