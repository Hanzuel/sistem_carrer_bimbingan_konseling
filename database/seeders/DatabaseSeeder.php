<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use App\Models\Mission;
use App\Models\ProfessionCard;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kelas / Sesi BK
        $classA = ClassGroup::create([
            'name' => 'Kelas X-A (Simulasi BK)',
            'description' => 'Kelompok uji coba layanan eksplorasi karier.',
        ]);

        // 2. Akun Admin
        User::create([
            'name' => 'Admin Career Quest',
            'identifier' => 'admin01',
            'email' => 'admin@careerquest.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 3. Akun Guru BK
        User::create([
            'name' => 'Guru BK Pendamping',
            'identifier' => 'bk01',
            'email' => 'bk@careerquest.local',
            'password' => Hash::make('password123'),
            'role' => 'bk',
            'class_group_id' => $classA->id,
            'is_active' => true,
        ]);

        // 4. Akun Siswa (Simulasi / Kode Peserta)
        $students = [
            ['name' => 'Peserta Uji 01', 'identifier' => 'siswa01'],
            ['name' => 'Peserta Uji 02', 'identifier' => 'siswa02'],
            ['name' => 'Peserta Uji 03', 'identifier' => 'siswa03'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'identifier' => $student['identifier'],
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'class_group_id' => $classA->id,
                'is_active' => true,
            ]);
        }

        // 5. Contoh Kartu Profesi (Sesuai Proposal: Tugas, Lingkungan Kerja, Keterampilan, Jalur Belajar)
        ProfessionCard::create([
            'title' => 'Pengembang Perangkat Lunak (Software Developer)',
            'duties' => 'Merancang, menulis, menguji, dan memelihara aplikasi web atau seluler sesuai kebutuhan pengguna.',
            'work_environment' => 'Perusahaan teknologi, instansi, tim produk digital, atau kerja jarak jauh berbasis komputer.',
            'general_skills' => 'Pemecahan masalah, logika pemrograman, komunikasi tim, dan kemauan belajar teknologi baru.',
            'education_path' => 'SMK RPL/TKJ, S1 Teknik Informatika/Ilmu Komputer/Sistem Informasi, atau pelatihan intensif (bootcamp).',
            'reference_links' => "https://skkni.kemnaker.go.id\nhttps://pddikti.kemdiktisaintek.go.id",
            'is_published' => true,
        ]);

        ProfessionCard::create([
            'title' => 'Desainer Komunikasi Visual / UI Designer',
            'duties' => 'Menyusun tata letak visual, ilustrasi, dan antarmuka aplikasi agar informasi mudah dipahami pengguna.',
            'work_environment' => 'Studio kreatif, agensi digital, tim media/produk, atau pekerja lepas.',
            'general_skills' => 'Kepekaan visual, empati terhadap pengguna, penguasaan perangkat desain, dan penyampaian ide.',
            'education_path' => 'SMK Multimedia/DKV, D3/S1 Desain Komunikasi Visual, atau portofolio pelatihan desain digital.',
            'reference_links' => "https://pddikti.kemdiktisaintek.go.id",
            'is_published' => true,
        ]);

        ProfessionCard::create([
            'title' => 'Konselor Sekolah / Guru Bimbingan dan Konseling',
            'duties' => 'Membantu peserta didik mengenali potensi diri, merencanakan studi/karier, dan menyelesaikan hambatan belajar.',
            'work_environment' => 'Sekolah menengah (SMP/SMA/SMK), lembaga pendidikan, atau pusat layanan pengembangan diri.',
            'general_skills' => 'Mendengarkan aktif, empati, komunikasi interpersonal, dan menjaga kerahasiaan data.',
            'education_path' => 'S1 Bimbingan dan Konseling (BK) / Bimbingan dan Konseling Pendidikan Islam (BKPI) serta Pendidikan Profesi Guru (PPG).',
            'reference_links' => "https://pddikti.kemdiktisaintek.go.id",
            'is_published' => true,
        ]);

        // 6. Contoh 5 Misi Eksplorasi (Sesuai Subbab 3.4.3 Proposal)
        $missions = [
            [
                'step_order' => 1,
                'title' => 'Misi 1: Kenali Dirimu',
                'instruction' => 'Pilih aktivitas dan mata pelajaran yang paling kamu sukai, lalu tuliskan alasan singkatnya.',
                'mission_type' => 'self_reflection',
            ],
            [
                'step_order' => 2,
                'title' => 'Misi 2: Jelajah Profesi',
                'instruction' => 'Baca minimal dua Kartu Profesi dan catat tugas yang menurutmu paling menarik beserta keterampilan umumnya.',
                'mission_type' => 'explore_card',
            ],
            [
                'step_order' => 3,
                'title' => 'Misi 3: Cari Jalurnya',
                'instruction' => 'Telusuri jalur pendidikan atau pelatihan dari salah satu profesi melalui sumber informasi tepercaya.',
                'mission_type' => 'find_path',
            ],
            [
                'step_order' => 4,
                'title' => 'Misi 4: Bandingkan Pilihan',
                'instruction' => 'Bandingkan dua alternatif karier berdasarkan minat, mata pelajaran disukai, lingkungan kerja, dan hal yang masih ingin kamu ketahui.',
                'mission_type' => 'compare_options',
            ],
            [
                'step_order' => 5,
                'title' => 'Misi 5: Langkah Berikutnya',
                'instruction' => 'Tentukan satu langkah lanjutan yang realistis, misalnya berdiskusi dengan guru BK atau mencari informasi jurusan.',
                'mission_type' => 'next_step',
            ],
        ];

        foreach ($missions as $mission) {
            Mission::create($mission);
        }
    }
}