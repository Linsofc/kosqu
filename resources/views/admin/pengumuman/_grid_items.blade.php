@forelse($pengumumans as $p)
<div class="widget" style="padding: 1.5rem; position: relative;">
    <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $p->warna_bg }}; color: {{ $p->warna_ikon }}; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="fa-solid {{ $p->ikon }}"></i>
        </div>
        <div style="flex-grow: 1;">
            <h3 style="font-weight: 800; color: #1E293B; font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $p->judul }}</h3>
            <p style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;">{{ $p->created_at->translatedFormat('d F Y • H:i') }}</p>
        </div>
    </div>
    <div style="color: #64748B; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem;">
        {{ Str::limit($p->konten, 150) }}
    </div>
    <div style="display: flex; gap: 0.5rem; border-top: 1px solid #F1F5F9; padding-top: 1rem;">
        <a href="{{ route('pengumuman.edit', $p->id) }}" style="flex-grow: 1; padding: 0.6rem; background: #F1F5F9; color: #475569; border-radius: 8px; text-align: center; text-decoration: none; font-weight: 700; font-size: 0.85rem; transition: all 0.2s;">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </a>
        <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" style="flex-grow: 1;" class="delete-pengumuman-form">
            @csrf
            @method('DELETE')
            <button type="submit" style="width: 100%; padding: 0.6rem; background: #FEF2F2; color: #DC2626; border-radius: 8px; border: none; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">
                <i class="fa-solid fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>
@empty
<div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem;">
    <i class="fa-solid fa-bullhorn" style="font-size: 4rem; color: #E2E8F0; margin-bottom: 1.5rem;"></i>
    <h3 style="color: #94A3B8; font-weight: 600;">Belum ada pengumuman.</h3>
    <p style="color: #CBD5E1; font-size: 0.9rem; margin-top: 0.5rem;">Terbitkan pengumuman pertama Anda atau ubah kata kunci pencarian.</p>
</div>
@endforelse
