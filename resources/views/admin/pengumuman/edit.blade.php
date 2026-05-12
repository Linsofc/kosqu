@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div>
        <h1>Edit Pengumuman</h1>
        <p>Perbarui informasi yang telah diterbitkan sebelumnya.</p>
    </div>
</div>

<div style="max-width: 800px; margin-top: 2rem;">
    <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" class="widget" style="padding: 2.5rem;">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">Judul Pengumuman</label>
                <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" style="width: 100%; padding: 0.85rem 1.25rem; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; font-size: 0.95rem;" required>
                @error('judul') <span style="color: #EF4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">Konten / Isi Pesan</label>
                <textarea name="konten" rows="5" style="width: 100%; padding: 0.85rem 1.25rem; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; font-size: 0.95rem; resize: vertical;" required>{{ old('konten', $pengumuman->konten) }}</textarea>
                @error('konten') <span style="color: #EF4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">Ikon FontAwesome</label>
                    <select name="ikon" style="width: 100%; padding: 0.85rem 1rem; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white;">
                        <option value="fa-bullhorn" {{ $pengumuman->ikon == 'fa-bullhorn' ? 'selected' : '' }}>Bullhorn (🔊)</option>
                        <option value="fa-circle-info" {{ $pengumuman->ikon == 'fa-circle-info' ? 'selected' : '' }}>Info (ℹ️)</option>
                        <option value="fa-triangle-exclamation" {{ $pengumuman->ikon == 'fa-triangle-exclamation' ? 'selected' : '' }}>Warning (⚠️)</option>
                        <option value="fa-calendar-days" {{ $pengumuman->ikon == 'fa-calendar-days' ? 'selected' : '' }}>Event (📅)</option>
                        <option value="fa-credit-card" {{ $pengumuman->ikon == 'fa-credit-card' ? 'selected' : '' }}>Payment (💳)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">Warna Background</label>
                    <input type="color" name="warna_bg" value="{{ $pengumuman->warna_bg }}" style="width: 100%; height: 45px; border: none; border-radius: 12px; cursor: pointer;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">Warna Ikon</label>
                    <input type="color" name="warna_ikon" value="{{ $pengumuman->warna_ikon }}" style="width: 100%; height: 45px; border: none; border-radius: 12px; cursor: pointer;">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <a href="{{ route('pengumuman.index') }}" style="flex-grow: 1; padding: 1rem; border-radius: 12px; background: #F1F5F9; color: #475569; text-decoration: none; text-align: center; font-weight: 700;">Batal</a>
                <button type="submit" style="flex-grow: 2; padding: 1rem; border-radius: 12px; background: var(--primary); color: white; border: none; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
