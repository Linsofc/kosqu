@extends('layouts.app')

@section('content')
<style>
    .report-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
    }

    /* Hover lebih stabil tanpa bikin layout goyang */
    .report-card:hover {
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        border-color: #CBD5E1;
    }

    .widget {
        overflow: hidden;
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .icon-primary {
        background: #EFF6FF;
        color: #2563EB;
    }

    .icon-success {
        background: #ECFDF5;
        color: #059669;
    }

    .icon-warning {
        background: #FFFBEB;
        color: #D97706;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748B;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1E293B;
        margin-top: 0.25rem;
    }

    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }

    /* Hover tabel lebih smooth */
    .data-table tbody tr:hover {
        background: #F8FAFC;
    }

    .data-table th {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }

    .data-table td {
        vertical-align: middle;
    }

    @media print {
        .sidebar,
        .dashboard-title p,
        .filter-section,
        .no-print {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .report-card,
        .widget {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

<div class="dashboard-title"
    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
    <div>
        <h1>Laporan Keuangan</h1>
        <p>Ringkasan pendapatan dan performa bisnis kos Anda.</p>
    </div>

    <div style="display: flex; gap: 1rem;" class="no-print">
        <a href="{{ route('laporan.export', ['year' => $year, 'month' => $month]) }}" class="btn-primary" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-file-pdf"></i>
            Unduh PDF
        </a>
        <button onclick="window.print()"
            style="padding: 0.75rem 1.5rem; background: #1E293B; color: white; border-radius: 12px; border: none; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-print"></i>
            Cetak Laporan
        </button>
    </div>
</div>

<div
    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">

    <div class="report-card">
        <div class="card-icon icon-primary">
            <i class="fa-solid fa-chart-line"></i>
        </div>

        <div class="stat-label">Pendapatan Bulan Ini</div>

        <div class="stat-value">
            Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
        </div>
    </div>

    <div class="report-card">
        <div class="card-icon icon-success">
            <i class="fa-solid fa-calendar-check"></i>
        </div>

        <div class="stat-label">Pendapatan Tahun Ini</div>

        <div class="stat-value">
            Rp {{ number_format($pendapatanTahunIni, 0, ',', '.') }}
        </div>
    </div>

    <div class="report-card">
        <div class="card-icon icon-warning">
            <i class="fa-solid fa-wallet"></i>
        </div>

        <div class="stat-label">Total Pendapatan</div>

        <div class="stat-value">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>
</div>

<div class="widget filter-section" style="margin-bottom: 2rem; padding: 1.5rem;">
    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="min-width: 150px;">
            <label
                style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">
                Pilih Tahun
            </label>

            <select id="filter-year" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white; cursor: pointer;">

                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="min-width: 150px;">
            <label
                style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; margin-bottom: 0.5rem; text-transform: uppercase;">
                Pilih Bulan
            </label>

            <select id="filter-month" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #E2E8F0; outline: none; font-size: 0.9rem; background: white; cursor: pointer;">

                <option value="">Semua Bulan</option>

                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <button type="button" onclick="resetFilters()" style="padding: 0.75rem 1.5rem; border: none; border-radius: 10px; background: #F1F5F9; color: #475569; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: 0.2s;">
            <i class="fa-solid fa-rotate-right"></i> Reset Filter
        </button>
        <button type="button" onclick="exportPdf()" style="padding: 0.75rem 1.5rem; border: none; border-radius: 10px; background: #DC2626; color: white; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: 0.2s; margin-left: auto;">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
    </div>
</div>

<div class="widget" style="margin-bottom: 2rem; padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-weight: 800; color: #1E293B;">
            <i class="fa-solid fa-chart-bar" style="color: var(--primary); margin-right: 0.5rem;"></i>
            Grafik Pendapatan Bulanan <span id="chart-year">{{ $year }}</span>
        </h3>
    </div>
    <div style="height: 300px;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<div class="widget">
    <div
        style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0;">

        <h3 style="font-weight: 800; color: #1E293B;">
            Rincian Transaksi Valid
        </h3>

        <span id="transaksi-count" style="font-size: 0.85rem; color: #64748B; font-weight: 600; background: #F1F5F9; padding: 0.25rem 0.75rem; border-radius: 20px;">
            {{ $transaksis->count() }} Transaksi
        </span>
    </div>

    <div>
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #E2E8F0;">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Tanggal</th>
                    <th style="padding: 1rem;">Penghuni</th>
                    <th style="padding: 1rem;">Unit</th>
                    <th style="padding: 1rem;">Bulan Tagihan</th>
                    <th style="padding: 1rem;">Jumlah</th>
                </tr>
            </thead>

            <tbody id="laporan-tbody" style="transition: opacity 0.2s;">
                @include('admin.laporan._table_rows', ['transaksis' => $transaksis])
            </tbody>

            <tfoot id="laporan-tfoot" style="{{ $transaksis->isEmpty() ? 'display: none;' : '' }}">
                <tr style="background: #F8FAFC; font-weight: 800;">
                    <td colspan="5"
                        style="padding: 1rem; text-align: right; border-top: 1px solid #E2E8F0;">
                        TOTAL PERIODE INI
                    </td>

                    <td id="total-filtered" style="padding: 1rem; color: #059669; border-top: 1px solid #E2E8F0;">
                        Rp {{ number_format($transaksis->sum('jumlah_bayar'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endsection

@section('scripts')
<script>
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const monthlyData = @json($monthlyData);
    let chartInstance = null;
    
    function initChart(data) {
        if(chartInstance) {
            chartInstance.destroy();
        }

        const chartData = Array(12).fill(0);
        data.forEach(item => {
            chartData[item.month - 1] = item.total;
        });

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 136, 168, 0.8)');
        gradient.addColorStop(1, 'rgba(10, 147, 150, 0.3)');

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: 'rgba(0, 136, 168, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        },
                        backgroundColor: '#1E293B',
                        titleFont: { weight: '700' },
                        padding: 12,
                        cornerRadius: 10,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                return 'Rp ' + value;
                            },
                            font: { weight: '600', size: 11 },
                            color: '#94A3B8',
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { weight: '600', size: 12 },
                            color: '#64748B',
                        }
                    }
                }
            }
        });
    }

    initChart(monthlyData);

    const filterYear = document.getElementById('filter-year');
    const filterMonth = document.getElementById('filter-month');
    const tbody = document.getElementById('laporan-tbody');
    const tfoot = document.getElementById('laporan-tfoot');
    const totalFiltered = document.getElementById('total-filtered');
    const transaksiCount = document.getElementById('transaksi-count');
    const chartYearLabel = document.getElementById('chart-year');

    filterYear.addEventListener('change', () => fetchData());
    filterMonth.addEventListener('change', () => fetchData());

    function fetchData() {
        const year = filterYear.value;
        const month = filterMonth.value;

        tbody.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (year) params.append('year', year);
        if (month) params.append('month', month);

        const url = `{{ route('laporan.index') }}?${params.toString()}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = data.html;
            
            if(data.totalCount > 0) {
                tfoot.style.display = 'table-footer-group';
                totalFiltered.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.totalFiltered);
            } else {
                tfoot.style.display = 'none';
            }

            transaksiCount.innerText = data.totalCount + ' Transaksi';
            chartYearLabel.innerText = year;
            
            initChart(data.monthlyData);
            
            tbody.style.opacity = '1';
        })
        .catch(err => {
            console.error('Fetch error:', err);
            tbody.style.opacity = '1';
        });
    }

    function resetFilters() {
        filterYear.value = new Date().getFullYear().toString();
        filterMonth.value = '';
        fetchData();
    }

    function exportPdf() {
        const year = filterYear.value;
        const month = filterMonth.value;
        const params = new URLSearchParams();
        if (year) params.append('year', year);
        if (month) params.append('month', month);
        
        window.location.href = `{{ route('laporan.export') }}?${params.toString()}`;
    }
</script>
@endsection