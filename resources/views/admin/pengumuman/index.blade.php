@extends('layouts.app')

@section('content')
<div class="dashboard-title" style="display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h1>Manajemen Pengumuman</h1>
        <p>Kelola pesan dan pengumuman yang akan muncul di dashboard penghuni.</p>
    </div>
    <a href="{{ route('pengumuman.create') }}" class="btn-primary" style="padding: 0.75rem 1.5rem; background: var(--primary); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;">
        <i class="fa-solid fa-plus"></i> Buat Pengumuman
    </a>
</div>

<div class="widget" style="margin-top: 2rem; padding: 1.5rem; display: flex; gap: 1rem; align-items: center;">
    <div style="position: relative; flex-grow: 1;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
        <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari judul atau isi pengumuman..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem;">
    </div>
</div>

<div id="pengumuman-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; transition: opacity 0.2s;">
    @include('admin.pengumuman._grid_items', ['pengumumans' => $pengumumans])
</div>

<div id="pagination-container" style="margin-top: 2rem;">
    {{ $pengumumans->links() }}
</div>
@endsection

@section('scripts')
<script>
    function attachDeleteEvents() {
        document.querySelectorAll('.delete-pengumuman-form').forEach(form => {
            // Remove existing listener to avoid duplicates if re-attached
            form.replaceWith(form.cloneNode(true));
        });
        
        document.querySelectorAll('.delete-pengumuman-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                Swal.fire({
                    title: 'Hapus Pengumuman?',
                    text: 'Pengumuman yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: '<i class="fa-solid fa-trash"></i> Ya, Hapus',
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
    const grid = document.getElementById('pengumuman-grid');
    const paginationContainer = document.getElementById('pagination-container');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchData(), 300);
    });

    function fetchData(pageUrl = null) {
        const search = searchInput.value.trim();
        grid.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (search) params.append('search', search);

        const url = pageUrl || `{{ route('pengumuman.index') }}?${params.toString()}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            grid.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            grid.style.opacity = '1';
            
            attachPaginationEvents();
            attachDeleteEvents();
        })
        .catch(err => {
            console.error('Fetch error:', err);
            grid.style.opacity = '1';
        });
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

    document.addEventListener('DOMContentLoaded', () => {
        attachPaginationEvents();
        attachDeleteEvents();
    });
</script>
@endsection
