@extends('layouts.app')

@section('title', 'Kenali Diri')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="card">
        <div class="border-b border-zinc-100 pb-5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700 ring-1 ring-inset ring-quest-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-quest-600"></span>
                Langkah 2 · Kenali Diri
            </span>
            <h1 class="mt-3 text-xl font-bold text-zinc-950 sm:text-2xl">
                Refleksi Minat & Aktivitas
            </h1>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Tidak ada jawaban benar atau salah. Tuliskan aktivitas dan mata pelajaran yang membuatmu ingin tahu lebih jauh.
            </p>
        </div>

        <form method="POST" action="{{ route('siswa.kenali-diri.simpan') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="liked_activities_text" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Aktivitas yang Disukai <span class="font-normal text-zinc-500">(pisahkan dengan koma)</span>
                </label>
                <input
                    id="liked_activities_text"
                    type="text"
                    name="liked_activities_text"
                    value="{{ old('liked_activities_text', implode(', ', $reflection->liked_activities ?? [])) }}"
                    placeholder="Contoh: merancang desain, berdiskusi, menyusun rencana, mencoba hal baru"
                    class="field"
                >
            </div>

            <div>
                <label for="liked_subjects_text" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Mata Pelajaran yang Disukai <span class="font-normal text-zinc-500">(pisahkan dengan koma)</span>
                </label>
                <input
                    id="liked_subjects_text"
                    type="text"
                    name="liked_subjects_text"
                    value="{{ old('liked_subjects_text', implode(', ', $reflection->liked_subjects ?? [])) }}"
                    placeholder="Contoh: Bahasa Inggris, Matematika, Seni Budaya, Informatika"
                    class="field"
                >
            </div>

            <div>
                <label for="interest_notes" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Catatan Refleksi Minat & Kekuatan Diri
                </label>
                <textarea
                    id="interest_notes"
                    name="interest_notes"
                    rows="4"
                    placeholder="Ceritakan secara singkat aktivitas apa yang membuatmu bersemangat atau ingin kamu pahami..."
                    class="field"
                >{{ old('interest_notes', $reflection->interest_notes) }}</textarea>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-100 pt-5">
                <a href="{{ route('siswa.beranda') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="M19 12H5"/>
                        <path d="m12 19-7-7 7-7"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
                <button type="submit" class="btn-primary">
                    <span>Simpan & Lanjut ke Kartu Profesi</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection