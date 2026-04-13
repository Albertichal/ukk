<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password'   => 'required',
        ]);

        $identifier = $request->identifier;
        $password   = $request->password;

        // Jika identifier berupa angka → pakai nis, selain itu pakai username
        $field = ctype_digit($identifier) ? 'nis' : 'username';

        if (Auth::attempt([$field => $identifier, 'password' => $password])) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            return match ($role) {
                'admin'             => redirect()->route('admin.dashboard'),
                'penanggung_jawab'  => redirect()->route('pj.dashboard'),
                default             => redirect()->route('siswa.dashboard'),
            };
        }

        return back()->withErrors(['identifier' => 'NIS atau username salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
