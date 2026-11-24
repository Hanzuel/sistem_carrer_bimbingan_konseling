@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
@php
    $loginAction = Route::has('login.post') ? route('login.post') : (Route::has('login') ? route('login') : null);
    $user = auth()->user();

    $roleLabel = match ($user?->role) {
        'admin' => 'Administrator',
        'bk' => 'Guru Bimbingan & Konseling',
        'siswa' => 'Siswa',
        default => 'Pengguna',
    };

    $primaryRoute = match ($user?->role) {
        'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : route('dashboard'),
        'bk' => Route::has('bk.dashboard') ? route('bk.dashboard') : route('dashboard'),
        'siswa' => Route::has('siswa.beranda') ? route('siswa.beranda') : route('dashboard'),
        default => route('dashboard'),
    };

    // Data dinamis jika siswa sudah login, atau data contoh saat guest
    $studentQuest = ($user && $user->role === 'siswa') ? $user->studentQuest : null;
    $showcaseName = $user?->name ?? 'Siswa Contoh';
    $showcaseIdentifier = $user?->identifier ?? 'siswa01';
    $showcaseInitial = strtoupper(mb_substr($showcaseName, 0, 1));

    $m1Done = !empty($studentQuest?->activities) && !empty($studentQuest?->favorite_subjects) && !empty($studentQuest?->study_environment) && !empty($studentQuest?->self_reason);
    $m2Done = !empty($studentQuest?->selected_profession_ids);
    $m3Done = !empty($studentQuest?->selected_education_paths);
    $m4Done = !empty($studentQuest?->career_a_title) && !empty($studentQuest?->career_b_title) && !empty($studentQuest?->comparison_conclusion);
    $m5Done = (bool) ($studentQuest?->is_completed ?? false);

    $isSiswaAuth = $user && $user->role === 'siswa';
    $completedSteps = $isSiswaAuth
        ? collect([$m1Done, $m2Done, $m3Done, $m4Done, $m5Done])->filter()->count()
        : 5;
    $progressPercent = ($completedSteps / 5) * 100;
    $statusRekapText = ($isSiswaAuth && !$m5Done) ? 'Dalam Proses Eksplorasi' : 'Terkirim ke Guru BK';
    $ctaLabel = ($isSiswaAuth && $m5Done) ? 'Tinjau Misi 5 →' : 'Buka Ruang Kerja →';
@endphp

<div class="space-y-10">
    {{-- BAGIAN 1: HERO + SAPAAN PERSONAL (SAAT LOGIN) / FORM MASUK SISWA & BK (SAAT GUEST) --}}
    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="{{ auth()->check() ? 'lg:col-span-12' : 'lg:col-span-7' }} space-y-5">
            <div class="card space-y-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="inline-flex items-center gap-2 rounded-full border border-quest-200 bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700">
                        <span class="h-2 w-2 rounded-full bg-quest-600"></span>
                        Media Bimbingan & Eksplorasi Karier
                    </div>

                    @auth
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-sage-200 bg-sage-50 px-3 py-1 text-xs font-semibold text-sage-700">
                            Masuk sebagai {{ $roleLabel }}
                        </span>
                    @endauth
                </div>

                @auth
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Selamat Datang Kembali</p>
                        <h1 class="text-3xl font-bold leading-tight text-zinc-950 sm:text-4xl">
                            Halo, {{ $user->name }}.
                        </h1>
                        <p class="max-w-3xl text-sm leading-relaxed text-zinc-600 sm:text-base">
                            @if ($user->role === 'siswa')
                                Kamu tidak harus punya semua jawaban sekarang. Ikuti tahapan misi secara runtut untuk mengenali minat, menjelajahi profesi, membandingkan pilihan, dan menyusun refleksi bersama Guru BK.
                            @elseif ($user->role === 'bk')
                                Pantau perkembangan 5 misi eksplorasi siswa, telaah topik diskusi BK, serta evaluasi umpan balik media secara terstruktur.
                            @else
                                Kelola data rombongan belajar, akun siswa dan Guru BK, serta kesiapan data referensi Career Quest.
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <a href="{{ $primaryRoute }}" class="btn-accent">
                            Lanjutkan ke {{ $user->role === 'siswa' ? 'Beranda Siswa' : 'Dashboard ' . $roleLabel }} →
                        </a>
                        @if ($user->role === 'siswa' && Route::has('siswa.pusat-materi'))
                            <a href="{{ route('siswa.pusat-materi') }}" class="btn-secondary">
                                Buka Pusat Materi
                            </a>
                        @endif
                    </div>
                @else
                    <div class="space-y-3">
                        <h1 class="text-3xl font-bold leading-tight text-zinc-950 sm:text-4xl">
                            Kenali Dirimu, Jelajahi Profesi, dan Rancang Langkah Karier dengan Reflektif
                        </h1>
                        <p class="text-sm leading-relaxed text-zinc-600 sm:text-base">
                            <strong>Career Quest</strong> mendampingi siswa melalui alur <strong>5 misi eksplorasi karier</strong>, <strong>Pusat Materi</strong>, dan <strong>rekap refleksi</strong> untuk didiskusikan bersama Guru BK.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-1 sm:grid-cols-3">
                        <div class="rounded-xl border border-zinc-200 bg-paper p-3.5">
                            <div class="text-xs font-bold text-quest-600">01–05 · Alur Misi</div>
                            <p class="mt-1 text-xs leading-relaxed text-zinc-600">Tahapan eksplorasi diri, profesi, jalur tempuh, hingga refleksi.</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-paper p-3.5">
                            <div class="text-xs font-bold text-sage-700">Pusat Materi</div>
                            <p class="mt-1 text-xs leading-relaxed text-zinc-600">Modul dan bacaan pendukung belajar mandiri kapan saja.</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-paper p-3.5">
                            <div class="text-xs font-bold text-sun-700">Pendampingan BK</div>
                            <p class="mt-1 text-xs leading-relaxed text-zinc-600">Rekap eksplorasi menjadi titik awal obrolan dengan Guru BK.</p>
                        </div>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Panel Login Siswa & Guru BK (Hanya tampil saat @guest) --}}
        @guest
            <div class="lg:col-span-5">
                <div class="card space-y-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Portal Siswa & Guru BK</p>
                        <h2 class="mt-1 text-xl font-bold text-zinc-950">Masuk ke Career Quest</h2>
                        <p class="mt-1 text-xs text-zinc-500">
                            Gunakan kode / ID akun Siswa atau Guru BK yang telah terdaftar.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="rounded-xl border border-sage-200 bg-sage-50 px-3.5 py-2.5 text-xs text-sage-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-3.5 py-2.5 text-xs text-red-700">
                            <ul class="list-disc space-y-0.5 pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($loginAction)
                        <form method="POST" action="{{ $loginAction }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="identifier" class="mb-1 block text-xs font-semibold text-zinc-700">
                                    Kode / ID Pengguna (Siswa / Guru BK)
                                </label>
                                <input
                                    id="identifier"
                                    type="text"
                                    name="identifier"
                                    value="{{ old('identifier') }}"
                                    required
                                    autofocus
                                    placeholder="Contoh: siswa01 atau bk01"
                                    class="field"
                                >
                            </div>

                            <div>
                                <label for="password" class="mb-1 block text-xs font-semibold text-zinc-700">
                                    Kata Sandi
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="Masukkan kata sandi"
                                    class="field"
                                >
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <label class="inline-flex cursor-pointer items-center gap-2 text-zinc-600">
                                    <input type="checkbox" name="remember" class="rounded border-zinc-300 text-quest-600 focus:ring-quest-600">
                                    <span>Ingat sesi saya</span>
                                </label>
                            </div>

                            <button type="submit" class="btn-accent w-full">
                                Masuk Sekarang
                            </button>
                        </form>
                    @endif

                    <div class="border-t border-zinc-100 pt-4">
                        <p class="mb-2.5 text-xs font-semibold text-zinc-700">Isi Cepat Akun Uji Coba</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <button type="button" onclick="fillDemo('siswa01', 'password123')"
                                    class="rounded-lg border border-zinc-200 bg-paper px-3 py-2 font-medium text-zinc-800 transition hover:border-quest-400 hover:bg-quest-50">
                                Siswa <span class="block text-[11px] font-normal text-zinc-500">siswa01</span>
                            </button>
                            <button type="button" onclick="fillDemo('bk01', 'password123')"
                                    class="rounded-lg border border-zinc-200 bg-paper px-3 py-2 font-medium text-zinc-800 transition hover:border-quest-400 hover:bg-quest-50">
                                Guru BK <span class="block text-[11px] font-normal text-zinc-500">bk01</span>
                            </button>
                        </div>

                        @if (Route::has('login'))
                            <div class="mt-3 text-center">
                                <a href="{{ route('login') }}" class="text-[11px] font-medium text-zinc-500 underline hover:text-zinc-800">
                                    Login Khusus Administrator
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endguest
    </div>

    {{-- BAGIAN 2: SHOWCASE OVERVIEW (PHONE MOCKUP DI MOBILE, LAPTOP MOCKUP DI DESKTOP) --}}
    <div class="space-y-3">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Tampilan Ruang Siswa</p>
                <h2 class="text-xl font-bold text-zinc-950">Pratinjau Antarmuka Career Quest</h2>
            </div>
            <p class="text-xs text-zinc-500">
                {{ $isSiswaAuth ? 'Menampilkan progres eksplorasi akunmu saat ini' : 'Gambaran halaman Beranda Siswa setelah masuk' }}
            </p>
        </div>

        {{-- ================= MODE MOBILE (< lg): PHONE MOCKUP ================= --}}
        <div class="lg:hidden py-2">
            <div class="mx-auto max-w-[375px] rounded-[44px] border-[10px] border-zinc-900 bg-white shadow-xl overflow-hidden">
                {{-- Top Status Bar & Dynamic Island --}}
                <div class="flex items-center justify-between bg-white px-6 pt-3 pb-2 text-[11px] font-semibold text-zinc-950">
                    <span>9:44</span>
                    <span class="h-5 w-24 rounded-full bg-zinc-950 inline-block"></span>
                    <div class="flex items-center gap-1 text-[10px]">
                        <span>•••</span>
                        <span>WiFi</span>
                        <span class="rounded-[3px] border border-zinc-900 px-1 py-[1px] text-[9px]">100</span>
                    </div>
                </div>

                {{-- App Top Bar --}}
                <div class="flex items-center justify-between border-b border-zinc-100 bg-white px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-zinc-950 text-[11px] font-bold text-white">
                            ↗
                        </span>
                        <span class="text-xs font-bold text-zinc-950">Career Quest</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-quest-100 text-[11px] font-bold text-quest-700">
                            {{ $showcaseInitial }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-lg border border-zinc-200 bg-white px-2 py-1 text-[10px] font-medium text-zinc-700">
                            <span>→|</span>
                            <span>Keluar</span>
                        </span>
                    </div>
                </div>

                {{-- Stepper Bar: Beranda | Langkah 1 dari 7 --}}
                <div class="border-b border-zinc-200 bg-white px-4 py-2.5 space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-zinc-950">Beranda</span>
                        <span class="text-zinc-400">Langkah 1 dari 7</span>
                    </div>
                    <div class="grid grid-cols-7 gap-1">
                        <span class="h-1 rounded-full bg-quest-600"></span>
                        <span class="h-1 rounded-full bg-zinc-200"></span>
                        <span class="h-1 rounded-full bg-zinc-200"></span>
                        <span class="h-1 rounded-full bg-zinc-200"></span>
                        <span class="h-1 rounded-full bg-zinc-200"></span>
                        <span class="h-1 rounded-full bg-zinc-200"></span>
                        <span class="h-1 rounded-full bg-zinc-200"></span>
                    </div>
                </div>

                {{-- Konten Mobile di Dalam Layar HP --}}
                <div class="bg-gradient-to-b from-quest-50/40 via-paper to-paper p-3.5 space-y-3.5">
                    <div class="rounded-2xl bg-quest-700 p-4 text-white space-y-3.5 shadow-sm">
                        <div class="space-y-1.5">
                            <div class="text-[10px] font-semibold uppercase tracking-wider text-quest-200">
                                PERJALANAN KARIERKU
                            </div>
                            <h3 class="text-2xl font-bold leading-tight">
                                Halo, {{ $showcaseName }}.
                            </h3>
                            <p class="text-xs leading-relaxed text-quest-100">
                                Kamu tidak harus punya semua jawaban sekarang. Ikuti tahapan misi secara runtut untuk mengenali minat, menjelajahi profesi, membandingkan pilihan, dan menyusun refleksi bersama Guru BK.
                            </p>
                        </div>

                        <div class="rounded-xl border border-white/15 bg-white/10 px-3 py-2.5 text-[11px]">
                            <div class="text-quest-200">Status Pengiriman Rekap</div>
                            <div class="mt-0.5 font-bold text-white">{{ $statusRekapText }}</div>
                        </div>

                        <div class="space-y-1.5 pt-0.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-quest-100">Progres eksplorasi misi</span>
                                <span class="font-bold text-white">{{ $completedSteps }} dari 5 misi selesai</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-quest-950/40">
                                <div class="h-full rounded-full bg-white" style="width: {{ $progressPercent }}%"></div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            @auth
                                <a href="{{ $primaryRoute }}" class="inline-flex items-center justify-center rounded-xl bg-white px-3.5 py-2 text-[11px] font-bold text-quest-800">
                                    {{ $ctaLabel }}
                                </a>
                            @else
                                <a href="#identifier" class="inline-flex items-center justify-center rounded-xl bg-white px-3.5 py-2 text-[11px] font-bold text-quest-800">
                                    Tinjau Misi 5 →
                                </a>
                            @endauth
                            <span class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/15 px-3 py-2 text-[11px] font-semibold text-white">
                                Pusat Materi (4)
                            </span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-zinc-200 bg-white p-3.5 space-y-1">
                        <div class="text-[9px] font-semibold uppercase tracking-wider text-quest-600">
                            RUANG EKSPLORASI SISWA
                        </div>
                        <div class="text-sm font-bold text-zinc-950">
                            Peta Alur 5 Misi Eksplorasi Karier
                        </div>
                    </div>
                </div>

                {{-- Mobile Safari Bottom Bar --}}
                <div class="border-t border-zinc-200 bg-zinc-50 px-4 pt-2.5 pb-2 space-y-2.5">
                    <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs text-zinc-700 shadow-2xs">
                        <span class="text-[11px] font-semibold text-zinc-500">AA</span>
                        <span class="inline-flex items-center gap-1 font-medium text-zinc-800">
                            <span>🔒</span>
                            <span>127.0.0.1</span>
                        </span>
                        <span class="text-sm text-zinc-600">↻</span>
                    </div>

                    <div class="flex items-center justify-between px-2 text-base text-quest-600">
                        <span>‹</span>
                        <span class="text-zinc-300">›</span>
                        <span>⎋</span>
                        <span>📖</span>
                        <span>❐</span>
                    </div>

                    <div class="mx-auto h-1 w-28 rounded-full bg-zinc-950"></div>
                </div>
            </div>
        </div>

        {{-- ================= MODE DESKTOP (>= lg): LAPTOP MOCKUP ================= --}}
        <div class="hidden lg:block relative mx-auto max-w-6xl pt-2">
            {{-- Floating Badge Kiri Atas --}}
            <div class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3.5 py-2 text-xs font-semibold text-zinc-800 shadow-sm absolute -left-3 top-28 z-10">
                <span class="h-2 w-2 rounded-full bg-quest-600"></span>
                Kenali dirimu · Jelajahi pilihan
            </div>

            {{-- Tutup / Bezel Layar Laptop --}}
            <div class="mx-auto max-w-5xl rounded-t-[26px] border-[10px] border-zinc-900 bg-zinc-900 shadow-xl">
                {{-- Kamera Webcam Tengah Atas --}}
                <div class="flex items-center justify-center pb-2">
                    <span class="h-2 w-2 rounded-full bg-zinc-700 ring-1 ring-zinc-600"></span>
                </div>

                {{-- Layar Dalam Laptop --}}
                <div class="overflow-hidden rounded-t-[14px] border border-zinc-800 bg-paper">
                    {{-- Browser Top Bar di Dalam Layar Laptop --}}
                    <div class="flex items-center justify-between border-b border-zinc-200 bg-white px-4 py-2">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="rounded-md border border-zinc-200 bg-paper px-3 py-0.5 text-[11px] font-medium text-zinc-500">
                            127.0.0.1:8000/siswa/beranda
                        </div>
                        <div class="w-10"></div>
                    </div>

                    {{-- Navbar Asli Career Quest --}}
                    <div class="border-b border-zinc-200 bg-white px-5 py-3">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-zinc-950 text-xs font-bold text-white">
                                    ↗
                                </span>
                                <span class="text-sm font-bold text-zinc-950">Career Quest</span>
                                <span class="border-l border-zinc-200 pl-2.5 text-xs text-zinc-500">Siswa</span>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="text-right leading-tight">
                                    <div class="text-xs font-semibold text-zinc-900">{{ $showcaseName }}</div>
                                    <div class="text-[10px] text-zinc-400">{{ $showcaseIdentifier }}</div>
                                </div>
                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-quest-100 text-xs font-bold text-quest-700">
                                    {{ $showcaseInitial }}
                                </div>
                                <span class="inline-flex items-center rounded-lg border border-zinc-200 bg-white px-2.5 py-1 text-[11px] font-medium text-zinc-600">
                                    Keluar
                                </span>
                            </div>
                        </div>

                        {{-- Stepper 1–7 --}}
                        <div class="mt-3 flex items-center gap-4 overflow-x-auto border-t border-zinc-100 pt-2.5 text-[11px]">
                            <div class="flex shrink-0 items-center gap-1.5 font-semibold text-zinc-950">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-quest-600 text-[10px] font-bold text-white">1</span>
                                <span>Beranda</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5 text-zinc-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-zinc-200 bg-paper text-[10px]">2</span>
                                <span>Misi 1: Kenali Diri</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5 text-zinc-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-zinc-200 bg-paper text-[10px]">3</span>
                                <span>Misi 2: Kartu Profesi</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5 text-zinc-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-zinc-200 bg-paper text-[10px]">4</span>
                                <span>Misi 3: Cari Jalurnya</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5 text-zinc-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-zinc-200 bg-paper text-[10px]">5</span>
                                <span>Misi 4: Bandingkan</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5 text-zinc-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-zinc-200 bg-paper text-[10px]">6</span>
                                <span>Misi 5: Refleksi</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5 text-zinc-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-zinc-200 bg-paper text-[10px]">7</span>
                                <span>Pusat Materi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Konten Utama di Layar Laptop --}}
                    <div class="p-6 space-y-5">
                        <div class="rounded-2xl bg-quest-700 p-6 text-white space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="space-y-1.5">
                                    <div class="text-[11px] font-semibold uppercase tracking-wider text-quest-200">
                                        PERJALANAN KARIERKU
                                    </div>
                                    <h3 class="text-3xl font-bold">
                                        Halo, {{ $showcaseName }}.
                                    </h3>
                                    <p class="max-w-2xl text-xs leading-relaxed text-quest-100">
                                        Kamu tidak harus punya semua jawaban sekarang. Ikuti tahapan misi secara runtut untuk mengenali minat, menjelajahi profesi, membandingkan pilihan, dan menyusun refleksi bersama Guru BK.
                                    </p>
                                </div>

                                <div class="shrink-0 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2.5 text-[11px]">
                                    <div class="text-quest-200">Status Pengiriman Rekap</div>
                                    <div class="mt-0.5 font-semibold text-white">{{ $statusRekapText }}</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-t border-white/15 pt-3.5">
                                <div class="flex-1 space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-quest-100">Progres eksplorasi misi</span>
                                        <span class="font-semibold">{{ $completedSteps }} dari 5 misi selesai</span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-quest-950/50">
                                        <div class="h-full rounded-full bg-white transition-all" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @auth
                                        <a href="{{ $primaryRoute }}" class="inline-flex items-center justify-center rounded-xl bg-white px-3.5 py-1.5 text-[11px] font-bold text-quest-800 transition hover:bg-quest-50">
                                            Buka Ruang Kerja →
                                        </a>
                                    @else
                                        <a href="#identifier" class="inline-flex items-center justify-center rounded-xl bg-white px-3.5 py-1.5 text-[11px] font-bold text-quest-800 transition hover:bg-quest-50">
                                            Mulai Eksplorasi →
                                        </a>
                                    @endauth
                                    <span class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/10 px-3 py-1.5 text-[11px] font-semibold text-white">
                                        Pusat Materi
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2.5 rounded-2xl border border-zinc-200 bg-white p-4">
                            <div>
                                <div class="text-[10px] font-semibold uppercase tracking-wider text-quest-600">RUANG EKSPLORASI SISWA</div>
                                <div class="text-base font-bold text-zinc-950">Peta Alur 5 Misi Eksplorasi Karier</div>
                            </div>
                            <div class="flex gap-2 text-[11px] text-zinc-600">
                                <span class="rounded-lg border border-zinc-200 bg-paper px-2.5 py-1">
                                    Kartu Profesi Tersedia: <strong class="text-zinc-950">6</strong>
                                </span>
                                <span class="rounded-lg border border-zinc-200 bg-paper px-2.5 py-1">
                                    Modul Pusat Materi: <strong class="text-zinc-950">4</strong>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3.5">
                            <div class="rounded-2xl border border-zinc-200 bg-white p-4 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-quest-600">Misi 1</span>
                                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-[10px] font-medium text-sage-700">
                                        {{ (!$isSiswaAuth || $m1Done) ? 'Selesai' : 'Belum Selesai' }}
                                    </span>
                                </div>
                                <div class="text-base font-bold text-zinc-950">Kenali Diri</div>
                                <p class="text-xs leading-relaxed text-zinc-600">Pilih 1–3 aktivitas favorit, mata pelajaran disukai, suasana belajar nyaman, serta alasan utamanya.</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-white p-4 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-quest-600">Misi 2</span>
                                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-[10px] font-medium text-sage-700">
                                        {{ (!$isSiswaAuth || $m2Done) ? 'Selesai' : 'Belum Selesai' }}
                                    </span>
                                </div>
                                <div class="text-base font-bold text-zinc-950">Kartu Profesi</div>
                                <p class="text-xs leading-relaxed text-zinc-600">Jelajahi kategori profesi, baca detail kartu, tandai dibaca, lalu pilih 1–2 profesi.</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-white p-4 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-quest-600">Misi 3</span>
                                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-[10px] font-medium text-sage-700">
                                        {{ (!$isSiswaAuth || $m3Done) ? 'Selesai' : 'Belum Selesai' }}
                                    </span>
                                </div>
                                <div class="text-base font-bold text-zinc-950">Cari Jalurnya</div>
                                <p class="text-xs leading-relaxed text-zinc-600">Pilih jalur pendidikan atau pelatihan yang relevan dengan rencana belajarmu.</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-white p-4 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-quest-600">Misi 4</span>
                                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-[10px] font-medium text-sage-700">
                                        {{ (!$isSiswaAuth || $m4Done) ? 'Selesai' : 'Belum Selesai' }}
                                    </span>
                                </div>
                                <div class="text-base font-bold text-zinc-950">Bandingkan Pilihan</div>
                                <p class="text-xs leading-relaxed text-zinc-600">Bandingkan dua alternatif karier (Karier A vs Karier B) beserta kesimpulan pertimbanganmu.</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-white p-4 space-y-1.5 col-span-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-quest-600">Misi 5</span>
                                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-[10px] font-medium text-sage-700">
                                        {{ (!$isSiswaAuth || $m5Done) ? 'Selesai' : 'Belum Selesai' }}
                                    </span>
                                </div>
                                <div class="text-base font-bold text-zinc-950">Refleksi & Penutup</div>
                                <p class="text-xs leading-relaxed text-zinc-600">Isi refleksi, tindakan minggu ini, angket tanggapan, lalu kirim rekap ke Guru BK.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Base / Keyboard Deck & Notch Bawah Laptop --}}
            <div class="mx-auto max-w-[1060px]">
                <div class="h-4 rounded-b-2xl border-x border-b border-zinc-300 bg-gradient-to-b from-zinc-200 to-zinc-300 shadow-md flex items-start justify-center">
                    <span class="h-1.5 w-24 rounded-b-md bg-zinc-400/80"></span>
                </div>
            </div>

            {{-- Floating Badge Kanan Bawah --}}
            <div class="inline-flex items-center gap-2 rounded-xl border border-sun-200 bg-sun-50 px-3.5 py-2 text-xs font-semibold text-sun-800 shadow-sm absolute right-2 bottom-6 z-10">
                Satu langkah kecil hari ini
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillDemo(identifier, password) {
        const idInput = document.getElementById('identifier');
        const pwInput = document.getElementById('password');
        if (idInput) idInput.value = identifier;
        if (pwInput) pwInput.value = password;
        idInput?.focus();
    }
</script>
@endpush