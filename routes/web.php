<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'bk' => redirect()->route('bk.dashboard'),
        'siswa' => redirect()->route('siswa.beranda'),
        default => redirect()->route('login'),
    };
})->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin
// Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/class-groups', [AdminController::class, 'storeClassGroup'])->name('class-groups.store');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    });

// Guru BK
Route::middleware(['auth', 'role:bk'])
    ->prefix('bk')
    ->name('bk.')
    ->group(function () {
        Route::get('/dashboard', [BkController::class, 'dashboard'])->name('dashboard');
    });

// Siswa (7 Alur Inti Career Quest)
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/beranda', [SiswaController::class, 'beranda'])->name('beranda');
        Route::get('/kenali-diri', [SiswaController::class, 'kenaliDiri'])->name('kenali-diri');
        Route::post('/kenali-diri', [SiswaController::class, 'simpanKenaliDiri'])->name('kenali-diri.simpan');

        Route::get('/kartu-profesi', [SiswaController::class, 'kartuProfesi'])->name('kartu-profesi');

        Route::get('/misi', [SiswaController::class, 'misi'])->name('misi');
        Route::post('/misi/{mission}', [SiswaController::class, 'simpanMisi'])->name('misi.simpan');

        Route::get('/tantangan-pilihan', [SiswaController::class, 'tantanganPilihan'])->name('tantangan-pilihan');
        Route::post('/tantangan-pilihan', [SiswaController::class, 'simpanTantanganPilihan'])->name('tantangan-pilihan.simpan');

        Route::get('/refleksi', [SiswaController::class, 'refleksi'])->name('refleksi');
        Route::post('/refleksi', [SiswaController::class, 'simpanRefleksi'])->name('refleksi.simpan');

        Route::get('/penutup', [SiswaController::class, 'penutup'])->name('penutup');
        Route::post('/penutup/bagikan', [SiswaController::class, 'bagikanRefleksi'])->name('penutup.bagikan');
    });