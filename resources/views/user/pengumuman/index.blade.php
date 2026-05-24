@extends('layouts.user')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Semua Pengumuman Kos</h1>
            <p>Daftar seluruh pengumuman dan informasi penting dari pengelola kos.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn-primary" style="background: white; color: var(--primary); border: 1px solid var(--border-light); text-decoration: none; padding: 0.5rem 1rem; border-radius: 12px; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<div class="widget" style="width: 100%;">
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @forelse($pengumumans as $p)
        <div style="display: flex; gap: 1rem; background: #F8FAFC; padding: 1.25rem; border-radius: 12px; border: 1px solid #F1F5F9;">
            <div style="width: 44px; height: 44px; background: {{ $p->warna_bg }}; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: {{ $p->warna_ikon }}; flex-shrink: 0;">
                <i class="fa-solid {{ $p->ikon }}"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.3rem;">{{ $p->created_at->translatedFormat('d M Y - H:i') }} WIB</div>
                <h4 style="margin: 0 0 0.4rem 0; font-size: 1.1rem; font-weight: 700; color: var(--primary);">{{ $p->judul }}</h4>
                <p style="margin: 0; font-size: 0.9rem; color: var(--slate); line-height: 1.6; white-space: pre-wrap;">{{ $p->konten }}</p>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: var(--text-muted); font-size: 0.9rem; background: #F8FAFC; border-radius: 12px; border: 1px solid #F1F5F9;">
            <i class="fa-solid fa-folder-open text-3xl mb-3" style="color: #CBD5E1;"></i><br>
            Tidak ada data pengumuman.
        </div>
        @endforelse
    </div>

    @if($pengumumans->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $pengumumans->links() }}
    </div>
    @endif
</div>
@endsection
