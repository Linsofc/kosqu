@extends('layouts.app')

@section('content')
<style>
    /* Transisi untuk baris tabel */
    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .data-table tbody tr:hover {
        background-color: #F8FAFC;
    }

    /* Tombol Reset Filter */
    .btn-reset {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #F1F5F9;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-reset:hover {
        background: #E2E8F0;
        color: #334155;
    }

    /* Tombol WhatsApp (Fonnte API) */
    .btn-action-wa {
        background: #ECFDF5;
        color: #059669;
        border: 1px solid #A7F3D0;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-action-wa:hover {
        background: #D1FAE5;
        color: #047857;
        transform: translateY(-1px);
    }
    .btn-action-wa:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
    }

    /* Tombol Edit Tagihan */
    .btn-action-bill {
        background: #F0F9FF;
        color: #0369A1;
        border: 1px solid #BAE6FD;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-action-bill:hover {
        background: #E0F2FE;
        color: #075985;
        transform: translateY(-1px);
    }

    /* Bulk Reminder Button */
    .btn-bulk-wa {
        background: linear-gradient(135deg, #059669, #10B981);
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-bulk-wa:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }
    .btn-bulk-wa:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Tempo Badge */
    .tempo-badge {
        background: #EFF6FF;
        color: #1D4ED8;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Glassmorphism Modal */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-content {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        width: 90%;
        max-width: 480px;
        padding: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-backdrop.active {
        display: flex;
        opacity: 1;
    }
    .modal-backdrop.active .modal-content {
        transform: translateY(0);
    }
    .modal-header {
        margin-bottom: 1.5rem;
    }
    .modal-header h2 {
        font-size: 1.25rem;
        color: #1E293B;
        font-weight: 800;
    }
    .form-group {
        margin-bottom: 1.25rem;
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
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .modal-footer {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn-submit {
        flex-grow: 1;
        background: var(--primary);
        color: white;
        padding: 0.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-submit:hover {
        background: #1D4ED8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .btn-cancel {
        flex-grow: 1;
        background: #F1F5F9;
        color: #475569;
        padding: 0.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
    }

    /* Not configured banner */
    .wa-not-configured {
        background: linear-gradient(135deg, #FEF3C7, #FFFBEB);
        border: 1px solid #FCD34D;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .wa-not-configured i {
        font-size: 1.5rem;
        color: #D97706;
    }
    .wa-not-configured a {
        color: #2563EB;
        text-decoration: underline;
        font-weight: 600;
    }
</style>

<div class="dashboard-title" style="display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h1>Manajemen Jatuh Tempo</h1>
        <p>Pantau masa sewa penghuni dan ingatkan pembayaran yang mendekati batas waktu.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        @if($fonnteConfigured)
        <form action="{{ route('tempo.send-bulk-reminder') }}" method="POST" style="display: inline;" id="bulkReminderForm">
            @csrf
            <button type="button" class="btn-bulk-wa" onclick="confirmBulkReminder()">
                <i class="fa-brands fa-whatsapp"></i> Kirim Pengingat Semua
            </button>
        </form>
        @else
        <button class="btn-bulk-wa" disabled title="Konfigurasi Fonnte terlebih dahulu di Settings">
            <i class="fa-brands fa-whatsapp"></i> Kirim Pengingat Semua
        </button>
        @endif
    </div>
</div>

@if(!$fonnteConfigured)
<div class="wa-not-configured">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <div>
        <div style="font-weight: 700; color: #92400E; font-size: 0.9rem;">WhatsApp Fonnte Belum Dikonfigurasi</div>
        <p style="font-size: 0.8rem; color: #A16207; margin-top: 0.15rem;">
            Untuk mengirim notifikasi WhatsApp otomatis, silakan atur token Fonnte di 
            <a href="{{ route('settings.index') }}">halaman Settings</a>.
        </p>
    </div>
</div>
@endif

{{-- Filter Widget --}}
<div class="widget" style="margin-bottom: 2rem;">
    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex-grow: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Cari Penghuni</label>
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Ketik nama penghuni..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem;">
            </div>
        </div>
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Status Tempo</label>
            <select id="filter-status" style="padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white; cursor: pointer;">
                <option value="Semua">Semua Status</option>
                <option value="Terlambat">Terlambat 🔴</option>
                <option value="Mendatang">Mendatang (7 Hari) 🟡</option>
                <option value="Aman">Aman 🟢</option>
            </select>
        </div>
        <button type="button" id="btn-reset" onclick="resetFilters()" class="btn-reset">
            <i class="fa-solid fa-rotate-right"></i> Reset
        </button>
    </div>
</div>

<div class="widget">
    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #E2E8F0;">
                    <th style="padding: 1rem 0.5rem;">UNIT</th>
                    <th style="padding: 1rem 0.5rem;">PENGHUNI</th>
                    <th style="padding: 1rem 0.5rem;">JATUH TEMPO</th>
                    <th style="padding: 1rem 0.5rem;">SISA HARI</th>
                    <th style="padding: 1rem 0.5rem;">TAGIHAN</th>
                    <th style="padding: 1rem 0.5rem;">TEMPO</th>
                    <th style="padding: 1rem 0.5rem;">STATUS</th>
                    <th style="text-align: center; padding: 1rem 0.5rem;">AKSI</th>
                </tr>
            </thead>
            <tbody id="tempo-tbody" style="transition: opacity 0.2s;">
                @include('admin.tempo._table_rows', ['penghunis' => $penghunis])
            </tbody>
        </table>
    </div>
</div>
        </table>
    </div>
</div>
@endsection

{{-- Modal Quick Edit Tagihan & Tempo --}}
<div id="billModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Atur Tagihan & Tempo</h2>
            <p style="font-size: 0.85rem; color: #64748B;" id="modalPenghuniNama"></p>
        </div>
        <form id="billForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tgl_jatuh_tempo" id="modalTglTempo" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jumlah Tagihan (Rp)</label>
                <input type="number" name="jumlah_tagihan" id="modalJumlahTagihan" class="form-control" placeholder="Biarkan kosong untuk harga standar kamar" min="0">
                <p style="font-size: 0.7rem; color: #94A3B8; margin-top: 0.4rem;">Kosongkan jika ingin menggunakan harga standar kamar.</p>
            </div>
            <div class="form-group">
                <label>Periode Tempo Pembayaran</label>
                <select name="tempo_periode" id="modalTempoPeriode" class="form-control">
                    <option value="1">1 Bulan</option>
                    <option value="2">2 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="4">4 Bulan</option>
                    <option value="5">5 Bulan</option>
                    <option value="6">6 Bulan (1 Semester)</option>
                    <option value="7">7 Bulan</option>
                    <option value="8">8 Bulan</option>
                    <option value="9">9 Bulan</option>
                    <option value="10">10 Bulan</option>
                    <option value="11">11 Bulan</option>
                    <option value="12">12 Bulan (1 Tahun)</option>
                </select>
                <p style="font-size: 0.7rem; color: #94A3B8; margin-top: 0.4rem;">Saat pembayaran divalidasi, jatuh tempo akan bertambah sesuai periode ini.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeBillModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBillModal(id, nama, tgl, jumlah, tempoPeriode) {
        const modal = document.getElementById('billModal');
        const form = document.getElementById('billForm');
        
        document.getElementById('modalPenghuniNama').innerText = 'Penghuni: ' + nama;
        document.getElementById('modalTglTempo').value = tgl;
        document.getElementById('modalJumlahTagihan').value = jumlah;
        document.getElementById('modalTempoPeriode').value = tempoPeriode;
        
        // Update form action
        form.action = `/tempo/${id}/update-tagihan`;
        
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeBillModal() {
        const modal = document.getElementById('billModal');
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    // Close on backdrop click
    window.onclick = function(event) {
        const modal = document.getElementById('billModal');
        if (event.target == modal) {
            closeBillModal();
        }
    }

    // Confirm bulk reminder
    function confirmBulkReminder() {
        Swal.fire({
            title: 'Kirim Pengingat Massal?',
            html: `
                <div style="text-align: left; font-size: 0.9rem; color: #475569; line-height: 1.6;">
                    <p>Pesan WhatsApp akan dikirim ke semua penghuni yang:</p>
                    <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
                        <li>Sudah <strong style="color: #DC2626;">melewati</strong> jatuh tempo</li>
                        <li>Jatuh tempo dalam <strong style="color: #D97706;">waktu dekat</strong></li>
                    </ul>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="fa-brands fa-whatsapp"></i> Ya, Kirim Semua',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkReminderForm').submit();
            }
        });
    }

    function attachActionEvents() {
        // Confirm individual WA reminder
        document.querySelectorAll('.wa-reminder-form').forEach(form => {
            // Remove existing listener to avoid duplicates if re-attached
            form.replaceWith(form.cloneNode(true));
        });
        
        document.querySelectorAll('.wa-reminder-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                Swal.fire({
                    title: 'Kirim Pengingat?',
                    text: 'Pesan pengingat WhatsApp akan dikirim ke penghuni ini melalui Fonnte.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: '<i class="fa-brands fa-whatsapp"></i> Kirim',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        formEl.submit();
                    }
                });
            });
        });
    }

    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const filterStatus = document.getElementById('filter-status');
    const tbody = document.getElementById('tempo-tbody');

    if (searchInput && filterStatus && tbody) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchData(), 300);
        });

        filterStatus.addEventListener('change', () => fetchData());
    }

    function fetchData(pageUrl = null) {
        const search = searchInput.value.trim();
        const status = filterStatus.value;

        tbody.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status !== 'Semua') params.append('status_tempo', status);

        const url = pageUrl || `{{ route('tempo.index') }}?${params.toString()}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = data.html;
            tbody.style.opacity = '1';
            
            attachActionEvents();
        })
        .catch(err => {
            console.error('Fetch error:', err);
            tbody.style.opacity = '1';
        });
    }

    function resetFilters() {
        if(searchInput) searchInput.value = '';
        if(filterStatus) filterStatus.value = 'Semua';
        fetchData();
    }

    document.addEventListener('DOMContentLoaded', () => {
        attachActionEvents();
    });
</script>