<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Mission;
use App\Models\ProfessionCard;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'class_groups' => ClassGroup::count(),
            'profession_cards' => ProfessionCard::count(),
            'missions' => Mission::count(),
            'bk_users' => User::where('role', 'bk')->count(),
            'students' => User::where('role', 'siswa')->count(),
        ];

        $totalMissions = Mission::count();
        $classGroups = ClassGroup::orderBy('name')->get();

        $students = User::where('role', 'siswa')
            ->with(['classGroup', 'missionProgresses', 'reflectionEntry'])
            ->orderBy('identifier')
            ->get();

        $bkUsers = User::where('role', 'bk')
            ->orderBy('identifier')
            ->get();

        return view('admin.dashboard', compact('stats', 'totalMissions', 'classGroups', 'students', 'bkUsers'));
    }

    public function storeClassGroup(Request $request): RedirectResponse
    {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100', 'unique:class_groups,name'],
        'description' => ['nullable', 'string', 'max:500'],
    ], [
        'name.unique' => 'Nama kelas / sesi tersebut sudah terdaftar.',
    ]);

    ClassGroup::create([
        'name' => trim($validated['name']),
        'description' => $validated['description'] ? trim($validated['description']) : null,
    ]);

    return back()->with('status', "Kelas / sesi \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identifier' => ['required', 'string', 'max:100', 'unique:users,identifier'],
            'role' => ['required', 'in:siswa,bk'],
            'class_group_id' => ['nullable', 'required_if:role,siswa', 'exists:class_groups,id'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'identifier.unique' => 'Kode / ID pengguna tersebut sudah digunakan.',
            'class_group_id.required_if' => 'Kelas / sesi wajib dipilih untuk akun siswa.',
        ]);

        User::create([
            'name' => $validated['name'],
            'identifier' => trim($validated['identifier']),
            'role' => $validated['role'],
            'class_group_id' => $validated['role'] === 'siswa' ? $validated['class_group_id'] : null,
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        $labelRole = $validated['role'] === 'siswa' ? 'Siswa' : 'Guru BK';

        return back()->with('status', "Akun {$labelRole} ({$validated['identifier']}) berhasil ditambahkan.");
    }
}

