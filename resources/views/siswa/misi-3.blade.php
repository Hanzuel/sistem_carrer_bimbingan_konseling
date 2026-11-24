@extends('layouts.app')

@section('title', 'Misi 3: Cari Jalurnya')

@section('content')
@php
    $savedPaths = old('selected_education_paths', $quest->selected_education_paths ?? []);
    $customSavedPaths = array_values(array_diff($savedPaths, $educationOptions));
@endphp

<div class="mx-auto max-w-4xl space-y-6">
    <div class="card">
        <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Misi 3 dari 5</p>
        <h1 class="mt-1 text-2xl font-bold text-zinc-950">Cari Jalurnya</h1>
        <p class="mt-1 text-sm text-zinc-600">
            Pelajari gambaran jalur pendidikan atau pelatihan yang relevan dengan profesi incaranmu, lalu pilih jalur yang ingin kamu telusuri lebih jauh.
        </p>
    </div>

    @if ($selectedProfessions->isNotEmpty())
        <div class="card space-y-3">
            <h2 class="text-sm font-bold text-zinc-950">Profesi yang Kamu Pilih di Misi 2</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($selectedProfessions as $prof)
                    <div class="space-y-1.5 rounded-xl border border-zinc-200 bg-paper p-4">
                        <div class="text-xs font-semibold text-quest-600">{{ $prof->category }}</div>
                        <div class="text-sm font-bold text-zinc-950">{{ $prof->title }}</div>
                        <p class="text-xs leading-relaxed text-zinc-600">{{ $prof->education_paths }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card border-sun-200 bg-sun-50/70 text-xs text-zinc-800">
            Kamu belum memilih profesi di Misi 2. Kamu tetap bisa memilih jalur di bawah atau kembali ke
            <a href="{{ route('siswa.misi-2') }}" class="font-semibold text-quest-700 underline">Misi 2</a> terlebih dahulu.
        </div>
    @endif

    <form id="misi3Form" action="{{ route('siswa.misi-3.simpan') }}" method="POST" class="space-y-6">
        @csrf

        <div class="card space-y-4">
            <div>
                <h2 class="text-base font-bold text-zinc-950">Pilih Jalur Pendidikan / Pelatihan (Minimal 1)</h2>
                <p class="mt-0.5 text-xs text-zinc-500">Centang jalur yang sesuai dengan minat, kebutuhan waktu, dan rencana belajarmu.</p>
            </div>

            <div class="space-y-2.5">
                @foreach ($educationOptions as $opt)
                    <label class="flex cursor-pointer items-start gap-2.5 rounded-xl border border-zinc-200 p-3.5 text-sm transition hover:bg-zinc-50">
                        <input type="checkbox" name="selected_education_paths[]" value="{{ $opt }}"
                               {{ in_array($opt, $savedPaths) ? 'checked' : '' }}
                               class="mt-0.5 rounded border-zinc-300 text-quest-600 focus:ring-quest-600">
                        <span>{{ $opt }}</span>
                    </label>
                @endforeach
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-zinc-700">Jalur Lainnya (opsional, pisahkan dengan koma)</label>
                <input type="text" name="custom_paths"
                       value="{{ old('custom_paths', implode(', ', $customSavedPaths)) }}"
                       placeholder="Contoh: Kursus desain portofolio, Magang studio kreatif"
                       class="field">
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('siswa.misi-2') }}" class="btn-secondary">
                Kembali ke Misi 2
            </a>
            <button type="submit" class="btn-accent">
                Simpan & Lanjut ke Misi 4
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('misi3Form')?.addEventListener('submit', function () {
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

        appendFn(this, this.querySelector('input[name="custom_paths"]'), 'selected_education_paths[]');
    });
</script>
@endpush