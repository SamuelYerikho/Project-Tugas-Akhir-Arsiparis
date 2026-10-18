<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Masa berlaku cookie "Ingat sesi saya", dalam menit (8 jam)
    const REMEMBER_MAX_MINUTES = 480;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required'],
            'password' => ['required'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $dataLogin = [
            $field     => $credentials['login'],
            'password' => $credentials['password'],
        ];

        // true jika checkbox "Ingat sesi saya" dicentang
        $remember = $request->boolean('remember');

        // Batasi masa berlaku cookie remember jadi 8 jam (default Laravel jauh lebih lama).
        // Harus dipanggil SEBELUM attempt(), karena cookie dibuat saat attempt() berhasil.
        if ($remember) {
            Auth::guard()->setRememberDuration(self::REMEMBER_MAX_MINUTES);
        }

        if (Auth::attempt($dataLogin, $remember)) {
            $request->session()->regenerate();

            $name = Auth::user()->name ?? 'Pengguna';

            return redirect()->intended(route('dashboard.index'))
                ->with('success', "Selamat datang kembali, {$name}!");
        }

        return back()->withErrors([
            'login' => 'Email/Username atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        $name = Auth::user()->name ?? 'Pengguna';

        // Auth::logout() juga merotasi remember_token, jadi cookie remember
        // di perangkat ini otomatis tidak berlaku lagi.
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', "Sampai jumpa lagi, {$name}!");
    }
}