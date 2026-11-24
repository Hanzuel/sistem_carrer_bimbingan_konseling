<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : (schema_has_identifier_column() ? 'identifier' : 'username');

        $user = User::where($loginField, $credentials['identifier'])
            ->orWhere('email', $credentials['identifier'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('identifier', 'remember'))
                ->withErrors([
                    'identifier' => 'Kode / ID pengguna atau kata sandi tidak sesuai.',
                ]);
        }

        // Jika login dilakukan dari halaman /login (portal admin) dan user bukan admin, tetap izinkan atau arahkan sesuai role
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'bk' => redirect()->intended(route('bk.dashboard')),
            'siswa' => redirect()->intended(route('siswa.beranda')),
            default => redirect()->intended(route('home')),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Kamu telah keluar dari sesi.');
    }
}

if (! function_exists('schema_has_identifier_column')) {
    function schema_has_identifier_column(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasColumn('users', 'identifier');
    }
}