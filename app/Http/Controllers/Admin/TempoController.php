<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Setting;
use App\Services\FonnteService;
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
        if ($request->filled('status_tempo') && $request->status_tempo !== 'Semua') {
            if ($request->status_tempo == 'Terlambat') {
                $query->where('tgl_jatuh_tempo', '<', $now->toDateString());
            } elseif ($request->status_tempo == 'Mendatang') {
                $query->whereBetween('tgl_jatuh_tempo', [$now->toDateString(), $now->copy()->addDays(7)->toDateString()]);
            } elseif ($request->status_tempo == 'Aman') {
                $query->where('tgl_jatuh_tempo', '>', $now->copy()->addDays(7)->toDateString());
            }
        }

        $penghunis = $query->orderBy('tgl_jatuh_tempo', 'asc')->get();
        $defaultTempo = Setting::get('tempo_periode', '1');
        $fonnteConfigured = !empty(Setting::get('fonnte_token'));

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.tempo._table_rows', compact('penghunis', 'now', 'defaultTempo', 'fonnteConfigured'))->render(),
            ]);
        }

        return view('admin.tempo.index', compact('penghunis', 'now', 'defaultTempo', 'fonnteConfigured'));
    }

    public function updateTagihan(Request $request, Penghuni $penghuni)
    {
        $request->validate([
            'tgl_jatuh_tempo' => 'required|date',
            'jumlah_tagihan' => 'nullable|numeric|min:0',
            'tempo_periode' => 'required|integer|min:1|max:24',
        ]);

        $penghuni->update([
            'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
            'jumlah_tagihan' => $request->jumlah_tagihan,
            'tempo_periode' => $request->tempo_periode,
        ]);

        return redirect()->back()->with('success', 'Tagihan & tempo ' . $penghuni->nama . ' berhasil diperbarui.');
    }

    /**
     * Send a WhatsApp reminder to a specific penghuni via Fonnte.
     */
    public function sendReminder(Penghuni $penghuni)
    {
        $fonnte = new FonnteService();
        $result = $fonnte->sendReminder($penghuni);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Pengingat WhatsApp berhasil dikirim ke ' . $penghuni->nama);
        }

        return redirect()->back()->with('error', 'Gagal mengirim pengingat: ' . $result['message']);
    }

    /**
     * Send bulk reminders to all penghuni approaching due date.
     */
    public function sendBulkReminder()
    {
        $daysBefore = (int) Setting::get('wa_reminder_days_before', 7);
        $now = Carbon::now();

        // Get penghuni yang jatuh tempo dalam $daysBefore hari ke depan ATAU sudah lewat
        $penghunis = Penghuni::with('kamar')
            ->where('status', 'Aktif')
            ->where('tgl_jatuh_tempo', '<=', $now->copy()->addDays($daysBefore)->toDateString())
            ->get();

        if ($penghunis->isEmpty()) {
            return redirect()->back()->with('success', 'Tidak ada penghuni yang perlu diingatkan saat ini.');
        }

        $fonnte = new FonnteService();
        $sent = 0;
        $failed = 0;

        foreach ($penghunis as $penghuni) {
            $result = $fonnte->sendReminder($penghuni);
            if ($result['success']) {
                $sent++;
            } else {
                $failed++;
            }
        }

        $message = "Pengingat terkirim: {$sent} berhasil";
        if ($failed > 0) {
            $message .= ", {$failed} gagal";
        }

        return redirect()->back()->with('success', $message);
    }
}
