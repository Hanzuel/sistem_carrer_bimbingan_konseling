<?php

namespace App\Http\Controllers;

use App\Models\MediaEvaluation;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class BkController extends Controller
{
    public function dashboard()
    {
        $relations = ['studentQuest', 'mediaEvaluation'];
        if (Schema::hasTable('class_groups') && Schema::hasColumn('users', 'class_group_id')) {
            $relations[] = 'classGroup';
        }

        $students = User::where('role', 'siswa')
            ->with($relations)
            ->orderBy('name')
            ->get();

        $professionsById = Profession::pluck('title', 'id');

        $submittedCount = $students->filter(fn ($s) => (bool) optional($s->studentQuest)->is_completed)->count();
        $evaluatedCount = $students->filter(fn ($s) => !is_null($s->mediaEvaluation))->count();

        $evaluations = MediaEvaluation::all();
        $avgScore = null;
        if ($evaluations->isNotEmpty()) {
            $totalSum = 0;
            $totalItems = 0;
            foreach ($evaluations as $ev) {
                for ($i = 1; $i <= 10; $i++) {
                    $val = $ev->{"q{$i}"};
                    if (!is_null($val)) {
                        $totalSum += (int) $val;
                        $totalItems++;
                    }
                }
            }
            if ($totalItems > 0) {
                $avgScore = round($totalSum / $totalItems, 2);
            }
        }

        return view('bk.dashboard', compact(
            'students',
            'professionsById',
            'submittedCount',
            'evaluatedCount',
            'avgScore'
        ));
    }
}