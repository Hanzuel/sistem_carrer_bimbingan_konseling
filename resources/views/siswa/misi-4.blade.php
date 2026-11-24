@extends('layouts.app')

@section('title', 'Misi 4: Bandingkan Pilihan')

@section('content')
@php
    $profList = $selectedProfessions->values();
    $defaultTitleA = old('career_a_title', $quest->career_a_title ?? ($profList[0]->title ?? 'Alternatif Karier A'));
    $defaultTitleB = old('career_b_title', $quest->career_b_title ?? ($profList[1]->title ?? 'Alternatif Karier B'));
    $notesA = old('career_a_notes', $quest->career_a_notes ?? '');
    $notesB = old('career_b_notes', $quest->career_b_notes ?? '');
    $conclusion = old('comparison_conclusion', $quest->comparison_conclusion ?? '');
@endphp

<div class="space-y-6">
    <div class="card space-y-3">
        <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Misi 4 dari 5</p>
        <h1 class="text-2xl font-bold text-zinc-950">Bandingkan Pilihan Karier</h1>
        <p class="text-sm text-zinc-600">
            Bandingkan dua alternatif pilihan karier secara objektif dan reflektif—bukan untuk mencari pemenang mutlak, melainkan untuk melihat kecocokan dengan minat, jalur tempuh, dan kondisi dirimu.
        </p>

        @if ($selectedProfessions->isNotEmpty())
            <div class="rounded-xl border border-zinc-200 bg-paper p-3.5 text-xs text-zinc-700">
                <span class="font-semibold text-zinc-950">Profesi yang kamu pilih di Misi 2:</span>
                <div class="mt-1.5 flex flex-wrap gap-2">
                    @foreach ($selectedProfessions as $prof)
                        <span class="rounded-md border border-quest-200 bg-quest-50 px-2.5 py-1 font-medium text-quest-700">
                            {{ $prof->title }} ({{ $prof->category }})
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($quest->selected_education_paths))
            <div class="rounded-xl border border-zinc-200 bg-paper p-3.5 text-xs text-zinc-700">
                <span class="font-semibold text-zinc-950">Jalur pendidikan/pelatihan yang kamu pertimbangkan di Misi 3:</span>
                <ul class="mt-1 list-disc space-y-0.5 pl-4 text-zinc-600">
                    @foreach ($quest->selected_education_paths as $path)
                        <li>{{ $path }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <form action="{{ route('siswa.misi-4.simpan') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Alternatif A --}}
            <div class="card space-y-4">
                <div class="flex items-center justify-between">
                    <span class="rounded-md bg-quest-50 px-2.5 py-1 text-xs font-semibold text-quest-700">
                        Alternatif A
                    </span>
                </div>

                <div>
                    <label for="career_a_title" class="mb-1 block text-xs font-semibold text-zinc-800">
                        Nama / Judul Pilihan A
                    </label>
                    <input
                        id="career_a_title"
                        type="text"
                        name="career_a_title"
                        value="{{ $defaultTitleA }}"
                        required
                        class="field"
                        placeholder="Contoh: UI/UX Designer atau Perawat"
                    >
                </div>

                <div>
                    <label for="career_a_notes" class="mb-1 block text-xs font-semibold text-zinc-800">
                        Catatan Pertimbangan Pilihan A (daya tarik, tantangan, jalur & kesiapan diri)
                    </label>
                    <textarea
                        id="career_a_notes"
                        name="career_a_notes"
                        rows="5"
                        required
                        class="field"
                        placeholder="Tuliskan hal yang kamu sukai dari pilihan ini, tantangan yang mungkin muncul, serta kesiapanmu..."
                    >{{ $notesA }}</textarea>
                </div>
            </div>

            {{-- Alternatif B --}}
            <div class="card space-y-4">
                <div class="flex items-center justify-between">
                    <span class="rounded-md bg-sun-50 px-2.5 py-1 text-xs font-semibold text-sun-700">
                        Alternatif B
                    </span>
                </div>

                <div>
                    <label for="career_b_title" class="mb-1 block text-xs font-semibold text-zinc-800">
                        Nama / Judul Pilihan B
                    </label>
                    <input
                        id="career_b_title"
                        type="text"
                        name="career_b_title"
                        value="{{ $defaultTitleB }}"
                        required
                        class="field"
                        placeholder="Contoh: Analis Data atau Wirausaha Kuliner"
                    >
                </div>

                <div>
                    <label for="career_b_notes" class="mb-1 block text-xs font-semibold text-zinc-800">
                        Catatan Pertimbangan Pilihan B (daya tarik, tantangan, jalur & kesiapan diri)
                    </label>
                    <textarea
                        id="career_b_notes"
                        name="career_b_notes"
                        rows="5"
                        required
                        class="field"
                        placeholder="Tuliskan hal yang kamu sukai dari pilihan ini, tantangan yang mungkin muncul, serta kesiapanmu..."
                    >{{ $notesB }}</textarea>
                </div>
            </div>
        </div>

        <div class="card space-y-3">
            <label for="comparison_conclusion" class="block text-sm font-bold text-zinc-950">
                Kesimpulan Sementara Perbandingan
            </label>
            <p class="text-xs text-zinc-600">
                Setelah membandingkan keduanya, poin apa yang paling menonjol untuk kamu diskusikan atau pelajari lebih lanjut?
            </p>
            <textarea
                id="comparison_conclusion"
                name="comparison_conclusion"
                rows="4"
                required
                class="field"
                placeholder="Tuliskan kesimpulan reflektifmu di sini..."
            >{{ $conclusion }}</textarea>
        </div>

        <div class="card flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('siswa.misi-3') }}" class="btn-secondary">
                Kembali ke Misi 3
            </a>
            <button type="submit" class="btn-accent">
                Simpan Perbandingan & Lanjut ke Misi 5
            </button>
        </div>
    </form>
</div>
@endsection