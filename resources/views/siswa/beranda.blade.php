@extends('layouts.app')

@section('title', 'Beranda Siswa')

@section('content')
@php
    $studentName = auth()->user()->name ?? auth()->user()->identifier ?? 'Siswa';
    $className = auth()->user()->classGroup?->name ?? null;

    $misiCards = [
        [
            'no' => 'Misi 1',
            'title' => 'Kenali Diri',
            'desc' => 'Pilih 1–3 aktivitas favorit, mata pelajaran disukai, suasana belajar nyaman, serta alasan utamanya.',
            'route' => route('siswa.misi-1'),
            'done' => !empty($quest->activities) && !empty($quest->favorite_subjects) && !empty($quest->study_environment) && !empty($quest->self_reason),
        ],
        [
            'no' => 'Misi 2',
            'title' => 'Kartu Profesi',
            'desc' => 'Jelajahi kategori profesi, baca detail kartu, tandai dibaca, lalu pilih 1–2 profesi.',
            'route' => route('siswa.misi-2'),
            'done' => !empty($quest->selected_profession_ids),
        ],
        [
            'no' => 'Misi 3',
            'title' => 'Cari Jalurnya',
            'desc' => 'Pilih jalur pendidikan atau pelatihan yang relevan dengan rencana belajarmu.',
            'route' => route('siswa.misi-3'),
            'done' => !empty($quest->selected_education_paths),
        ],
        [
            'no' => 'Misi 4',
            'title' => 'Bandingkan Pilihan',
            'desc' => 'Bandingkan dua alternatif karier (Karier A vs Karier B) beserta kesimpulan pertimbanganmu.',
            'route' => route('siswa.misi-4'),
            'done' => !empty($quest->career_a_title) && !empty($quest->career_b_title) && !empty($quest->comparison_conclusion),
        ],
        [
            'no' => 'Misi 5',
            'title' => 'Refleksi & Penutup',
            'desc' => 'Isi refleksi, tindakan minggu ini, angket tanggapan, lalu kirim rekap ke Guru BK.',
            'route' => route('siswa.misi-5'),
            'done' => (bool) $quest->is_completed,
        ],
    ];

    $completedCount = collect($misiCards)->where('done', true)->count();
    $progressPercent = ($completedCount / count($misiCards)) * 100;
    $nextMisi = collect($misiCards)->firstWhere('done', false) ?? end($misiCards);
@endphp

<div class="space-y-6">
    {{-- Banner Sapaan Personal Siswa & Progres --}}
    <div class="rounded-2xl bg-quest-700 p-6 text-white shadow-sm space-y-5">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wider text-quest-200">
                    <span>Perjalanan Karierku</span>
                    @if ($className)
                        <span>·</span>
                        <span>{{ $className }}</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold sm:text-3xl">Halo, {{ $studentName }}.</h1>
                <p class="max-w-2xl text-sm leading-relaxed text-quest-100">
                    Kamu tidak harus punya semua jawaban sekarang. Ikuti tahapan misi secara runtut untuk mengenali minat, menjelajahi profesi, membandingkan pilihan, dan menyusun refleksi bersama Guru BK.
                </p>
            </div>

            <div class="shrink-0 rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-xs">
                <div class="text-quest-200">Status Pengiriman Rekap</div>
                @if ($quest->is_completed)
                    <div class="mt-0.5 font-semibold text-white">Terkirim ke Guru BK</div>
                @else
                    <div class="mt-0.5 font-semibold text-sun-200">Dalam Proses Eksplorasi</div>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-4 border-t border-white/15 pt-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex-1 space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-quest-100">Progres eksplorasi misi</span>
                    <span class="font-semibold text-white">{{ $completedCount }} dari {{ count($misiCards) }} misi selesai</span>
                </div>
                <div class="h-2.5 w-full overflow-hidden rounded-full bg-quest-950/50">
                    <div class="h-full rounded-full bg-white transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ $nextMisi['route'] }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-xs font-bold text-quest-800 transition hover:bg-quest-50">
                    {{ $quest->is_completed ? 'Tinjau Misi 5' : 'Lanjutkan ' . $nextMisi['no'] }} →
                </a>
                <a href="{{ route('siswa.pusat-materi') }}" class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/10 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-white/15">
                    Pusat Materi ({{ $totalMaterials ?? 0 }})
                </a>
            </div>
        </div>
    </div>

    {{-- Info Ringkas Referensi --}}
    <div class="card flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Ruang Eksplorasi Siswa</p>
            <h2 class="mt-0.5 text-lg font-bold text-zinc-950">Peta Alur 5 Misi Eksplorasi Karier</h2>
        </div>
        <div class="flex flex-wrap gap-3 text-xs text-zinc-600">
            <span class="rounded-lg border border-zinc-200 bg-paper px-3 py-1.5">
                Kartu Profesi Tersedia: <strong class="text-zinc-950">{{ $totalProfessions ?? 0 }}</strong>
            </span>
            <span class="rounded-lg border border-zinc-200 bg-paper px-3 py-1.5">
                Modul Pusat Materi: <strong class="text-zinc-950">{{ $totalMaterials ?? 0 }}</strong>
            </span>
        </div>
    </div>

    {{-- Grid 5 Misi --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($misiCards as $card)
            <div class="card flex flex-col justify-between">
                <div>
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <span class="text-xs font-bold text-quest-600">{{ $card['no'] }}</span>
                        @if ($card['done'])
                            <span class="rounded-full border border-sage-200 bg-sage-50 px-2.5 py-0.5 text-xs font-medium text-sage-700">
                                Selesai
                            </span>
                        @else
                            <span class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600">
                                Belum Selesai
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-950">{{ $card['title'] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ $card['desc'] }}</p>
                </div>
                <div class="mt-5">
                    <a href="{{ $card['route'] }}" class="btn-primary w-full">
                        Buka {{ $card['no'] }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Batas Media & Privasi --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="card border-sun-200 bg-sun-50/60">
            <h3 class="text-sm font-semibold text-zinc-950">Pernyataan Batas Media</h3>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-700">
                Career Quest adalah media pendukung layanan bimbingan dan konseling karier, bukan tes psikologi diagnostik dan bukan penentu karier mutlak. Hasil isian digunakan sebagai bahan refleksi serta diskusi bersama Guru BK.
            </p>
        </div>
        <div class="card">
            <h3 class="text-sm font-semibold text-zinc-950">Kontrol Privasi & Keamanan Data Siswa</h3>
            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                Data eksplorasi dan refleksi tersimpan pada akunmu untuk keperluan pendampingan bimbingan karier. Pastikan keluar (logout) setelah selesai menggunakan perangkat bersama.
            </p>
        </div>
    </div>
</div>
@endsection