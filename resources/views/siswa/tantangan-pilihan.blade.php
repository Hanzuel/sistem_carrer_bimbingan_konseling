@extends('layouts.app')

@section('title', 'Tantangan Pilihan')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="card">
        <div class="border-b border-zinc-100 pb-5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-quest-50 px-3 py-1 text-xs font-semibold text-quest-700 ring-1 ring-inset ring-quest-200/80">
                <span class="h-1.5 w-1.5 rounded-full bg-quest-600"></span>
                Langkah 5 · Tantangan Pilihan
            </span>
            <h1 class="mt-3 text-xl font-bold text-zinc-950 sm:text-2xl">
                Bandingkan Dua Alternatif Karier
            </h1>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Bandingkan dua pilihan berdasarkan minat, mata pelajaran yang disukai, kegiatan kerja, serta hal yang masih perlu digali.
            </p>
        </div>

        <form method="POST" action="{{ route('siswa.tantangan-pilihan.simpan') }}" class="mt-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-quest-200/70 bg-quest-50/40 p-4">
                    <label for="option_one" class="mb-1.5 flex items-center gap-2 text-sm font-semibold text-zinc-950">
                        <span class="grid h-5 w-5 place-items-center rounded-md bg-quest-600 text-[11px] font-bold text-white">A</span>
                        <span>Pilihan Alternatif 1</span>
                    </label>
                    <input
                        id="option_one"
                        type="text"
                        name="option_one"
                        required
                        value="{{ old('option_one', $compared['option_one'] ?? '') }}"
                        placeholder="Contoh: Pengembang Perangkat Lunak"
                        class="field"
                    >
                </div>

                <div class="rounded-xl border border-sage-200/70 bg-sage-50/40 p-4">
                    <label for="option_two" class="mb-1.5 flex items-center gap-2 text-sm font-semibold text-zinc-950">
                        <span class="grid h-5 w-5 place-items-center rounded-md bg-sage-600 text-[11px] font-bold text-white">B</span>
                        <span>Pilihan Alternatif 2</span>
                    </label>
                    <input
                        id="option_two"
                        type="text"
                        name="option_two"
                        required
                        value="{{ old('option_two', $compared['option_two'] ?? '') }}"
                        placeholder="Contoh: Desainer Komunikasi Visual"
                        class="field"
                    >
                </div>
            </div>

            <div>
                <label for="comparison_notes" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Perbandingan Minat, Pelajaran Disukai & Kegiatan Kerja
                </label>
                <textarea
                    id="comparison_notes"
                    name="comparison_notes"
                    rows="4"
                    required
                    placeholder="Apa kemiripan atau perbedaan kedua pilihan ini menurut minat dan mata pelajaran yang kamu sukai?"
                    class="field"
                >{{ old('comparison_notes', $compared['comparison_notes'] ?? '') }}</textarea>
            </div>

            <div>
                <label for="unknown_aspects" class="mb-1.5 block text-sm font-medium text-zinc-900">
                    Hal yang Masih Perlu Diketahui
                </label>
                <textarea
                    id="unknown_aspects"
                    name="unknown_aspects"
                    rows="2"
                    placeholder="Contoh: jalur jurusan kuliah, biaya pelatihan, atau rutinitas kerja sehari-hari..."
                    class="field"
                >{{ old('unknown_aspects', $compared['unknown_aspects'] ?? '') }}</textarea>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-100 pt-5">
                <a href="{{ route('siswa.misi') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="M19 12H5"/>
                        <path d="m12 19-7-7 7-7"/>
                    </svg>
                    <span>Kembali ke Misi</span>
                </a>
                <button type="submit" class="btn-primary">
                    <span>Simpan & Lanjut ke Refleksi</span>
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