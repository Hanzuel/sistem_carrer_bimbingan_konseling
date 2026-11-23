@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    {{-- 1. Header & Overview --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Administrasi Sistem</p>
        <h1 class="text-xl font-semibold mt-1">Ringkasan Data & Status Pengisian Career Quest</h1>
        <p class="text-sm text-slate-600 mt-1">
            Pantau ketersediaan kelas/sesi, kartu profesi, misi, akun pengguna, serta status pengisian peserta.
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Kelas / Sesi</p>
            <p class="text-2xl font-semibold mt-1">{{ $stats['class_groups'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Kartu Profesi</p>
            <p class="text-2xl font-semibold mt-1">{{ $stats['profession_cards'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Misi Eksplorasi</p>
            <p class="text-2xl font-semibold mt-1">{{ $stats['missions'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Akun BK</p>
            <p class="text-2xl font-semibold mt-1">{{ $stats['bk_users'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Peserta Siswa</p>
            <p class="text-2xl font-semibold mt-1">{{ $stats['students'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Form Tambah Kelas / Sesi --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Tambah Kelas / Kelompok Sesi</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Tambahkan kelas atau kelompok layanan BK sebelum mendaftarkan siswa.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.class-groups.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Nama Kelas / Sesi</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Contoh: Kelas XI IPA 1"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Keterangan (Opsional)</label>
                <input
                    type="text"
                    name="description"
                    value="{{ old('description') }}"
                    placeholder="Contoh: Sesi bimbingan karier semester ganjil"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            <div>
                <button type="submit" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition">
                    + Tambah Kelas
                </button>
            </div>
        </form>

        @if ($classGroups->isNotEmpty())
            <div class="pt-2 border-t border-slate-100 flex flex-wrap gap-2">
                @foreach ($classGroups as $group)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ $group->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 2. Form Tambah Akun Siswa / Guru BK --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Tambah Akun Siswa / Guru BK</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Buat akun baru untuk peserta didik (Siswa) maupun pendamping (Guru BK).
            </p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Peran (Role)</label>
                <select name="role" id="roleSelect" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                    <option value="siswa" @selected(old('role') === 'siswa')>Siswa (Murid)</option>
                    <option value="bk" @selected(old('role') === 'bk')>Guru BK</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Kode / ID Login</label>
                <input
                    type="text"
                    name="identifier"
                    value="{{ old('identifier') }}"
                    required
                    placeholder="Contoh: siswa02 / bk02"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            <div id="classGroupWrapper">
                <label class="block text-xs font-medium text-slate-700 mb-1">Kelas / Sesi (Siswa)</label>
                <select name="class_group_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($classGroups as $group)
                        <option value="{{ $group->id }}" @selected(old('class_group_id') == $group->id)>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Kata Sandi Awal</label>
                <input
                    type="text"
                    name="password"
                    value="{{ old('password', 'password123') }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            <div class="sm:col-span-2 lg:col-span-5 pt-1">
                <button type="submit" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition">
                    + Simpan Akun
                </button>
            </div>
        </form>
    </div>

    {{-- 3. Tabel Rekap Status Pengisian Siswa --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200">
            <h2 class="text-base font-semibold text-slate-900">Rekap Status Pengisian Siswa</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Kode / Nama</th>
                        <th class="px-4 py-3">Kelas / Sesi</th>
                        <th class="px-4 py-3">Misi Selesai</th>
                        <th class="px-4 py-3">Status Refleksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        @php
                            $doneCount = $student->missionProgresses->where('status', 'completed')->count();
                            $entry = $student->reflectionEntry;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">
                                {{ $student->name }} <span class="text-xs text-slate-500">({{ $student->identifier }})</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $student->classGroup->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $doneCount }} / {{ $totalMissions ?? 0 }}</td>
                            <td class="px-4 py-3 text-xs">
                                @if ($entry && $entry->shared_with_bk)
                                    <span class="px-2.5 py-1 rounded-full font-medium bg-sky-100 text-sky-800">Dibagikan ke BK</span>
                                @elseif ($entry && ($entry->new_learnings || $entry->next_action || $entry->interest_notes))
                                    <span class="px-2.5 py-1 rounded-full font-medium bg-amber-100 text-amber-800">Sudah Mengisi (Privat)</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full font-medium bg-slate-100 text-slate-600">Belum Mengisi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                Belum ada data peserta siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('roleSelect');
    const classGroupWrapper = document.getElementById('classGroupWrapper');

    function toggleClassGroup() {
        if (!roleSelect || !classGroupWrapper) return;
        classGroupWrapper.style.display = roleSelect.value === 'bk' ? 'none' : 'block';
    }

    roleSelect?.addEventListener('change', toggleClassGroup);
    toggleClassGroup();
</script>
@endsection