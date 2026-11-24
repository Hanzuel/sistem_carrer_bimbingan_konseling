<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\MediaEvaluation;
use App\Models\Profession;
use App\Models\StudentQuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    private function getQuest(): StudentQuest
    {
        return StudentQuest::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'activities' => [],
                'favorite_subjects' => [],
                'read_profession_ids' => [],
                'selected_profession_ids' => [],
                'selected_education_paths' => [],
                'is_completed' => false,
            ]
        );
    }

    private function mergeProfessionIds(array $existingIds, array $visibleIds, array $checkedVisibleIds): array
    {
        $existing = collect($existingIds)->map(fn ($id) => (int) $id)->filter(fn ($id) => $id > 0);
        $visible  = collect($visibleIds)->map(fn ($id) => (int) $id)->filter(fn ($id) => $id > 0);
        $checked  = collect($checkedVisibleIds)->map(fn ($id) => (int) $id)->filter(fn ($id) => $id > 0);

        return $existing
            ->reject(fn ($id) => $visible->contains($id))
            ->merge($checked)
            ->unique()
            ->take(2)
            ->values()
            ->all();
    }

    public function beranda()
    {
        $quest = $this->getQuest();
        $totalProfessions = Profession::count();
        $totalMaterials = LearningMaterial::count();

        return view('siswa.beranda', compact('quest', 'totalProfessions', 'totalMaterials'));
    }

    public function pusatMateri(Request $request)
    {
        $category = $request->query('category');
        $categories = LearningMaterial::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $materials = LearningMaterial::when($category, function ($query) use ($category) {
            $query->where('category', $category);
        })->orderBy('id')->get();

        return view('siswa.pusat-materi', compact('materials', 'categories', 'category'));
    }

    // Misi 1: Kenali Diri
    public function misiSatu()
    {
        $quest = $this->getQuest();

        return view('siswa.misi-1', compact('quest'));
    }

    public function simpanMisiSatu(Request $request)
    {
        $validated = $request->validate([
            'activities' => ['required', 'array', 'min:1', 'max:3'],
            'activities.*' => ['required', 'string', 'max:120'],
            'favorite_subjects' => ['required', 'array', 'min:1', 'max:5'],
            'favorite_subjects.*' => ['required', 'string', 'max:120'],
            'study_environment' => ['required', 'string', 'max:255'],
            'self_reason' => ['required', 'string', 'max:2000'],
        ]);

        $quest = $this->getQuest();
        $quest->update([
            'activities' => array_values($validated['activities']),
            'favorite_subjects' => array_values($validated['favorite_subjects']),
            'study_environment' => $validated['study_environment'],
            'self_reason' => $validated['self_reason'],
        ]);

        return redirect()->route('siswa.misi-2')->with('success', 'Misi 1 (Kenali Diri) berhasil disimpan.');
    }

    // Misi 2: Kartu Profesi Interaktif
    public function misiDua(Request $request)
    {
        $quest = $this->getQuest();
        $category = $request->query('category');
        $categories = Profession::distinct()->pluck('category');

        $professions = Profession::when($category, function ($query) use ($category) {
            $query->where('category', $category);
        })->orderBy('id')->get();

        return view('siswa.misi-2', compact('quest', 'professions', 'categories', 'category'));
    }

    public function tandaiDibaca(Request $request, Profession $profession)
    {
        $quest = $this->getQuest();

        $mergedSelected = $this->mergeProfessionIds(
            $quest->selected_profession_ids ?? [],
            $request->input('visible_profession_ids', []),
            $request->input('selected_profession_ids', [])
        );

        $readIds = collect($quest->read_profession_ids ?? [])
            ->push($profession->id)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $quest->update([
            'read_profession_ids' => $readIds,
            'selected_profession_ids' => $mergedSelected,
        ]);

        return back()->with('success', "Kartu profesi \"{$profession->title}\" ditandai sudah dibaca.");
    }

    public function autoSaveMisiDua(Request $request)
    {
        $validated = $request->validate([
            'selected_profession_ids' => ['nullable', 'array'],
            'selected_profession_ids.*' => ['integer', 'exists:professions,id'],
            'visible_profession_ids' => ['nullable', 'array'],
            'visible_profession_ids.*' => ['integer'],
        ]);

        $quest = $this->getQuest();

        $selectedIds = $this->mergeProfessionIds(
            $quest->selected_profession_ids ?? [],
            $validated['visible_profession_ids'] ?? [],
            $validated['selected_profession_ids'] ?? []
        );

        $quest->update([
            'selected_profession_ids' => $selectedIds,
        ]);

        return response()->json([
            'ok' => true,
            'selected_profession_ids' => $selectedIds,
            'count' => count($selectedIds),
        ]);
    }

    public function simpanMisiDua(Request $request)
    {
        $validated = $request->validate([
            'selected_profession_ids' => ['nullable', 'array'],
            'selected_profession_ids.*' => ['integer', 'exists:professions,id'],
            'visible_profession_ids' => ['nullable', 'array'],
            'visible_profession_ids.*' => ['integer'],
        ]);

        $quest = $this->getQuest();

        $selectedIds = $this->mergeProfessionIds(
            $quest->selected_profession_ids ?? [],
            $validated['visible_profession_ids'] ?? [],
            $validated['selected_profession_ids'] ?? []
        );

        if (count($selectedIds) < 1 || count($selectedIds) > 2) {
            return back()
                ->withInput()
                ->withErrors(['selected_profession_ids' => 'Pilih 1 hingga 2 kartu profesi sebelum lanjut ke Misi 3.']);
        }

        $quest->update([
            'selected_profession_ids' => $selectedIds,
        ]);

        return redirect()->route('siswa.misi-3')->with('success', 'Pilihan kartu profesi pada Misi 2 berhasil disimpan.');
    }

    // Misi 3: Cari Jalurnya
    public function misiTiga()
    {
        $quest = $this->getQuest();
        $selectedProfessions = Profession::whereIn('id', $quest->selected_profession_ids ?? [])->get();

        $educationOptions = [
            'Jalur Akademik / Perguruan Tinggi (SNBP / SNBT / Mandiri)',
            'Jalur Vokasi / Politeknik / Diploma (D3 / D4)',
            'Sekolah Kedinasan',
            'Pelatihan Keterampilan / Sertifikasi / Bootcamp',
            'Magang / Praktik Kerja & Wirausaha',
        ];

        return view('siswa.misi-3', compact('quest', 'selectedProfessions', 'educationOptions'));
    }

    public function simpanMisiTiga(Request $request)
    {
        $validated = $request->validate([
            'selected_education_paths' => ['required', 'array', 'min:1'],
            'selected_education_paths.*' => ['required', 'string', 'max:200'],
        ]);

        $quest = $this->getQuest();
        $quest->update([
            'selected_education_paths' => array_values($validated['selected_education_paths']),
        ]);

        return redirect()->route('siswa.misi-4')->with('success', 'Pilihan jalur pendidikan & pelatihan pada Misi 3 berhasil disimpan.');
    }

    // Misi 4: Bandingkan Pilihan
    public function misiEmpat()
    {
        $quest = $this->getQuest();
        $selectedProfessions = Profession::whereIn('id', $quest->selected_profession_ids ?? [])->get();

        return view('siswa.misi-4', compact('quest', 'selectedProfessions'));
    }

    public function simpanMisiEmpat(Request $request)
    {
        $validated = $request->validate([
            'career_a_title' => ['required', 'string', 'max:150'],
            'career_a_notes' => ['required', 'string', 'max:2000'],
            'career_b_title' => ['required', 'string', 'max:150'],
            'career_b_notes' => ['required', 'string', 'max:2000'],
            'comparison_conclusion' => ['required', 'string', 'max:2000'],
        ]);

        $quest = $this->getQuest();
        $quest->update($validated);

        return redirect()->route('siswa.misi-5')->with('success', 'Perbandingan dua alternatif karier pada Misi 4 berhasil disimpan.');
    }

    // Misi 5: Refleksi & Penutup + Angket Tanggapan + Kirim Rekap
    public function misiLima()
    {
        $quest = $this->getQuest();
        $evaluation = MediaEvaluation::where('user_id', Auth::id())->first();

        $weeklyActions = [
            'Berdiskusi dengan Guru BK',
            'Membaca Modul di Pusat Materi',
            'Berdiskusi dengan Orang Tua / Praktisi',
        ];

        $evaluationQuestions = [
            1 => 'Petunjuk penggunaan Career Quest mudah dipahami.',
            2 => 'Saya dapat menemukan menu yang dibutuhkan dengan mudah.',
            3 => 'Informasi pada kartu profesi disajikan dengan jelas.',
            4 => 'Aktivitas misi membantu saya mencari informasi karier.',
            5 => 'Pertanyaan refleksi membantu saya memikirkan minat dan kegiatan yang disukai.',
            6 => 'Saya dapat membandingkan pilihan karier dengan lebih terarah setelah aktivitas.',
            7 => 'Media tidak membuat saya merasa harus memilih satu profesi tertentu.',
            8 => 'Saya memahami bahwa hasil isian hanya untuk refleksi, bukan diagnosis atau penentu karier.',
            9 => 'Tampilan dan tulisan nyaman dibaca.',
            10 => 'Saya mengetahui langkah berikutnya untuk mengeksplorasi karier.',
        ];

        return view('siswa.misi-5', compact('quest', 'evaluation', 'weeklyActions', 'evaluationQuestions'));
    }

    public function simpanMisiLima(Request $request)
    {
        $rules = [
            'learned_summary' => ['required', 'string', 'max:2500'],
            'bk_discussion_topic' => ['required', 'string', 'max:2500'],
            'weekly_action' => ['required', 'string', 'max:150'],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $rules["q{$i}"] = ['required', 'integer', 'between:1,4'];
        }

        $validated = $request->validate($rules);

        $quest = $this->getQuest();
        $quest->update([
            'learned_summary' => $validated['learned_summary'],
            'bk_discussion_topic' => $validated['bk_discussion_topic'],
            'weekly_action' => $validated['weekly_action'],
            'is_completed' => true,
            'submitted_at' => now(),
        ]);

        $evalData = [];
        for ($i = 1; $i <= 10; $i++) {
            $evalData["q{$i}"] = (int) $validated["q{$i}"];
        }

        MediaEvaluation::updateOrCreate(
            ['user_id' => Auth::id()],
            $evalData
        );

        return redirect()->route('siswa.misi-5')->with('success', 'Misi 5 selesai dan rekap berhasil dikirim ke Guru BK.');
    }
}