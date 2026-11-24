@extends('layouts.app')

@section('title', 'Kelola Modul Pusat Materi')

@section('content')
<div class="space-y-8">
    {{-- Header Halaman --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Ruang Guru BK</p>
            <h1 class="text-2xl font-bold text-zinc-950 sm:text-3xl">Kelola Modul Pusat Materi</h1>
            <p class="mt-1 text-xs text-zinc-600 sm:text-sm">
                Tambahkan panduan bacaan, dokumen PDF, atau tautan pendukung yang akan tampil di Pusat Materi siswa.
            </p>
        </div>

        <a href="{{ route('bk.dashboard') }}" class="btn-secondary self-start sm:self-auto">
            ← Kembali ke Dashboard BK
        </a>
    </div>

    {{-- Notifikasi Status & Error --}}
    @if (session('status'))
        <div class="rounded-xl border border-sage-200 bg-sage-50 px-4 py-3 text-xs font-medium text-sage-800 sm:text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 sm:text-sm">
            <p class="font-semibold">Periksa kembali isian form:</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Modul Baru --}}
    <div class="card space-y-5">
        <div class="border-b border-zinc-100 pb-3">
            <h2 class="text-lg font-bold text-zinc-950">Tambah Modul Panduan Baru</h2>
            <p class="text-xs text-zinc-500">Pilih jenis konten agar kolom input yang sesuai muncul otomatis.</p>
        </div>

        <form method="POST" action="{{ route('bk.materials.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="title" class="mb-1 block text-xs font-semibold text-zinc-700">
                        Judul Modul <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Contoh: Panduan Membandingkan Pilihan Karier"
                        class="field"
                    >
                </div>

                <div>
                    <label for="category" class="mb-1 block text-xs font-semibold text-zinc-700">
                        Kategori / Tahap Misi <span class="text-red-500">*</span>
                    </label>
                    <select id="category" name="category" required class="field">
                        @php
                            $categories = [
                                'Umum',
                                'Kenali Diri (Misi 1)',
                                'Kartu Profesi (Misi 2)',
                                'Cari Jalurnya (Misi 3)',
                                'Bandingkan Pilihan (Misi 4)',
                                'Refleksi & Persiapan BK (Misi 5)',
                            ];
                        @endphp
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category', 'Umum') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="summary" class="mb-1 block text-xs font-semibold text-zinc-700">
                    Ringkasan Singkat <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="summary"
                    name="summary"
                    rows="2"
                    required
                    placeholder="Tuliskan ringkasan 1–2 kalimat agar siswa paham manfaat modul ini."
                    class="field"
                >{{ old('summary') }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="create_content_type" class="mb-1 block text-xs font-semibold text-zinc-700">
                        Jenis Konten <span class="text-red-500">*</span>
                    </label>
                    <select id="create_content_type" name="content_type" onchange="toggleContentInputs('create')" class="field">
                        <option value="text" @selected(old('content_type', 'text') === 'text')>Teks / Bacaan Langsung</option>
                        <option value="file" @selected(old('content_type') === 'file')>Dokumen PDF</option>
                        <option value="link" @selected(old('content_type') === 'link')>Tautan Eksternal (URL)</option>
                    </select>
                </div>

                <div class="flex items-end pb-1">
                    <label class="inline-flex cursor-pointer items-center gap-2.5 rounded-xl border border-zinc-200 bg-paper px-3.5 py-2.5 text-xs font-semibold text-zinc-800 w-full">
                        <input
                            type="checkbox"
                            name="is_published"
                            value="1"
                            @checked(old('is_published', true))
                            class="rounded border-zinc-300 text-quest-600 focus:ring-quest-600"
                        >
                        <span>Terbitkan langsung ke Pusat Materi Siswa</span>
                    </label>
                </div>
            </div>

            {{-- Input Khusus Tipe: text --}}
            <div id="create_field_text" class="space-y-1">
                <label for="create_body" class="mb-1 block text-xs font-semibold text-zinc-700">
                    Isi Modul / Bacaan Panduan <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="create_body"
                    name="body"
                    rows="5"
                    placeholder="Tulis isi materi panduan untuk dibaca siswa..."
                    class="field"
                >{{ old('body') }}</textarea>
            </div>

            {{-- Input Khusus Tipe: file --}}
            <div id="create_field_file" class="hidden space-y-1">
                <label for="create_file" class="mb-1 block text-xs font-semibold text-zinc-700">
                    Unggah File PDF (Maks. 5 MB) <span class="text-red-500">*</span>
                </label>
                <input
                    id="create_file"
                    type="file"
                    name="file"
                    accept="application/pdf,.pdf"
                    class="field file:mr-3 file:rounded-lg file:border-0 file:bg-quest-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-quest-700 hover:file:bg-quest-100"
                >
            </div>

            {{-- Input Khusus Tipe: link --}}
            <div id="create_field_link" class="hidden space-y-1">
                <label for="create_external_url" class="mb-1 block text-xs font-semibold text-zinc-700">
                    Tautan Eksternal <span class="text-red-500">*</span>
                </label>
                <input
                    id="create_external_url"
                    type="url"
                    name="external_url"
                    value="{{ old('external_url') }}"
                    placeholder="https://contoh.com/panduan-karier"
                    class="field"
                >
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-accent">
                    Simpan Modul
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Modul Tersedia --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-zinc-950">Daftar Modul ({{ $materials->count() }})</h2>
        </div>

        @forelse ($materials as $material)
            <div class="card space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full border border-quest-200 bg-quest-50 px-2.5 py-0.5 text-[11px] font-semibold text-quest-700">
                                {{ $material->category }}
                            </span>

                            @php
                                $typeBadge = match ($material->content_type) {
                                    'file' => ['label' => 'PDF', 'class' => 'border-sun-200 bg-sun-50 text-sun-800'],
                                    'link' => ['label' => 'Tautan', 'class' => 'border-zinc-200 bg-zinc-100 text-zinc-700'],
                                    default => ['label' => 'Teks', 'class' => 'border-quest-200 bg-quest-50/60 text-quest-800'],
                                };
                            @endphp
                            <span class="rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $typeBadge['class'] }}">
                                {{ $typeBadge['label'] }}
                            </span>

                            @if ($material->is_published)
                                <span class="rounded-full border border-sage-200 bg-sage-50 px-2.5 py-0.5 text-[11px] font-semibold text-sage-700">
                                    Terbit
                                </span>
                            @else
                                <span class="rounded-full border border-zinc-200 bg-zinc-50 px-2.5 py-0.5 text-[11px] font-medium text-zinc-500">
                                    Draf
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-zinc-950">{{ $material->title }}</h3>
                        <p class="text-xs leading-relaxed text-zinc-600 sm:text-sm">{{ $material->summary }}</p>

                        <div class="pt-1 text-xs text-zinc-500">
                            @if ($material->content_type === 'file' && $material->file_path)
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" rel="noopener" class="font-semibold text-quest-600 underline hover:text-quest-700">
                                    Lihat / Unduh Dokumen PDF ↗
                                </a>
                            @elseif ($material->content_type === 'link' && $material->external_url)
                                <a href="{{ $material->external_url }}" target="_blank" rel="noopener" class="font-semibold text-quest-600 underline hover:text-quest-700">
                                    {{ $material->external_url }} ↗
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button
                            type="button"
                            onclick="toggleEditMaterial({{ $material->id }}, this)"
                            class="btn-secondary px-3 py-1.5 text-xs"
                        >
                            Edit
                        </button>

                        <form
                            id="delete-form-{{ $material->id }}"
                            method="POST"
                            action="{{ route('bk.materials.destroy', $material) }}"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="button"
                                data-title="{{ $material->title }}"
                                onclick="openDeleteConfirm({{ $material->id }}, this.dataset.title)"
                                class="rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Form Edit Inline (Default hidden di awal) --}}
                <div id="edit-panel-{{ $material->id }}" class="hidden border-t border-zinc-200 pt-4">
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-xs text-amber-800">
                        <strong>Perhatian:</strong> Anda sedang mengubah modul <strong>"{{ $material->title }}"</strong>. Perubahan akan langsung memengaruhi tampilan materi di Pusat Materi Siswa.
                    </div>

                    <form
                        id="edit-form-{{ $material->id }}"
                        method="POST"
                        action="{{ route('bk.materials.update', $material) }}"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-zinc-700">Judul Modul</label>
                                <input type="text" name="title" value="{{ old('title', $material->title) }}" required class="field">
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-semibold text-zinc-700">Kategori / Tahap Misi</label>
                                <select name="category" required class="field">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat }}" @selected(old('category', $material->category) === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-zinc-700">Ringkasan Singkat</label>
                            <textarea name="summary" rows="2" required class="field">{{ old('summary', $material->summary) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-zinc-700">Jenis Konten</label>
                                <select
                                    id="edit_{{ $material->id }}_content_type"
                                    name="content_type"
                                    onchange="toggleContentInputs('edit_{{ $material->id }}')"
                                    class="field"
                                >
                                    <option value="text" @selected($material->content_type === 'text')>Teks / Bacaan Langsung</option>
                                    <option value="file" @selected($material->content_type === 'file')>Dokumen PDF</option>
                                    <option value="link" @selected($material->content_type === 'link')>Tautan Eksternal (URL)</option>
                                </select>
                            </div>

                            <div class="flex items-end pb-1">
                                <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-zinc-800">
                                    <input
                                        type="checkbox"
                                        name="is_published"
                                        value="1"
                                        @checked($material->is_published)
                                        class="rounded border-zinc-300 text-quest-600 focus:ring-quest-600"
                                    >
                                    <span>Terbitkan ke Pusat Materi Siswa</span>
                                </label>
                            </div>
                        </div>

                        <div id="edit_{{ $material->id }}_field_text" class="{{ $material->content_type === 'text' ? '' : 'hidden' }}">
                            <label class="mb-1 block text-xs font-semibold text-zinc-700">Isi Modul Teks</label>
                            <textarea name="body" rows="4" class="field">{{ $material->body }}</textarea>
                        </div>

                        <div id="edit_{{ $material->id }}_field_file" class="{{ $material->content_type === 'file' ? '' : 'hidden' }}">
                            <label class="mb-1 block text-xs font-semibold text-zinc-700">Ganti File PDF (Opsional jika sudah ada file)</label>
                            <input type="file" name="file" accept="application/pdf,.pdf" class="field">
                        </div>

                        <div id="edit_{{ $material->id }}_field_link" class="{{ $material->content_type === 'link' ? '' : 'hidden' }}">
                            <label class="mb-1 block text-xs font-semibold text-zinc-700">Tautan Eksternal</label>
                            <input type="url" name="external_url" value="{{ $material->external_url }}" class="field">
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEditMaterial({{ $material->id }})" class="btn-secondary text-xs">Batal</button>
                            <button
                                type="button"
                                data-title="{{ $material->title }}"
                                onclick="openSaveConfirm({{ $material->id }}, this.dataset.title)"
                                class="btn-accent text-xs"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="card text-center py-8">
                <p class="text-sm font-semibold text-zinc-800">Belum ada modul di Pusat Materi.</p>
                <p class="mt-1 text-xs text-zinc-500">Gunakan form di atas untuk menambahkan panduan pertama bagi siswa.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Konfirmasi Kustom (Simpan Edit & Hapus) --}}
<div
    id="actionConfirmModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-zinc-950/50 p-4 backdrop-blur-[2px]"
    role="dialog"
    aria-modal="true"
    aria-labelledby="actionModalTitle"
>
    <div class="w-full max-w-md rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl">
        <div class="space-y-2">
            <span
                id="actionModalBadge"
                class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-semibold"
            >
                Konfirmasi
            </span>
            <h3 id="actionModalTitle" class="text-lg font-bold text-zinc-950"></h3>
            <p id="actionModalDesc" class="text-xs leading-relaxed text-zinc-600 sm:text-sm"></p>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2.5">
            <button
                type="button"
                onclick="closeActionModal()"
                class="btn-secondary px-4 py-2 text-xs"
            >
                Batal
            </button>
            <button
                id="actionModalConfirmBtn"
                type="button"
                class="rounded-xl px-4 py-2 text-xs font-semibold text-white transition"
            >
                Lanjutkan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let pendingFormId = null;

    function toggleContentInputs(prefix) {
        const selectEl = document.getElementById(`${prefix}_content_type`);
        if (!selectEl) return;

        const val = selectEl.value;
        const textBox = document.getElementById(`${prefix}_field_text`);
        const fileBox = document.getElementById(`${prefix}_field_file`);
        const linkBox = document.getElementById(`${prefix}_field_link`);

        if (textBox) textBox.classList.toggle('hidden', val !== 'text');
        if (fileBox) fileBox.classList.toggle('hidden', val !== 'file');
        if (linkBox) linkBox.classList.toggle('hidden', val !== 'link');
    }

    function toggleEditMaterial(materialId, btn) {
        const panel = document.getElementById(`edit-panel-${materialId}`);
        if (!panel) return;

        const isOpening = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !isOpening);
        if (btn) {
            btn.textContent = isOpening ? 'Tutup Edit' : 'Edit';
        }
    }

    function closeEditMaterial(materialId) {
        const panel = document.getElementById(`edit-panel-${materialId}`);
        if (!panel) return;
        panel.classList.add('hidden');

        const card = panel.closest('.card');
        const editBtn = card?.querySelector('button[onclick^="toggleEditMaterial"]');
        if (editBtn) {
            editBtn.textContent = 'Edit';
        }
    }

    function showActionModal({ mode, title, desc, formId, confirmText }) {
        pendingFormId = formId;

        const modal = document.getElementById('actionConfirmModal');
        const badge = document.getElementById('actionModalBadge');
        const titleEl = document.getElementById('actionModalTitle');
        const descEl = document.getElementById('actionModalDesc');
        const confirmBtn = document.getElementById('actionModalConfirmBtn');

        titleEl.textContent = title;
        descEl.textContent = desc;
        confirmBtn.textContent = confirmText;

        if (mode === 'delete') {
            badge.textContent = 'Peringatan Penghapusan';
            badge.className = 'inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2.5 py-0.5 text-[11px] font-semibold text-red-700';
            confirmBtn.className = 'rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700';
        } else {
            badge.textContent = 'Konfirmasi Perubahan';
            badge.className = 'inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800';
            confirmBtn.className = 'rounded-xl bg-quest-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-quest-700';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeActionModal() {
        pendingFormId = null;
        const modal = document.getElementById('actionConfirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openSaveConfirm(materialId, materialTitle) {
        const form = document.getElementById(`edit-form-${materialId}`);
        if (!form) return;

        if (!form.reportValidity()) return;

        showActionModal({
            mode: 'edit',
            title: 'Simpan Perubahan Modul?',
            desc: `Perubahan pada "${materialTitle}" akan langsung tersimpan dan memengaruhi tampilan di Pusat Materi Siswa.`,
            formId: `edit-form-${materialId}`,
            confirmText: 'Ya, Simpan Perubahan',
        });
    }

    function openDeleteConfirm(materialId, materialTitle) {
        showActionModal({
            mode: 'delete',
            title: 'Hapus Modul Secara Permanen?',
            desc: `Modul "${materialTitle}" akan dihapus permanen dari sistem dan tidak lagi tampil di Pusat Materi Siswa.`,
            formId: `delete-form-${materialId}`,
            confirmText: 'Ya, Hapus Modul',
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleContentInputs('create');

        const confirmBtn = document.getElementById('actionModalConfirmBtn');
        const modal = document.getElementById('actionConfirmModal');

        confirmBtn?.addEventListener('click', () => {
            if (!pendingFormId) return;
            const targetForm = document.getElementById(pendingFormId);
            const formToSubmit = targetForm;
            closeActionModal();
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        modal?.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeActionModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeActionModal();
            }
        });
    });
</script>
@endpush