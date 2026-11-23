<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'identifier' => $credentials['identifier'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            $request->session()->regenerate();

            return $this->redirectByRole($request->user()->role);
        }

        return back()->withErrors([
            'identifier' => 'Kode/ID pengguna atau kata sandi tidak sesuai, atau akun tidak aktif.',
        ])->onlyInput('identifier');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'bk' => redirect()->route('bk.dashboard'),
            'siswa' => redirect()->route('siswa.beranda'),
            default => redirect()->route('login'),
        };
    }
}