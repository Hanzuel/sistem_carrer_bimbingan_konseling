@extends('layouts.app')

@section('title', 'Misi 5: Refleksi & Penutup')

@section('content')
@php
    $selectedAction = old('weekly_action', $quest->weekly_action ?? '');
    $likertLabels = [
        1 => '1 — Sangat Tidak Setuju',
        2 => '2 — Tidak Setuju',
        3 => '3 — Setuju',
        4 => '4 — Sangat Setuju',
    ];
@endphp

<div class="mx-auto max-w-5xl space-y-6">
    <div class="card flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Misi 5 dari 5</p>
            <h1 class="mt-1 text-2xl font-bold text-zinc-950">Refleksi, Angket Tanggapan & Kirim Rekap</h1>
            <p class="mt-1 text-sm text-zinc-600">
                Lengkapi refleksi akhir, pilih langkah tindakan minggu ini, isi angket tanggapan media, lalu kirim rekap ke Guru BK.
            </p>
        </div>
        <div class="shrink-0 rounded-xl border border-zinc-200 bg-paper px-4 py-3 text-xs">
            <div class="text-zinc-500">Status Rekap</div>
            @if ($quest->is_completed)
                <div class="mt-0.5 font-semibold text-sage-700">
                    Sudah Dikirim {{ $quest->submitted_at ? '(' . $quest->submitted_at->format('d M Y H:i') . ')' : '' }}
                </div>
            @else
                <div class="mt-0.5 font-semibold text-sun-700">Belum Dikirim</div>
            @endif
        </div>
    </div>

    <form action="{{ route('siswa.misi-5.simpan') }}" method="POST" class="space-y-6">
        @csrf

        <div class="card space-y-5">
            <h2 class="text-base font-bold text-zinc-950">Bagian A: Refleksi & Langkah Minggu Ini</h2>

            <div>
                <label class="mb-1 block text-xs font-semibold text-zinc-700">1. Hal Baru yang Kamu Pelajari Tentang Diri & Pilihan Karier</label>
                <textarea name="learned_summary" rows="4" required
                          placeholder="Tuliskan insight atau hal penting yang kamu sadari selama mengerjakan Misi 1–4..."
                          class="field">{{ old('learned_summary', $quest->learned_summary) }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-zinc-700">2. Topik / Pertanyaan yang Ingin Didiskusikan dengan Guru BK</label>
                <textarea name="bk_discussion_topic" rows="3" required
                          placeholder="Contoh: Bagaimana menyesuaikan pilihan jalur kuliah/pelatihan dengan kondisi dan rencana belajarku?"
                          class="field">{{ old('bk_discussion_topic', $quest->bk_discussion_topic) }}</textarea>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold text-zinc-700">3. Tindakan Minggu Ini (Pilih Satu)</label>
                <div class="grid grid-cols-1 gap-2.5 md:grid-cols-3">
                    @foreach ($weeklyActions as $action)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-zinc-200 p-3 text-sm transition hover:bg-zinc-50">
                            <input type="radio" name="weekly_action" value="{{ $action }}"
                                   {{ $selectedAction === $action ? 'checked' : '' }}
                                   class="border-zinc-300 text-quest-600 focus:ring-quest-600">
                            <span>{{ $action }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card space-y-5">
            <div>
                <h2 class="text-base font-bold text-zinc-950">Bagian B: Angket Tanggapan Penggunaan Career Quest</h2>
                <p class="mt-0.5 text-xs text-zinc-500">
                    Berikan tanggapanmu secara jujur pada setiap pernyataan berikut (1 = Sangat Tidak Setuju, 2 = Tidak Setuju, 3 = Setuju, 4 = Sangat Setuju).
                </p>
            </div>

            <div class="space-y-4">
                @foreach ($evaluationQuestions as $num => $questionText)
                    @php
                        $fieldName = "q{$num}";
                        $currentVal = old($fieldName, $evaluation->{$fieldName} ?? null);
                    @endphp
                    <div class="space-y-2.5 rounded-xl border border-zinc-200 p-4">
                        <div class="text-sm font-medium text-zinc-950">
                            {{ $num }}. {{ $questionText }}
                        </div>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($likertLabels as $val => $label)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border p-2.5 text-xs transition {{ (string) $currentVal === (string) $val ? 'border-quest-600 bg-quest-50 font-semibold text-quest-900' : 'border-zinc-200 text-zinc-700 hover:bg-zinc-50' }}">
                                    <input type="radio" name="{{ $fieldName }}" value="{{ $val }}"
                                           {{ (string) $currentVal === (string) $val ? 'checked' : '' }}
                                           required
                                           class="border-zinc-300 text-quest-600 focus:ring-quest-600">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('siswa.misi-4') }}" class="btn-secondary">
                Kembali ke Misi 4
            </a>
            <button type="submit" class="btn-primary">
                Simpan Refleksi, Angket & Kirim Rekap
            </button>
        </div>
    </form>
</div>
@endsection