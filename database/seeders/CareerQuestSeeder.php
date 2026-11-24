<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Profession;
use App\Models\StudentQuest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CareerQuestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun default (Admin, Guru BK, Siswa)
        $users = [
            [
                'identifier' => 'admin01',
                'name' => 'Admin Career Quest',
                'email' => 'admin@careerquest.local',
                'role' => 'admin',
            ],
            [
                'identifier' => 'bk01',
                'name' => 'Guru BK Career Quest',
                'email' => 'bk@careerquest.local',
                'role' => 'bk',
            ],
            [
                'identifier' => 'siswa01',
                'name' => 'Siswa Contoh',
                'email' => 'siswa01@careerquest.local',
                'role' => 'siswa',
            ],
        ];

        foreach ($users as $data) {
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
            ];

            if (Schema::hasColumn('users', 'identifier')) {
                $payload['identifier'] = $data['identifier'];
            }
            if (Schema::hasColumn('users', 'role')) {
                $payload['role'] = $data['role'];
            }

            $lookup = Schema::hasColumn('users', 'identifier')
                ? ['identifier' => $data['identifier']]
                : ['email' => $data['email']];

            $user = User::updateOrCreate($lookup, $payload);

            if (($data['role'] ?? null) === 'siswa') {
                StudentQuest::firstOrCreate(['user_id' => $user->id]);
            }
        }

        // 2. Data dummy Kartu Profesi (Misi 2)
        $professions = [
            [
                'category' => 'Teknologi & Digital',
                'title' => 'UI/UX Designer',
                'main_tasks' => 'Merancang alur pengguna, wireframe, dan tampilan antarmuka aplikasi atau situs web yang mudah digunakan.',
                'general_skills' => 'Empati pengguna, komunikasi visual, pemecahan masalah, dan kerja sama tim.',
                'work_environment' => 'Studio digital, perusahaan teknologi, agensi kreatif, atau kerja remote/hybrid.',
                'education_paths' => 'S1 Desain Komunikasi Visual / Sistem Informasi / Informatika, bootcamp UI/UX, atau portofolio mandiri.',
            ],
            [
                'category' => 'Teknologi & Digital',
                'title' => 'Data Analyst',
                'main_tasks' => 'Mengolah, membersihkan, dan menganalisis data untuk membantu tim mengambil keputusan berbasis fakta.',
                'general_skills' => 'Berpikir analitis, ketelitian, penyajian informasi/visualisasi, dan pemahaman bisnis dasar.',
                'work_environment' => 'Perusahaan ritel, keuangan, kesehatan, startup, atau instansi berbasis data.',
                'education_paths' => 'S1 Statistika / Matematika / Sains Data / Sistem Informasi, pelatihan analitik data, dan proyek kasus nyata.',
            ],
            [
                'category' => 'Sosial, Pendidikan & Konseling',
                'title' => 'Psikolog / Konselor Pendidikan',
                'main_tasks' => 'Mendampingi siswa atau individu dalam memahami diri, mengelola stres, dan merencanakan pengembangan diri.',
                'general_skills' => 'Mendengarkan aktif, empati, komunikasi interpersonal, dan menjaga kerahasiaan.',
                'work_environment' => 'Sekolah, lembaga pendidikan, klinik/pusat layanan konseling, atau komunitas.',
                'education_paths' => 'S1 Psikologi atau S1 Bimbingan dan Konseling (dilanjutkan jenjang profesi/terkait sesuai jalur).',
            ],
            [
                'category' => 'Kesehatan & Lingkungan',
                'title' => 'Spesialis Keberlanjutan / Lingkungan',
                'main_tasks' => 'Membantu organisasi menyusun program ramah lingkungan, efisiensi energi/sumber daya, dan kepatuhan keberlanjutan.',
                'general_skills' => 'Koordinasi lintas divisi, analisis dampak, penyusunan laporan, dan adaptasi regulasi.',
                'work_environment' => 'Industri manufaktur, energi, konsultan lingkungan, atau proyek sosial/komunitas.',
                'education_paths' => 'S1 Teknik Lingkungan / Ilmu Lingkungan / Manajemen, serta sertifikasi atau pelatihan keberlanjutan.',
            ],
            [
                'category' => 'Bisnis, Media & Kreatif',
                'title' => 'Content & Brand Strategist',
                'main_tasks' => 'Menyusun arah pesan, rencana konten, dan identitas komunikasi merek agar relevan dengan audiens.',
                'general_skills' => 'Menulis kreatif, riset audiens, manajemen waktu, dan analisis performa konten.',
                'work_environment' => 'Agensi kreatif, media, UMKM/brand lokal, atau tim komunikasi perusahaan.',
                'education_paths' => 'S1 Ilmu Komunikasi / Pemasaran / Sastra, magang kreatif, atau portofolio kampanye konten.',
            ],
            [
                'category' => 'Teknik & Vokasi',
                'title' => 'Teknisi Otomasi & IoT',
                'main_tasks' => 'Merakit, menguji, dan merawat perangkat sensor, mikrokontroler, serta sistem kendali otomatis.',
                'general_skills' => 'Troubleshooting teknis, pembacaan skema/dokumentasi, ketelitian, dan keselamatan kerja.',
                'work_environment' => 'Pabrik/industri, laboratorium, bengkel rekayasa, atau proyek sistem pintar.',
                'education_paths' => 'D3/D4/S1 Teknik Elektro / Mekatronika / Instrumentasi atau pelatihan vokasi dan sertifikasi teknis.',
            ],
        ];

        foreach ($professions as $prof) {
            Profession::updateOrCreate(['title' => $prof['title']], $prof);
        }

        // 3. Modul Pusat Materi (sesuai struktur modul klien)
        $materials = [
            [
                'format_type' => 'Modul PDF & Lembar Kerja',
                'title' => 'Modul 1: Mengenal Minat, Bakat & Konsep Diri Remaja',
                'category' => 'Kenali Diri',
                'summary' => 'Panduan reflektif berdasarkan teori perkembangan karier Super untuk siswa SMA/SMK.',
                'key_takeaways' => 'Membahas pemahaman minat, bakat, nilai diri, serta cara menghubungkannya dengan mata pelajaran favorit di sekolah.',
                'content' => 'Gunakan modul ini untuk menuliskan aktivitas yang paling kamu nikmati, mata pelajaran yang memberi energi, serta suasana belajar yang paling membuatmu fokus.',
                'external_url' => null,
            ],
            [
                'format_type' => 'Infografis & Katalog',
                'title' => 'Modul 2: Peta Ragam Profesi Era Digital & Hijau',
                'category' => 'Kartu Profesi',
                'summary' => 'Eksplorasi berbagai bidang pekerjaan masa kini beserta keterampilan abad 21 yang dibutuhkan.',
                'key_takeaways' => 'Memuat gambaran tugas utama, lingkungan kerja, dan keterampilan umum dari berbagai profesi.',
                'content' => 'Bandingkan jenis pekerjaan yang lebih banyak berinteraksi dengan data, orang, atau perangkat teknis sebelum memilih kartu profesi.',
                'external_url' => null,
            ],
            [
                'format_type' => 'Panduan Jalur Lanjut',
                'title' => 'Modul 3: Panduan Jalur Pendidikan (SNBP, SNBT, Vokasi & Kedinasan)',
                'category' => 'Jalur Pendidikan',
                'summary' => 'Informasi ringkas mengenai perbedaan jalur studi lanjutan beserta fokus persiapan masing-masing.',
                'key_takeaways' => 'Membantu membedakan jalur akademik dan vokasi sesuai kondisi serta rencana siswa.',
                'content' => 'Sesuaikan pilihan jalur studi atau pelatihan dengan target keterampilan, durasi belajar, dan kesiapanmu.',
                'external_url' => null,
            ],
            [
                'format_type' => 'Panduan Praktis',
                'title' => 'Modul 4: Strategi Mengatasi Kebingungan Memilih Karier',
                'category' => 'Pengambilan Keputusan',
                'summary' => 'Langkah sistematis membandingkan beberapa alternatif pilihan karier secara realistis.',
                'key_takeaways' => 'Memuat teknik pengenalan keraguan karier dan cara berdiskusi aktif dengan konselor atau wali.',
                'content' => 'Ingat: memilih 2 alternatif untuk dibandingkan bukan berarti mengunci nasib selamanya, melainkan latihan berpikir jernih.',
                'external_url' => null,
            ],
        ];

        foreach ($materials as $item) {
            LearningMaterial::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}