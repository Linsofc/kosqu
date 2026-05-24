<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Setting;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        $settings = Setting::getMany([
            'fonnte_token',
            'tempo_periode',
            'wa_reminder_enabled',
            'wa_reminder_days_before',
            'wa_reminder_template',
            'wa_overdue_template',
            'admin_phone',
        ]);

        $whatsappLogs = \App\Models\WhatsappLog::with('penghuni')->orderBy('created_at', 'desc')->limit(100)->get();

        return view('admin.settings.index', compact('admin', 'settings', 'whatsappLogs'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        $request->validate([
            'nama_admin' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:tb_admin,username,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password|current_password:admin',
            'new_password' => ['nullable', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $admin->nama_admin = $request->nama_admin;
        $admin->username = $request->username;

        if ($request->filled('new_password')) {
            // Jangan gunakan Hash::make() — model Admin sudah memiliki cast 'password' => 'hashed'
            $admin->password = $request->new_password;
        }

        $admin->save();

        return redirect()->back()->with('success', 'Pengaturan profil berhasil diperbarui.');
    }

    /**
     * Update tempo default settings.
     */
    public function updateTempo(Request $request)
    {
        $request->validate([
            'tempo_periode' => 'required|integer|min:1|max:24',
        ]);

        Setting::set('tempo_periode', $request->tempo_periode);

        if ($request->has('apply_to_all') && $request->apply_to_all == '1') {
            // Update semua penghuni aktif ke periode tempo baru
            $updated = Penghuni::where('status', 'Aktif')->update([
                'tempo_periode' => $request->tempo_periode,
            ]);
            return redirect()->route('settings.index', ['#tempo'])->with('success', "Tempo default diperbarui menjadi {$request->tempo_periode} bulan. {$updated} penghuni aktif telah ikut diperbarui.")->with('_tab', 'tempo');
        }

        return redirect()->route('settings.index', ['#tempo'])->with('success', "Tempo default berhasil diperbarui menjadi {$request->tempo_periode} bulan untuk penghuni baru.")->with('_tab', 'tempo');
    }

    /**
     * Update WhatsApp / Fonnte settings.
     */
    public function updateWhatsapp(Request $request)
    {
        $request->validate([
            'fonnte_token' => 'nullable|string|max:500',
            'admin_phone' => 'nullable|string|max:20',
            'wa_reminder_days_before' => 'required|integer|min:1|max:30',
            'wa_reminder_template' => 'required|string|max:1000',
            'wa_overdue_template' => 'required|string|max:1000',
        ]);

        Setting::set('fonnte_token', $request->fonnte_token);
        Setting::set('admin_phone', $request->admin_phone);
        Setting::set('wa_reminder_enabled', $request->has('wa_reminder_enabled') ? 'true' : 'false');
        Setting::set('wa_reminder_days_before', $request->wa_reminder_days_before);
        Setting::set('wa_reminder_template', $request->wa_reminder_template);
        Setting::set('wa_overdue_template', $request->wa_overdue_template);

        return redirect()->route('settings.index', ['#whatsapp'])->with('success', 'Pengaturan WhatsApp berhasil diperbarui.')->with('_tab', 'whatsapp');
    }

    /**
     * Send a test WhatsApp message.
     */
    public function testWhatsapp(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string|max:20',
        ]);

        $fonnte = new FonnteService();
        $result = $fonnte->sendMessage(
            $request->test_phone,
            '🔔 Ini adalah pesan test dari KOSQU Management System. Jika Anda menerima pesan ini, konfigurasi WhatsApp Fonnte telah berhasil! ✅'
        );

        if ($result['success']) {
            return redirect()->route('settings.index', ['#whatsapp'])->with('success', 'Pesan test berhasil dikirim ke ' . $request->test_phone)->with('_tab', 'whatsapp');
        }

        return redirect()->route('settings.index', ['#whatsapp'])->with('error', 'Gagal mengirim pesan: ' . $result['message'])->with('_tab', 'whatsapp');
    }
}
