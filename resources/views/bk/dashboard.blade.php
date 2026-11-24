@extends('layouts.app')

@section('title', 'Dashboard Guru BK')

@section('content')
@php
    $materials = \App\Models\LearningMaterial::latest()->get();
    $publishedMaterialsCount = $materials->where('is_published', true)->count();
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-indigo-600">Pendampingan Bimbingan & Konseling</p>
            <h1 class="text-xl font-semibold mt-1 text-slate-900">Rekap Eksplorasi 5 Misi & Tanggapan Siswa</h1>
            <p class="text-sm text-slate-600 mt-1">
                Gunakan rekap berikut sebagai bahan diskusi dan pendampingan karier siswa—bukan sebagai alat diagnosis atau penentu karier mutlak.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a
                href="{{ route('bk.export.xlsx') }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs font-semibold text-emerald-800 transition hover:bg-emerald-100"
            >
                <svg class="h-4 w-4 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Cetak Excel (.xlsx)</span>
            </a>

            <a href="{{ route('bk.materials.index') }}" class="btn-accent text-xs">
                Kelola Modul Pusat Materi ({{ $materials->count() }})
            </a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Total Siswa</p>
            <p class="text-2xl font-semibold mt-1 text-slate-900">{{ $students->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Rekap Misi 5 Terkirim</p>
            <p class="text-2xl font-semibold mt-1 text-emerald-700">{{ $submittedCount }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Angket Terisi</p>
            <p class="text-2xl font-semibold mt-1 text-indigo-700">{{ $evaluatedCount }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Rata-Rata Angket (Skala 1–4)</p>
            <p class="text-2xl font-semibold mt-1 text-slate-900">{{ $avgScore ?? '-' }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 col-span-2 lg:col-span-1">
            <p class="text-xs text-slate-500">Modul Pusat Materi</p>
            <p class="text-2xl font-semibold mt-1 text-quest-700">{{ $publishedMaterialsCount }} <span class="text-xs font-normal text-slate-500">terbit</span></p>
        </div>
    </div>

    {{-- Daftar Rekap Per Siswa (Ringkas + Detail Saat Diklik) --}}
    <div class="space-y-3">
        @forelse ($students as $student)
            @php
                $quest = $student->studentQuest;
                $eval = $student->mediaEvaluation;

                $misiDone = 0;
                if ($quest) {
                    if (!empty($quest->activities) && !empty($quest->favorite_subjects) && !empty($quest->study_environment) && !empty($quest->self_reason)) {
                        $misiDone++;
                    }
                    if (!empty($quest->selected_profession_ids)) {
                        $misiDone++;
                    }
                    if (!empty($quest->selected_education_paths)) {
                        $misiDone++;
                    }
                    if (!empty($quest->career_a_title) && !empty($quest->career_b_title) && !empty($quest->comparison_conclusion)) {
                        $misiDone++;
                    }
                    if ($quest->is_completed) {
                        $misiDone++;
                    }
                }

                $chosenProfTitles = collect($quest->selected_profession_ids ?? [])
                    ->map(fn ($id) => $professionsById[$id] ?? null)
                    ->filter()
                    ->values()
                    ->all();

                $evalTotal = null;
                $evalAvg = null;
                if ($eval) {
                    $scores = [];
                    for ($i = 1; $i <= 10; $i++) {
                        if (!is_null($eval->{"q{$i}"})) {
                            $scores[] = (int) $eval->{"q{$i}"};
                        }
                    }
                    if (count($scores) > 0) {
                        $evalTotal = array_sum($scores);
                        $evalAvg = round($evalTotal / count($scores), 2);
                    }
                }

                $hasQuestData = $quest && ($misiDone > 0 || !empty($quest->learned_summary) || !empty($quest->bk_discussion_topic));
            @endphp

            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 space-y-4">
                {{-- Baris Ringkas --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <div class="space-y-0.5">
                        <h2 class="text-base font-semibold text-slate-900">
                            {{ $student->name }}
                            <span class="text-xs font-normal text-slate-500">({{ $student->identifier }})</span>
                        </h2>
                        <p class="text-xs text-slate-500">
                            Kelas/Sesi: {{ $student->classGroup->name ?? '-' }} • Progres: <strong>{{ $misiDone }}/5 Misi</strong>
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        @if ($quest && $quest->is_completed)
                            <span class="px-2.5 py-1 rounded-full font-medium bg-emerald-100 text-emerald-800">
                                Terkirim {{ $quest->submitted_at ? '• ' . $quest->submitted_at->format('d M Y H:i') : '' }}
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full font-medium bg-amber-100 text-amber-800">
                                Belum Dikirim
                            </span>
                        @endif

                        @if (!is_null($evalAvg))
                            <span class="px-2.5 py-1 rounded-full font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                Angket: {{ $evalAvg }}/4 ({{ $evalTotal }}/40)
                            </span>
                        @endif

                        <button
                            type="button"
                            onclick="toggleStudentDetail({{ $student->id }}, this)"
                            class="btn-secondary px-3 py-1.5 text-xs"
                        >
                            Lihat Detail
                        </button>
                    </div>
                </div>

                {{-- Detail 4 Kotak (Tersembunyi sampai tombol "Lihat Detail" diklik) --}}
                <div id="student-detail-{{ $student->id }}" class="hidden border-t border-slate-100 pt-4">
                    @if (!$hasQuestData)
                        <p class="text-sm text-slate-500">Siswa belum mengisi data eksplorasi misi.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-700 leading-relaxed">
                            <div class="bg-slate-50 rounded-xl p-3.5 space-y-1.5 border border-slate-200/70">
                                <div class="font-semibold text-slate-900">Misi 1 — Kenali Diri</div>
                                <div><span class="font-medium">Aktivitas:</span> {{ !empty($quest->activities) ? implode(', ', $quest->activities) : '-' }}</div>
                                <div><span class="font-medium">Mapel Disukai:</span> {{ !empty($quest->favorite_subjects) ? implode(', ', $quest->favorite_subjects) : '-' }}</div>
                                <div><span class="font-medium">Suasana Belajar:</span> {{ $quest->study_environment ?: '-' }}</div>
                                <div><span class="font-medium">Alasan:</span> {{ $quest->self_reason ?: '-' }}</div>
                            </div>

                            <div class="bg-slate-50 rounded-xl p-3.5 space-y-1.5 border border-slate-200/70">
                                <div class="font-semibold text-slate-900">Misi 2 & 3 — Profesi & Jalur</div>
                                <div><span class="font-medium">Profesi Dipilih:</span> {{ !empty($chosenProfTitles) ? implode(', ', $chosenProfTitles) : '-' }}</div>
                                <div><span class="font-medium">Jalur Dipilih:</span> {{ !empty($quest->selected_education_paths) ? implode('; ', $quest->selected_education_paths) : '-' }}</div>
                            </div>

                            <div class="bg-slate-50 rounded-xl p-3.5 space-y-1.5 border border-slate-200/70">
                                <div class="font-semibold text-slate-900">Misi 4 — Perbandingan Pilihan</div>
                                <div><span class="font-medium">Karier A ({{ $quest->career_a_title ?: '-' }}):</span> {{ $quest->career_a_notes ?: '-' }}</div>
                                <div><span class="font-medium">Karier B ({{ $quest->career_b_title ?: '-' }}):</span> {{ $quest->career_b_notes ?: '-' }}</div>
                                <div><span class="font-medium">Kesimpulan:</span> {{ $quest->comparison_conclusion ?: '-' }}</div>
                            </div>

                            <div class="bg-slate-50 rounded-xl p-3.5 space-y-1.5 border border-slate-200/70">
                                <div class="font-semibold text-slate-900">Misi 5 — Refleksi & Tindak Lanjut</div>
                                <div><span class="font-medium">Hal yang Dipelajari:</span> {{ $quest->learned_summary ?: '-' }}</div>
                                <div><span class="font-medium">Topik Diskusi BK:</span> {{ $quest->bk_discussion_topic ?: '-' }}</div>
                                <div><span class="font-medium">Tindakan Minggu Ini:</span> {{ $quest->weekly_action ?: '-' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-xl p-8 text-center text-sm text-slate-500">
                Belum ada akun siswa terdaftar.
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleStudentDetail(studentId, btn) {
        const panel = document.getElementById(`student-detail-${studentId}`);
        if (!panel) return;

        const isHidden = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !isHidden);
        btn.textContent = isHidden ? 'Tutup Detail' : 'Lihat Detail';
    }
</script>
@endpush