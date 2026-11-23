@extends('layouts.app')

@section('title', 'Beranda Siswa')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="card relative overflow-hidden">
        <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-quest-100/60 blur-2xl" aria-hidden="true"></div>

        <div class="relative">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700 ring-1 ring-inset ring-quest-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-quest-600"></span>
                Langkah 1 · Beranda & Panduan
            </span>

            <h1 class="mt-3 text-2xl font-bold text-zinc-950 sm:text-3xl">
                Selamat Datang di Career Quest
            </h1>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-zinc-600 sm:text-base">
                Media ini membantu kamu mengenali minat, menelusuri informasi profesi dan jalur pendidikan, membandingkan pilihan, serta menyusun refleksi secara bertahap bersama pendampingan Guru BK.
            </p>
        </div>

        <div class="mt-6 flex items-start gap-3.5 rounded-xl border border-sun-200/90 bg-sun-50/80 p-4 text-sm text-sun-900">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-5 w-5 shrink-0 text-sun-600" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 8v4.5M12 16h.01"/>
            </svg>
            <div>
                <p class="font-semibold text-zinc-950">Batasan Media</p>
                <p class="mt-1 leading-relaxed text-zinc-700">
                    Aplikasi ini bukan tes psikologi, bukan alat diagnosis, dan tidak menetapkan profesi tertentu untukmu. Hasil aktivitas dipakai sebagai bahan refleksi dan diskusi bersama Guru BK.
                </p>
            </div>
        </div>

        @php
            $progressPercent = $totalMissions > 0 ? min(100, round(($completedCount / $totalMissions) * 100)) : 0;
        @endphp

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-zinc-200/90 bg-zinc-50/70 p-4">
                <div class="flex items-center gap-2 text-xs font-medium text-zinc-500">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-quest-600" aria-hidden="true">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Kelas / Kelompok Sesi</span>
                </div>
                <p class="mt-1.5 font-display text-lg font-semibold text-zinc-950">
                    {{ $user->classGroup->name ?? 'Belum ditentukan' }}
                </p>
            </div>

            <div class="rounded-xl border border-zinc-200/90 bg-zinc-50/70 p-4">
                <div class="flex items-center justify-between gap-2 text-xs font-medium text-zinc-500">
                    <span class="flex items-center gap-2">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-sage-600" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>Progres Misi Selesai</span>
                    </span>
                    <span class="font-semibold text-quest-700">{{ $progressPercent }}%</span>
                </div>
                <p class="mt-1.5 font-display text-lg font-semibold text-zinc-950">
                    {{ $completedCount }} <span class="text-sm font-normal text-zinc-500">/ {{ $totalMissions }} Misi</span>
                </p>
                <div class="mt-2.5 h-1.5 w-full overflow-hidden rounded-full bg-zinc-200">
                    <div class="h-full rounded-full bg-gradient-to-r from-quest-500 to-quest-700 transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="mt-7 flex flex-wrap items-center justify-between gap-3 border-t border-zinc-100 pt-5">
            <p class="text-xs text-zinc-500">Siap memulai perjalanan eksplorasimu?</p>
            <a href="{{ route('siswa.kenali-diri') }}" class="btn-accent">
                <span>Mulai Eksplorasi: Kenali Diri</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                    <path d="M5 12h14"/>
                     <path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection