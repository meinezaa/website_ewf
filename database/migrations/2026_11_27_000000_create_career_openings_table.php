<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('career_openings')) {
            return;
        }

        Schema::create('career_openings', function (Blueprint $table): void {
            $table->id();
            $table->string('opening_key', 100)->unique();
            $table->string('type', 20)->index();
            $table->string('title', 180);
            $table->string('category', 100)->nullable();
            $table->string('major', 180)->nullable()->index();
            $table->string('location', 180);
            $table->string('work_system', 120);
            $table->string('education', 180);
            $table->text('qualifications');
            $table->text('description');
            $table->text('benefits');
            $table->string('duration', 120)->nullable();
            $table->string('status', 20)->default('open')->index();
            $table->timestamps();
        });

        $professionals = [
            ['OB', 'ob'],
            ['Resepsionis', 'resepsionis'],
            ['Asisten WPB', 'asisten-wpb'],
            ['Driver', 'driver'],
            ['Staf', 'staf'],
        ];

        foreach ($professionals as $index => [$title, $category]) {
            \Illuminate\Support\Facades\DB::table('career_openings')->insert([
                'opening_key' => 'professional-'.$index,
                'type' => 'professional',
                'title' => $title,
                'category' => $category,
                'location' => 'Kantor · Surabaya',
                'work_system' => 'Work From Office (WFO)',
                'education' => 'Minimal SMA/SMK sederajat',
                'qualifications' => "Pendidikan minimal SMA/SMK sederajat.\nKomunikatif, disiplin, dan bertanggung jawab.\nMampu bekerja secara mandiri maupun bersama tim.",
                'description' => $title.' mendukung kegiatan dan kebutuhan operasional PT Equityworld Futures sesuai penempatan, prosedur, serta arahan atasan.',
                'benefits' => 'Aman, nyaman, untung',
                'duration' => null,
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $programs = [
            'Sistem Informasi' => [
                ['Business Analyst', 'internship-2', 'teknologi'],
                ['System Analyst', 'internship-2', 'teknologi'],
                ['Fullstack Developer', 'internship-2', 'teknologi'],
                ['UI/UX Designer', 'internship-3', 'desain'],
            ],
            'Administrasi Bisnis' => [
                ['Admin & Operasional', 'internship-0', 'administrasi'],
                ['HR Support', 'internship-4', 'operasional'],
                ['Business Development', 'internship-1', 'marketing'],
            ],
            'Manajemen' => [
                ['Manajemen Proyek', 'internship-4', 'operasional'],
                ['HRD', 'internship-4', 'operasional'],
                ['Operasional', 'internship-4', 'operasional'],
            ],
            'Hubungan Internasional' => [
                ['Hubungan Masyarakat', 'internship-1', 'marketing'],
                ['Riset Kebijakan', 'internship-0', 'administrasi'],
                ['Kerja Sama Lembaga', 'internship-1', 'marketing'],
            ],
            'Ilmu Komunikasi' => [
                ['Public Relations', 'internship-1', 'marketing'],
                ['Social Media', 'internship-3', 'desain'],
                ['Content Creator', 'internship-3', 'desain'],
            ],
            'Bisnis Digital' => [
                ['Digital Marketing', 'internship-1', 'marketing'],
                ['E-Commerce', 'internship-1', 'marketing'],
                ['Growth Analyst', 'internship-2', 'teknologi'],
            ],
            'Informatika' => [
                ['Backend Developer', 'internship-2', 'teknologi'],
                ['Frontend Developer', 'internship-2', 'teknologi'],
                ['Data Analyst', 'internship-2', 'teknologi'],
            ],
            'Akuntansi' => [
                ['Staff Akuntansi', 'internship-0', 'administrasi'],
                ['Pajak', 'internship-0', 'administrasi'],
                ['Audit Internal', 'internship-0', 'administrasi'],
            ],
            'Pemasaran' => [
                ['Marketing Communication', 'internship-1', 'marketing'],
                ['Sales & Partnership', 'internship-1', 'marketing'],
                ['Brand Strategy', 'internship-3', 'desain'],
            ],
        ];

        $index = 0;
        foreach ($programs as $major => $fields) {
            foreach ($fields as [$title, $legacyKey, $category]) {
                $index++;
                \Illuminate\Support\Facades\DB::table('career_openings')->insert([
                    'opening_key' => 'internship-program-'.$index,
                    'type' => 'internship',
                    'title' => $title,
                    'category' => $category,
                    'major' => $major,
                    'location' => 'Program Magang & PKL · Surabaya',
                    'work_system' => 'Work From Office (WFO)',
                    'education' => 'SMA/SMK/D3/D4/S1/S2',
                    'qualifications' => "Siswa atau mahasiswa dari jurusan yang sesuai.\nMemiliki kemauan belajar dan mampu bekerja sama.\nBersedia mengikuti ketentuan program.",
                    'description' => 'Program magang '.$title.' memberikan pengalaman kerja langsung sesuai jurusan '.$major.'. Peserta akan mendapatkan bimbingan mentor dan berkontribusi pada kegiatan tim.',
                    'benefits' => 'Sertifikat, mentorship, dan pengalaman kerja profesional',
                    'duration' => 'Sesuai ketentuan program',
                    'status' => 'open',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('career_openings');
    }
};
