<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_type' => 'required|in:admin,user',
            'username_or_nik' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = $request->login_type;
        $credentials = [
            'password' => $request->password,
        ];

        if ($loginType === 'admin') {
            $credentials['username'] = $request->username_or_nik;
            if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
        } else {
            $credentials['nik'] = $request->username_or_nik;
            $credentials['status'] = 'Aktif'; // Ensure only active residents can login
            
            if (Auth::guard('penghuni')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/user/dashboard');
            }

            // Check if login failed because they moved out
            $penghuni = \App\Models\Penghuni::where('nik', $request->username_or_nik)->first();
            if ($penghuni && $penghuni->status === 'Keluar') {
                throw ValidationException::withMessages([
                    'username_or_nik' => ['Akun Anda sudah tidak aktif (Sudah Keluar). Silakan hubungi admin.'],
                ]);
            }
        }

        throw ValidationException::withMessages([
            'username_or_nik' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('penghuni')->check()) {
            Auth::guard('penghuni')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
