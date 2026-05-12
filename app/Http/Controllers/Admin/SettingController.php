<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        return view('admin.settings.index', compact('admin'));
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
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->back()->with('success', 'Pengaturan profil berhasil diperbarui.');
    }
}
