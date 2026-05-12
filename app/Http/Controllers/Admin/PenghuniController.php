<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenghuniController extends Controller
{
    public function index()
    {
        $penghunis = Penghuni::with('kamar')->get();
        return view('admin.penghuni.index', compact('penghunis'));
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
            'no_hp' => 'required|string|max:15',
            'tgl_masuk' => 'required|date',
            'password' => 'required|string|min:6',
        ]);

        // Note: Do NOT use Hash::make() here — the Penghuni model's
        // 'password' => 'hashed' cast handles hashing automatically.
        $penghuni = Penghuni::create([
            'id_kamar' => $request->id_kamar,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_jatuh_tempo' => $request->tgl_masuk,
            'password' => $request->password,
        ]);

        // Update room status
        Kamar::find($request->id_kamar)->update(['status' => 'Terisi']);

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
        } 
        // Logic if status changes back to Aktif
        elseif ($penghuni->status == 'Keluar' && $request->status == 'Aktif') {
            // Re-occupy the room
            Kamar::find($request->id_kamar)->update(['status' => 'Terisi']);
        }
        // If room changed while status is still Aktif
        elseif ($penghuni->status == 'Aktif' && $request->status == 'Aktif' && $penghuni->id_kamar != $request->id_kamar) {
            // Free old room
            Kamar::find($penghuni->id_kamar)->update(['status' => 'Tersedia']);
            // Occupy new room
            Kamar::find($request->id_kamar)->update(['status' => 'Terisi']);
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
        
        $penghuni->delete();

        return redirect()->route('penghuni.index')->with('success', 'Data penghuni berhasil dihapus.');
    }
}
