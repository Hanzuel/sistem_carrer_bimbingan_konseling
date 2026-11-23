@extends('layouts.app')

@section('title', 'Tantangan Pilihan')

@section('content')
<div class="bg-white border border-slate-200 rounded-xl p-6">
    <div class="mb-6">
        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">5. Tantangan Pilihan</p>
        <h1 class="text-xl font-semibold mt-1">Bandingkan Dua Alternatif Karier</h1>
        <p class="text-sm text-slate-600 mt-1">
            Bandingkan dua pilihan berdasarkan minat, mata pelajaran yang disukai, kegiatan kerja, serta hal yang masih perlu digali.
        </p>
    </div>

    <form method="POST" action="{{ route('siswa.tantangan-pilihan.simpan') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pilihan Alternatif 1</label>
                <input
                    type="text"
                    name="option_one"
                    required
                    value="{{ old('option_one', $compared['option_one'] ?? '') }}"
                    placeholder="Contoh: Pengembang Perangkat Lunak"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pilihan Alternatif 2</label>
                <input
                    type="text"
                    name="option_two"
                    required
                    value="{{ old('option_two', $compared['option_two'] ?? '') }}"
                    placeholder="Contoh: Desainer Komunikasi Visual"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm"
                >
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Perbandingan Minat, Pelajaran Disukai & Kegiatan Kerja
            </label>
            <textarea
                name="comparison_notes"
                rows="4"
                required
                placeholder="Apa kemiripan atau perbedaan kedua pilihan ini menurut minat dan mata pelajaran yang kamu sukai?"
                class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm"
            >{{ old('comparison_notes', $compared['comparison_notes'] ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Hal yang Masih Perlu Diketahui
            </label>
            <textarea
                name="unknown_aspects"
                rows="2"
                placeholder="Contoh: jalur jurusan kuliah, biaya pelatihan, atau rutinitas kerja sehari-hari..."
                class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm"
            >{{ old('unknown_aspects', $compared['unknown_aspects'] ?? '') }}</textarea>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('siswa.misi') }}" class="text-sm text-slate-600 hover:underline">← Kembali ke Misi</a>
            <button type="submit" class="rounded-lg bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition">
                Simpan & Lanjut ke Refleksi →
            </button>
        </div>
    </form>
</div>
@endsection