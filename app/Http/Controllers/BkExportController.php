<?php

namespace App\Http\Controllers;

use App\Models\ProfessionCard;
use App\Models\User;
use Illuminate\Http\Request;
use Shuchkin\SimpleXLSXGen;

class BkExportController extends Controller
{
    public function exportRekapSiswa(Request $request)
    {
        $professionsById = ProfessionCard::pluck('title', 'id')->toArray();

        $students = User::where('role', 'siswa')
            ->with(['classGroup', 'studentQuest', 'mediaEvaluation'])
            ->orderBy('name')
            ->get();

        $headerStyle = '<style bgcolor="#1E293B" color="#FFFFFF"><b>%s</b></style>';

        $rows = [
            [
                sprintf($headerStyle, 'No'),
                sprintf($headerStyle, 'Nama Siswa'),
                sprintf($headerStyle, 'Kode / ID'),
                sprintf($headerStyle, 'Kelas / Sesi'),
                sprintf($headerStyle, 'Misi Selesai'),
                sprintf($headerStyle, 'Status Rekap'),
                sprintf($headerStyle, 'Waktu Kirim'),
                sprintf($headerStyle, 'Skor Angket'),
                sprintf($headerStyle, 'Rata-Rata Angket'),
                sprintf($headerStyle, 'Misi 1: Aktivitas'),
                sprintf($headerStyle, 'Misi 1: Mapel Disukai'),
                sprintf($headerStyle, 'Misi 1: Suasana Belajar'),
                sprintf($headerStyle, 'Misi 1: Alasan'),
                sprintf($headerStyle, 'Misi 2: Profesi Dipilih'),
                sprintf($headerStyle, 'Misi 3: Jalur Dipilih'),
                sprintf($headerStyle, 'Misi 4: Karier A'),
                sprintf($headerStyle, 'Misi 4: Catatan Karier A'),
                sprintf($headerStyle, 'Misi 4: Karier B'),
                sprintf($headerStyle, 'Misi 4: Catatan Karier B'),
                sprintf($headerStyle, 'Misi 4: Kesimpulan'),
                sprintf($headerStyle, 'Misi 5: Hal Dipelajari'),
                sprintf($headerStyle, 'Misi 5: Topik Diskusi BK'),
                sprintf($headerStyle, 'Misi 5: Tindakan Minggu Ini'),
            ]
        ];

        $no = 1;
        foreach ($students as $student) {
            $quest = $student->studentQuest;
            $eval = $student->mediaEvaluation;

            $misiDone = 0;
            if ($quest) {
                if (!empty($quest->activities) && !empty($quest->favorite_subjects) && !empty($quest->study_environment) && !empty($quest->self_reason)) {
                    $misiDone++;
                }
                if (!empty($quest->selected_profession_ids)) {
                    $misiDone++;
                }
                if (!empty($quest->selected_education_paths)) {
                    $misiDone++;
                }
                if (!empty($quest->career_a_title) && !empty($quest->career_b_title) && !empty($quest->comparison_conclusion)) {
                    $misiDone++;
                }
                if ($quest->is_completed) {
                    $misiDone++;
                }
            }

            $statusRekap = 'Belum Mulai';
            if ($quest && $quest->is_completed) {
                $statusRekap = 'Terkirim';
            } elseif ($misiDone > 0) {
                $statusRekap = 'Dalam Proses';
            }

            $waktuKirim = ($quest && $quest->submitted_at)
                ? $quest->submitted_at->format('d M Y H:i')
                : '-';

            $evalTotal = '-';
            $evalAvg = '-';
            if ($eval) {
                $scores = [];
                for ($i = 1; $i <= 10; $i++) {
                    if (!is_null($eval->{"q{$i}"})) {
                        $scores[] = (int) $eval->{"q{$i}"};
                    }
                }
                if (count($scores) > 0) {
                    $sum = array_sum($scores);
                    $evalTotal = $sum . '/40';
                    $evalAvg = round($sum / count($scores), 2) . '/4';
                }
            }

            $chosenProfTitles = collect($quest->selected_profession_ids ?? [])
                ->map(fn ($id) => $professionsById[$id] ?? null)
                ->filter()
                ->implode(', ');

            $activities = is_array($quest->activities ?? null) ? implode(', ', $quest->activities) : ($quest->activities ?? '-');
            $favSubjects = is_array($quest->favorite_subjects ?? null) ? implode(', ', $quest->favorite_subjects) : ($quest->favorite_subjects ?? '-');
            $eduPaths = is_array($quest->selected_education_paths ?? null) ? implode('; ', $quest->selected_education_paths) : ($quest->selected_education_paths ?? '-');

            $rows[] = [
                $no++,
                $student->name,
                $student->identifier ?? '-',
                $student->classGroup->name ?? '-',
                $misiDone . '/5',
                $statusRekap,
                $waktuKirim,
                $evalTotal,
                $evalAvg,
                $activities ?: '-',
                $favSubjects ?: '-',
                $quest->study_environment ?? '-',
                $quest->self_reason ?? '-',
                $chosenProfTitles ?: '-',
                $eduPaths ?: '-',
                $quest->career_a_title ?? '-',
                $quest->career_a_notes ?? '-',
                $quest->career_b_title ?? '-',
                $quest->career_b_notes ?? '-',
                $quest->comparison_conclusion ?? '-',
                $quest->learned_summary ?? '-',
                $quest->bk_discussion_topic ?? '-',
                $quest->weekly_action ?? '-',
            ];
        }

        $xlsx = SimpleXLSXGen::fromArray($rows);
        $filename = 'Rekap_Eksplorasi_5_Misi_Siswa_' . now()->format('Y-m-d_His') . '.xlsx';

        return response()->streamDownload(function () use ($xlsx) {
            echo $xlsx;
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]); 
    }
}