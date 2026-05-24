@extends('layouts.app')

@section('content')
<div class="dashboard-title" style="display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h1>Manajemen Booking</h1>
        <p>Kelola reservasi kamar untuk calon penghuni baru.</p>
    </div>
    <a href="{{ route('booking.create') }}" class="btn-primary" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
        <i class="fa-solid fa-plus"></i> Buat Booking
    </a>
</div>

{{-- Stat Cards --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #EFF6FF; color: #2563EB;">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <h3>TOTAL BOOKING</h3>
            <div class="value" id="stat-total">{{ $totalBooking }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFFBEB; color: #D97706;">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>MENUNGGU</h3>
            <div class="value" style="color: #D97706;" id="stat-pending">{{ $pendingBooking }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <h3>DIKONFIRMASI</h3>
            <div class="value" style="color: #059669;" id="stat-confirmed">{{ $confirmedBooking }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF2F2; color: #DC2626;">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div class="stat-info">
            <h3>DIBATALKAN</h3>
            <div class="value" style="color: #DC2626;" id="stat-cancelled">{{ $cancelledBooking }}</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="widget" style="margin-bottom: 2rem; padding: 1.5rem;">
    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex-grow: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Cari Booking</label>
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Nama, NIK, No. HP, atau Unit..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem;">
            </div>
        </div>
        <div style="min-width: 180px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Status</label>
            <select id="filter-status" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white; cursor: pointer;">
                <option value="Semua">Semua Status</option>
                <option value="Pending">🟡 Pending</option>
                <option value="Dikonfirmasi">🟢 Dikonfirmasi</option>
                <option value="Dibatalkan">🔴 Dibatalkan</option>
            </select>
        </div>
        <button type="button" id="btn-reset" onclick="resetFilters()" style="padding: 0.75rem 1.5rem; border-radius: 10px; background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;">
            <i class="fa-solid fa-rotate-right"></i> Reset
        </button>
    </div>
</div>

{{-- Table --}}
<div class="widget">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #E2E8F0;">
                    <th style="padding: 1rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #64748B; text-transform: uppercase;">KAMAR</th>
                    <th style="padding: 1rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #64748B; text-transform: uppercase;">CALON PENGHUNI</th>
                    <th style="padding: 1rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #64748B; text-transform: uppercase;">TGL BOOKING</th>
                    <th style="padding: 1rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #64748B; text-transform: uppercase;">RENCANA MASUK</th>
                    <th style="padding: 1rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #64748B; text-transform: uppercase;">STATUS</th>
                    <th style="padding: 1rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #64748B; text-transform: uppercase; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody id="booking-tbody" style="transition: opacity 0.2s;">
                @include('admin.booking._table_rows', ['bookings' => $bookings])
            </tbody>
        </table>
    </div>

    <div id="pagination-container" style="padding: 1rem;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const filterStatus = document.getElementById('filter-status');
    const tbody = document.getElementById('booking-tbody');
    const paginationContainer = document.getElementById('pagination-container');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchData(), 300);
    });

    filterStatus.addEventListener('change', () => fetchData());

    function fetchData(pageUrl = null) {
        const search = searchInput.value.trim();
        const status = filterStatus.value;

        tbody.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status !== 'Semua') params.append('status', status);

        const url = pageUrl || `{{ route('booking.index') }}?${params.toString()}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            
            document.getElementById('stat-total').textContent = data.total;
            document.getElementById('stat-pending').textContent = data.pending;
            document.getElementById('stat-confirmed').textContent = data.confirmed;
            document.getElementById('stat-cancelled').textContent = data.cancelled;
            
            tbody.style.opacity = '1';
            
            // Re-attach events
            attachPaginationEvents();
            attachActionEvents();
        })
        .catch(err => {
            console.error('Fetch error:', err);
            tbody.style.opacity = '1';
        });
    }

    function resetFilters() {
        searchInput.value = '';
        filterStatus.value = 'Semua';
        fetchData();
    }

    function attachPaginationEvents() {
        const links = paginationContainer.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchData(this.href);
            });
        });
    }

    function attachActionEvents() {
        // Confirm booking
        document.querySelectorAll('.confirm-booking-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                Swal.fire({
                    title: 'Konfirmasi Booking?',
                    html: '<div style="font-size: 0.9rem; color: #475569; line-height: 1.6;">Calon penghuni akan <strong>didaftarkan sebagai penghuni aktif</strong> dan kamar akan ditetapkan sebagai terisi.</div>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Konfirmasi',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) formEl.submit();
                });
            });
        });

        // Cancel booking
        document.querySelectorAll('.cancel-booking-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                Swal.fire({
                    title: 'Batalkan Booking?',
                    text: 'Kamar akan kembali tersedia untuk booking lain.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: '<i class="fa-solid fa-xmark"></i> Ya, Batalkan',
                    cancelButtonText: 'Kembali',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) formEl.submit();
                });
            });
        });
    }

    // Attach initial events
    document.addEventListener('DOMContentLoaded', () => {
        attachPaginationEvents();
        attachActionEvents();
    });
</script>
@endsection
