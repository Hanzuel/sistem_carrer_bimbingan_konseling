<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\MissionProgress;
use App\Models\ProfessionCard;
use App\Models\ReflectionEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    private function getOrCreateReflection(int $userId): ReflectionEntry
    {
        return ReflectionEntry::firstOrCreate(
            ['user_id' => $userId],
            ['shared_with_bk' => false]
        );
    }

    private function markMissionCompleted(int $userId, string $missionType, array $payload = []): void
    {
        $mission = Mission::where('mission_type', $missionType)->first();
        if (! $mission) {
            return;
        }

        MissionProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'mission_id' => $mission->id,
            ],
            [
                'status' => 'completed',
                'response_data' => $payload,
                'completed_at' => now(),
            ]
        );
    }

    public function beranda(): View
    {
        $user = Auth::user();
        $totalMissions = Mission::count();
        $completedCount = MissionProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return view('siswa.beranda', compact('user', 'totalMissions', 'completedCount'));
    }

    public function kenaliDiri(): View
    {
        $reflection = $this->getOrCreateReflection(Auth::id());

        return view('siswa.kenali-diri', compact('reflection'));
    }

    public function simpanKenaliDiri(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'liked_activities_text' => ['nullable', 'string', 'max:1000'],
            'liked_subjects_text' => ['nullable', 'string', 'max:1000'],
            'interest_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $activities = array_values(array_filter(array_map('trim', explode(',', $validated['liked_activities_text'] ?? ''))));
        $subjects = array_values(array_filter(array_map('trim', explode(',', $validated['liked_subjects_text'] ?? ''))));

        $reflection = $this->getOrCreateReflection(Auth::id());
        $reflection->update([
            'liked_activities' => $activities,
            'liked_subjects' => $subjects,
            'interest_notes' => $validated['interest_notes'] ?? null,
        ]);

        $this->markMissionCompleted(Auth::id(), 'self_reflection', [
            'activities' => $activities,
            'subjects' => $subjects,
        ]);

        return redirect()->route('siswa.kartu-profesi')
            ->with('status', 'Catatan Kenali Diri tersimpan sebagai bahan refleksi.');
    }

    public function kartuProfesi(): View
    {
        $cards = ProfessionCard::where('is_published', true)->orderBy('title')->get();

        return view('siswa.kartu-profesi', compact('cards'));
    }

    public function misi(): View
    {
        $missions = Mission::orderBy('step_order')->get();
        $progresses = MissionProgress::where('user_id', Auth::id())
            ->get()
            ->keyBy('mission_id');

        return view('siswa.misi', compact('missions', 'progresses'));
    }

    public function simpanMisi(Request $request, Mission $mission): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:2000'],
            'source_link' => ['nullable', 'string', 'max:500'],
        ]);

        MissionProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'mission_id' => $mission->id,
            ],
            [
                'status' => 'completed',
                'response_data' => [
                    'notes' => $validated['notes'],
                    'source_link' => $validated['source_link'] ?? null,
                ],
                'completed_at' => now(),
            ]
        );

        return back()->with('status', $mission->title.' ditandai selesai.');
    }

    public function tantanganPilihan(): View
    {
        $reflection = $this->getOrCreateReflection(Auth::id());
        $compared = $reflection->compared_options ?? [];

        return view('siswa.tantangan-pilihan', compact('reflection', 'compared'));
    }

    public function simpanTantanganPilihan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'option_one' => ['required', 'string', 'max:255'],
            'option_two' => ['required', 'string', 'max:255'],
            'comparison_notes' => ['required', 'string', 'max:2500'],
            'unknown_aspects' => ['nullable', 'string', 'max:1500'],
        ]);

        $comparedPayload = [
            'option_one' => $validated['option_one'],
            'option_two' => $validated['option_two'],
            'comparison_notes' => $validated['comparison_notes'],
            'unknown_aspects' => $validated['unknown_aspects'] ?? null,
        ];

        $reflection = $this->getOrCreateReflection(Auth::id());
        $reflection->update([
            'compared_options' => $comparedPayload,
        ]);

        $this->markMissionCompleted(Auth::id(), 'compare_options', $comparedPayload);

        return redirect()->route('siswa.refleksi')
            ->with('status', 'Perbandingan pilihan karier berhasil disimpan.');
    }

    public function refleksi(): View
    {
        $reflection = $this->getOrCreateReflection(Auth::id());

        return view('siswa.refleksi', compact('reflection'));
    }

    public function simpanRefleksi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'new_learnings' => ['required', 'string', 'max:2000'],
            'follow_up_questions' => ['nullable', 'string', 'max:2000'],
            'next_action' => ['required', 'string', 'max:2000'],
        ]);

        $reflection = $this->getOrCreateReflection(Auth::id());
        $reflection->update($validated);

        $this->markMissionCompleted(Auth::id(), 'next_step', [
            'next_action' => $validated['next_action'],
        ]);

        return redirect()->route('siswa.penutup')
            ->with('status', 'Refleksi dan rencana langkah berikutnya tersimpan.');
    }

    public function penutup(Request $request): View
    {
    $reflection = $this->getOrCreateReflection($request->user()->id);

    return view('siswa.penutup', compact('reflection'));
    }

    public function bagikanRefleksi(Request $request): RedirectResponse
    {
        $reflection = $this->getOrCreateReflection(Auth::id());
        $reflection->update([
            'shared_with_bk' => $request->boolean('shared_with_bk'),
        ]);

        return back()->with('status', 'Preferensi berbagi catatan refleksi ke Guru BK telah diperbarui.');
    }
}