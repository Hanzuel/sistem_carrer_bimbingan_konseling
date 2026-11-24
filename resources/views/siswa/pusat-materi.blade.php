@extends('layouts.app')

@section('title', 'Pusat Materi')

@section('content')
@php
    $categories = $materials->pluck('category')->filter()->unique()->values();
@endphp

<div class="space-y-8">
    {{-- Header Halaman --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Pusat Materi Siswa</p>
            <h1 class="text-2xl font-bold text-zinc-950 sm:text-3xl">Panduan & Bacaan Eksplorasi Karier</h1>
            <p class="mt-1 text-xs text-zinc-600 sm:text-sm">
                Pelajari modul panduan dari Guru BK untuk membantu mengenali diri, memilih profesi, membandingkan jalur, dan menyiapkan diskusi karier.
            </p>
        </div>

        <a href="{{ route('siswa.beranda') }}" class="btn-secondary self-start sm:self-auto">
            ← Kembali ke Beranda
        </a>
    </div>

    {{-- Filter Kategori --}}
    @if ($categories->isNotEmpty())
        <div class="flex flex-wrap items-center gap-2" id="categoryFilterBar">
            <button
                type="button"
                data-filter="all"
                onclick="filterMaterials('all')"
                class="filter-btn rounded-full border border-quest-600 bg-quest-600 px-3.5 py-1.5 text-xs font-semibold text-white transition"
            >
                Semua Modul ({{ $materials->count() }})
            </button>

            @foreach ($categories as $category)
                <button
                    type="button"
                    data-filter="{{ $category }}"
                    onclick="filterMaterials(@js($category))"
                    class="filter-btn rounded-full border border-zinc-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-zinc-700 transition hover:border-quest-300"
                >
                    {{ $category }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- Grid Daftar Modul --}}
    @if ($materials->isNotEmpty())
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3" id="materialsGrid">
            @foreach ($materials as $material)
                @php
                    $typeMeta = match ($material->content_type) {
                        'file' => ['label' => 'Dokumen PDF', 'class' => 'border-sun-200 bg-sun-50 text-sun-800'],
                        'link' => ['label' => 'Tautan Luar', 'class' => 'border-zinc-200 bg-zinc-100 text-zinc-700'],
                        default => ['label' => 'Bacaan Teks', 'class' => 'border-quest-200 bg-quest-50 text-quest-700'],
                    };
                @endphp

                <div
                    data-category="{{ $material->category }}"
                    class="material-card card flex flex-col justify-between gap-4"
                >
                    <div class="space-y-2.5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="rounded-full border border-quest-200 bg-quest-50 px-2.5 py-0.5 text-[11px] font-semibold text-quest-700">
                                {{ $material->category }}
                            </span>
                            <span class="rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $typeMeta['class'] }}">
                                {{ $typeMeta['label'] }}
                            </span>
                        </div>

                        <h2 class="text-lg font-bold leading-snug text-zinc-950">
                            {{ $material->title }}
                        </h2>

                        <p class="text-xs leading-relaxed text-zinc-600 sm:text-sm">
                            {{ $material->summary }}
                        </p>
                    </div>

                    <div class="border-t border-zinc-100 pt-3 flex items-center justify-between gap-2">
                        @if ($material->content_type === 'text')
                            <button
                                type="button"
                                data-title="{{ $material->title }}"
                                data-category="{{ $material->category }}"
                                data-summary="{{ $material->summary }}"
                                data-body="{{ $material->body }}"
                                onclick="openMaterialModal(this)"
                                class="btn-accent w-full text-xs"
                            >
                                Baca Modul →
                            </button>
                        @elseif ($material->content_type === 'file' && $material->file_path)
                            <a
                                href="{{ asset('storage/' . $material->file_path) }}"
                                target="_blank"
                                rel="noopener"
                                class="btn-accent w-full text-xs"
                            >
                                Buka PDF Panduan ↗
                            </a>
                        @elseif ($material->content_type === 'link' && $material->external_url)
                            <a
                                href="{{ $material->external_url }}"
                                target="_blank"
                                rel="noopener"
                                class="btn-secondary w-full text-xs"
                            >
                                Kunjungi Tautan ↗
                            </a>
                        @else
                            <span class="text-xs text-zinc-400">Konten belum tersedia</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card py-10 text-center space-y-1.5">
            <p class="text-sm font-bold text-zinc-900">Belum ada modul yang diterbitkan di Pusat Materi.</p>
            <p class="text-xs text-zinc-500">Materi panduan dari Guru BK akan muncul di sini setelah diterbitkan.</p>
        </div>
    @endif

    {{-- Modal Bacaan untuk Modul Tipe Teks (Default hidden di awal) --}}
    <div
        id="materialModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/50 p-4"
        onclick="if (event.target === this) closeMaterialModal()"
    >
        <div class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl space-y-4">
            <div class="flex items-start justify-between gap-4 border-b border-zinc-100 pb-3">
                <div class="space-y-1">
                    <span id="modalCategory" class="inline-block rounded-full border border-quest-200 bg-quest-50 px-2.5 py-0.5 text-[11px] font-semibold text-quest-700"></span>
                    <h3 id="modalTitle" class="text-xl font-bold text-zinc-950"></h3>
                    <p id="modalSummary" class="text-xs text-zinc-500"></p>
                </div>

                <button
                    type="button"
                    onclick="closeMaterialModal()"
                    class="rounded-lg border border-zinc-200 bg-paper px-2.5 py-1 text-xs font-semibold text-zinc-600 hover:bg-zinc-100"
                >
                    Tutup ✕
                </button>
            </div>

            <div id="modalBody" class="whitespace-pre-line text-sm leading-relaxed text-zinc-700"></div>

            <div class="flex justify-end border-t border-zinc-100 pt-3">
                <button type="button" onclick="closeMaterialModal()" class="btn-accent text-xs">
                    Selesai Membaca
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openMaterialModal(btn) {
        const modal = document.getElementById('materialModal');
        if (!modal) return;

        document.getElementById('modalTitle').textContent = btn.dataset.title || '';
        document.getElementById('modalCategory').textContent = btn.dataset.category || '';
        document.getElementById('modalSummary').textContent = btn.dataset.summary || '';
        document.getElementById('modalBody').textContent = btn.dataset.body || '';

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMaterialModal() {
        const modal = document.getElementById('materialModal');
        if (!modal) return;

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function filterMaterials(category) {
        const cards = document.querySelectorAll('.material-card');
        cards.forEach((card) => {
            const match = category === 'all' || card.dataset.category === category;
            card.classList.toggle('hidden', !match);
        });

        const buttons = document.querySelectorAll('.filter-btn');
        buttons.forEach((btn) => {
            const isActive = btn.dataset.filter === category;
            btn.classList.toggle('border-quest-600', isActive);
            btn.classList.toggle('bg-quest-600', isActive);
            btn.classList.toggle('text-white', isActive);

            btn.classList.toggle('border-zinc-200', !isActive);
            btn.classList.toggle('bg-white', !isActive);
            btn.classList.toggle('text-zinc-700', !isActive);
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMaterialModal();
        }
    });
</script>
@endpush