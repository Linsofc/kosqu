<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\RiwayatKamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        // AJAX Request
        if ($request->ajax()) {
            $query = Kamar::query();
            
            if ($request->filled('search')) {
                $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
            }
            
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }
            
            $kamars = $query->orderBy('nomor_kamar', 'asc')->paginate(10);
            
            // Stats for current query (unpaginated)
            $baseQuery = Kamar::query();
            if ($request->filled('search')) {
                $baseQuery->where('nomor_kamar', 'like', '%' . $request->search . '%');
            }
            if ($request->filled('status') && $request->status !== 'Semua') {
                $baseQuery->where('status', $request->status);
            }
            
            return response()->json([
                'html' => view('admin.kamar._table_rows', compact('kamars'))->render(),
                'pagination' => (string) $kamars->links(),
                'total' => $baseQuery->count(),
                'tersedia' => (clone $baseQuery)->where('status', 'Tersedia')->count(),
                'terisi' => (clone $baseQuery)->where('status', 'Terisi')->count(),
            ]);
        }

        $kamars = Kamar::orderBy('nomor_kamar', 'asc')->paginate(10);
        
        $totalKamar = Kamar::count();
        $terisi = Kamar::where('status', 'Terisi')->count();
        $tersedia = Kamar::where('status', 'Tersedia')->count();
        $booking = Kamar::where('status', 'Booking')->count();

        return view('admin.kamar.index', compact('kamars', 'totalKamar', 'terisi', 'tersedia', 'booking'));
    }

    public function show(Kamar $kamar)
    {
        $riwayats = RiwayatKamar::with('penghuni')
            ->where('id_kamar', $kamar->id)
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        return view('admin.kamar.show', compact('kamar', 'riwayats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_kamar' => 'required|unique:tb_kamar,nomor_kamar',
            'harga_sewa' => 'required|numeric',
            'status' => 'required|in:Tersedia,Terisi,Booking',
            'fasilitas' => 'nullable|string',
        ]);

        Kamar::create($request->all());

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'nomor_kamar' => 'required|unique:tb_kamar,nomor_kamar,' . $kamar->id,
            'harga_sewa' => 'required|numeric',
            'status' => 'required|in:Tersedia,Terisi,Booking',
            'fasilitas' => 'nullable|string',
        ]);

        $kamar->update($request->all());

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->status == 'Terisi') {
            return redirect()->back()->with('error', 'Kamar yang terisi tidak dapat dihapus.');
        }

        if ($kamar->status == 'Booking') {
            return redirect()->back()->with('error', 'Kamar yang sedang dibooking tidak dapat dihapus.');
        }

        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
