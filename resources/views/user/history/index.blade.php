@extends('layouts.user')

@section('content')
<div class="dashboard-title">
    <div>
        <h1>Riwayat Aktivitas</h1>
        <p>Log lengkap aktivitas Anda di sistem KOSQU.</p>
    </div>
</div>

<div class="widget">
    <div class="widget-header" style="margin-bottom: 2rem;">
        <div class="widget-title">Kronologi Aktivitas</div>
    </div>

    <div style="position: relative; padding-left: 2rem;">
        {{-- Vertical Line --}}
        <div style="position: absolute; left: 0.5rem; top: 0; bottom: 0; width: 2px; background: #E2E8F0; border-radius: 1px;"></div>

        <div style="display: flex; flex-direction: column; gap: 2.5rem;">
            @forelse($activities as $activity)
                <div style="position: relative;">
                    {{-- Dot --}}
                    @php
                        $dotColor = '#2563EB'; // Default blue
                        if(str_contains(strtolower($activity->tipe), 'pembayaran')) $dotColor = '#10B981';
                        if(str_contains(strtolower($activity->tipe), 'validasi')) $dotColor = '#F59E0B';
                        if(str_contains(strtolower($activity->status_badge), 'keluar') || str_contains(strtolower($activity->status_badge), 'invalid')) $dotColor = '#EF4444';
                    @endphp
                    <div style="position: absolute; left: -2.05rem; top: 0.25rem; width: 14px; height: 14px; background: white; border: 3px solid {{ $dotColor }}; border-radius: 50%; z-index: 1;"></div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-main);">{{ $activity->judul }}</h3>
                                <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-top: 0.15rem;">
                                    {{ $activity->created_at->translatedFormat('d M Y') }} • {{ $activity->created_at->format('H:i') }}
                                </div>
                            </div>
                            <span class="badge {{ $activity->warna_badge ?? 'badge-info' }}" style="font-size: 0.7rem; padding: 0.25rem 0.6rem;">
                                {{ $activity->status_badge }}
                            </span>
                        </div>
                        
                        <div style="background: #F8FAFC; padding: 1rem; border-radius: 12px; border: 1px solid #F1F5F9; font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">
                            {{ $activity->deskripsi }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 3rem 0;">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size: 3rem; color: #CBD5E1; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
                    <p style="color: var(--text-muted); font-weight: 600;">Belum ada riwayat aktivitas untuk saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($activities->hasPages())
        <div style="margin-top: 3rem; border-top: 1px solid #E2E8F0; padding-top: 1.5rem;">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection
