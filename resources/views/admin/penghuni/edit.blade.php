@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Edit Penghuni</h1>
            <p>Perbarui informasi penghuni <strong style="color: var(--primary);">{{ $penghuni->nama }}</strong>.</p>
        </div>
        <a href="{{ route('penghuni.index') }}" class="btn-primary" style="background: #FFF; color: var(--text-muted); border: 1px solid #E2E8F0; text-decoration: none; box-shadow: none;">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

<div class="widget" style="max-width: 900px;">
    <div class="widget-header" style="border-bottom: 1px solid #E2E8F0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
        <div class="widget-title" style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 36px; height: 36px; background: #FFFBEB; color: #D97706; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            Pembaruan Data Penghuni
        </div>
    </div>

    <form action="{{ route('penghuni.update', $penghuni->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Kamar Saat Ini</label>
                    <select name="id_kamar" required style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC; outline: none;">
                        @foreach($kamars as $k)
                            <option value="{{ $k->id }}" {{ $penghuni->id_kamar == $k->id ? 'selected' : '' }}>
                                {{ $k->nomor_kamar }} {{ $penghuni->id_kamar == $k->id ? '(Saat Ini)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kamar') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" value="{{ $penghuni->tgl_masuk }}" required style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC; outline: none;">
                    @error('tgl_masuk') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Status Akun</label>
                    <select name="status" required style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #FFF; outline: none; font-weight: 600; color: {{ $penghuni->status == 'Aktif' ? '#22C55E' : '#EF4444' }};">
                        <option value="Aktif" {{ $penghuni->status == 'Aktif' ? 'selected' : '' }} style="color: #22C55E;">Aktif</option>
                        <option value="Keluar" {{ $penghuni->status == 'Keluar' ? 'selected' : '' }} style="color: #EF4444;">Keluar</option>
                    </select>
                    @error('status') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $penghuni->nama }}" required style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC; outline: none;">
                    @error('nama') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">NIK</label>
                        <input type="text" name="nik" value="{{ $penghuni->nik }}" required style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC; outline: none;">
                        @error('nik') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">No. HP</label>
                        <input type="text" name="no_hp" value="{{ $penghuni->no_hp }}" required style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC; outline: none;">
                        @error('no_hp') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px; padding: 1.5rem; margin-top: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="width: 32px; height: 32px; background: #E0F2FE; color: #0284C7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 style="margin: 0; font-size: 1rem; color: var(--primary);">Ubah Keamanan</h3>
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Ganti Password <span style="font-weight: normal; opacity: 0.8;">(Kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" placeholder="Minimal 6 karakter jika ingin diubah" style="width: 100%; box-sizing: border-box; padding: 0.8rem; border: 1px solid #E2E8F0; border-radius: 8px; background: #FFF; outline: none;">
                @error('password') <div style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn-primary" style="flex: 2; padding: 1rem; justify-content: center; font-size: 1rem;">
                <i class="fa-solid fa-rotate"></i>
                Perbarui Data Penghuni
            </button>
            <a href="{{ route('penghuni.index') }}" style="flex: 1; display: flex; align-items: center; justify-content: center; background: #FFF; border: 1px solid #E2E8F0; border-radius: 8px; text-decoration: none; color: var(--text-muted); font-weight: 600; transition: 0.2s;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection