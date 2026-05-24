<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        // Get all announcements paginated or just all (the prompt says "melihat semua pengumuman")
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->paginate(20);
        
        return view('user.pengumuman.index', compact('pengumumans'));
    }
}
