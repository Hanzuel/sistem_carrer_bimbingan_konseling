@extends('layouts.app')

@section('title', 'Penutup')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="card space-y-6">
        <div class="border-b border-zinc-100 pb-5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-50 px-3 py-1 text-xs font-semibold text-sage-700 ring-1 ring-inset ring-sage-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-sage-600"></span>
                Langkah 7 · Penutup
            </span>
            <h1 class="mt-3 text-xl font-bold text-zinc-950 sm:text-2xl">
                Ringkasan Sesi & Tindak Lanjut Bersama Guru BK
            </h1>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Eksplorasi karier bersifat bertahap dan dapat terus berkembang. Gunakan catatan refleksimu sebagai bahan diskusi bersama Guru BK.
            </p>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-zinc-50/70 p-5">
            <div class="flex items-center gap-2 text-sm font-semibold text-zinc-950">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-quest-600" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span>Ringkasan Catatan Refleksimu Saat Ini</span>
            </div>

            <dl class="mt-4 divide-y divide-zinc-200/80 text-sm">
                <div class="py-3 first:pt-0">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Hal baru dipelajari</dt>
                    <dd class="mt-1 leading-relaxed text-zinc-800">{{ $reflection->new_learnings ?? '-' }}</dd>
                </div>
                <div class="py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Pertanyaan lanjutan</dt>
                    <dd class="mt-1 leading-relaxed text-zinc-800">{{ $reflection->follow_up_questions ?? '-' }}</dd>
                </div>
                <div class="py-3 last:pb-0">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Langkah berikutnya</dt>
                    <dd class="mt-1 leading-relaxed text-zinc-800">{{ $reflection->next_action ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <form method="POST" action="{{ route('siswa.penutup.bagikan') }}" class="space-y-5 border-t border-zinc-100 pt-5">
            @csrf

            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-zinc-200 bg-white p-4 text-sm text-zinc-800 transition hover:border-quest-300 hover:bg-quest-50/30">
                <input
                    type="checkbox"
                    name="shared_with_bk"
                    value="1"
                    @checked($reflection->shared_with_bk)
                    class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-quest-600 focus:ring-quest-600/30"
                >
                <span class="leading-relaxed">
                    Saya menyerahkan/membagikan catatan refleksi ini secara sukarela untuk dibahas bersama Guru BK.
                </span>
            </label>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('siswa.refleksi') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="M19 12H5"/>
                        <path d="m12 19-7-7 7-7"/>
                    </svg>
                    <span>Kembali ke Refleksi</span>
                </a>
                <button type="submit" class="btn-accent">
                    <span>Simpan Preferensi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 