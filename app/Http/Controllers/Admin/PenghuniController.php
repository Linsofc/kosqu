<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenghuniController extends Controller
{
    public function index(Request $request)
    {
        // AJAX search/filter request
        if ($request->ajax()) {
            $query = Penghuni::with('kamar');

            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('nama', 'like', "%{$s}%")
                      ->orWhere('nik', 'like', "%{$s}%")
                      ->orWhere('no_hp', 'like', "%{$s}%");
                });
            }

            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }

            if ($request->filled('kamar') && $request->kamar !== 'Semua') {
                $query->where('id_kamar', $request->kamar);
            }

            $penghunis = $query->get();

            return response()->json([
                'html' => view('admin.penghuni._table_rows', compact('penghunis'))->render(),
                'total' => $penghunis->count(),
                'aktif' => $penghunis->where('status', 'Aktif')->count(),
                'keluar' => $penghunis->where('status', 'Keluar')->count(),
            ]);
        }

        $penghunis = Penghuni::with('kamar')->get();
        $trashedCount = Penghuni::onlyTrashed()->count();
        $kamars = Kamar::orderBy('nomor_kamar')->get();
        return view('admin.penghuni.index', compact('penghunis', 'trashedCount', 'kamars'));
    }

    public function create()
    {
        $kamars = Kamar::where('status', 'Tersedia')->get();
        return view('admin.penghuni.create', compact('kamars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kamar' => 'required|exists:tb_kamar,id',
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_penghuni,nik',
            'tgl_masuk' => 'required|date',
            'password' => 'required|string|min:6',
        ]);

        $kamar = Kamar::findOrFail($request->id_kamar);
        $defaultTempo = (int) \App\Models\Setting::get('tempo_periode', 1);
        $tglJatuhTempo = \Carbon\Carbon::parse($request->tgl_masuk)->addMonths($defaultTempo)->format('Y-m-d');

        // Note: Do NOT use Hash::make() here — the Penghuni model's
        // 'password' => 'hashed' cast handles hashing automatically.
        $penghuni = Penghuni::create([
            'id_kamar' => $request->id_kamar,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'jumlah_tagihan' => $kamar->harga_sewa,
            'tempo_periode' => $defaultTempo,
            'password' => $request->password,
        ]);

        // Create initial transaction (Lunas for first month)
        \App\Models\Transaksi::create([
            'id_penghuni' => $penghuni->id,
            'bulan_tagihan' => \Carbon\Carbon::parse($request->tgl_masuk)->format('Y-m'),
            'jumlah_bayar' => $kamar->harga_sewa,
            'bukti_transfer' => 'Pembayaran Awal (Otomatis)',
            'tgl_bayar' => \Carbon\Carbon::now()->format('Y-m-d'),
            'metode_bayar' => 'Tunai',
            'status_validasi' => 'Valid',
        ]);

        // Update room status
        $kamar->update(['status' => 'Terisi']);

        // Log Room History
        \App\Models\RiwayatKamar::create([
            'id_penghuni' => $penghuni->id,
            'id_kamar' => $request->id_kamar,
            'tgl_masuk' => $request->tgl_masuk,
        ]);

        // Send Welcome Message
        $fonnteService = new \App\Services\FonnteService();
        $fonnteService->sendWelcomeMessage($penghuni, $request->password);

        return redirect()->route('penghuni.index')->with('success', 'Data penghuni berhasil ditambahkan.');
    }

    public function edit(Penghuni $penghuni)
    {
        $kamars = Kamar::where('status', 'Tersedia')->orWhere('id', $penghuni->id_kamar)->get();
        return view('admin.penghuni.edit', compact('penghuni', 'kamars'));
    }

    public function update(Request $request, Penghuni $penghuni)
    {
        $request->validate([
            'id_kamar' => 'required|exists:tb_kamar,id',
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_penghuni,nik,' . $penghuni->id,
            'no_hp' => 'required|string|max:15',
            'tgl_masuk' => 'required|date',
            'status' => 'required|in:Aktif,Keluar',
        ]);

        // Logic if status changes to Keluar
        if ($penghuni->status != 'Keluar' && $request->status == 'Keluar') {
            // Free the room
            Kamar::find($penghuni->id_kamar)->update(['status' => 'Tersedia']);
            
            // Log Room History exit
            $riwayat = \App\Models\RiwayatKamar::where('id_penghuni', $penghuni->id)->whereNull('tgl_keluar')->latest()->first();
            if ($riwayat) {
                $riwayat->update(['tgl_keluar' => \Carbon\Carbon::now()->format('Y-m-d')]);
            }
        } 
        // Logic if status changes back to Aktif
        elseif ($penghuni->status == 'Keluar' && $request->status == 'Aktif') {
            // Re-occupy the room
            Kamar::find($request->id_kamar)->update(['status' => 'Terisi']);
            
            // Log new Room History entry
            \App\Models\RiwayatKamar::create([
                'id_penghuni' => $penghuni->id,
                'id_kamar' => $request->id_kamar,
                'tgl_masuk' => \Carbon\Carbon::now()->format('Y-m-d'),
            ]);
        }
        // If room changed while status is still Aktif
        elseif ($penghuni->status == 'Aktif' && $request->status == 'Aktif' && $penghuni->id_kamar != $request->id_kamar) {
            // Free old room
            Kamar::find($penghuni->id_kamar)->update(['status' => 'Tersedia']);
            // Occupy new room
            Kamar::find($request->id_kamar)->update(['status' => 'Terisi']);
            
            // End old room history
            $riwayat = \App\Models\RiwayatKamar::where('id_penghuni', $penghuni->id)->whereNull('tgl_keluar')->latest()->first();
            if ($riwayat) {
                $riwayat->update(['tgl_keluar' => \Carbon\Carbon::now()->format('Y-m-d')]);
            }
            
            // Log new room history entry
            \App\Models\RiwayatKamar::create([
                'id_penghuni' => $penghuni->id,
                'id_kamar' => $request->id_kamar,
                'tgl_masuk' => \Carbon\Carbon::now()->format('Y-m-d'),
            ]);
        }

        $data = [
            'id_kamar' => $request->id_kamar,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tgl_masuk' => $request->tgl_masuk,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $penghuni->update($data);

        return redirect()->route('penghuni.index')->with('success', 'Data penghuni berhasil diperbarui.');
    }

    public function destroy(Penghuni $penghuni)
    {
        // Free room
        Kamar::find($penghuni->id_kamar)->update(['status' => 'Tersedia']);
        
        // Log Room History exit
        $riwayat = \App\Models\RiwayatKamar::where('id_penghuni', $penghuni->id)->whereNull('tgl_keluar')->latest()->first();
        if ($riwayat) {
            $riwayat->update(['tgl_keluar' => \Carbon\Carbon::now()->format('Y-m-d')]);
        }
        
        $penghuni->update(['status' => 'Keluar']);
        $penghuni->delete();

        return redirect()->route('penghuni.index')->with('success', 'Data penghuni berhasil dihapus. Anda bisa mengembalikannya dari menu Arsip.');
    }

    /**
     * Display trashed (soft-deleted) penghuni.
     */
    public function trashed()
    {
        $trashedPenghunis = Penghuni::onlyTrashed()->with('kamar')->latest('deleted_at')->get();
        return view('admin.penghuni.trashed', compact('trashedPenghunis'));
    }

    /**
     * Restore a soft-deleted penghuni.
     */
    public function restore($id)
    {
        $penghuni = Penghuni::onlyTrashed()->findOrFail($id);
        
        // Restore the record
        $penghuni->restore();

        return redirect()->route('penghuni.trashed')->with('success', 'Penghuni "' . $penghuni->nama . '" berhasil dikembalikan.');
    }

    /**
     * Permanently delete a soft-deleted penghuni.
     */
    public function forceDelete($id)
    {
        $penghuni = Penghuni::onlyTrashed()->findOrFail($id);
        $nama = $penghuni->nama;
        $penghuni->forceDelete();

        return redirect()->route('penghuni.trashed')->with('success', 'Data "' . $nama . '" telah dihapus permanen.');
    }
}
