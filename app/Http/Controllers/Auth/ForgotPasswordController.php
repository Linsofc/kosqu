<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penghuni;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\FonnteService;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;
        
        $penghuni = Penghuni::where('nik', $identifier)->first();

        if (!$penghuni) {
            return back()->withErrors(['identifier' => 'Data penghuni dengan NIK tersebut tidak ditemukan.']);
        }

        if (!$penghuni->no_hp) {
            return back()->withErrors(['identifier' => 'Nomor WhatsApp belum terdaftar di akun ini.']);
        }

        // Generate temporary random password
        $newPassword = Str::random(8);

        // Update password (using model assignment because we have hashed cast)
        $penghuni->update([
            'password' => $newPassword
        ]);

        // Send via Fonnte
        $fonnteService = new FonnteService();
        $result = $fonnteService->sendResetPassword($penghuni, $newPassword);

        if ($result['success']) {
            return back()->with('success', 'Permintaan reset kata sandi sedang diproses. Silakan cek WhatsApp Anda dalam beberapa menit.');
        } else {
            return back()->withErrors(['identifier' => 'Gagal mengirim pesan WA: ' . $result['message']]);
        }
    }
}
