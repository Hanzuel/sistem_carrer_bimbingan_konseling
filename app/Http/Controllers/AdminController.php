<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\MediaEvaluation;
use App\Models\Profession;
use App\Models\StudentQuest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
    {
        $classGroups = Schema::hasTable('class_groups')
            ? \App\Models\ClassGroup::orderBy('name')->get()
            : collect();

        $students = User::where('role', 'siswa')
            ->with(['studentQuest', 'mediaEvaluation'])
            ->orderBy('name')
            ->get();

        $stats = [
            'class_groups' => $classGroups->count(),
            'profession_cards' => Profession::count(),
            'learning_materials' => LearningMaterial::count(),
            'bk_users' => User::where('role', 'bk')->count(),
            'students' => $students->count(),
            'submitted_quests' => StudentQuest::where('is_completed', true)->count(),
            'evaluations' => MediaEvaluation::count(),
        ];

        return view('admin.dashboard', compact('stats', 'classGroups', 'students'));
    }

    public function storeClassGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        if (Schema::hasTable('class_groups')) {
            \App\Models\ClassGroup::create($validated);
        }

        return back()->with('success', 'Kelas / kelompok sesi berhasil ditambahkan.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:siswa,bk'],
            'name' => ['required', 'string', 'max:150'],
            'identifier' => ['required', 'string', 'max:100', 'unique:users,identifier'],
            'class_group_id' => ['nullable', 'integer'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'identifier' => $validated['identifier'],
            'email' => $validated['identifier'] . '@careerquest.local',
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ];

        if (Schema::hasColumn('users', 'class_group_id')) {
            $payload['class_group_id'] = $validated['role'] === 'siswa'
                ? ($validated['class_group_id'] ?? null)
                : null;
        }

        $user = User::create($payload);

        if ($user->role === 'siswa') {
            StudentQuest::firstOrCreate(['user_id' => $user->id]);
        }

        return back()->with('success', 'Akun pengguna berhasil dibuat.');
    }
}