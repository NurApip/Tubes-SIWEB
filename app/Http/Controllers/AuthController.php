<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'hp' => 'required|string|max:15',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function showProfile()
    {
        return view('auth.profil');
    }

    public function updateProfile(Request $request)
    {
        // Menggunakan Auth facade agar tidak error undefined method
        $userId = Auth::id(); 
        $user = User::find($userId); 

        // Pastikan $user ditemukan sebelum lanjut
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Logika Simpan Foto
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }
            $path = $request->file('foto')->store('profil', 'public');
            $user->foto = $path;
        }

        // Update data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp; 

        $user->save(); 

        return redirect()->route('profil.edit')->with('status', 'Profil berhasil diperbarui!');
    }
}