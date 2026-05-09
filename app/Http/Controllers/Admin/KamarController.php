<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Kamar::query();

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search nomor kamar
        if ($request->has('search') && $request->search != '') {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('nomor_kamar', 'asc')->paginate(10);

        // Stats for cards
        $totalKamar = Kamar::count();
        $terisi = Kamar::where('status', 'Terisi')->count();
        $tersedia = Kamar::where('status', 'Tersedia')->count();
        $booking = Kamar::where('status', 'Booking')->count();

        return view('admin.kamar.index', compact('kamars', 'totalKamar', 'terisi', 'tersedia', 'booking'));
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

        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
