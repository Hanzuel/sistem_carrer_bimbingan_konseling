@extends('layouts.app')

@section('title', 'Refleksi & Rencana Tindakan')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="card">
        <div class="border-b border-zinc-100 pb-5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700 ring-1 ring-inset ring-quest-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-quest-600"></span>
                Langkah 6 · Refleksi
            </span>
            <h1 class="mt-3 text-xl font-bold text-zinc-950 sm:text-2xl">
                Catatan Refleksi & Langkah Berikutnya
            </h1>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Rumuskan hal baru yang kamu pahami, pertanyaan lanjutan, dan langkah eksplorasi yang realistis.
            </p>
        </div>

        <form method="POST" action="{{ route('siswa.refleksi.simpan') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="new_learnings" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Hal Baru yang Dipelajari
                </label>
                <textarea
                    id="new_learnings"
                    name="new_learnings"
                    rows="3"
                    required
                    placeholder="Tuliskan wawasan baru tentang diri sendiri, profesi, atau jalur pendidikan..."
                    class="field"
                >{{ old('new_learnings', $reflection->new_learnings) }}</textarea>
            </div>

            <div>
                <label for="follow_up_questions" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Pertanyaan Lanjutan untuk Diskusi
                </label>
                <textarea
                    id="follow_up_questions"
                    name="follow_up_questions"
                    rows="2"
                    placeholder="Apa yang masih ingin kamu tanyakan kepada Guru BK atau narasumber lain?"
                    class="field"
                >{{ old('follow_up_questions', $reflection->follow_up_questions) }}</textarea>
            </div>

            <div>
                <label for="next_action" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Rencana Langkah Berikutnya
                </label>
                <textarea
                    id="next_action"
                    name="next_action"
                    rows="3"
                    required
                    placeholder="Contoh: berdiskusi dengan Guru BK pada sesi berikutnya atau mencari info jalur pendidikan..."
                    class="field"
                >{{ old('next_action', $reflection->next_action) }}</textarea>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-100 pt-5">
                <a href="{{ route('siswa.tantangan-pilihan') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="M19 12H5"/>
                        <path d="m12 19-7-7 7-7"/>
                    </svg>
                    <span>Kembali ke Tantangan Pilihan</span>
                </a>
                <button type="submit" class="btn-primary">
                    <span>Simpan & Lanjut ke Penutup</span>
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