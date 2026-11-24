@extends('layouts.app')

@section('title', 'Misi 2: Kartu Profesi')

@section('content')
@php
    $readIds = collect($quest->read_profession_ids ?? [])->map(fn ($id) => (int) $id)->all();
    $selectedIds = collect(old('selected_profession_ids', $quest->selected_profession_ids ?? []))
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->take(2)
        ->values()
        ->all();
    $visibleIds = $professions->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
@endphp

<div class="space-y-6">
    <div class="card space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Misi 2 dari 5</p>
                <h1 class="mt-1 text-2xl font-bold text-zinc-950">Kartu Profesi Interaktif</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Baca informasi kartu profesi, tandai kartu yang sudah kamu pelajari, lalu pilih <strong>1–2 profesi</strong> untuk dieksplorasi lebih lanjut.
                </p>
            </div>
            <div class="shrink-0 rounded-xl border border-zinc-200 bg-paper px-4 py-2.5 text-xs">
                <div>Kartu dibaca: <strong class="text-zinc-950">{{ count($readIds) }}</strong></div>
                <div class="mt-0.5">
                    Dipilih (total semua kategori):
                    <strong id="selectedCountBadge" class="text-quest-600">{{ count($selectedIds) }}/2</strong>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 pt-2">
            <a href="{{ route('siswa.misi-2') }}"
               data-category-link
               class="rounded-lg border px-3 py-1.5 text-xs font-medium transition {{ empty($category) ? 'border-zinc-950 bg-zinc-950 text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50' }}">
                Semua Kategori
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('siswa.misi-2', ['category' => $cat]) }}"
                   data-category-link
                   class="rounded-lg border px-3 py-1.5 text-xs font-medium transition {{ $category === $cat ? 'border-zinc-950 bg-zinc-950 text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <form id="form-pilih-profesi" action="{{ route('siswa.misi-2.simpan') }}" method="POST" class="space-y-6">
        @csrf

        @foreach ($visibleIds as $vid)
            <input type="hidden" name="visible_profession_ids[]" value="{{ $vid }}">
        @endforeach

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            @foreach ($professions as $prof)
                @php
                    $profId = (int) $prof->id;
                    $isRead = in_array($profId, $readIds, true);
                    $isChecked = in_array($profId, $selectedIds, true);
                @endphp
                <div data-prof-card class="card flex flex-col justify-between space-y-4 {{ $isChecked ? 'border-quest-500 ring-2 ring-quest-100' : '' }}">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <span class="rounded-md bg-quest-50 px-2.5 py-1 text-xs font-semibold text-quest-700">
                                {{ $prof->category }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if ($isRead)
                                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-xs font-medium text-sage-700">
                                        Sudah Dibaca
                                    </span>
                                @endif
                                <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-800">
                                    <input type="checkbox"
                                           name="selected_profession_ids[]"
                                           value="{{ $profId }}"
                                           {{ $isChecked ? 'checked' : '' }}
                                           class="prof-checkbox rounded border-zinc-300 text-quest-600 focus:ring-quest-600">
                                    <span>Pilih</span>
                                </label>
                            </div>
                        </div>

                        <h2 class="text-lg font-bold text-zinc-950">{{ $prof->title }}</h2>

                        <div class="space-y-2 text-xs leading-relaxed text-zinc-700">
                            <div>
                                <span class="font-semibold text-zinc-950">Tugas Utama:</span>
                                <span>{{ $prof->main_tasks }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-zinc-950">Keterampilan Umum:</span>
                                <span>{{ $prof->general_skills }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-zinc-950">Lingkungan Kerja:</span>
                                <span>{{ $prof->work_environment }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-zinc-950">Gambaran Jalur Pendidikan/Pelatihan:</span>
                                <span>{{ $prof->education_paths }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-zinc-100 pt-3">
                        <button type="submit"
                                formaction="{{ route('siswa.misi-2.baca', $profId) }}"
                                formmethod="POST"
                                class="btn-secondary !px-3.5 !py-1.5 !text-xs">
                            {{ $isRead ? 'Tandai Ulang Sudah Dibaca' : 'Tandai Sudah Dibaca' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('siswa.misi-1') }}" class="btn-secondary">
                Kembali ke Misi 1
            </a>
            <button type="submit" class="btn-accent">
                Simpan Pilihan & Lanjut ke Misi 3
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('form-pilih-profesi');
        const checkboxes = [...document.querySelectorAll('.prof-checkbox')];
        const badge = document.getElementById('selectedCountBadge');
        const autoSaveUrl = @json(Route::has('siswa.misi-2.autosave') ? route('siswa.misi-2.autosave') : null);
        const csrfToken = form?.querySelector('input[name="_token"]')?.value || '';
        const visibleIds = [...form?.querySelectorAll('input[name="visible_profession_ids[]"]') || []].map(i => Number(i.value));

        const initialSelectedIds = @json($selectedIds).map(Number);
        const initialCheckedVisibleCount = checkboxes.filter(cb => cb.checked).length;
        let savedOtherCategoryCount = Math.max(0, initialSelectedIds.length - initialCheckedVisibleCount);

        function updateCardHighlight(cb) {
            const card = cb.closest('[data-prof-card]');
            if (!card) return;
            card.classList.toggle('border-quest-500', cb.checked);
            card.classList.toggle('ring-2', cb.checked);
            card.classList.toggle('ring-quest-100', cb.checked);
        }

        function refreshBadge(totalCount) {
            if (!badge) return;
            const count = typeof totalCount === 'number'
                ? totalCount
                : savedOtherCategoryCount + checkboxes.filter(cb => cb.checked).length;
            badge.textContent = `${count}/2`;
        }

        async function syncSelection() {
            const checkedIds = checkboxes.filter(cb => cb.checked).map(cb => Number(cb.value));
            refreshBadge();

            if (!autoSaveUrl || !csrfToken) return;

            try {
                const res = await fetch(autoSaveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        selected_profession_ids: checkedIds,
                        visible_profession_ids: visibleIds,
                    }),
                });

                if (res.ok) {
                    const data = await res.json();
                    if (Array.isArray(data.selected_profession_ids)) {
                        const serverIds = data.selected_profession_ids.map(Number);
                        const checkedVisible = serverIds.filter(id => visibleIds.includes(id)).length;
                        savedOtherCategoryCount = Math.max(0, serverIds.length - checkedVisible);
                        refreshBadge(serverIds.length);
                    }
                }
            } catch (e) {
                // Tetap aman jika koneksi terganggu; tombol submit utama tetap bisa menyimpan
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', async () => {
                const totalChecked = checkboxes.filter(item => item.checked).length;
                if (cb.checked && (savedOtherCategoryCount + totalChecked) > 2) {
                    cb.checked = false;
                    alert('Kamu bisa memilih maksimal 2 profesi (termasuk yang sudah dipilih di kategori lain).');
                    return;
                }
                updateCardHighlight(cb);
                await syncSelection();
            });
        });

        document.querySelectorAll('[data-category-link]').forEach(link => {
            link.addEventListener('click', async function (e) {
                if (!autoSaveUrl) return;
                e.preventDefault();
                const targetUrl = this.href;
                await syncSelection();
                window.location.href = targetUrl;
            });
        });
    })();
</script>
@endpush