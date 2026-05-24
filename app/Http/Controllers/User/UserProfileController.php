<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth('penghuni')->user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Penghuni $user */
        $user = auth('penghuni')->user();

        $request->validate([
            'no_hp' => 'required|string|max:15',
            'current_password' => 'nullable|required_with:new_password|current_password:penghuni',
            'new_password' => ['nullable', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $user->no_hp = $request->no_hp;

        if ($request->filled('new_password')) {
            // Jangan gunakan Hash::make() karena model sudah mengatur cast 'password' => 'hashed'
            $user->password = $request->new_password;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
