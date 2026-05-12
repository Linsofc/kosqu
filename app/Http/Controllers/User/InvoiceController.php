<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $penghuni = Auth::guard('penghuni')->user();
        
        $invoices = Transaksi::where('id_penghuni', $penghuni->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.invoice.index', compact('penghuni', 'invoices'));
    }
}
