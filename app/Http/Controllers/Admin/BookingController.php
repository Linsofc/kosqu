<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Booking::with('kamar')->latest();
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhereHas('kamar', function($k) use ($search) {
                          $k->where('nomor_kamar', 'like', "%{$search}%");
                      });
                });
            }
            
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }
            
            $bookings = $query->paginate(10);
            
            $baseQuery = Booking::query();
            if ($request->filled('search')) {
                $search = $request->search;
                $baseQuery->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhereHas('kamar', function($k) use ($search) {
                          $k->where('nomor_kamar', 'like', "%{$search}%");
                      });
                });
            }
            
            return response()->json([
                'html' => view('admin.booking._table_rows', compact('bookings'))->render(),
                'pagination' => (string) $bookings->links(),
                'total' => $baseQuery->count(),
                'pending' => (clone $baseQuery)->where('status', 'Pending')->count(),
                'confirmed' => (clone $baseQuery)->where('status', 'Dikonfirmasi')->count(),
                'cancelled' => (clone $baseQuery)->where('status', 'Dibatalkan')->count(),
            ]);
        }

        $bookings = Booking::with('kamar')->latest()->paginate(10);

        $totalBooking = Booking::count();
        $pendingBooking = Booking::where('status', 'Pending')->count();
        $confirmedBooking = Booking::where('status', 'Dikonfirmasi')->count();
        $cancelledBooking = Booking::where('status', 'Dibatalkan')->count();

        return view('admin.booking.index', compact(
            'bookings', 'totalBooking', 'pendingBooking', 'confirmedBooking', 'cancelledBooking'
        ));
    }

    public function create()
    {
        $kamars = Kamar::where('status', 'Tersedia')->orderBy('nomor_kamar')->get();
        return view('admin.booking.create', compact('kamars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kamar'          => 'required|exists:tb_kamar,id',
            'nama'              => 'required|string|max:100',
            'nik'               => 'required|string|size:16|unique:tb_booking,nik,NULL,id,status,Pending',
            'no_hp'             => 'required|string|max:15',
            'password'          => 'required|string|min:6',
            'tgl_rencana_masuk' => 'required|date|after_or_equal:today',
            'tempo_periode'     => 'required|integer|min:1',
            'jumlah_dp'         => 'required|numeric|min:0',
            'catatan'           => 'nullable|string|max:500',
        ]);

        $kamar = Kamar::findOrFail($request->id_kamar);

        if ($kamar->status !== 'Tersedia') {
            return back()->with('error', 'Kamar ini sudah tidak tersedia.')->withInput();
        }

        Booking::create([
            'id_kamar'          => $request->id_kamar,
            'nama'              => $request->nama,
            'nik'               => $request->nik,
            'no_hp'             => $request->no_hp,
            'password'          => $request->password,
            'tgl_booking'       => Carbon::now()->toDateString(),
            'tgl_rencana_masuk' => $request->tgl_rencana_masuk,
            'tempo_periode'     => $request->tempo_periode,
            'jumlah_dp'         => $request->jumlah_dp,
            'status_dp'         => 'Belum',
            'catatan'           => $request->catatan,
            'status'            => 'Pending',
        ]);

        // Set room status to Booking
        $kamar->update(['status' => 'Booking']);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat. Kamar ' . $kamar->nomor_kamar . ' telah direservasi.');
    }

    public function show(Booking $booking)
    {
        $booking->load('kamar');
        return view('admin.booking.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'Pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        $kamar = $booking->kamar;
        $bookingTempo = (int) $booking->tempo_periode ?: 1;
        $tglMasuk = Carbon::parse($booking->tgl_rencana_masuk);

        // Calculate total tagihan for this period
        $totalTagihan = $kamar->harga_sewa * $bookingTempo;
        $dpAmount = (int) $booking->jumlah_dp;

        // Determine tgl_jatuh_tempo based on whether DP covers full period
        if ($dpAmount >= $totalTagihan) {
            // Fully paid via DP → extend jatuh tempo
            $tglJatuhTempo = $tglMasuk->copy()->addMonths($bookingTempo)->format('Y-m-d');
        } else {
            // Partially paid (DP only) → jatuh tempo = tgl_rencana_masuk (deadline to pay full)
            $tglJatuhTempo = $tglMasuk->copy()->format('Y-m-d');
        }

        // Create Penghuni from booking data
        $penghuni = Penghuni::create([
            'id_kamar'        => $booking->id_kamar,
            'nama'            => $booking->nama,
            'nik'             => $booking->nik,
            'no_hp'           => $booking->no_hp,
            'password'        => $booking->password,
            'tgl_masuk'       => $booking->tgl_rencana_masuk,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'jumlah_tagihan'  => $kamar->harga_sewa,
            'tempo_periode'   => $bookingTempo,
            'status'          => 'Aktif',
        ]);

        $periodeKey = $tglMasuk->format('Y-m');

        // Create DP transaction (if DP > 0)
        if ($dpAmount > 0) {
            \App\Models\Transaksi::create([
                'id_penghuni'      => $penghuni->id,
                'bulan_tagihan'    => 'DP Booking',
                'periode_tagihan'  => $periodeKey,
                'jumlah_bayar'     => $dpAmount,
                'bukti_transfer'   => 'DP Booking (Dikonfirmasi Admin)',
                'tgl_bayar'        => Carbon::now()->toDateString(),
                'metode_bayar'     => 'Tunai',
                'status_validasi'  => 'Valid',
            ]);
        }

        // Update room status
        $kamar->update(['status' => 'Terisi']);

        // Log Room History
        \App\Models\RiwayatKamar::create([
            'id_penghuni' => $penghuni->id,
            'id_kamar'    => $booking->id_kamar,
            'tgl_masuk'   => $booking->tgl_rencana_masuk,
        ]);

        // Update booking status
        $booking->update([
            'status'    => 'Dikonfirmasi',
            'status_dp' => $dpAmount > 0 ? 'Lunas' : 'Belum',
        ]);

        // Send Booking Confirmation WA with login + DP details
        $fonnteService = new \App\Services\FonnteService();
        $fonnteService->sendBookingConfirmation($penghuni, $booking->password, $dpAmount, $totalTagihan, $booking->tgl_rencana_masuk);

        $sisaPelunasan = $totalTagihan - $dpAmount;
        $successMsg = "Booking dikonfirmasi! {$booking->nama} terdaftar di Kamar {$kamar->nomor_kamar}.";
        if ($sisaPelunasan > 0) {
            $successMsg .= " Sisa pelunasan: Rp " . number_format($sisaPelunasan, 0, ',', '.') . ".";
        } else {
            $successMsg .= " Pembayaran LUNAS.";
        }

        return redirect()->route('booking.index')->with('success', $successMsg);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'Pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        // Free the room
        $booking->kamar->update(['status' => 'Tersedia']);

        // Update booking status
        $booking->update(['status' => 'Dibatalkan']);

        return redirect()->route('booking.index')->with('success', 'Booking untuk ' . $booking->nama . ' telah dibatalkan. Kamar kembali tersedia.');
    }
}
