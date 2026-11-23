@extends('layouts.app')

@section('title', 'Dashboard Guru BK')

@section('content')
<div class="space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Layanan Bimbingan & Konseling</p>
        <h1 class="text-xl font-semibold mt-1">Pantauan Eksplorasi & Refleksi Siswa</h1>
        <p class="text-sm text-slate-600 mt-1">
            Gunakan data progres dan catatan refleksi yang dibagikan siswa sebagai bahan diskusi dan pendampingan—bukan label atau diagnosis[cite: 7, 14, 17].
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Peserta / Kode</th>
                        <th class="px-4 py-3">Kelas / Sesi</th>
                        <th class="px-4 py-3">Misi Selesai</th>
                        <th class="px-4 py-3">Status Refleksi</th>
                        <th class="px-4 py-3">Catatan Refleksi & Langkah Lanjut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        @php
                            $doneCount = $student->missionProgresses->where('status', 'completed')->count();
                            $entry = $student->reflectionEntry;
                        @endphp
                        <tr class="align-top">
                            <td class="px-4 py-3 font-medium text-slate-900">
                                {{ $student->name }}
                                <div class="text-xs text-slate-500">{{ $student->identifier }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $student->classGroup->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $doneCount > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $doneCount }} / {{ $totalMissions }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if ($entry && $entry->shared_with_bk)
                                    <span class="px-2.5 py-1 rounded-full font-medium bg-sky-100 text-sky-800">Dibagikan</span>
                                @elseif ($entry && ($entry->new_learnings || $entry->next_action || $entry->interest_notes))
                                    <span class="px-2.5 py-1 rounded-full font-medium bg-amber-100 text-amber-800">Draft / Belum Dibagikan</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full font-medium bg-slate-100 text-slate-600">Belum Mengisi</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-700 space-y-1">
                                @if ($entry && $entry->shared_with_bk)
                                    <div><span class="font-medium text-slate-900">Hal baru:</span> {{ $entry->new_learnings ?: '-' }}</div>
                                    <div><span class="font-medium text-slate-900">Pertanyaan:</span> {{ $entry->follow_up_questions ?: '-' }}</div>
                                    <div><span class="font-medium text-slate-900">Langkah lanjut:</span> {{ $entry->next_action ?: '-' }}</div>
                                @else
                                    <span class="text-slate-400">Catatan belum diserahkan untuk dibaca BK.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Belum ada data peserta siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection