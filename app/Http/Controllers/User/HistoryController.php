<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $penghuni = Auth::guard('penghuni')->user();
        
        $activities = Aktivitas::where('id_penghuni', $penghuni->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.history.index', compact('penghuni', 'activities'));
    }
}
