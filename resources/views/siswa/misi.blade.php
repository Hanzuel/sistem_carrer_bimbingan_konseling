@extends('layouts.app')

@section('title', 'Misi Eksplorasi')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="card flex flex-wrap items-center justify-between gap-4">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700 ring-1 ring-inset ring-quest-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-quest-600"></span>
                Langkah 4 · Misi Eksplorasi
            </span>
            <h1 class="mt-3 text-xl font-bold text-zinc-950 sm:text-2xl">
                Aktivitas Bertahap Jelajah Karier
            </h1>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Catat temuan singkat dari setiap misi untuk melatih kebiasaan menggali informasi secara terarah.
            </p>
        </div>

        <a href="{{ route('siswa.tantangan-pilihan') }}" class="btn-primary shrink-0">
            <span>Lanjut ke Tantangan Pilihan</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                <path d="M5 12h14"/>
                <path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="space-y-4">
        @foreach ($missions as $mission)
            @php
                $progress = $progresses->get($mission->id);
                $isCompleted = $progress && $progress->status === 'completed';
            @endphp

            <div class="card {{ $isCompleted ? 'border-sage-200/90' : '' }}">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-start gap-3.5">
                        <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-lg text-xs font-bold {{ $isCompleted ? 'bg-sage-600 text-white' : 'bg-quest-50 text-quest-700 ring-1 ring-inset ring-quest-200' }}">
                            {{ $mission->step_order }}
                        </span>
                        <div>
                            <p class="text-xs font-medium text-zinc-500">Misi Langkah {{ $mission->step_order }}</p>
                            <h2 class="text-base font-bold text-zinc-950 sm:text-lg">{{ $mission->title }}</h2>
                            <p class="mt-1 text-sm leading-relaxed text-zinc-600">{{ $mission->instruction }}</p>
                        </div>
                    </div>

                    @if ($isCompleted)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-50 px-3 py-1 text-xs font-semibold text-sage-700 ring-1 ring-inset ring-sage-200">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span>
                            Belum selesai
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('siswa.misi.simpan', $mission) }}" class="mt-5 space-y-4 border-t border-zinc-100 pt-4">
                    @csrf

                    <div>
                        <label for="notes-{{ $mission->id }}" class="mb-1.5 block text-xs font-semibold text-zinc-800">
                            Catatan Hasil Misi / Temuan Singkat
                        </label>
                        <textarea
                            id="notes-{{ $mission->id }}"
                            name="notes"
                            rows="2"
                            required
                            placeholder="Tuliskan temuan atau catatan singkat dari misi ini..."
                            class="field"
                        >{{ old('notes', $progress->response_data['notes'] ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="source-link-{{ $mission->id }}" class="mb-1.5 block text-xs font-semibold text-zinc-800">
                            Tautan / Sumber Rujukan <span class="font-normal text-zinc-500">(opsional)</span>
                        </label>
                        <input
                            id="source-link-{{ $mission->id }}"
                            type="text"
                            name="source_link"
                            value="{{ old('source_link', $progress->response_data['source_link'] ?? '') }}"
                            placeholder="Contoh: https://pddikti.kemdiktisaintek.go.id"
                            class="field"
                        >
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="{{ $isCompleted ? 'btn-secondary !py-2 !text-xs' : 'btn-primary !py-2 !text-xs' }}">
                            {{ $isCompleted ? 'Perbarui Catatan Misi' : 'Tandai Misi Selesai' }}
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection