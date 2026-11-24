<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkController;
use App\Http\Controllers\BkExportController;
use App\Http\Controllers\CareerMaterialController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Beranda publik (bisa diakses tamu maupun user yang sudah login)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirect otomatis ke dashboard/beranda sesuai role user yang sudah login
Route::get('/dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'bk' => redirect()->route('bk.dashboard'),
        'siswa' => redirect()->route('siswa.beranda'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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
        Route::get('/export-rekap-xlsx', [BkExportController::class, 'exportRekapSiswa'])->name('export.xlsx');

        // Kelola Modul / Materi Pusat Materi oleh Guru BK
        Route::get('/materi', [CareerMaterialController::class, 'index'])->name('materials.index');
        Route::post('/materi', [CareerMaterialController::class, 'store'])->name('materials.store');
        Route::put('/materi/{material}', [CareerMaterialController::class, 'update'])->name('materials.update');
        Route::delete('/materi/{material}', [CareerMaterialController::class, 'destroy'])->name('materials.destroy');
    });

// Siswa (Alur 5 Misi + Pusat Materi)
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/beranda', [SiswaController::class, 'beranda'])->name('beranda');
        Route::get('/pusat-materi', [CareerMaterialController::class, 'siswaIndex'])->name('pusat-materi');

        // Misi 1: Kenali Diri
        Route::get('/misi-1', [SiswaController::class, 'misiSatu'])->name('misi-1');
        Route::post('/misi-1', [SiswaController::class, 'simpanMisiSatu'])->name('misi-1.simpan');

        // Misi 2: Kartu Profesi Interaktif
        Route::get('/misi-2', [SiswaController::class, 'misiDua'])->name('misi-2');
        Route::post('/misi-2/baca/{profession}', [SiswaController::class, 'tandaiDibaca'])->name('misi-2.baca');
        Route::post('/misi-2/autosave', [SiswaController::class, 'autoSaveMisiDua'])->name('misi-2.autosave');
        Route::post('/misi-2/simpan', [SiswaController::class, 'simpanMisiDua'])->name('misi-2.simpan');

        // Misi 3: Cari Jalurnya
        Route::get('/misi-3', [SiswaController::class, 'misiTiga'])->name('misi-3');
        Route::post('/misi-3', [SiswaController::class, 'simpanMisiTiga'])->name('misi-3.simpan');

        // Misi 4: Bandingkan Pilihan
        Route::get('/misi-4', [SiswaController::class, 'misiEmpat'])->name('misi-4');
        Route::post('/misi-4', [SiswaController::class, 'simpanMisiEmpat'])->name('misi-4.simpan');

        // Misi 5: Refleksi & Penutup + Angket 10 Butir + Kirim Rekap
        Route::get('/misi-5', [SiswaController::class, 'misiLima'])->name('misi-5');
        Route::post('/misi-5', [SiswaController::class, 'simpanMisiLima'])->name('misi-5.simpan');
    });