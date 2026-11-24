@extends('layouts.app')

@section('title', 'Misi 1: Kenali Diri')

@section('content')
@php
    $activityPresets = [
        'Membaca & menulis',
        'Menggambar / desain visual',
        'Coding / logika / angka',
        'Berdiskusi & membantu teman',
        'Eksperimen / merakit alat',
        'Organisasi / memimpin kegiatan',
        'Olahraga & aktivitas fisik',
        'Musik / seni pertunjukan',
    ];

    $subjectPresets = [
        'Matematika',
        'Bahasa Indonesia',
        'Bahasa Inggris',
        'Informatika / TIK',
        'Fisika',
        'Kimia',
        'Biologi',
        'Ekonomi / Akuntansi',
        'Sosiologi / Sejarah',
        'Seni Budaya / Prakarya',
    ];

    $environmentPresets = [
        'Tenang dan mandiri tanpa banyak gangguan',
        'Kolaboratif dan diskusi bersama teman',
        'Praktik langsung dengan alat/proyek nyata',
        'Fleksibel dan terstruktur dengan target jelas',
    ];

    $selectedActivities = old('activities', $quest->activities ?? []);
    $selectedSubjects = old('favorite_subjects', $quest->favorite_subjects ?? []);
    $selectedEnv = old('study_environment', $quest->study_environment ?? '');
    $customActivities = array_values(array_diff($selectedActivities, $activityPresets));
    $customSubjects = array_values(array_diff($selectedSubjects, $subjectPresets));
@endphp

<div class="mx-auto max-w-4xl space-y-6">
    <div class="card">
        <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Misi 1 dari 5</p>
        <h1 class="mt-1 text-2xl font-bold text-zinc-950">Kenali Diri</h1>
        <p class="mt-1 text-sm text-zinc-600">
            Isi bagian berikut secara jujur sebagai bahan refleksi awal sebelum menjelajahi ragam profesi.
        </p>
    </div>

    <form id="misi1Form" action="{{ route('siswa.misi-1.simpan') }}" method="POST" class="space-y-6">
        @csrf

        <div class="card space-y-4">
            <div>
                <h2 class="text-base font-bold text-zinc-950">1. Aktivitas yang Paling Kamu Nikmati (Pilih 1–3)</h2>
                <p class="mt-0.5 text-xs text-zinc-500">Centang pilihan yang paling sesuai atau tambahkan aktivitas lain di kolom khusus.</p>
            </div>

            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                @foreach ($activityPresets as $act)
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-zinc-200 p-3 text-sm transition hover:bg-zinc-50">
                        <input type="checkbox" name="activities[]" value="{{ $act }}"
                               {{ in_array($act, $selectedActivities) ? 'checked' : '' }}
                               class="rounded border-zinc-300 text-quest-600 focus:ring-quest-600">
                        <span>{{ $act }}</span>
                    </label>
                @endforeach
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-zinc-700">Aktivitas Lainnya (opsional, pisahkan dengan koma)</label>
                <input type="text" name="activities_custom"
                       value="{{ old('activities_custom', implode(', ', $customActivities)) }}"
                       placeholder="Contoh: Membuat video pendek, merakit PC"
                       class="field">
            </div>
        </div>

        <div class="card space-y-4">
            <div>
                <h2 class="text-base font-bold text-zinc-950">2. Mata Pelajaran yang Paling Memberi Energi (Pilih 1–5)</h2>
                <p class="mt-0.5 text-xs text-zinc-500">Pilih mata pelajaran yang paling sering kamu sukai atau mudah dipahami.</p>
            </div>

            <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
                @foreach ($subjectPresets as $subj)
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-zinc-200 p-3 text-sm transition hover:bg-zinc-50">
                        <input type="checkbox" name="favorite_subjects[]" value="{{ $subj }}"
                               {{ in_array($subj, $selectedSubjects) ? 'checked' : '' }}
                               class="rounded border-zinc-300 text-quest-600 focus:ring-quest-600">
                        <span>{{ $subj }}</span>
                    </label>
                @endforeach
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-zinc-700">Mata Pelajaran / Kejuruan Lainnya (opsional)</label>
                <input type="text" name="subjects_custom"
                       value="{{ old('subjects_custom', implode(', ', $customSubjects)) }}"
                       placeholder="Contoh: Desain Grafis, Pemrograman Dasar"
                       class="field">
            </div>
        </div>

        <div class="card space-y-4">
            <div>
                <h2 class="text-base font-bold text-zinc-950">3. Suasana Belajar yang Paling Nyaman Buatmu</h2>
                <p class="mt-0.5 text-xs text-zinc-500">Pilih satu yang paling menggambarkan kondisi belajarmu.</p>
            </div>

            <div class="space-y-2.5">
                @foreach ($environmentPresets as $env)
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-zinc-200 p-3 text-sm transition hover:bg-zinc-50">
                        <input type="radio" name="study_environment" value="{{ $env }}"
                               {{ $selectedEnv === $env ? 'checked' : '' }}
                               class="border-zinc-300 text-quest-600 focus:ring-quest-600">
                        <span>{{ $env }}</span>
                    </label>
                @endforeach
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-zinc-700">Atau tulis sendiri suasana belajar nyamannya:</label>
                <input type="text" name="study_environment_custom"
                       value="{{ old('study_environment_custom', !in_array($selectedEnv, $environmentPresets) ? $selectedEnv : '') }}"
                       placeholder="Contoh: Di ruangan ber-AC dengan musik pelan dan target harian"
                       class="field">
            </div>
        </div>

        <div class="card space-y-3">
            <h2 class="text-base font-bold text-zinc-950">4. Ceritakan Alasanmu Memilih Hal-Hal di Atas</h2>
            <p class="text-xs text-zinc-500">Mengapa aktivitas, mapel, dan suasana tersebut terasa cocok atau menarik bagimu?</p>
            <textarea name="self_reason" rows="4" required
                      placeholder="Tuliskan ceritamu di sini..."
                      class="field">{{ old('self_reason', $quest->self_reason) }}</textarea>
        </div>

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('siswa.beranda') }}" class="btn-secondary">
                Kembali ke Beranda
            </a>
            <button type="submit" class="btn-accent">
                Simpan & Lanjut ke Misi 2
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('misi1Form')?.addEventListener('submit', function () {
        const appendFn = window.CareerQuest?.appendCommaValuesAsHiddenInputs || function (form, input, fieldName) {
            if (!input || !input.value.trim()) return;
            input.value.split(',').map(v => v.trim()).filter(Boolean).forEach(val => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = fieldName;
                hidden.value = val;
                form.appendChild(hidden);
            });
        };

        appendFn(this, this.querySelector('input[name="activities_custom"]'), 'activities[]');
        appendFn(this, this.querySelector('input[name="favorite_subjects_custom"], input[name="subjects_custom"]'), 'favorite_subjects[]');

        const envCustom = this.querySelector('input[name="study_environment_custom"]');
        if (envCustom && envCustom.value.trim() !== '') {
            this.querySelectorAll('input[name="study_environment"]').forEach(r => r.checked = false);
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'study_environment';
            hidden.value = envCustom.value.trim();
            this.appendChild(hidden);
        }
    });
</script>
@endpush