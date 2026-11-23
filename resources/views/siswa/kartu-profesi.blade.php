@extends('layouts.app')

@section('title', 'Kartu Profesi')

@section('content')
<div class="space-y-6">
    <div class="card flex flex-wrap items-center justify-between gap-4">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700 ring-1 ring-inset ring-quest-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-quest-600"></span>
                Langkah 3 · Kartu Profesi
            </span>
            <h1 class="mt-3 text-xl font-bold text-zinc-950 sm:text-2xl">
                Gambaran Ragam Profesi & Jalur Belajar
            </h1>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Pelajari tugas utama, lingkungan kerja, keterampilan umum, serta jalur pendidikan/pelatihan sebagai bahan eksplorasi.
            </p>
        </div>

        <a href="{{ route('siswa.misi') }}" class="btn-primary shrink-0">
            <span>Lanjut ke Misi Eksplorasi</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                <path d="M5 12h14"/>
                <path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @forelse ($cards as $card)
            <article class="card flex flex-col justify-between transition-colors duration-200 hover:border-zinc-300">
                <div>
                    <div class="flex items-start justify-between gap-3 border-b border-zinc-100 pb-3.5">
                        <h2 class="text-base font-bold text-zinc-950 sm:text-lg">
                            {{ $card->title }}
                        </h2>
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-quest-50 text-quest-600 ring-1 ring-inset ring-quest-200/70">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                        </span>
                    </div>

                    <dl class="mt-4 space-y-3 text-sm leading-relaxed text-zinc-700">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Tugas utama</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $card->duties }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Lingkungan kerja</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $card->work_environment }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Keterampilan umum</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $card->general_skills }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Jalur pendidikan/pelatihan</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $card->education_path }}</dd>
                        </div>
                    </dl>
                </div>

                @if ($card->reference_links)
                    <div class="mt-4 rounded-xl border border-zinc-100 bg-zinc-50/80 p-3 text-xs text-zinc-600">
                        <p class="font-semibold text-zinc-800">Sumber rujukan</p>
                        <div class="mt-1 whitespace-pre-line break-words leading-relaxed">{{ $card->reference_links }}</div>
                    </div>
                @endif
            </article>
        @empty
            <div class="card col-span-full flex flex-col items-center justify-center py-12 text-center">
                <div class="grid h-11 w-11 place-items-center rounded-full bg-zinc-100 text-zinc-500">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <p class="mt-3 text-sm font-medium text-zinc-900">Belum ada kartu profesi yang tersedia</p>
                <p class="mt-1 text-xs text-zinc-500">Silakan lanjut ke langkah berikutnya atau hubungi Guru BK.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection