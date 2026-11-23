<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BkController extends Controller
{
    public function dashboard(): View
    {
        $bk = Auth::user();
        $totalMissions = Mission::count();

        $studentsQuery = User::where('role', 'siswa')
            ->with(['classGroup', 'missionProgresses', 'reflectionEntry']);

        if ($bk->class_group_id) {
            $studentsQuery->where('class_group_id', $bk->class_group_id);
        }

        $students = $studentsQuery->orderBy('identifier')->get();

        return view('bk.dashboard', compact('students', 'totalMissions'));
    }
}