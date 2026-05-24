<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class HelpCenterController extends Controller
{
    public function index()
    {
        $adminPhone = Setting::get('admin_phone', '6281234567890');
        
        // Clean the phone number just in case
        $adminPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (str_starts_with($adminPhone, '08')) {
            $adminPhone = '62' . substr($adminPhone, 1);
        }

        return view('user.help-center', compact('adminPhone'));
    }
}
