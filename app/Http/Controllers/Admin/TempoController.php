<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TempoController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $query = Penghuni::with('kamar')->where('status', 'Aktif');

        // Search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter Status Tempo
        if ($request->filled('status_tempo')) {
            if ($request->status_tempo == 'Terlambat') {
                $query->where('tgl_jatuh_tempo', '<', $now->toDateString());
            } elseif ($request->status_tempo == 'Mendatang') {
                $query->whereBetween('tgl_jatuh_tempo', [$now->toDateString(), $now->copy()->addDays(7)->toDateString()]);
            } elseif ($request->status_tempo == 'Aman') {
                $query->where('tgl_jatuh_tempo', '>', $now->copy()->addDays(7)->toDateString());
            }
        }

        $penghunis = $query->orderBy('tgl_jatuh_tempo', 'asc')->get();

        return view('admin.tempo.index', compact('penghunis', 'now'));
    }

    public function updateTagihan(Request $request, Penghuni $penghuni)
    {
        $request->validate([
            'tgl_jatuh_tempo' => 'required|date',
            'jumlah_tagihan' => 'nullable|numeric|min:0',
        ]);

        $penghuni->update([
            'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
            'jumlah_tagihan' => $request->jumlah_tagihan,
        ]);

        return redirect()->back()->with('success', 'Tagihan ' . $penghuni->nama . ' berhasil diperbarui.');
    }
}
