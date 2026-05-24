<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        // Stats
        $totalPendapatan = Transaksi::where('status_validasi', 'Valid')->sum('jumlah_bayar');
        
        $pendapatanBulanIni = Transaksi::where('status_validasi', 'Valid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('jumlah_bayar');

        $pendapatanTahunIni = Transaksi::where('status_validasi', 'Valid')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('jumlah_bayar');

        // Data Bulanan untuk Grafik/Tabel Summary (12 bulan terakhir atau tahun terpilih)
        $monthlyData = Transaksi::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->where('status_validasi', 'Valid')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Detail Transaksi Terpilih
        $query = Transaksi::with(['penghuni.kamar'])
            ->where('status_validasi', 'Valid')
            ->whereYear('created_at', $year);

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $month);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        // List Tahun untuk Filter
        $years = Transaksi::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([Carbon::now()->year]);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.laporan._table_rows', compact('transaksis'))->render(),
                'monthlyData' => $monthlyData,
                'totalFiltered' => $transaksis->sum('jumlah_bayar'),
                'totalCount' => $transaksis->count(),
            ]);
        }

        return view('admin.laporan.index', compact(
            'totalPendapatan',
            'pendapatanBulanIni',
            'pendapatanTahunIni',
            'monthlyData',
            'transaksis',
            'year',
            'month',
            'years'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');

        $query = Transaksi::with(['penghuni.kamar'])
            ->where('status_validasi', 'Valid')
            ->whereYear('created_at', $year);

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $month);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksis->sum('jumlah_bayar');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('transaksis', 'totalPendapatan', 'year', 'month'));
        
        $filename = 'Laporan_Keuangan_KOSQU_' . $year . ($month ? '_Bulan_'.$month : '') . '.pdf';
        return $pdf->download($filename);
    }
}
