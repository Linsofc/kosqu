@extends('layouts.app')

@section('content')
<div class="dashboard-title">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Manajemen Data Kamar</h1>
            <p>Kelola daftar kamar, harga, dan ketersediaan unit kos Anda.</p>
        </div>
        <button class="btn-primary" onclick="toggleModal('addKamarModal')">
            <i class="fa-solid fa-plus"></i>
            Tambah Kamar
        </button>
    </div>
</div>



<div class="stats-grid" style="margin-bottom: 2rem; grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #0369A1;">
            <i class="fa-solid fa-bed"></i>
        </div>
        <div class="stat-info">
            <h3>TOTAL UNIT</h3>
            <div class="value">{{ $totalKamar }}</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--text-muted);">Kapasitas Total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <h3>TERSEDIA</h3>
            <div class="value" style="color: #059669;">{{ $tersedia }}</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--text-muted);">Unit Siap Huni</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
            <i class="fa-solid fa-user-tag"></i>
        </div>
        <div class="stat-info">
            <h3>TERISI</h3>
            <div class="value" style="color: #D97706;">{{ $terisi }}</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--text-muted);">Unit Sedang Disewa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #2563EB;">
            <i class="fa-solid fa-bookmark"></i>
        </div>
        <div class="stat-info">
            <h3>BOOKING</h3>
            <div class="value" style="color: #2563EB;">{{ $booking }}</div>
            <div style="font-size: 0.75rem; margin-top: 0.4rem; color: var(--text-muted);">Unit Dipesan</div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div class="widget-title">Daftar Kamar</div>
        
        <form action="{{ route('kamar.index') }}" method="GET" style="display: flex; gap: 1rem; flex-grow: 1; max-width: 600px;">
            <div style="position: relative; flex-grow: 1;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="search" placeholder="Cari nomor kamar..." value="{{ request('search') }}" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem;">
            </div>
            <select name="status" onchange="this.form.submit()" style="padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; background: white; outline: none; font-size: 0.9rem;">
                <option value="">Semua Status</option>
                <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Terisi" {{ request('status') == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                <option value="Booking" {{ request('status') == 'Booking' ? 'selected' : '' }}>Booking</option>
            </select>
        </form>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NOMOR KAMAR</th>
                    <th>HARGA SEWA</th>
                    <th>FASILITAS</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kamars as $kamar)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #F1F5F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary);">
                                {{ $kamar->nomor_kamar }}
                            </div>
                            <span style="font-weight: 600;">Unit {{ $kamar->nomor_kamar }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--primary);">Rp {{ number_format($kamar->harga_sewa, 0, ',', '.') }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">per bulan</div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $kamar->fasilitas }}">
                            {{ $kamar->fasilitas ?? 'Tidak ada data fasilitas' }}
                        </div>
                    </td>
                    <td>
                        @php
                            $badgeClass = 'badge-success';
                            if($kamar->status == 'Terisi') $badgeClass = 'badge-warning';
                            if($kamar->status == 'Booking') $badgeClass = 'badge-info';
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ $kamar->status }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button class="action-btn edit" onclick='openEditModal(@json($kamar))' title="Edit Kamar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="action-btn delete" title="Hapus Kamar" 
                                onclick="confirmDeleteKamar('{{ route('kamar.destroy', $kamar) }}', '{{ $kamar->nomor_kamar }}')"
                                {{ $kamar->status == 'Terisi' ? 'disabled style=opacity:0.5;cursor:not-allowed' : '' }}>
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        <i class="fa-solid fa-bed" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                        Belum ada data kamar yang sesuai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $kamars->links() }}
    </div>
</div>

<!-- Modal Tambah Kamar -->
<div id="addKamarModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Kamar Baru</h2>
            <button class="modal-close" onclick="toggleModal('addKamarModal')">&times;</button>
        </div>
        <form action="{{ route('kamar.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nomor Kamar</label>
                <input type="text" name="nomor_kamar" placeholder="Contoh: 101" required>
            </div>
            <div class="form-group">
                <label>Harga Sewa (Rp)</label>
                <input type="number" name="harga_sewa" placeholder="Contoh: 1500000" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Terisi">Terisi</option>
                    <option value="Booking">Booking</option>
                </select>
            </div>
            <div class="form-group">
                <label>Fasilitas</label>
                <textarea name="fasilitas" rows="3" placeholder="Contoh: AC, WiFi, Kamar Mandi Dalam..."></textarea>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-secondary" onclick="toggleModal('addKamarModal')" style="flex: 1;">Batal</button>
                <button type="submit" class="btn-primary" style="flex: 1;">Simpan Kamar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kamar -->
<div id="editKamarModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Data Kamar</h2>
            <button class="modal-close" onclick="toggleModal('editKamarModal')">&times;</button>
        </div>
        <form id="editKamarForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nomor Kamar</label>
                <input type="text" name="nomor_kamar" id="edit_nomor_kamar" required>
            </div>
            <div class="form-group">
                <label>Harga Sewa (Rp)</label>
                <input type="number" name="harga_sewa" id="edit_harga_sewa" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status" required>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Terisi">Terisi</option>
                    <option value="Booking">Booking</option>
                </select>
            </div>
            <div class="form-group">
                <label>Fasilitas</label>
                <textarea name="fasilitas" id="edit_fasilitas" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-secondary" onclick="toggleModal('editKamarModal')" style="flex: 1;">Batal</button>
                <button type="submit" class="btn-primary" style="flex: 1;">Perbarui Kamar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.style.display === 'flex') {
            modal.style.display = 'none';
        } else {
            modal.style.display = 'flex';
        }
    }

    function openEditModal(kamar) {
        document.getElementById('editKamarForm').action = `/kamar/${kamar.id}`;
        document.getElementById('edit_nomor_kamar').value = kamar.nomor_kamar;
        document.getElementById('edit_harga_sewa').value = kamar.harga_sewa;
        document.getElementById('edit_status').value = kamar.status;
        document.getElementById('edit_fasilitas').value = kamar.fasilitas || '';
        toggleModal('editKamarModal');
    }

    function confirmDeleteKamar(actionUrl, nomorKamar) {
        confirmAction({
            title: 'Hapus Kamar?',
            text: `Anda akan menghapus Unit Kamar ${nomorKamar}. Tindakan ini tidak dapat dibatalkan!`,
            confirmText: 'Ya, Hapus Kamar',
            callback: function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-muted);
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        outline: none;
        font-size: 0.9rem;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: var(--primary);
    }
    .btn-secondary {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: var(--text-muted);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }
</style>
@endsection
