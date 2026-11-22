{{-- resources/views/karir.blade.php --}}

@php
    // Cari otomatis foto "bg karir" di public/image (jpg/png/webp; nama pakai spasi, - atau _)
    $bgHero = '';
    foreach (glob(public_path('image/*')) ?: [] as $f) {
        if (preg_match('/^bg[\s_-]*karir/i', pathinfo($f, PATHINFO_FILENAME))) {
            $bgHero = asset('image/' . rawurlencode(basename($f)));
            break;
        }
    }

    $trust = [
        ['bi-shield-check', 'Perusahaan Terverifikasi', 'Informasi legalitas perusahaan telah melalui proses verifikasi sesuai ketentuan yang berlaku.'],
        ['bi-gift', 'Proses Lamaran Tanpa Biaya', 'Pendaftaran dan pengajuan lamaran dilakukan tanpa biaya administrasi maupun pungutan lainnya.'],
        ['bi-graph-up-arrow', 'Proses Transparan', 'Pantau perkembangan lamaran dan lihat setiap tahapan proses seleksi secara langsung melalui akun.'],
        ['bi-person-vcard', 'Satu Akun Terpadu', 'Gunakan satu profil dan CV terverifikasi untuk mengajukan lamaran ke berbagai posisi yang tersedia.'],
    ];

    $lowongan = [
        ['EWF', 'PT Equityworld Futures', 'Kantor · Work From Office', 'OB', ['Work From Office (WFO)', 'Pendidikan min. SMA/SMK'], 'Benefit', 'Aman, nyaman, untung', 'Lowongan dibuka', 'Lihat Karir', true, 'ob'],
        ['EWF', 'PT Equityworld Futures', 'Kantor · Work From Office', 'Resepsionis', ['Work From Office (WFO)', 'Pendidikan min. SMA/SMK'], 'Benefit', 'Aman, nyaman, untung', 'Lowongan dibuka', 'Lihat Karir', true, 'resepsionis'],
        ['EWF', 'PT Equityworld Futures', 'Kantor · Work From Office', 'Asisten WPB', ['Work From Office (WFO)', 'Pendidikan min. SMA/SMK'], 'Benefit', 'Aman, nyaman, untung', 'Lowongan dibuka', 'Lihat Karir', true, 'asisten-wpb'],
        ['EWF', 'PT Equityworld Futures', 'Kantor · Work From Office', 'Driver', ['Work From Office (WFO)', 'Pendidikan min. SMA/SMK'], 'Benefit', 'Aman, nyaman, untung', 'Lowongan dibuka', 'Lihat Karir', true, 'driver'],
        ['EWF', 'PT Equityworld Futures', 'Kantor · Work From Office', 'Staf', ['Work From Office (WFO)', 'Pendidikan min. SMA/SMK'], 'Benefit', 'Aman, nyaman, untung', 'Lowongan dibuka', 'Lihat Karir', true, 'staf'],
    ];
    $lowongan = array_slice($lowongan, 0, 6);
    $professionalCount = count($lowongan);

    $professionalDetails = [
        'professional-0' => [
            'about' => 'OB mendukung kebersihan, kerapian, dan kenyamanan area kerja agar kegiatan kantor berjalan dengan tertib.',
            'tasks' => [
                'Menjaga kebersihan dan kerapian ruang kerja serta area umum.',
                'Membantu menyiapkan ruang rapat dan kebutuhan operasional kantor.',
                'Mendukung penyajian minuman atau kebutuhan tamu sesuai arahan.',
                'Melaporkan kebutuhan perlengkapan kebersihan kepada penanggung jawab.',
            ],
            'qualifications' => [
                'Pendidikan minimal SMA/SMK sederajat.',
                'Rajin, jujur, disiplin, dan bertanggung jawab.',
                'Mampu bekerja mandiri maupun bersama tim.',
                'Memiliki sikap sopan dan perhatian terhadap kebersihan.',
            ],
            'workSystem' => [
                'Penempatan di kantor PT Equityworld Futures.',
                'Bekerja secara Work From Office (WFO).',
                'Jadwal kerja dan penugasan disampaikan saat proses penerimaan.',
            ],
        ],
        'professional-1' => [
            'about' => 'Resepsionis menjadi kontak pertama bagi tamu dan membantu memastikan kebutuhan informasi serta kunjungan ditangani dengan ramah dan profesional.',
            'tasks' => [
                'Menyambut dan mengarahkan tamu sesuai keperluan kunjungan.',
                'Menjawab panggilan telepon dan meneruskan pesan kepada pihak terkait.',
                'Membantu mencatat jadwal kunjungan dan menjaga area penerimaan tetap rapi.',
                'Memberikan informasi umum sesuai arahan perusahaan.',
            ],
            'qualifications' => [
                'Pendidikan minimal SMA/SMK sederajat.',
                'Berpenampilan rapi serta komunikatif dan ramah.',
                'Mampu mengoperasikan komputer dan aplikasi perkantoran dasar.',
                'Teliti, sopan, dan mampu menjaga kerahasiaan informasi.',
            ],
            'workSystem' => [
                'Penempatan di kantor PT Equityworld Futures.',
                'Bekerja secara Work From Office (WFO).',
                'Jadwal kerja dan penugasan disampaikan saat proses penerimaan.',
            ],
        ],
        'professional-2' => [
            'about' => 'Asisten WPB membantu kebutuhan administrasi dan koordinasi kegiatan tim, dengan menjalankan tugas sesuai arahan serta prosedur perusahaan.',
            'tasks' => [
                'Membantu menyiapkan dan merapikan dokumen kerja tim.',
                'Mendukung pencatatan jadwal serta koordinasi kegiatan.',
                'Membantu menindaklanjuti kebutuhan administrasi sesuai arahan.',
                'Menjaga kerapian arsip dan kerahasiaan informasi.',
            ],
            'qualifications' => [
                'Pendidikan minimal SMA/SMK sederajat.',
                'Teliti, disiplin, dan mampu mengelola dokumen.',
                'Mampu mengoperasikan aplikasi perkantoran dasar.',
                'Memiliki komunikasi yang baik dan menjaga kerahasiaan.',
            ],
            'workSystem' => [
                'Penempatan di kantor PT Equityworld Futures.',
                'Bekerja secara Work From Office (WFO).',
                'Tugas dijalankan sesuai arahan dan ketentuan perusahaan.',
            ],
        ],
        'professional-3' => [
            'about' => 'Driver mendukung mobilitas operasional perusahaan dengan mengantar personel atau dokumen sesuai penugasan dan menjaga kendaraan tetap aman serta terawat.',
            'tasks' => [
                'Mengantar personel atau dokumen sesuai jadwal dan penugasan.',
                'Memastikan kendaraan dalam kondisi bersih dan layak digunakan.',
                'Mematuhi peraturan lalu lintas serta prosedur keselamatan.',
                'Melaporkan kondisi kendaraan dan kebutuhan perawatan.',
            ],
            'qualifications' => [
                'Pendidikan minimal SMA/SMK sederajat.',
                'Memiliki SIM yang masih berlaku dan sesuai jenis kendaraan.',
                'Memahami rute perjalanan dan peraturan lalu lintas.',
                'Disiplin, bertanggung jawab, dan mengutamakan keselamatan.',
            ],
            'workSystem' => [
                'Penempatan di PT Equityworld Futures.',
                'Bersedia menjalankan penugasan perjalanan sesuai kebutuhan operasional.',
                'Jadwal kerja dan penugasan disampaikan saat proses penerimaan.',
            ],
        ],
        'professional-4' => [
            'about' => 'Staf mendukung pelaksanaan kegiatan dan kebutuhan operasional perusahaan sesuai penempatan, prosedur, serta arahan atasan.',
            'tasks' => [
                'Membantu menjalankan tugas operasional sesuai penempatan.',
                'Mendukung koordinasi dan tindak lanjut pekerjaan tim.',
                'Menyusun catatan atau laporan pekerjaan sesuai kebutuhan.',
                'Menjaga ketertiban, kualitas, dan ketepatan waktu pekerjaan.',
            ],
            'qualifications' => [
                'Pendidikan minimal SMA/SMK sederajat.',
                'Teliti, disiplin, dan memiliki tanggung jawab kerja.',
                'Mampu berkomunikasi dan bekerja sama dalam tim.',
                'Bersedia mempelajari prosedur dan tugas sesuai penempatan.',
            ],
            'workSystem' => [
                'Penempatan di kantor PT Equityworld Futures.',
                'Bekerja secara Work From Office (WFO).',
                'Tugas dan ketentuan kerja mengikuti kebutuhan perusahaan.',
            ],
        ],
    ];

    $magang = [
        ['EWF', 'PT Equityworld Futures', 'Program Magang & PKL · Work From Office', 'Magang Administrasi', ['Work From Office (WFO)', 'SMA/SMK/D3/D4/S1/S2', 'Durasi sesuai ketentuan program'], '', 'Sertifikat & Mentorship', 'Aman, nyaman, untung', 'Pendaftaran dibuka', 'Lihat Karir', true],
        ['EWF', 'PT Equityworld Futures', 'Program Magang & PKL · Work From Office', 'Magang Marketing', ['Work From Office (WFO)', 'SMA/SMK/D3/D4/S1/S2', 'Durasi sesuai ketentuan program'], '', 'Sertifikat & Mentorship', 'Aman, nyaman, untung', 'Pendaftaran dibuka', 'Lihat Karir', true],
        ['EWF', 'PT Equityworld Futures', 'Program Magang & PKL · Work From Office', 'Magang IT (Teknologi Informasi)', ['Work From Office (WFO)', 'SMA/SMK/D3/D4/S1/S2', 'Durasi sesuai ketentuan program'], '', 'Sertifikat & Mentorship', 'Aman, nyaman, untung', 'Pendaftaran dibuka', 'Lihat Karir', true],
        ['EWF', 'PT Equityworld Futures', 'Program Magang & PKL · Work From Office', 'Magang Desain Grafis & Kreatif', ['Work From Office (WFO)', 'SMA/SMK/D3/D4/S1/S2', 'Durasi sesuai ketentuan program'], '', 'Sertifikat & Mentorship', 'Aman, nyaman, untung', 'Pendaftaran dibuka', 'Lihat Karir', true],
        ['EWF', 'PT Equityworld Futures', 'Program Magang & PKL · Work From Office', 'Magang Operasional', ['Work From Office (WFO)', 'SMA/SMK/D3/D4/S1/S2', 'Durasi sesuai ketentuan program'], '', 'Sertifikat & Mentorship', 'Aman, nyaman, untung', 'Pendaftaran dibuka', 'Lihat Karir', true],
    ];
    $internshipCount = collect($internshipPrograms)->sum(fn ($fields) => count($fields));
    $internshipFields = [
        ['value' => 'administrasi', 'label' => 'Administrasi'],
        ['value' => 'marketing', 'label' => 'Marketing'],
        ['value' => 'teknologi', 'label' => 'IT'],
        ['value' => 'desain', 'label' => 'Desain'],
        ['value' => 'operasional', 'label' => 'Operasional'],
    ];
    $internshipCategories = array_column($internshipFields, 'value');
    $internshipEducationLevels = ['SMA', 'SMK', 'D3', 'D4', 'S1', 'S2'];
    $professionalCategories = [
        ['value' => 'ob', 'label' => 'OB'],
        ['value' => 'resepsionis', 'label' => 'Resepsionis'],
        ['value' => 'asisten-wpb', 'label' => 'Asisten WPB'],
        ['value' => 'driver', 'label' => 'Driver'],
        ['value' => 'staf', 'label' => 'Staf'],
    ];
    $allCareerCategories = [];
    foreach (array_merge($professionalCategories, $internshipFields) as $category) {
        $allCareerCategories[$category['value']] = $category;
    }
    $allCareerCategories = array_values($allCareerCategories);

    $internshipDetails = [
        'internship-0' => [
            'about' => 'Program Magang Administrasi memberikan kesempatan kepada peserta untuk memperoleh pengalaman dalam kegiatan administrasi perusahaan. Peserta akan membantu proses pengelolaan dokumen, pencatatan data, pengarsipan, serta penyusunan laporan sederhana guna mendukung kelancaran aktivitas operasional perusahaan. Melalui program ini, peserta akan mempelajari proses administrasi secara langsung, meningkatkan ketelitian dalam bekerja, serta memahami pentingnya pengelolaan informasi yang terstruktur dan akurat.',
            'tasks' => [
                'Membantu pengelolaan dan pengarsipan dokumen perusahaan.',
                'Melakukan pencatatan, pemeriksaan, dan pembaruan data administrasi.',
                'Membantu menyiapkan laporan dan dokumen pendukung kegiatan perusahaan.',
                'Memastikan kelengkapan dokumen sesuai arahan pembimbing.',
                'Mendukung kebutuhan administrasi dan koordinasi antarbagian.',
            ],
            'qualifications' => [
                'Siswa SMA/SMK atau mahasiswa sesuai persyaratan posisi.',
                'Diutamakan dari jurusan Administrasi Perkantoran, Akuntansi, Manajemen, atau bidang terkait.',
                'Mampu mengoperasikan Microsoft Word dan Excel atau aplikasi perkantoran lainnya.',
                'Memiliki ketelitian, kedisiplinan, dan kemampuan mengelola dokumen.',
                'Mampu berkomunikasi dan bekerja sama dalam tim.',
            ],
            'workSystem' => [
                'Work From Office (WFO) dengan durasi sesuai ketentuan program.',
                'Sertifikat resmi setelah menyelesaikan program magang.',
                'Pengalaman kerja langsung di lingkungan profesional.',
                'Bimbingan dan pendampingan dari mentor berpengalaman.',
                'Pengembangan keterampilan sesuai bidang magang.',
                'Kesempatan membangun portofolio dan relasi profesional.',
                'Kesempatan konversi SKS sesuai kebijakan perguruan tinggi (bagi yang konversi).',
            ],
        ],
        'internship-1' => [
            'about' => 'Program Magang Marketing memberikan kesempatan kepada peserta untuk mempelajari kegiatan pemasaran, komunikasi bisnis, dan pengenalan produk atau layanan perusahaan. Peserta akan terlibat dalam aktivitas pendukung pemasaran, pengumpulan informasi pasar, pengelolaan materi promosi, serta kegiatan komunikasi dengan calon pelanggan. Program ini dirancang untuk membantu peserta memahami strategi pemasaran sekaligus mengembangkan kemampuan komunikasi, kreativitas, dan kerja sama dalam lingkungan profesional.',
            'tasks' => [
                'Membantu pelaksanaan kegiatan pemasaran dan promosi perusahaan.',
                'Mendukung pembuatan materi promosi untuk kebutuhan pemasaran.',
                'Membantu mengumpulkan dan mengelola informasi terkait pasar.',
                'Mendukung kegiatan komunikasi dan pengelolaan informasi calon pelanggan.',
                'Membantu dokumentasi serta pelaporan kegiatan pemasaran.',
            ],
            'qualifications' => [
                'Siswa SMA/SMK atau mahasiswa sesuai persyaratan posisi.',
                'Diutamakan dari jurusan Pemasaran, Bisnis, Komunikasi, Manajemen, atau bidang terkait.',
                'Memiliki kemampuan komunikasi yang baik.',
                'Kreatif, aktif, dan memiliki ketertarikan pada dunia pemasaran.',
                'Mampu bekerja sama dalam tim dan memiliki kemauan untuk belajar.',
            ],
            'workSystem' => [
                'Work From Office (WFO) dengan durasi sesuai ketentuan program.',
                'Sertifikat resmi setelah menyelesaikan program magang.',
                'Pengalaman kerja langsung di lingkungan profesional.',
                'Bimbingan dan pendampingan dari mentor berpengalaman.',
                'Pengembangan keterampilan sesuai bidang magang.',
                'Kesempatan membangun portofolio dan relasi profesional.',
                'Kesempatan konversi SKS sesuai kebijakan perguruan tinggi (bagi yang konversi).',
            ],
        ],
        'internship-2' => [
            'about' => 'Program Magang Teknologi Informasi memberikan kesempatan kepada peserta untuk memperoleh pengalaman dalam penerapan teknologi guna mendukung kegiatan operasional perusahaan. Peserta akan membantu berbagai aktivitas yang berkaitan dengan pengelolaan perangkat komputer, dukungan teknis, pengolahan data digital, serta pemeliharaan sistem informasi. Untuk posisi tertentu, peserta juga dapat terlibat dalam kegiatan pengembangan website, pengelolaan basis data, dan pengujian aplikasi sesuai kebutuhan perusahaan. Program ini bertujuan meningkatkan pemahaman peserta mengenai teknologi informasi melalui pengalaman kerja secara langsung.',
            'tasks' => [
                'Membantu pemeriksaan dan pemeliharaan perangkat komputer.',
                'Memberikan dukungan teknis dasar terkait perangkat dan aplikasi perusahaan.',
                'Membantu pengelolaan data serta dokumentasi sistem informasi.',
                'Mendukung pengujian dan pemeriksaan fungsi aplikasi atau website.',
                'Membantu menyelesaikan permasalahan teknis sesuai arahan pembimbing.',
            ],
            'qualifications' => [
                'Siswa SMA/SMK atau mahasiswa sesuai persyaratan posisi.',
                'Diutamakan dari jurusan RPL, TKJ, Informatika, Sistem Informasi, atau bidang terkait.',
                'Memiliki ketertarikan pada teknologi dan perkembangan sistem informasi.',
                'Memahami dasar penggunaan komputer dan aplikasi perkantoran.',
                'Memiliki kemampuan berpikir logis, teliti, dan mampu memecahkan masalah.',
            ],
            'workSystem' => [
                'Work From Office (WFO) dengan durasi sesuai ketentuan program.',
                'Sertifikat resmi setelah menyelesaikan program magang.',
                'Pengalaman kerja langsung di lingkungan profesional.',
                'Bimbingan dan pendampingan dari mentor berpengalaman.',
                'Pengembangan keterampilan sesuai bidang magang.',
                'Kesempatan membangun portofolio dan relasi profesional.',
                'Kesempatan konversi SKS sesuai kebijakan perguruan tinggi (bagi yang konversi).',
            ],
        ],
        'internship-3' => [
            'about' => 'Program Magang Desain Grafis dan Kreatif memberikan kesempatan kepada peserta untuk mengembangkan kemampuan dalam pembuatan materi visual yang menarik dan informatif. Peserta akan membantu proses perancangan konten visual untuk kebutuhan komunikasi, publikasi, dan promosi perusahaan. Kegiatan dapat mencakup pembuatan desain media sosial, poster, banner, presentasi, serta materi visual lainnya sesuai kebutuhan perusahaan. Melalui program ini, peserta akan mempelajari proses kreatif, penerapan identitas visual perusahaan, serta pengembangan kemampuan desain dalam lingkungan kerja profesional.',
            'tasks' => [
                'Membantu membuat desain konten untuk media sosial perusahaan.',
                'Membuat materi visual seperti poster, banner, dan media promosi.',
                'Membantu menyiapkan desain presentasi dan kebutuhan publikasi.',
                'Menyesuaikan desain dengan identitas visual perusahaan.',
                'Melakukan revisi desain berdasarkan arahan pembimbing.',
            ],
            'qualifications' => [
                'Siswa SMA/SMK atau mahasiswa sesuai persyaratan posisi.',
                'Diutamakan dari jurusan DKV, Multimedia, Desain Grafis, atau bidang terkait.',
                'Memiliki kreativitas dan ketertarikan pada bidang desain visual.',
                'Memahami penggunaan aplikasi desain seperti Canva, Photoshop, atau Illustrator.',
                'Memiliki kemampuan komunikasi, ketelitian, dan kemauan menerima masukan.',
            ],
            'workSystem' => [
                'Work From Office (WFO) dengan durasi sesuai ketentuan program.',
                'Sertifikat resmi setelah menyelesaikan program magang.',
                'Pengalaman kerja langsung di lingkungan profesional.',
                'Bimbingan dan pendampingan dari mentor berpengalaman.',
                'Pengembangan keterampilan sesuai bidang magang.',
                'Kesempatan membangun portofolio dan relasi profesional.',
                'Kesempatan konversi SKS sesuai kebijakan perguruan tinggi (bagi yang konversi).',
            ],
        ],
        'internship-4' => [
            'about' => 'Program Magang Operasional memberikan kesempatan kepada peserta untuk memahami proses kegiatan operasional perusahaan dan koordinasi antarbagian. Peserta akan membantu pelaksanaan aktivitas operasional sehari-hari, pengelolaan informasi kegiatan, pencatatan data, serta penyusunan dokumentasi yang mendukung kelancaran pekerjaan perusahaan. Melalui program ini, peserta akan memperoleh pemahaman mengenai alur kerja perusahaan, penerapan prosedur kerja, serta pentingnya kedisiplinan dan tanggung jawab dalam menyelesaikan pekerjaan.',
            'tasks' => [
                'Membantu pelaksanaan kegiatan operasional perusahaan.',
                'Melakukan pencatatan dan pengelolaan data kegiatan operasional.',
                'Membantu menyiapkan dokumen dan kebutuhan administrasi operasional.',
                'Mendukung koordinasi kegiatan antarbagian.',
                'Membantu menyusun dokumentasi dan laporan aktivitas operasional.',
            ],
            'qualifications' => [
                'Siswa SMA/SMK atau mahasiswa sesuai persyaratan posisi.',
                'Diutamakan dari jurusan Manajemen, Administrasi, Bisnis, atau bidang terkait.',
                'Memiliki kemampuan komunikasi dan pengorganisasian yang baik.',
                'Mampu bekerja secara teliti, disiplin, dan bertanggung jawab.',
                'Mampu bekerja sama dalam tim dan mengikuti prosedur kerja.',
            ],
            'workSystem' => [
                'Work From Office (WFO) dengan durasi sesuai ketentuan program.',
                'Sertifikat resmi setelah menyelesaikan program magang.',
                'Pengalaman kerja langsung di lingkungan profesional.',
                'Bimbingan dan pendampingan dari mentor berpengalaman.',
                'Pengembangan keterampilan sesuai bidang magang.',
                'Kesempatan membangun portofolio dan relasi profesional.',
                'Kesempatan konversi SKS sesuai kebijakan perguruan tinggi (bagi yang konversi).',
            ],
        ],
    ];

    $trackingStages = [
        ['label' => 'Tahap 1', 'status' => 'Selesai', 'title' => 'Lamaran diterima', 'description' => 'Lamaran dan dokumen Anda berhasil dikirim.', 'state' => 'done'],
        ['label' => 'Tahap 2', 'status' => 'Menunggu', 'title' => 'Verifikasi berkas', 'description' => 'Tim rekrutmen akan meninjau kelengkapan dokumen.', 'state' => 'active'],
        ['label' => 'Tahap 3', 'status' => 'Menunggu', 'title' => 'Seleksi dan wawancara', 'description' => 'Jadwal wawancara akan ditampilkan setelah diumumkan oleh tim HR.', 'state' => 'wait'],
        ['label' => 'Tahap 4', 'status' => 'Menunggu', 'title' => 'Hasil akhir', 'description' => 'Hasil seleksi akan diperbarui setelah proses selesai.', 'state' => 'wait'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Karir | PT Equityworld Futures</title>
@vite(['resources/css/app.css'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">


 </head>
<body class="career-page">
@include('partials.header')

<div class="krr" @if($bgHero) style="--career-hero-image: url('{{ $bgHero }}')" @endif>

    {{-- HERO --}}
    <section class="hero">
        <span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span>
        <div class="wrap">
            <div>
                <span class="chip"><i class="bi bi-patch-check-fill" style="color:var(--ok)"></i> Perusahaan Terverifikasi Legalitas Resmi &amp; BAPPEBTI</span>
                <h1>Bangun <span>Masa Depan</span> dan Kembangkan Potensi Bersama Kami.</h1>
                <p class="lead">Temukan lowongan kerja dan magang melalui informasi lowongan resmi dari perusahaan. Ajukan lamaran dengan mudah, unggah dokumen persyaratan secara online, dan pantau setiap tahapan seleksi dengan informasi yang jelas dan transparan.</p>
            </div>

            <div class="float">
                <div class="head">
                    <img class="brand-logo" src="{{ asset('image/logo ewf.png') }}" alt="Logo EWF">
                    <span class="brand-name">PT Equityworld Futures</span>
                    <span class="tag g">Resmi</span>
                </div>
                <div class="mini">
                    <div><small>Tingkat Validitas</small><b>100% <i class="bi bi-check-circle-fill" style="color:var(--ok);font-size:16.5px"></i></b></div>
                    <div><small>Biaya Seleksi</small><b class="free">Gratis Rp 0</b></div>
                </div>
                <div class="safe"><i class="bi bi-shield-check"></i><span>Perlindungan Pelamar<br><small style="color:var(--mut);font-weight:600">100% Aman &amp; Akurat</small></span></div>
            </div>
        </div>
    </section>

    {{-- PENCARIAN --}}
    <div class="wrap">
        <div class="tabs">
            @if($applications->isEmpty())
                <a href="#lowongan" class="on" data-career-pane="job"><i class="bi bi-briefcase"></i> Lowongan Kerja Profesional <em>{{ $professionalCount }} Aktif</em></a>
                <a href="#magang" data-career-pane="mg"><i class="bi bi-mortarboard"></i> Program Magang &amp; PKL <em>{{ $internshipCount }} Terbuka</em></a>
                <a href="#saved" class="saved-link"><i class="bi bi-bookmark-heart"></i> Tersimpan <em data-saved-count>0</em></a>
            @else
                <span class="chip"><i class="bi bi-briefcase"></i> Lowongan Kerja Profesional</span>
                <span class="chip"><i class="bi bi-mortarboard"></i> Program Magang &amp; PKL</span>
            @endif
        </div>
        <form class="search{{ $applications->isEmpty() ? '' : ' tracking-search' }}" action="#" method="GET" @if(!$applications->isEmpty()) id="trackingSearchForm" @endif>
            @if($applications->isEmpty())
                <label class="field"><i class="bi bi-search"></i><span style="flex:1"><small>Posisi</small><input name="q" placeholder="Misal: OB, Resepsionis, atau Driver"></span></label>
                <label class="field"><i class="bi bi-briefcase"></i><span style="flex:1"><small>Jenis Karir</small>
                    <select name="tipe" id="homeCareerType"><option value="profesional">Lowongan Kerja</option><option value="magang">Magang/PKL</option></select></span></label>
                <label class="field"><i class="bi bi-grid"></i><span style="flex:1"><small>Bidang</small>
                    <select name="bidang" id="homeCareerCategory">
                        <option value="" selected disabled hidden>Pilih bidang</option>
                        @foreach($professionalCategories as $category)
                            <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                        @endforeach
                    </select></span></label>
                <button class="btn" type="submit">Cari Peluang</button>
            @else
                <label class="field"><i class="bi bi-search"></i><span style="flex:1"><small>Posisi, nama, atau tahap lamaran</small><input name="q" autocomplete="off" placeholder="Cari di status lamaran Anda"></span></label>
                <button class="btn" type="submit"><i class="bi bi-search"></i> Cari Status</button>
            @endif
        </form>
        @if($applications->isEmpty())
            <div class="popular-terms home-popular-terms" aria-label="Riwayat pencarian" hidden>
                <b>Riwayat pencarian:</b>
                <div class="popular-list" data-popular-list></div>
                <button class="history-clear" type="button" data-clear-search-history>Hapus riwayat</button>
            </div>
        @else
            <p class="tracking-search-feedback" id="trackingSearchStatus" role="status">Cari posisi, nama pelamar, atau tahapan dalam status lamaran Anda.</p>
        @endif
        <div class="trust">
            @foreach($trust as $t)
                <div class="card"><div class="ico"><i class="bi {{ $t[0] }}"></i></div>
                    <div><h3>{{ $t[1] }}</h3><p>{{ $t[2] }}</p></div></div>
            @endforeach
        </div>
    </div>

    @if($applications->isEmpty())
    {{-- JALUR --}}
    <section class="s band">
        <div class="wrap center">
            <span class="pill"><i class="bi bi-funnel"></i> Dua jalur pertumbuhan karir resmi</span>
            <h2 class="sec-title">Pilih Jalur yang Sesuai dengan Fase Anda</h2>
            <p class="sec-sub">Kami membedakan standar seleksi, hak kompensasi, dan fasilitas pengembangan untuk profesional siap kerja maupun mahasiswa &amp; fresh graduate.</p>

            <div class="paths">
                <div class="path">
                    <span class="badge"><i class="bi bi-briefcase"></i> LOWONGAN KERJA</span>
                    <div class="hd"><div class="ico"><i class="bi bi-briefcase"></i></div><div><small>JALUR 01 · KARIER PROFESIONAL</small><h3>Lowongan Kerja</h3></div></div>
                    <p>Ditujukan bagi individu yang ingin mengembangkan karier dan bergabung bersama PT Equityworld Futures melalui berbagai posisi pekerjaan yang tersedia.</p>
                    <div class="feats">
                        <div class="feat"><b><i class="bi bi-wallet2"></i> Gaji dan Tunjangan</b>Sesuai posisi dan ketentuan perusahaan.</div>
                        <div class="feat"><b><i class="bi bi-file-earmark-text"></i> Kontrak Kerja yang Jelas</b>Perjanjian kerja sesuai ketentuan.</div>
                        <div class="feat"><b><i class="bi bi-graph-up-arrow"></i> Peluang Pengembangan Karier</b>Kesempatan mengembangkan kemampuan.</div>
                        <div class="feat"><b><i class="bi bi-trophy"></i> Apresiasi Kinerja</b>Penghargaan berdasarkan pencapaian.</div>
                    </div>
                    <div class="foot"><span>{{ $professionalCount }} Lowongan Kerja Dibuka</span><a href="#lowongan" data-explore="job" class="btn sm">Jelajahi Lowongan Kerja <i class="bi bi-arrow-down"></i></a></div>
                </div>

                <div class="path">
                    <span class="badge"><i class="bi bi-mortarboard"></i> PROGRAM MAGANG &amp; PKL</span>
                    <div class="hd"><div class="ico"><i class="bi bi-mortarboard"></i></div><div><small>JALUR 02 · SISWA &amp; MAHASISWA</small><h3>Program Magang dan PKL</h3></div></div>
                    <p>Terbuka bagi siswa SMA/SMK dan mahasiswa perguruan tinggi yang ingin memperoleh pengalaman kerja, mengembangkan keterampilan, serta mengenal lingkungan kerja secara langsung.</p>
                    <div class="feats">
                        <div class="feat"><b><i class="bi bi-briefcase"></i> Pengalaman Kerja Langsung</b>Belajar dan terlibat dalam kegiatan kerja.</div>
                        <div class="feat"><b><i class="bi bi-people"></i> Bimbingan Tim Profesional</b>Arahan dan pendampingan selama program.</div>
                        <div class="feat"><b><i class="bi bi-lightning-charge"></i> Pengembangan Keterampilan</b>Meningkatkan kemampuan dan kerja sama.</div>
                        <div class="feat"><b><i class="bi bi-patch-check"></i> Sertifikat Program</b>Sertifikat setelah menyelesaikan program.</div>
                    </div>
                    <div class="foot"><span>{{ $internshipCount }} Program Magang &amp; PKL</span><a href="#magang" data-explore="mg" class="btn sm">Jelajahi Program Magang <i class="bi bi-arrow-down"></i></a></div>
                </div>
            </div>
        </div>
    </section>

    {{-- DAFTAR --}}
    <section class="s listbg" id="lowongan">
        <div class="wrap">
            <div class="head-row">
                <div><span class="pill"><i class="bi bi-patch-check"></i> Pilihan peluang karir terverifikasi</span>
                    <h2 class="sec-title">Daftar Lowongan &amp; Program Magang</h2>
                    <p class="sec-sub">Pilih kategori jalur karir di bawah untuk melihat rincian kualifikasi dan paket benefit.</p></div>
                <div class="switch" id="krSwitch">
                    <button class="on" data-t="job"><i class="bi bi-briefcase"></i> Lowongan Kerja ({{ $professionalCount }})</button>
                    <button data-t="mg"><i class="bi bi-mortarboard"></i> Program Magang ({{ $internshipCount }})</button>
                </div>
            </div>

            <div data-pane="job">
                <div class="grp"><h3>Lowongan Profesional (Full-Time &amp; Freelance)</h3><span class="tag">Kualifikasi: Berpengalaman</span><a href="#jobs?type=profesional" class="link">Lihat Semua Lowongan Kerja <i class="bi bi-chevron-right"></i></a></div>
                <div class="jobs">
                    @foreach($lowongan as $j)
                        <div class="card job"
                             data-job-id="professional-{{ $loop->index }}"
                             data-career-category="{{ $j[10] }}"
                             data-career-type="profesional"
                             data-career-title="{{ $j[3] }}"
                             data-career-company="{{ $j[1] }}"
                             data-career-company-info="{{ $j[2] }}"
                             data-career-work-system="{{ $j[4][0] }}"
                             data-career-benefit="{{ $j[6] }}"
                             data-career-posted="{{ $j[7] }}"
                             data-career-applicants="0"
                             data-career-sample="false">
                            <i class="bi bi-bookmark mark"></i>
                            <div class="co"><div class="logo"><img src="{{ asset('image/logo ewf.png') }}" alt="Logo EWF"></div><div><b>{{ $j[1] }} @if($loop->index < 3)<i class="bi bi-patch-check-fill" style="color:var(--o)"></i>@endif</b><small>{{ $j[2] }}</small></div></div>
                            <h4>{{ $j[3] }}</h4>
                            <div class="meta">@foreach($j[4] as $m)<span>{{ $m }}</span>@endforeach</div>
                            <div class="pay"><small>{{ $j[5] }}</small><b>{{ $j[6] }}</b></div>
                            <div class="bot"><span class="{{ $j[9] ? 'live' : '' }}"><i class="bi bi-{{ $j[9] ? 'broadcast' : 'clock' }}"></i> {{ $j[7] }}</span><a href="#job/professional-{{ $loop->index }}" class="btn sm {{ $j[9] ? '' : 'ghost' }}">{{ $j[8] }}</a></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div data-pane="mg" id="magang" style="display:none">
                <div class="grp"><h3>Pilih Jurusan &amp; Bidang Magang</h3><span class="tag">{{ $internshipCount }} pilihan bidang</span><a href="#jobs?type=magang" class="link">Jelajahi Program Magang <i class="bi bi-chevron-right"></i></a></div>
                <p class="sec-sub internship-intro">Pilih jurusan yang paling sesuai, lalu tentukan bidang yang ingin dipelajari. Pilihan ini akan tercantum di formulir pendaftaran.</p>
                <div class="internship-majors">
                    @foreach($internshipPrograms as $major => $fields)
                        <article class="card internship-major-card" @if($loop->index >= 6) hidden @endif>
                            <div class="co">
                                <div class="logo"><img src="{{ asset('image/logo ewf.png') }}" alt="Logo EWF"></div>
                                <div><b>PT Equityworld Futures <i class="bi bi-patch-check-fill internship-company-badge" aria-label="Perusahaan terverifikasi"></i></b><small>Program Magang &amp; PKL · Work From Office</small></div>
                            </div>
                            <h4>{{ $major }}</h4>
                            <div class="meta"><span>Work From Office (WFO)</span><span>SMA/SMK/D3/D4/S1/S2</span><span>Durasi sesuai program</span></div>
                            <div class="internship-field-choice">
                                <label for="internshipField{{ $loop->index }}">Pilih bidang untuk jurusan ini</label>
                                <select id="internshipField{{ $loop->index }}" data-internship-field required>
                                    <option value="" selected disabled hidden>Pilih bidang</option>
                                    @foreach($fields as $field)
                                        <option value="{{ $field['label'] }}" data-job-id="{{ $field['job_id'] }}">{{ $field['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pay"><small>Benefit</small><b>Aman, nyaman, untung</b></div>
                            <div class="bot">
                                <span class="live"><i class="bi bi-broadcast"></i> Pendaftaran dibuka</span>
                                <button class="btn sm" type="button" data-internship-apply data-major="{{ $major }}">Lihat Karir</button>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="jobs" hidden aria-hidden="true">
                    @foreach($magang as $j)
                        <div class="card job"
                             data-job-id="internship-{{ $loop->index }}"
                             data-career-category="{{ $internshipCategories[$loop->index] }}"
                             data-career-type="magang"
                             data-career-title="{{ $j[3] }}"
                             data-career-company="{{ $j[1] }}"
                             data-career-company-info="{{ $j[2] }}"
                             data-career-work-system="{{ $j[4][0] }}"
                             data-career-education="{{ implode(',', $internshipEducationLevels) }}"
                             data-career-benefit="{{ $j[7] }}"
                             data-career-posted="{{ $j[8] }}"
                             data-career-applicants="0"
                             data-career-sample="false">
                            <i class="bi bi-bookmark mark"></i>
                            <div class="co"><div class="logo">@if($j[1] === 'PT Equityworld Futures')<img src="{{ asset('image/logo ewf.png') }}" alt="Logo EWF">@else{{ $j[0] }}@endif</div><div><b>{{ $j[1] }} @if($loop->index < 3)<i class="bi bi-patch-check-fill" style="color:var(--o)"></i>@endif</b><small>{{ $j[2] }}</small></div></div>
                            <h4>{{ preg_replace('/^Magang\s+/u', '', $j[3]) }}</h4>
                            <div class="meta">@foreach($j[4] as $m)<span>{{ $m }}</span>@endforeach</div>
                            <div class="pay"><small>{{ $j[6] }}</small><b>{{ $j[7] }}</b></div>
                            <div class="bot"><span><i class="bi bi-people"></i> {{ $j[8] }}</span><a href="#job/internship-{{ $loop->index }}" class="btn sm {{ $j[10] ? '' : 'ghost' }}">{{ $j[9] }}</a></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="wrap">
        <div class="cta">
            <span class="eyebrow"><i class="bi bi-shield-lock"></i> Komitmen keamanan rekrutmen</span>
            <h2>Wujudkan Langkah Karier Anda Bersama Kami</h2>
            <p>Temukan peluang kerja dan magang yang sesuai dengan potensi Anda. Buat profil, ajukan lamaran, dan pantau proses seleksi dengan mudah melalui satu platform.</p>
            @guest
                <a href="{{ route('daftar', ['next' => request()->getPathInfo()]) }}" class="btn" data-return-current>Daftar Sebagai Pelamar</a>
            @endguest
        </div>
    </section>

    <section class="career-explorer" id="careerExplorer" hidden>
        <div class="career-wrap">
            <div class="career-crumb"><a href="{{ route('karir') }}">Karir</a><i class="bi bi-chevron-right"></i><span>Temukan lowongan</span></div>
            <div class="career-heading">
                <div><h1>Temukan peluang karier</h1><p>Jelajahi lowongan terverifikasi yang sesuai dengan keahlian Anda.</p></div>
                <a class="btn sm ghost" href="#saved"><i class="bi bi-bookmark-heart"></i> Tersimpan <b data-saved-count>0</b></a>
            </div>
            <form class="explorer-search" id="explorerSearch">
                <label><i class="bi bi-search"></i><input id="explorerQuery" type="search" placeholder="Cari posisi, keahlian, atau perusahaan" aria-label="Cari posisi, keahlian, atau perusahaan"></label>
                <button class="btn" type="submit"><i class="bi bi-search"></i> Cari lowongan</button>
            </form>
            <div class="popular-terms explorer-popular-terms" aria-label="Riwayat pencarian" hidden><b>Riwayat pencarian:</b><div class="popular-list" data-popular-list></div><button class="history-clear" type="button" data-clear-search-history>Hapus riwayat</button></div>
            <div class="explorer-layout">
                <aside class="explorer-filter">
                    <div class="filter-heading"><b><i class="bi bi-sliders"></i> Filter</b><button type="button" class="filter-clear">Atur ulang</button></div>
                    <div class="filter-group">
                        <label id="filterCategoryLabel" for="filterCategory">Bidang pekerjaan</label>
                        <select id="filterCategory">
                            <option value="" selected disabled hidden>Pilih bidang</option>
                            @foreach($allCareerCategories as $category)
                                <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="explorerSort">Urutkan lowongan</label>
                        <select id="explorerSort" aria-label="Urutkan lowongan"><option value="title">Nama posisi (A–Z)</option><option value="posted">Postingan terbaru</option><option value="oldest">Postingan terlama</option></select>
                    </div>
                    <input type="hidden" id="filterType">
                </aside>
                <div class="explorer-result">
                    <div class="result-heading"><b id="explorerCount" role="status" aria-live="polite">Lowongan tersedia</b></div>
                    <div class="explorer-jobs" id="explorerJobs"></div>
                    <div class="explorer-empty" id="explorerEmpty" hidden><i class="bi bi-search"></i>Tidak ada lowongan yang cocok. Coba ubah kata kunci atau filter.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="career-detail" id="careerDetail" hidden>
        <div class="career-wrap">
            <div class="career-crumb"><a href="{{ route('karir') }}">Karir</a><i class="bi bi-chevron-right"></i><a href="#jobs" class="detail-back">Lowongan</a><i class="bi bi-chevron-right"></i><span id="detailBreadcrumb">Detail posisi</span></div>
            <div class="detail-top">
                <div class="detail-top-main">
                    <div class="detail-topline">
                        <div class="logo" id="detailLogo"></div>
                        <div class="detail-heading"><h1 id="detailTitle"></h1><div class="detail-company"><span id="detailCompany"></span><i class="bi bi-patch-check-fill verified-icon" id="detailVerifiedIcon" aria-label="Perusahaan terverifikasi"></i><span class="verified-label" id="detailVerifiedLabel">Perusahaan terverifikasi</span></div></div>
                    </div>
                    <div class="detail-meta">
                        <div class="detail-metric"><i class="bi bi-cash-coin"></i><div><span>Benefit</span><b id="detailBenefitMeta"></b></div></div>
                        <div class="detail-metric"><i class="bi bi-building"></i><div><span>Sistem kerja</span><b id="detailWorkSystemMeta"></b></div></div>
                        <div class="detail-metric"><i class="bi bi-file-earmark-text"></i><div><span>Jenis karir</span><b id="detailTypeMeta"></b></div></div>
                    </div>
                    <div class="detail-tags" id="detailTags"></div>
                    <div class="detail-program-picker" id="detailInternshipPicker" hidden>
                        <label for="detailInternshipMajor">Jurusan program</label>
                        <select id="detailInternshipMajor">
                            <option value="" selected disabled hidden>Pilih jurusan</option>
                        </select>
                        <label for="detailInternshipField">Bidang magang</label>
                        <select id="detailInternshipField" disabled>
                            <option value="" selected disabled hidden>Pilih bidang</option>
                        </select>
                        <p id="detailInternshipStatus" role="status">Pilih jurusan, lalu bidang magang yang ingin dilamar.</p>
                    </div>
                </div>
                <div class="detail-top-right">
                    <div class="detail-actions">
                        <button class="btn sm" type="button" id="detailApply"><i class="bi bi-send"></i> Lamar sekarang</button>
                        <button class="btn sm ghost" type="button" id="detailSave"><i class="bi bi-bookmark"></i> Simpan</button>
                        <button class="btn sm ghost" type="button" id="detailShare"><i class="bi bi-share"></i> Bagikan</button>
                    </div>
                    <div class="detail-posted"><i class="bi bi-clock"></i> <span id="detailPosted"></span></div>
                    <div class="applicant-summary"><i class="bi bi-people"></i><div><b id="detailApplicants"></b><small>Jumlah pelamar masih data contoh; sistem lamaran belum terhubung.</small></div></div>
                    <div class="apply-notice" id="applyNotice" role="status">Pengajuan lamaran online belum tersedia untuk posisi ini. Silakan hubungi perusahaan melalui kanal resminya.</div>
                </div>
            </div>
            <div class="detail-grid">
                <div class="detail-main">
                    <article class="detail-panel"><h2><i class="bi bi-file-text"></i> Deskripsi pekerjaan</h2><p id="detailDescription"></p></article>
                    <article class="detail-panel"><h2><i class="bi bi-list-check"></i> Tanggung jawab</h2><ul id="detailResponsibilities"></ul></article>
                    <article class="detail-panel"><h2><i class="bi bi-person-check"></i> Kualifikasi</h2><ul id="detailQualifications"></ul></article>
                    <article class="detail-panel"><h2><i class="bi bi-gift"></i> Sistem kerja &amp; benefit</h2><ul id="detailBenefits"></ul></article>
                    <article class="detail-panel"><h2><i class="bi bi-diagram-3"></i> Tahapan seleksi</h2><ul id="detailStages"></ul></article>
                </div>
                <aside class="detail-side">
                    <section class="detail-panel"><h2><i class="bi bi-building"></i> Tentang perusahaan</h2>
                        <div class="company-mini"><div class="logo" id="detailCompanyLogo"></div><div><b id="detailCompanyName"></b><small id="detailCompanyInfo"></small></div></div>
                        <div class="detail-salary">Benefit<b id="detailSalary"></b></div>
                    </section>
                    <section class="detail-panel"><h2><i class="bi bi-shield-check"></i> Informasi aman</h2><div class="detail-note">Jangan pernah membayar biaya pendaftaran atau seleksi. Pastikan komunikasi lamaran dilakukan melalui kanal resmi perusahaan.</div></section>
                    <section class="detail-panel"><h2><i class="bi bi-stars"></i> Lowongan serupa</h2><div class="detail-similar" id="detailSimilar"></div></section>
                </aside>
            </div>
        </div>
    </section>

    <section class="career-apply" id="careerApply" hidden>
        <div class="career-wrap apply-wrap">
            <div class="career-crumb"><a href="{{ route('karir') }}">Karir</a><i class="bi bi-chevron-right"></i><a href="#jobs" class="application-back">Lowongan</a><i class="bi bi-chevron-right"></i><span>Profil &amp; CV</span></div>
            <div class="apply-heading">
                <div>
                    <h1>Profil &amp; CV</h1>
                    <p>Lengkapi profil agar tim rekrutmen dapat mengenal pengalaman dan potensi Anda.</p>
                    <span class="apply-position"><i class="bi bi-briefcase"></i><span id="applicationJobTitle">Posisi yang dilamar</span></span>
                </div>
            </div>

            @if(session('application_submitted'))
                <div class="apply-success" role="status">
                    <b><i class="bi bi-check-circle"></i> Berkas lamaran berhasil dikirim</b>
                    Nomor pendaftaran lamaran: <strong>{{ session('application_reference') }}</strong>. Kode ini digunakan untuk mengenali lamaran Anda.
                </div>
            @endif
            @if($errors->any())
                <div class="apply-errors" role="alert">
                    <b>Periksa kembali data lamaran Anda.</b>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="apply-progress" aria-live="polite">
                <div class="apply-progress-value" id="applicationProgressValue">0%</div>
                <div class="apply-progress-copy">
                    <b>Profil Anda <span id="applicationProgressText">0%</span> lengkap</b>
                    <span id="applicationProgressDescription">Kelengkapan mengikuti jenis formulir dan isian yang sudah diisi.</span>
                    <div class="apply-progress-track"><i id="applicationProgressBar"></i></div>
                </div>
                <i class="bi bi-stars" style="color:var(--o);font-size:22px" aria-hidden="true"></i>
            </div>

            <div class="apply-layout">
                <main class="apply-main">
                    <form id="careerApplicationForm" data-user-id="{{ auth()->id() }}" data-application-submitted="{{ session('application_submitted') ? 'true' : 'false' }}" action="{{ route('karir.apply.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="job_id" id="applicationJobId" value="{{ old('job_id') }}">
                        <div class="apply-main">
                            <section class="apply-card">
                                <div class="apply-card-heading">
                                    <div><h2><i class="bi bi-person-vcard"></i> Profil</h2><p>Informasi ini akan digunakan untuk menghubungi Anda terkait lamaran.</p></div>
                                </div>
                                <div class="apply-form-grid">
                                    <div class="apply-field">
                                        <label for="applicationName">Nama lengkap</label>
                                        <input id="applicationName" name="full_name" value="{{ old('full_name', auth()->user()?->name ?? '') }}" autocomplete="name" required maxlength="150" data-profile-required>
                                        @error('full_name')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="apply-field">
                                        <label for="applicationEmail">Alamat email</label>
                                        <input id="applicationEmail" type="email" name="email" value="{{ old('email', auth()->user()?->email ?? '') }}" autocomplete="email" required maxlength="255" readonly data-profile-required>
                                        @error('email')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="apply-field">
                                        <label for="applicationPhone">Nomor WhatsApp</label>
                                        <input id="applicationPhone" type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone ?? '') }}" autocomplete="tel" required maxlength="40" placeholder="+62..." data-profile-required>
                                        @error('phone')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="apply-field">
                                        <label for="applicationLocation">Kota domisili</label>
                                        <input id="applicationLocation" name="location" value="{{ old('location', auth()->user()?->location ?? '') }}" autocomplete="address-level2" required maxlength="150" placeholder="Contoh: Jakarta Selatan" data-profile-required>
                                        @error('location')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="apply-field">
                                        <label for="applicationBirthDate">Tanggal lahir</label>
                                        <input id="applicationBirthDate" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ now()->toDateString() }}" required data-profile-required>
                                        @error('date_of_birth')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="apply-field">
                                        <label for="applicationGender">Jenis kelamin (opsional)</label>
                                        <select id="applicationGender" name="gender">
                                            <option value="" hidden @selected(!old('gender'))></option>
                                            <option value="perempuan" @selected(old('gender') === 'perempuan')>Perempuan</option>
                                            <option value="laki-laki" @selected(old('gender') === 'laki-laki')>Laki-laki</option>
                                        </select>
                                    </div>
                                    <div class="apply-field">
                                        <label for="applicationPortfolio">Portofolio / LinkedIn (opsional)</label>
                                        <input id="applicationPortfolio" type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" maxlength="255" placeholder="https://">
                                        @error('portfolio_url')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </section>

                            <section class="apply-card" data-professional-only>
                                <div class="apply-card-heading">
                                    <div><h2><i class="bi bi-briefcase"></i> Pengalaman kerja/magang/organisasi</h2><p>Tambahkan pengalaman yang relevan. Anda dapat menambah atau menghapus beberapa riwayat.</p></div>
                                    <button class="apply-add" type="button" data-add-row="experience"><i class="bi bi-plus-lg"></i> Tambah</button>
                                </div>
                                <div class="apply-repeat-list" id="experienceList">
                                    <div class="apply-repeat-item" data-repeat-item>
                                        <button class="apply-remove" type="button" aria-label="Hapus pengalaman" data-remove-row><i class="bi bi-x-lg"></i></button>
                                        <div class="apply-form-grid">
                                            <div class="apply-field" data-internship-only hidden><label>Jenis pengalaman</label><select name="experiences[0][type]"><option value="" hidden @selected(!old('experiences.0.type'))>Pilih jenis pengalaman</option>@foreach(['Pengalaman magang/PKL','Organisasi','Kepanitiaan','Freelance','Projek akademik','Lainnya'] as $experienceType)<option value="{{ $experienceType }}" @selected(old('experiences.0.type') === $experienceType)>{{ $experienceType }}</option>@endforeach</select></div>
                                            <div class="apply-field"><label>Nama perusahaan/organisasi</label><input name="experiences[0][company]" value="{{ old('experiences.0.company') }}" maxlength="150" placeholder="Contoh: PT Equityworld Futures"></div>
                                            <div class="apply-field"><label>Posisi atau jabatan</label><input name="experiences[0][position]" value="{{ old('experiences.0.position') }}" maxlength="150" placeholder="Contoh: Staf keuangan"></div>
                                            <div class="apply-field" data-internship-only hidden><label>Periode mulai</label><input type="month" name="experiences[0][start_period]"></div>
                                            <div class="apply-field" data-internship-only hidden><label>Periode selesai</label><input type="month" name="experiences[0][end_period]"><label class="apply-toggle"><input type="checkbox" name="experiences[0][currently_working]" value="1"> Masih berlangsung</label></div>
                                            <div class="apply-field full"><label>Deskripsi pekerjaan/tanggung jawab</label><textarea name="experiences[0][description]" maxlength="1000" placeholder="Contoh: Menyusun laporan bulanan dan mengarsipkan dokumen."></textarea></div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="apply-card">
                                <div class="apply-card-heading">
                                    <div><h2><i class="bi bi-mortarboard"></i> Pendidikan</h2><p>Cantumkan pendidikan terakhir atau yang sedang ditempuh.</p></div>
                                    <button class="apply-add" type="button" data-add-row="education"><i class="bi bi-plus-lg"></i> Tambah</button>
                                </div>
                                <div class="apply-repeat-list" id="educationList">
                                    <div class="apply-repeat-item" data-repeat-item>
                                        <button class="apply-remove" type="button" aria-label="Hapus pendidikan" data-remove-row><i class="bi bi-x-lg"></i></button>
                                        <div class="apply-form-grid">
                                            <div class="apply-field" data-internship-only hidden><label>Jenjang pendidikan</label><select name="education[0][level]" data-internship-required><option value="" hidden @selected(!old('education.0.level'))>Pilih jenjang</option>@foreach(['SMA','SMK','D1','D2','D3','D4','S1','S2','Lainnya'] as $educationLevel)<option value="{{ $educationLevel }}" @selected(old('education.0.level') === $educationLevel)>{{ $educationLevel }}</option>@endforeach</select></div>
                                            <div class="apply-field"><label>Nama asal sekolah/perguruan tinggi</label><input name="education[0][institution]" value="{{ old('education.0.institution') }}" maxlength="180" placeholder="Contoh: SMA Negeri 1 Jakarta / Universitas Indonesia" data-internship-required></div>
                                            <div class="apply-field"><label>Jurusan/program studi</label><input name="education[0][major]" value="{{ old('education.0.major') }}" maxlength="150" placeholder="Contoh: Akuntansi" data-internship-required></div>
                                            <div class="apply-field" data-internship-only hidden><label>Status pendidikan</label><select name="education[0][status]" data-internship-required><option value="" hidden @selected(!old('education.0.status'))>Pilih status</option><option value="Sedang berlangsung" @selected(old('education.0.status') === 'Sedang berlangsung')>Sedang berlangsung</option><option value="Sudah lulus" @selected(old('education.0.status') === 'Sudah lulus')>Sudah lulus</option></select></div>
                                            <div class="apply-field" data-internship-only hidden><label>Tahun masuk</label><input type="number" name="education[0][start_year]" min="1950" max="{{ now()->year + 1 }}" placeholder="Contoh: {{ now()->year - 3 }}" data-internship-required></div>
                                            <div class="apply-field" data-internship-only hidden><label>Tahun lulus/perkiraan lulus</label><input type="number" name="education[0][completion_year]" min="1950" max="{{ now()->year + 10 }}" placeholder="Contoh: {{ now()->year + 1 }}" data-internship-required></div>
                                            <div class="apply-field" data-professional-only><label>Tahun lulus</label><input name="education[0][year]" value="{{ old('education.0.year') }}" maxlength="20" placeholder="Contoh: 2024"></div>
                                            <div class="apply-field"><label>IPK/nilai rata-rata (opsional)</label><input type="number" name="education[0][gpa]" min="0" max="4" step="0.01" placeholder="Contoh: 3.75"></div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="apply-card" data-internship-only hidden>
                                <div class="apply-card-heading">
                                    <div>
                                        <h2><i class="bi bi-calendar2-check"></i> Informasi pendaftaran</h2>
                                        <p data-internship-only hidden>Sesuaikan periode dan jenis program dengan kebutuhan sekolah atau kampus.</p>
                                    </div>
                                </div>
                                <div class="apply-form-grid">
                                    <div class="apply-field full" data-internship-only hidden><label for="applicationRegistrationType">Jenis pendaftaran</label><select id="applicationRegistrationType" name="registration_type" data-internship-required><option value="" hidden @selected(!old('registration_type'))>Pilih jenis pendaftaran</option><option value="Magang / Internship" @selected(old('registration_type') === 'Magang / Internship')>Magang / Internship</option><option value="Praktik Kerja Lapangan (PKL)" @selected(old('registration_type') === 'Praktik Kerja Lapangan (PKL)')>Praktik Kerja Lapangan (PKL)</option></select></div>
                                    <div class="apply-field" data-internship-only hidden><label for="applicationInternshipMajor">Jurusan program yang dipilih</label><input id="applicationInternshipMajor" name="internship_major" value="{{ old('internship_major', request()->query('internship_major')) }}" maxlength="150" readonly data-internship-required></div>
                                    <div class="apply-field" data-internship-only hidden><label for="applicationTargetPosition">Bidang magang yang dipilih</label><input id="applicationTargetPosition" name="target_position" value="{{ old('target_position', request()->query('target_position')) }}" maxlength="180" readonly data-internship-required></div>
                                    <div class="apply-field" data-internship-only hidden><label id="applicationInternshipStartLabel" for="applicationInternshipStart">Periode awal program</label><input id="applicationInternshipStart" type="date" name="internship_start_period" value="{{ old('internship_start_period') }}" data-internship-required></div>
                                    <div class="apply-field" data-internship-only hidden><label id="applicationInternshipEndLabel" for="applicationInternshipEnd">Periode selesai program</label><input id="applicationInternshipEnd" type="date" name="internship_end_period" value="{{ old('internship_end_period') }}" data-internship-required></div>
                                </div>
                            </section>

                            <section class="apply-card">
                                <div class="apply-card-heading"><div><h2><i class="bi bi-lightning"></i> Keahlian</h2><p>Pisahkan setiap keahlian dengan koma.</p></div></div>
                                <div class="apply-field">
                                    <label for="applicationSkills">Keahlian yang relevan</label>
                                    <input id="applicationSkills" name="skills" value="{{ old('skills') }}" maxlength="1000" placeholder="Contoh: Microsoft Excel, Analisis data, Komunikasi">
                                </div>
                            </section>

                            <section class="apply-card">
                                <div class="apply-card-heading"><div><h2><i class="bi bi-cloud-arrow-up"></i> Dokumen</h2><p>CV wajib diunggah; dokumen pendukung opsional atau sesuai persyaratan.</p></div></div>
                                <div class="apply-form-grid">
                                    <div class="apply-field full">
                                        <span>Dokumen CV / Curriculum Vitae</span>
                                        <label class="apply-upload" for="applicationCv">
                                            <span><i class="bi bi-file-earmark-arrow-up"></i><b>Pilih atau jatuhkan CV di sini</b><small>PDF, DOC, DOCX · maksimal 5 MB</small></span>
                                            <div class="apply-file-list" id="applicationCvFiles" aria-live="polite"></div>
                                            <input id="applicationCv" type="file" name="cv" accept=".pdf,.doc,.docx" required data-profile-required>
                                        </label>
                                        @error('cv')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="apply-field full">
                                        <span>Dokumen pendukung (opsional atau sesuai persyaratan)</span>
                                        <div id="applicationDocumentInputs" class="apply-form-grid">
                                            <div class="apply-field">
                                                <label for="applicationTranscript">Transkrip nilai / rapor</label>
                                                <label class="apply-upload" for="applicationTranscript"><span><i class="bi bi-file-earmark-text"></i><b>Pilih transkrip atau rapor</b><small>PDF, DOC, DOCX, JPG, PNG · maksimal 10 MB</small></span><div class="apply-file-list" id="applicationTranscriptFiles" aria-live="polite"></div><input id="applicationTranscript" type="file" name="documents[transcript]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></label>
                                            </div>
                                            <div class="apply-field" data-internship-only hidden>
                                                <label for="applicationCoverLetter">Surat pengantar magang / PKL (sesuai kebutuhan)</label>
                                                <label class="apply-upload" for="applicationCoverLetter"><span><i class="bi bi-envelope-paper"></i><b>Pilih surat pengantar sekolah/kampus</b><small>Khusus magang/PKL · unggah jika diminta · maksimal 10 MB</small></span><div class="apply-file-list" id="applicationCoverLetterFiles" aria-live="polite"></div><input id="applicationCoverLetter" type="file" name="documents[cover_letter]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></label>
                                            </div>
                                            <div class="apply-field">
                                                <label for="applicationPortfolioFile">Portofolio</label>
                                                <label class="apply-upload" for="applicationPortfolioFile"><span><i class="bi bi-images"></i><b>Pilih portofolio</b><small>Opsional · maksimal 10 MB</small></span><div class="apply-file-list" id="applicationPortfolioFiles" aria-live="polite"></div><input id="applicationPortfolioFile" type="file" name="documents[portfolio]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></label>
                                            </div>
                                            <div class="apply-field">
                                                <label for="applicationKtp">Fotokopi KTP</label>
                                                <label class="apply-upload" for="applicationKtp"><span><i class="bi bi-person-vcard"></i><b>Pilih fotokopi KTP</b><small>PDF, JPG, PNG · maksimal 10 MB</small></span><div class="apply-file-list" id="applicationKtpFiles" aria-live="polite"></div><input id="applicationKtp" type="file" name="documents[ktp]" accept=".pdf,.jpg,.jpeg,.png"></label>
                                            </div>
                                            <div class="apply-field full" id="applicationDiplomaField">
                                                <label id="applicationDiplomaLabel" for="applicationDiploma">Fotokopi ijazah terakhir</label>
                                                <label class="apply-upload" for="applicationDiploma"><span><i class="bi bi-award"></i><b id="applicationDiplomaPrompt">Pilih fotokopi ijazah terakhir</b><small>PDF, DOC, DOCX, JPG, PNG · maksimal 10 MB</small></span><div class="apply-file-list" id="applicationDiplomaFiles" aria-live="polite"></div><input id="applicationDiploma" type="file" name="documents[diploma]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></label>
                                            </div>
                                        </div>
                                        @error('documents')<small class="apply-error">{{ $message }}</small>@enderror
                                        @error('documents.*')<small class="apply-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </section>

                            <section class="apply-card">
                                <label class="apply-consent"><input id="applicationConsent" type="checkbox" name="privacy_consent" value="1" required @checked(old('privacy_consent')) data-profile-required>
                                    <span>Saya menyatakan informasi dan dokumen yang diberikan benar, serta menyetujui penggunaannya untuk proses seleksi posisi ini.</span>
                                </label>
                                @error('privacy_consent')<small class="apply-error">{{ $message }}</small>@enderror
                                <div class="apply-actions">
                                    <button class="btn ghost" type="button" id="saveApplicationDraft"><i class="bi bi-cloud-check"></i> Simpan sebagai Draft</button>
                                    <button class="btn ghost" type="button" id="applicationPreview"><i class="bi bi-eye"></i> Pratinjau sebelum kirim</button>
                                    <button class="btn" type="submit" id="applicationSubmit"><i class="bi bi-send-check"></i> Submit berkas</button>
                                    <span class="apply-draft-status" id="applicationDraftStatus" role="status"></span>
                                    <div class="apply-errors" id="applicationSessionWarning" role="alert" hidden>Sesi login perlu diperbarui. Draft formulir tetap tersimpan di browser. Muat ulang halaman, masuk kembali, lalu buka lowongan ini untuk melanjutkan.</div>
                                </div>
                            </section>
                        </div>
                    </form>
                </main>
                <aside class="apply-sidebar">
                    <section class="apply-card">
                        <h3>Dokumen <span id="applicationDocumentCount" aria-live="polite">0/6</span></h3>
                        <div class="apply-doc-status"><span>CV / Resume</span><i class="bi bi-circle" id="applicationCvStatus"></i></div>
                        <div class="apply-doc-status"><span>Dokumen pendukung</span><span id="applicationSupportingCount">0/5</span></div>
                    </section>
                    <section class="apply-card">
                        <h3>Kelengkapan profil</h3>
                        <div class="apply-checklist">
                            <span data-check="applicationName"><i class="bi bi-circle"></i> Profil</span>
                            <span data-check="applicationExperience" data-professional-only><i class="bi bi-circle"></i> Pengalaman</span>
                            <span data-check="applicationEducation"><i class="bi bi-circle"></i> Pendidikan</span>
                            <span data-check="applicationRegistration" data-internship-only hidden><i class="bi bi-circle"></i> Informasi pendaftaran</span>
                            <span data-check="applicationSkills"><i class="bi bi-circle"></i> Keahlian</span>
                            <span data-check="applicationCv"><i class="bi bi-circle"></i> Dokumen</span>
                            <span data-check="applicationConsent"><i class="bi bi-circle"></i> Persetujuan data</span>
                        </div>
                    </section>
                    <section class="apply-card">
                        <h3>Privasi &amp; keamanan</h3>
                        <div class="apply-privacy"><i class="bi bi-shield-lock"></i> Dokumen disimpan secara privat dan hanya digunakan untuk proses seleksi. Jangan mengunggah kata sandi, data kartu, atau informasi keuangan pribadi.</div>
                    </section>
                </aside>
            </div>
        </div>
        <dialog class="apply-preview" id="applicationPreviewDialog">
            <div class="apply-preview-inner">
                <header class="apply-preview-header">
                    <span class="apply-preview-icon"><i class="bi bi-clipboard2-check"></i></span>
                    <div><span class="apply-preview-kicker">Periksa sebelum mengirim</span><h2>Pratinjau pendaftaran</h2><p id="applicationPreviewDescription">Pastikan informasi dan dokumen sudah sesuai.</p></div>
                </header>
                <div class="apply-preview-job">
                    <i class="bi bi-briefcase"></i>
                    <div class="apply-preview-job-copy"><small>Posisi yang dilamar</small><b id="applicationPreviewJobTitle"></b><small id="applicationPreviewCompany"></small></div>
                    <span class="apply-preview-kind" id="applicationPreviewKind"></span>
                </div>
                <div class="apply-preview-content" id="applicationPreviewContent"></div>
                <div class="apply-preview-actions"><button class="btn sm ghost" type="button" id="closeApplicationPreview">Kembali mengedit</button><button class="btn sm" type="button" id="confirmApplicationSubmit"><i class="bi bi-send-check"></i> Kirim pendaftaran</button></div>
            </div>
        </dialog>
    </section>

    {{-- HALAMAN LOWONGAN TERSIMPAN --}}
    <section class="career-saved" id="careerSaved" hidden>
        <div class="career-wrap">
            <div class="career-crumb"><a href="{{ route('karir') }}">Karir</a><i class="bi bi-chevron-right"></i><span>Lowongan tersimpan</span></div>
            <div class="career-heading">
                <div><h1>Lowongan tersimpan</h1><p>Semua lowongan yang Anda simpan ada di sini.</p></div>
                <a class="btn sm ghost" href="#jobs"><i class="bi bi-search"></i> Cari lowongan lain</a>
            </div>
            <div class="explorer-result">
                <div class="result-heading"><b id="savedCount">0 lowongan tersimpan</b><button type="button" class="filter-clear" id="clearSaved">Hapus semua</button></div>
                <div class="explorer-jobs" id="savedJobs"></div>
                <div class="explorer-empty" id="savedEmpty" hidden><i class="bi bi-bookmark"></i>Belum ada lowongan tersimpan. Tekan ikon bookmark pada lowongan yang menarik.<br><br><a href="#jobs" class="btn sm">Cari lowongan</a></div>
            </div>
        </div>
    </section>
    @else
        <main class="career-tracking-only" id="status-pendaftaran">
            <div class="career-wrap">
                @php
                    $allApplicationsCompleted = $applications->isNotEmpty() && $applications->every(
                        fn ($application) => $application instanceof \App\Models\InternshipRegistration
                            && $application->isProgramCompleted()
                    );
                @endphp
                <div class="career-heading tracking-heading">
                    <div>
                        @if($allApplicationsCompleted)
                            <span class="pill"><i class="bi bi-award"></i> Dokumen penyelesaian magang</span>
                            <h1>Unduh dokumen magang</h1>
                            <p>Sertifikat dan Letter of Acceptance (LOA) tersedia di bawah ini.</p>
                        @else
                            <span class="pill"><i class="bi bi-broadcast"></i> Pelacakan lamaran</span>
                            <h1>Status pelacakan lamaran</h1>
                            <p>Pantau perkembangan setiap lamaran yang telah Anda kirim.</p>
                        @endif
                    </div>
                </div>

                @if(session('application_submitted'))
                    <div class="apply-success" role="status">
                        <b><i class="bi bi-check-circle"></i> Lamaran berhasil dikirim</b>
                        Pelamar: <strong>{{ $applications->first()?->full_name }}</strong>. Pantau perkembangan seleksi pada halaman ini.
                    </div>
                @endif
                @if(session('application_already_submitted'))
                    <div class="apply-errors" role="alert">
                        <b><i class="bi bi-info-circle"></i> Anda sudah mengirim satu lamaran.</b>
                        Setiap akun pelamar hanya dapat mengirim satu lamaran. Halaman ini menampilkan status lamaran Anda.
                    </div>
                @endif

                @php
                    $completedTrackingStages = collect($trackingStages)->where('state', 'done')->count();
                    $trackingProgress = (int) round($completedTrackingStages / count($trackingStages) * 100);
                @endphp

                @foreach($applications as $application)
                    @php
                        $applicationDisplayTitle = preg_replace('/^Magang\s+/i', '', $application->job_title);
                    @endphp
                    @if($application instanceof \App\Models\InternshipRegistration && $application->isProgramCompleted())
                        @php
                            $issuedDocuments = $downloadableDocuments[$application->getKey()] ?? [];
                            $completionDocuments = [
                                ['key' => 'certificate', 'title' => 'Sertifikat Magang / PKL', 'detail' => 'Bukti resmi penyelesaian program Anda.', 'icon' => 'bi-award'],
                                ['key' => 'loa', 'title' => 'Letter of Acceptance (LOA)', 'detail' => 'Surat penerimaan resmi dari perusahaan.', 'icon' => 'bi-envelope-paper'],
                            ];
                        @endphp
                        <section class="completion-card" aria-label="Dokumen penyelesaian {{ $applicationDisplayTitle }}">
                            <div class="completion-heading">
                                <span class="completion-icon" aria-hidden="true"><i class="bi bi-mortarboard"></i></span>
                                <div>
                                    <span class="completion-status"><i class="bi bi-check-circle-fill"></i> Program selesai</span>
                                    <h2>Selamat menyelesaikan program!</h2>
                                    <p>{{ $applicationDisplayTitle }} · {{ $application->full_name }}<br>Dokumen resmi program magang atau PKL Anda tersedia di bawah ini.</p>
                                </div>
                            </div>
                            <div class="completion-documents">
                                @foreach($completionDocuments as $document)
                                    @if($issuedDocuments[$document['key']] ?? false)
                                        <a class="completion-document" href="{{ route('karir.application.document.download', ['applicationId' => $application->getKey(), 'document' => $document['key']]) }}">
                                            <span class="completion-document-icon" aria-hidden="true"><i class="bi {{ $document['icon'] }}"></i></span>
                                            <span class="completion-document-copy"><b>{{ $document['title'] }}</b><small>{{ $document['detail'] }}</small></span>
                                            <span class="completion-document-action"><i class="bi bi-download"></i> Unduh</span>
                                        </a>
                                    @else
                                        <div class="completion-document unavailable" aria-disabled="true">
                                            <span class="completion-document-icon" aria-hidden="true"><i class="bi {{ $document['icon'] }}"></i></span>
                                            <span class="completion-document-copy"><b>{{ $document['title'] }}</b><small>{{ $document['detail'] }}</small></span>
                                            <span class="completion-document-action"><i class="bi bi-download"></i> Unduh</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @if(!($issuedDocuments['certificate'] ?? false) || !($issuedDocuments['loa'] ?? false))
                                <p class="completion-note"><i class="bi bi-info-circle"></i> Tampilan unduh sudah disiapkan. Tombol akan aktif setelah PDF sertifikat dan LOA tersedia.</p>
                            @endif
                        </section>
                    @else
                    <section class="tracker" aria-label="Pelacakan {{ $applicationDisplayTitle }}">
                        <div class="tr-head">
                            <div class="tracker-person">
                                <span class="ico"><i class="bi bi-person-vcard"></i></span>
                                <div>
                                <h3>{{ $applicationDisplayTitle }} <span class="tag" style="font-size:12px">Menunggu verifikasi</span></h3>
                                <p class="tracker-applicant"><i class="bi bi-person"></i> Pelamar: <b>{{ $application->full_name }}</b></p>
                                <p>Dikirim {{ $application->created_at->format('d-m-Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="card" style="padding:12px 16px;min-width:260px">
                                <div class="row" style="display:flex;justify-content:space-between;font-size:13px;font-weight:700">
                                    <span>Kemajuan seleksi</span>
                                    <span style="color:var(--o)">{{ $completedTrackingStages }} dari {{ count($trackingStages) }} tahap ({{ $trackingProgress }}%)</span>
                                </div>
                                <div class="bar"><i style="width:{{ $trackingProgress }}%"></i></div>
                            </div>
                        </div>
                        <div class="steps">
                            @foreach($trackingStages as $stage)
                                <div class="step {{ $stage['state'] }}" @if($loop->first) data-date="{{ $application->created_at->format('Y-m-d\TH:i') }}" @endif>
                                    <span class="st">{{ $stage['label'] }}</span>
                                    <span class="stat">{{ $stage['status'] }}</span>
                                    <b>{{ $stage['title'] }}</b>
                                    <span class="desc">{{ $stage['description'] }}</span>
                                    <div class="sched">
                                        @if($loop->first)
                                            <small>Tanggal pengiriman</small>
                                            <span class="when">{{ $application->created_at->format('d/m/Y, H:i') }} WIB</span>
                                        @elseif($loop->index === 1)
                                            <small>Waktu verifikasi</small>
                                            <span class="when">Menunggu pembaruan HR</span>
                                        @elseif($loop->index === 2)
                                            <small>Jadwal wawancara</small>
                                            <span class="when">Belum diumumkan</span>
                                            <div class="rsvp-pending" aria-label="Konfirmasi kehadiran belum tersedia">
                                                <div class="rsvp-opts">
                                                    <button class="rsvp-opt yes" type="button" disabled><i class="bi bi-check-lg"></i> Hadir</button>
                                                    <button class="rsvp-opt no" type="button" disabled><i class="bi bi-x-lg"></i> Tidak hadir</button>
                                                </div>
                                                <small>Konfirmasi aktif setelah jadwal wawancara diumumkan.</small>
                                            </div>
                                        @else
                                            <small>Waktu pengumuman hasil</small>
                                            <span class="when">Belum tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="step-actions" id="trackActions"
                             data-name="{{ $application->full_name }}"
                             data-code="{{ $application->reference }}"
                             data-position="{{ $applicationDisplayTitle }}"
                             data-company="{{ $application->company }}"
                             data-wa="623160003788"
                             data-logo="{{ asset('image/logo ewf.png') }}">
                            <button type="button" class="act" data-act="pdf">
                                <span class="ai"><i class="bi bi-download"></i></span>
                                <span class="at"><b>Unduh Berkas PDF</b><small>Ringkasan status seleksi</small></span>
                            </button>
                            <button type="button" class="act" data-act="calendar">
                                <span class="ai"><i class="bi bi-calendar3"></i></span>
                                <span class="at"><b>Kalender</b><small>Lihat jadwal seleksi</small></span>
                            </button>
                            <button type="button" class="act" data-act="wa">
                                <span class="ai"><i class="bi bi-whatsapp"></i></span>
                                <span class="at"><b>WhatsApp Helpdesk HR</b><small>Tanya status seleksi</small></span>
                            </button>
                        </div>
                    </section>
                    @endif
                @endforeach
            </div>
        </main>
    @endif
</div>

@include('partials.footer')

<script>
    (function(){
        var root = document.querySelector('.krr');
        var explorer = document.getElementById('careerExplorer');
        var detail = document.getElementById('careerDetail');
        var applicationSection = document.getElementById('careerApply');
        var list = document.getElementById('explorerJobs');
        if(!root || !explorer || !detail || !applicationSection || !list) return;

        var categorySelect = document.getElementById('filterCategory');
        var typeSelect = document.getElementById('filterType');
        var queryInput = document.getElementById('explorerQuery');
        var countLabel = document.getElementById('explorerCount');
        var emptyState = document.getElementById('explorerEmpty');

        /* ===== Data lowongan tersimpan (disimpan di browser lewat localStorage) ===== */
        var SAVED_KEY = 'ewf-saved-jobs';
        var savedSection = document.getElementById('careerSaved');
        function loadSaved(){
            try{
                var a = JSON.parse(localStorage.getItem(SAVED_KEY) || '[]');
                return new Set(Array.isArray(a) ? a : []);
            }catch(e){ return new Set(); }
        }
        var savedJobs = loadSaved();
        function persistSaved(){
            try{ localStorage.setItem(SAVED_KEY, JSON.stringify(Array.from(savedJobs))); }
            catch(e){ console.warn('Gagal menyimpan daftar lowongan:', e); }
        }

        var activeCategory = '';
        var categoryNames = {};
        Array.from(document.getElementById('filterCategory').options).forEach(function(option){
            if(option.value) categoryNames[option.value] = option.textContent.trim();
        });
        var allCareerCategories = @json($allCareerCategories);
        var professionalCategoryOptions = @json($professionalCategories);
        var internshipCategoryOptions = @json($internshipFields);
        var internshipProgramData = @json($internshipPrograms);
        var homeCareerType = root.querySelector('.search [name="tipe"]');
        var homeCareerCategory = root.querySelector('.search [name="bidang"]');

        function configureHomeCareerCategories(){
            if(!homeCareerType || !homeCareerCategory) return;
            var selected = homeCareerCategory.value;
            var options = homeCareerType.value === 'magang' ? internshipCategoryOptions : professionalCategoryOptions;
            homeCareerCategory.replaceChildren();
            var placeholder = makeElement('option', '', 'Pilih bidang');
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.hidden = true;
            placeholder.selected = true;
            homeCareerCategory.appendChild(placeholder);
            options.forEach(function(option){
                var item = makeElement('option', '', option.label);
                item.value = option.value;
                homeCareerCategory.appendChild(item);
            });
            homeCareerCategory.value = options.some(function(option){ return option.value === selected; }) ? selected : '';
        }
        if(homeCareerType && homeCareerCategory){
            homeCareerType.addEventListener('change', configureHomeCareerCategories);
            configureHomeCareerCategories();
        }

        var copyByCategory = {
            teknologi: {
                about: 'Berkontribusi pada pengembangan dan pemeliharaan solusi teknologi sesuai kebutuhan tim dan pengguna.',
                tasks: ['Mengerjakan kebutuhan teknis sesuai prioritas dan arahan tim.', 'Menjaga kualitas, dokumentasi, dan keandalan hasil kerja.', 'Berkolaborasi dengan tim lintas fungsi untuk menyelesaikan pekerjaan.'],
                qualifications: ['Memahami dasar bidang teknologi yang relevan dengan posisi.', 'Mampu berkomunikasi dan bekerja sama dalam tim.', 'Rincian pengalaman dan kualifikasi mengikuti informasi dari perusahaan.']
            },
            keuangan: {
                about: 'Mendukung proses keuangan dan administrasi perpajakan dengan teliti, terstruktur, serta menjaga kerahasiaan data.',
                tasks: ['Membantu menyiapkan dan memeriksa dokumen keuangan atau perpajakan.', 'Menjaga pencatatan dan kelengkapan dokumen sesuai prosedur kerja.', 'Berkoordinasi dengan tim terkait untuk memenuhi tenggat pekerjaan.'],
                qualifications: ['Memiliki latar belakang atau minat pada akuntansi, keuangan, atau perpajakan.', 'Teliti, mampu mengelola dokumen, dan menjaga kerahasiaan data.', 'Pengalaman dan sertifikasi mengikuti persyaratan posisi yang dipilih.']
            },
            pialang: {
                about: 'Mendukung kegiatan layanan dan edukasi perdagangan berjangka dengan komunikasi profesional dan pemahaman risiko yang baik.',
                tasks: ['Menyampaikan informasi produk dan layanan secara jelas sesuai materi resmi.', 'Membangun komunikasi profesional dengan calon maupun nasabah.', 'Mengikuti prosedur perusahaan dan ketentuan yang berlaku.'],
                qualifications: ['Memiliki kemampuan komunikasi dan layanan yang baik.', 'Bersedia mempelajari produk serta ketentuan perdagangan berjangka.', 'Persyaratan pengalaman dan lisensi mengikuti ketentuan posisi.']
            },
            administrasi: {
                about: 'Membantu kelancaran proses administrasi dan kepatuhan melalui pengelolaan dokumen yang rapi dan akurat.',
                tasks: ['Menyiapkan, memeriksa, dan mengarsipkan dokumen terkait pekerjaan.', 'Memantau kelengkapan administrasi dan tindak lanjut yang diperlukan.', 'Berkoordinasi dengan tim untuk menjaga alur kerja tetap tertib.'],
                qualifications: ['Teliti dalam mengelola dokumen dan data.', 'Mampu berkomunikasi serta bekerja dengan tenggat waktu.', 'Rincian latar belakang dan pengalaman mengikuti kebutuhan posisi.']
            }
        };
        var professionalDetails = @json($professionalDetails);
        var internshipDetails = @json($internshipDetails);
        var ewfLogoUrl = @json(asset('image/logo ewf.png'));
        var defaultCopy = copyByCategory.administrasi;
        var jobs = [];

        function makeElement(tag, className, text){
            var element = document.createElement(tag);
            if(className) element.className = className;
            if(text !== undefined) element.textContent = text;
            return element;
        }
        function displayJobTitle(job){
            return job.type === 'magang' ? job.title.replace(/^Magang\s+/i, '') : job.title;
        }
        function configureCareerFilters(selectedCategory){
            var selected = typeof selectedCategory === 'string' ? selectedCategory : categorySelect.value;
            var availableJobs = jobs.filter(function(job){
                return !typeSelect.value || job.type === typeSelect.value;
            });
            var options = [];
            var seenCategories = new Set();
            if(typeSelect.value !== 'magang'){
                availableJobs.filter(function(job){ return job.type === 'profesional'; }).forEach(function(job){
                    if(seenCategories.has(job.category)) return;
                    seenCategories.add(job.category);
                    options.push({value: job.category, label: job.categoryLabel});
                });
            }
            if(typeSelect.value !== 'profesional'){
                internshipProgramFilters.forEach(function(program){ options.push(program); });
            }
            var categoryLabel = document.getElementById('filterCategoryLabel');
            categorySelect.replaceChildren();
            var placeholder = makeElement('option', '', 'Pilih bidang');
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.hidden = true;
            placeholder.selected = true;
            categorySelect.appendChild(placeholder);
            options.forEach(function(option){
                var item = makeElement('option', '', option.label);
                item.value = option.value;
                categorySelect.appendChild(item);
                categoryNames[option.value] = option.label;
            });
            categoryLabel.textContent = typeSelect.value === 'magang' ? 'Bidang magang/PKL' :
                typeSelect.value === 'profesional' ? 'Bidang pekerjaan' : 'Bidang karier';
            categorySelect.value = options.some(function(option){ return option.value === selected; }) ? selected : '';
        }
        function setCompanyLogo(element, job){
            if(job.company !== 'PT Equityworld Futures'){
                element.textContent = job.logo;
                return;
            }
            var image = makeElement('img');
            image.src = ewfLogoUrl;
            image.alt = 'Logo EWF';
            element.replaceChildren(image);
        }
        function roleDescription(job){
            var details = professionalDetails[job.id] || internshipDetails[job.id];
            if(details) return details.about;
            var copy = copyByCategory[job.category] || defaultCopy;
            return copy.about + ' Posisi ' + job.typeLabel.toLowerCase() + ' ini memiliki sistem kerja: ' + job.workSystem + '.';
        }
        function buildListingCard(job, program){
            var card = makeElement('article', 'explorer-job');
            card.dataset.jobId = job.id;
            card.dataset.category = job.category;
            card.dataset.type = job.type;
            card.dataset.workSystem = job.workSystem.toLowerCase();
            card.dataset.search = [program ? program.label : job.title, program ? program.major : '', job.company, job.categoryLabel, job.workSystem, job.tags.join(' ')].join(' ').toLowerCase();

            var logo = makeElement('div', 'logo' + (job.featured ? ' dark' : ''));
            setCompanyLogo(logo, job);
            var main = makeElement('div', 'listing-main');
            var top = makeElement('div', 'listing-top');
            var heading = makeElement('div');
            var listingTitle = program ? program.field : displayJobTitle(job);
            heading.appendChild(makeElement('h3', '', listingTitle));
            var company = makeElement('div', 'listing-company');
            company.appendChild(document.createTextNode(job.company + ' '));
            if(!job.isSample){
                var verified = makeElement('i', 'bi bi-patch-check-fill');
                verified.setAttribute('aria-label', 'Perusahaan terverifikasi');
                company.appendChild(verified);
            }
            heading.appendChild(company);
            top.appendChild(heading);
            top.appendChild(makeElement('span', 'listing-posted', job.posted));
            main.appendChild(top);

            var workSystem = makeElement('div', 'listing-location');
            workSystem.innerHTML = '<i class="bi bi-building"></i>';
            workSystem.appendChild(document.createTextNode(' ' + job.workSystem));
            workSystem.appendChild(document.createTextNode('  ·  ' + job.typeLabel));
            if(program) workSystem.appendChild(document.createTextNode('  ·  Jurusan ' + program.major));
            main.appendChild(workSystem);
            main.appendChild(makeElement('p', 'listing-description', roleDescription(job)));
            var tags = job.source.querySelector('.meta');
            if(tags) main.appendChild(tags.cloneNode(true));

            var footer = makeElement('div', 'listing-footer');
            var pay = makeElement('span', 'listing-pay');
            pay.innerHTML = '<i class="bi bi-cash-coin"></i>';
            pay.appendChild(document.createTextNode(job.benefit));
            footer.appendChild(pay);
            var actions = makeElement('div', 'listing-actions');
            var save = makeElement('button', 'save-job');
            save.type = 'button';
            save.dataset.saveJob = job.id;
            save.setAttribute('aria-label', 'Simpan lowongan ' + listingTitle);
            save.setAttribute('aria-pressed', String(savedJobs.has(job.id)));
            save.innerHTML = '<i class="bi bi-bookmark"></i>';
            var view = makeElement('a', 'btn sm', 'Lihat detail');
            var detailQuery = program ? new URLSearchParams({major: program.major, field: program.field}) : null;
            view.href = '#job/' + encodeURIComponent(job.id) + (detailQuery ? '?' + detailQuery.toString() : '');
            actions.appendChild(save);
            actions.appendChild(view);
            footer.appendChild(actions);
            main.appendChild(footer);
            card.appendChild(logo);
            card.appendChild(main);
            setSavedButton(save, savedJobs.has(job.id));
            return card;
        }

        function buildInternshipProgramCard(major, selectedProgram){
            var fields = internshipProgramData[major] || [];
            var card = makeElement('article', 'card internship-major-card explorer-program-card');
            var info = makeElement('div', 'explorer-program-info');
            var company = makeElement('div', 'co');
            var logo = makeElement('div', 'logo');
            setCompanyLogo(logo, {
                company: 'PT Equityworld Futures',
                logo: 'EWF'
            });
            company.appendChild(logo);
            var companyCopy = makeElement('div');
            var companyName = makeElement('b');
            companyName.appendChild(document.createTextNode('PT Equityworld Futures '));
            var verified = makeElement('i', 'bi bi-patch-check-fill internship-company-badge');
            verified.setAttribute('aria-label', 'Perusahaan terverifikasi');
            companyName.appendChild(verified);
            companyCopy.appendChild(companyName);
            companyCopy.appendChild(makeElement('small', '', 'Program Magang & PKL · Work From Office'));
            company.appendChild(companyCopy);
            info.appendChild(company);
            info.appendChild(makeElement('h4', '', major));

            var meta = makeElement('div', 'meta');
            ['Work From Office (WFO)', 'SMA/SMK/D3/D4/S1/S2', 'Durasi sesuai program'].forEach(function(tag){
                meta.appendChild(makeElement('span', '', tag));
            });
            info.appendChild(meta);
            var benefit = makeElement('div', 'pay');
            benefit.appendChild(makeElement('small', '', 'Benefit'));
            benefit.appendChild(makeElement('b', '', 'Aman, nyaman, untung'));
            info.appendChild(benefit);
            card.appendChild(info);

            var actions = makeElement('div', 'explorer-program-actions');
            var choice = makeElement('div', 'internship-field-choice');
            var fieldId = 'explorerInternshipField-' + internshipProgramFilters.findIndex(function(program){
                return program.major === major;
            });
            var fieldLabel = makeElement('label', '', 'Pilih bidang untuk jurusan ini');
            fieldLabel.htmlFor = fieldId;
            choice.appendChild(fieldLabel);
            var select = makeElement('select');
            select.id = fieldId;
            select.required = true;
            select.setAttribute('aria-label', 'Bidang magang untuk jurusan ' + major);
            setSelectPlaceholder(select, 'Pilih bidang');
            fields.forEach(function(field){
                var option = makeElement('option', '', field.label);
                option.value = field.label;
                option.dataset.jobId = field.job_id;
                select.appendChild(option);
            });
            if(selectedProgram && selectedProgram.major === major){
                select.value = fields.some(function(field){
                    return field.label === selectedProgram.field && field.job_id === selectedProgram.jobId;
                }) ? selectedProgram.field : '';
            }
            choice.appendChild(select);
            actions.appendChild(choice);
            var bot = makeElement('div', 'bot');
            var status = makeElement('span', 'live');
            status.innerHTML = '<i class="bi bi-broadcast"></i> Pendaftaran dibuka';
            bot.appendChild(status);
            var viewCareer = makeElement('button', 'btn sm', 'Lihat Karir');
            viewCareer.type = 'button';
            viewCareer.addEventListener('click', function(){
                var target = internshipProgramDestination(major, select.selectedOptions[0]);
                if(target) location.hash = target;
            });
            bot.appendChild(viewCareer);
            actions.appendChild(bot);
            card.appendChild(actions);
            return card;
        }

        root.querySelectorAll('#lowongan .job[data-job-id]').forEach(function(source){
            var id = source.dataset.jobId;
            var companyLogo = source.querySelector('.logo');
            var companyName = source.dataset.careerCompany;
            var companyInfo = source.dataset.careerCompanyInfo;
            var category = source.dataset.careerCategory;
            var categoryLabel = categoryNames[category] || 'Karir';
            var type = source.dataset.careerType;
            var tags = Array.from(source.querySelectorAll('.meta span')).map(function(tag){ return tag.textContent.trim(); });
            jobs.push({
                id: id,
                source: source,
                title: source.dataset.careerTitle,
                company: companyName,
                companyInfo: companyInfo,
                category: category,
                categoryLabel: categoryLabel,
                type: type,
                typeLabel: type === 'magang' ? 'Magang' : 'Full-time / profesional',
                workSystem: source.dataset.careerWorkSystem,
                educationLevels: source.dataset.careerEducation ? source.dataset.careerEducation.split(',') : [],
                benefit: source.dataset.careerBenefit,
                posted: source.dataset.careerPosted,
                applicants: Number(source.dataset.careerApplicants),
                isSample: source.dataset.careerSample === 'true',
                logo: companyName === 'PT Equityworld Futures' ? 'EWF' :
                    companyLogo ? companyLogo.textContent.trim() : companyName.slice(0, 2).toUpperCase(),
                featured: source.classList.contains('pri'),
                tags: tags
            });
        });

        var internshipProgramFilters = [];
        Object.keys(internshipProgramData).forEach(function(major){
            internshipProgramData[major].forEach(function(field){
                var matchingJob = jobs.find(function(job){
                    return job.id === field.job_id && job.type === 'magang';
                });
                if(!matchingJob) return;
                internshipProgramFilters.push({
                    value: 'internship-program-' + internshipProgramFilters.length,
                    label: field.label + ' · ' + major,
                    major: major,
                    field: field.label,
                    jobId: field.job_id
                });
            });
        });

        function internshipProgramDestination(major, selectedOption){
            var hasSelectedField = selectedOption && selectedOption.value;
            var program = hasSelectedField
                ? {label: selectedOption.value, job_id: selectedOption.dataset.jobId}
                : (internshipProgramData[major] || []).find(function(field){
                    return jobs.some(function(job){
                        return job.id === field.job_id && job.type === 'magang';
                    });
                });
            if(!program){
                console.error('Tidak ada lowongan magang yang tersedia untuk jurusan:', major);
                return null;
            }
            var query = new URLSearchParams({major: major});
            if(hasSelectedField) query.set('field', program.label);
            return '#job/' + encodeURIComponent(program.job_id) + '?' + query.toString();
        }

        root.querySelectorAll('[data-internship-field]').forEach(function(select){
            var card = select.closest('.internship-major-card');
            var applyButton = card.querySelector('[data-internship-apply]');

            applyButton.addEventListener('click', function(){
                var selectedMajor = applyButton.dataset.major;
                var target = internshipProgramDestination(selectedMajor, select.selectedOptions[0]);
                if(target) location.hash = target;
            });
        });

        var searchStorageKey = 'ewf-career-popular-searches';
        var popularLists = root.querySelectorAll('[data-popular-list]');
        function readSearchHistory(){
            try{
                var stored = JSON.parse(localStorage.getItem(searchStorageKey) || '[]');
                return Array.isArray(stored) ? stored.filter(function(item){
                    return item && typeof item.term === 'string' && typeof item.count === 'number';
                }) : [];
            }catch(error){
                console.warn('Riwayat pencarian tidak dapat dibaca:', error);
                return [];
            }
        }
        var searchHistory = readSearchHistory();
        function renderSearchHistory(){
            var terms = searchHistory.slice().sort(function(a,b){
                return (Number(b.lastUsed) || 0) - (Number(a.lastUsed) || 0) || b.count - a.count;
            }).slice(0, 4).map(function(item){ return item.term; });
            popularLists.forEach(function(container){
                container.closest('.popular-terms').hidden = terms.length === 0;
                container.replaceChildren();
                terms.forEach(function(term){
                    var button = makeElement('button', '', term);
                    button.type = 'button';
                    button.dataset.searchTerm = term;
                    container.appendChild(button);
                });
            });
        }
        function rememberSearch(term){
            var value = term.trim();
            if(value.length < 2) return;
            var key = value.toLocaleLowerCase('id');
            var existing = searchHistory.find(function(item){ return item.term.toLocaleLowerCase('id') === key; });
            if(existing){
                existing.count += 1;
                existing.lastUsed = Date.now();
            }else{
                searchHistory.push({term: value, count: 1, lastUsed: Date.now()});
            }
            searchHistory = searchHistory.slice(-12);
            try{
                localStorage.setItem(searchStorageKey, JSON.stringify(searchHistory));
            }catch(error){
                console.warn('Riwayat pencarian tidak dapat disimpan:', error);
            }
            renderSearchHistory();
        }
        renderSearchHistory();

        function formatList(targetId, items){
            var target = document.getElementById(targetId);
            target.replaceChildren();
            items.forEach(function(text){ target.appendChild(makeElement('li', '', text)); });
        }
        function setSavedButton(button, isSaved){
            button.setAttribute('aria-pressed', String(isSaved));
            if(button.classList.contains('save-job')){
                button.setAttribute('aria-label', isSaved ? 'Hapus lowongan dari tersimpan' : 'Simpan lowongan');
                button.innerHTML = isSaved ? '<i class="bi bi-bookmark-check"></i>' : '<i class="bi bi-bookmark"></i>';
                return;
            }
            button.innerHTML = isSaved ? '<i class="bi bi-bookmark-check"></i> Tersimpan' : '<i class="bi bi-bookmark"></i> Simpan';
        }
        function updateResults(){
            var term = queryInput.value.trim().toLocaleLowerCase('id');
            var category = categorySelect.value;
            var type = typeSelect.value;
            var selectedProgram = internshipProgramFilters.find(function(program){
                return program.value === category;
            });
            if(type === 'magang' || selectedProgram){
                var matchingMajors = Object.keys(internshipProgramData).filter(function(major){
                    if(selectedProgram){
                        return major === selectedProgram.major && (!term ||
                            major.toLocaleLowerCase('id').indexOf(term) !== -1 ||
                            selectedProgram.field.toLocaleLowerCase('id').indexOf(term) !== -1);
                    }
                    var fields = internshipProgramData[major].filter(function(field){
                        return jobs.some(function(job){ return job.id === field.job_id && job.type === 'magang'; });
                    });
                    return !term || major.toLocaleLowerCase('id').indexOf(term) !== -1 ||
                        fields.some(function(field){ return field.label.toLocaleLowerCase('id').indexOf(term) !== -1; });
                });
                var matchingFieldCount = selectedProgram ? matchingMajors.length : matchingMajors.reduce(function(count, major){
                    var fields = internshipProgramData[major].filter(function(field){
                        return jobs.some(function(job){ return job.id === field.job_id && job.type === 'magang'; });
                    });
                    return count + fields.filter(function(field){
                        return !term || major.toLocaleLowerCase('id').indexOf(term) !== -1 ||
                            field.label.toLocaleLowerCase('id').indexOf(term) !== -1;
                    }).length;
                }, 0);
                list.classList.add('program-list');
                list.replaceChildren();
                matchingMajors.forEach(function(major){
                    list.appendChild(buildInternshipProgramCard(major, selectedProgram));
                });
                countLabel.textContent = selectedProgram
                    ? '1 bidang ditemukan'
                    : matchingFieldCount + ' pilihan bidang di ' + matchingMajors.length + ' jurusan';
                emptyState.hidden = matchingMajors.length !== 0;
                return;
            }
            list.classList.remove('program-list');
            var visible = jobs.filter(function(job){
                var categoryMatches = !category || (selectedProgram
                    ? job.id === selectedProgram.jobId
                    : job.category === category);
                var searchableText = [
                    selectedProgram ? selectedProgram.field : job.title,
                    selectedProgram ? selectedProgram.major : '',
                    job.company,
                    job.categoryLabel,
                    job.workSystem,
                    job.tags.join(' ')
                ].join(' ').toLocaleLowerCase('id');
                return categoryMatches &&
                    (!type || job.type === type) &&
                    (!term || searchableText.indexOf(term) !== -1);
            });
            var sort = document.getElementById('explorerSort').value;
            if(sort === 'title') visible.sort(function(a,b){ return a.title.localeCompare(b.title, 'id'); });
            if(sort === 'posted' || sort === 'oldest') visible.sort(function(a,b){
                var ageInHours = function(job){
                    if(job.isSample) return null;
                    if(job.posted === 'Aktif Merekrut') return 0;
                    var match = job.posted.match(/(\d+)\s*(menit|jam|hari|minggu|bulan)\s+lalu/i);
                    if(!match) return null;
                    var amount = Number(match[1]);
                    return amount * ({menit:1/60,jam:1,hari:24,minggu:168,bulan:720}[match[2].toLowerCase()]);
                };
                var ageA = ageInHours(a);
                var ageB = ageInHours(b);
                if(ageA === null && ageB === null) return a.title.localeCompare(b.title, 'id');
                if(ageA === null) return 1;
                if(ageB === null) return -1;
                return sort === 'posted' ? ageA - ageB : ageB - ageA;
            });
            list.replaceChildren();
            visible.forEach(function(job){
                list.appendChild(buildListingCard(job, selectedProgram && selectedProgram.jobId === job.id ? selectedProgram : null));
            });
            countLabel.textContent = visible.length + ' lowongan ditemukan';
            emptyState.hidden = visible.length !== 0;
        }
        var detailMajorSelect = document.getElementById('detailInternshipMajor');
        var detailFieldSelect = document.getElementById('detailInternshipField');
        function setSelectPlaceholder(select, text){
            var placeholder = makeElement('option', '', text);
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.hidden = true;
            placeholder.selected = true;
            select.replaceChildren(placeholder);
        }
        function updateDetailProgramFields(jobId, major, selectedField){
            setSelectPlaceholder(detailFieldSelect, 'Pilih bidang');
            var fields = (internshipProgramData[major] || []).filter(function(field){
                return detail.dataset.majorBrowse === 'true' || field.job_id === jobId;
            });
            fields.forEach(function(field){
                var option = makeElement('option', '', field.label);
                option.value = field.label;
                option.dataset.jobId = field.job_id;
                detailFieldSelect.appendChild(option);
            });
            detailFieldSelect.disabled = fields.length === 0;
            detailFieldSelect.value = fields.some(function(field){
                return field.label === selectedField;
            }) ? selectedField : '';
            return fields.length;
        }
        function updateDetailProgramStatus(job){
            var isInternship = job && job.type === 'magang';
            var major = detailMajorSelect.value;
            var field = detailFieldSelect.value;
            var applyButton = document.getElementById('detailApply');
            var selectionTitle = isInternship && major
                ? major + (field ? ' · ' + field : '')
                : displayJobTitle(job);
            document.getElementById('detailBreadcrumb').textContent = selectionTitle;
            document.getElementById('detailTitle').textContent = selectionTitle;
            document.getElementById('detailCompany').textContent = isInternship && major
                ? job.company + ' · Program Magang'
                : job.company + ' · ' + job.categoryLabel;
            applyButton.disabled = isInternship && (!major || !field);
            if(!isInternship) return;
            document.getElementById('detailInternshipStatus').textContent = !major
                ? 'Pilih jurusan terlebih dahulu untuk melihat bidang yang tersedia.'
                : !field
                    ? 'Pilih bidang magang. Setelah itu, tombol Lamar sekarang akan aktif.'
                    : 'Pilihan siap. Klik Lamar sekarang untuk melanjutkan ke formulir pendaftaran.';
        }
        function configureDetailProgramPicker(job, selectedProgram){
            var picker = document.getElementById('detailInternshipPicker');
            var isInternship = job.type === 'magang';
            detail.dataset.majorBrowse = selectedProgram.major && !selectedProgram.field ? 'true' : 'false';
            picker.hidden = !isInternship;
            if(!isInternship){
                detail.dataset.majorBrowse = 'false';
                detailMajorSelect.value = '';
                setSelectPlaceholder(detailFieldSelect, 'Pilih bidang');
                detailFieldSelect.disabled = true;
                updateDetailProgramStatus(job);
                return;
            }

            setSelectPlaceholder(detailMajorSelect, 'Pilih jurusan');
            Object.keys(internshipProgramData).forEach(function(major){
                var hasMatchingField = internshipProgramData[major].some(function(field){
                    return field.job_id === job.id;
                });
                if(detail.dataset.majorBrowse !== 'true' && !hasMatchingField) return;
                var option = makeElement('option', '', major);
                option.value = major;
                detailMajorSelect.appendChild(option);
            });
            detailMajorSelect.value = Array.from(detailMajorSelect.options).some(function(option){
                return option.value === selectedProgram.major;
            }) ? selectedProgram.major : '';
            updateDetailProgramFields(job.id, detailMajorSelect.value, selectedProgram.field || '');
            updateDetailProgramStatus(job);
        }
        detailMajorSelect.addEventListener('change', function(){
            var job = jobs.find(function(item){ return item.id === detail.dataset.jobId; });
            if(!job) return;
            if(detail.dataset.majorBrowse === 'true'){
                var target = internshipProgramDestination(detailMajorSelect.value, null);
                if(target){
                    location.hash = target;
                    return;
                }
            }
            updateDetailProgramFields(job.id, detailMajorSelect.value, '');
            updateDetailProgramStatus(job);
        });
        detailFieldSelect.addEventListener('change', function(){
            var job = jobs.find(function(item){ return item.id === detail.dataset.jobId; });
            var selectedField = detailFieldSelect.selectedOptions[0];
            if(job && selectedField && selectedField.value && selectedField.dataset.jobId !== job.id){
                var query = new URLSearchParams({
                    major: detailMajorSelect.value,
                    field: selectedField.value
                });
                location.hash = '#job/' + encodeURIComponent(selectedField.dataset.jobId) + '?' + query.toString();
                return;
            }
            updateDetailProgramStatus(job);
        });
        function openExplorer(category, type){
            activeCategory = category || '';
            if(typeof type !== 'undefined') typeSelect.value = type || '';
            var params = new URLSearchParams();
            if(activeCategory) params.set('category', activeCategory);
            if(typeSelect.value) params.set('type', typeSelect.value);
            var query = params.toString();
            var target = '#jobs' + (query ? '?' + query : '');
            if(location.hash === target) renderRoute();
            else{
                location.hash = target;
                renderRoute();
            }
        }
        function renderDetail(job, selectedProgram){
            var copy = professionalDetails[job.id] || internshipDetails[job.id] ||
                copyByCategory[job.category] || defaultCopy;
            detail.dataset.jobId = job.id;
            var title = selectedProgram.major
                ? selectedProgram.major + (selectedProgram.field ? ' · ' + selectedProgram.field : '')
                : displayJobTitle(job);
            document.getElementById('detailBreadcrumb').textContent = title;
            setCompanyLogo(document.getElementById('detailLogo'), job);
            document.getElementById('detailTitle').textContent = title;
            document.getElementById('detailCompany').textContent = selectedProgram.major
                ? job.company + ' · Program Magang'
                : job.company + ' · ' + job.categoryLabel;
            document.getElementById('detailVerifiedIcon').hidden = job.isSample;
            document.getElementById('detailVerifiedLabel').hidden = job.isSample;
            setCompanyLogo(document.getElementById('detailCompanyLogo'), job);
            document.getElementById('detailCompanyName').textContent = job.company;
            document.getElementById('detailCompanyInfo').textContent = job.companyInfo;
            document.getElementById('detailSalary').textContent = job.benefit;
            document.getElementById('detailBenefitMeta').textContent = job.benefit;
            document.getElementById('detailWorkSystemMeta').textContent = job.workSystem;
            document.getElementById('detailTypeMeta').textContent = job.type === 'magang' ? 'Magang' : 'Lowongan kerja';
            document.getElementById('detailPosted').textContent = job.posted;
            document.getElementById('detailApplicants').textContent = job.applicants + ' pelamar';
            var applyButton = document.getElementById('detailApply');
            applyButton.title = '';
            applyButton.disabled = false;
            applyButton.innerHTML = '<i class="bi bi-send"></i> Lamar sekarang';
            configureDetailProgramPicker(job, selectedProgram || {});
            document.getElementById('detailDescription').textContent = roleDescription(job);
            document.getElementById('applyNotice').classList.remove('show');
            var allTags = [job.workSystem, job.typeLabel].concat(job.tags.filter(function(tag){
                var normalized = tag.toLocaleLowerCase('id');
                return normalized !== job.workSystem.toLocaleLowerCase('id') &&
                    job.typeLabel.toLocaleLowerCase('id').indexOf(normalized) === -1;
            }));
            var tagContainer = document.getElementById('detailTags');
            tagContainer.replaceChildren();
            allTags.forEach(function(tag){ tagContainer.appendChild(makeElement('span', '', tag)); });
            formatList('detailResponsibilities', copy.tasks);
            formatList('detailQualifications', copy.qualifications);
            formatList('detailBenefits', copy.workSystem
                ? copy.workSystem.concat(job.type === 'magang' ? [] : ['Aman, nyaman, untung.'])
                : [
                    job.benefit,
                    'Sistem kerja dan jadwal disampaikan oleh perusahaan saat proses seleksi.',
                    'Aman, nyaman, untung.'
                ]);
            formatList('detailStages', [
                'Pemeriksaan CV dan kesesuaian pengalaman.',
                'Wawancara awal dengan tim rekrutmen.',
                'Diskusi kompetensi sesuai posisi yang dilamar.',
                'Konfirmasi hasil dan informasi penawaran.'
            ]);
            var save = document.getElementById('detailSave');
            save.dataset.saveJob = job.id;
            setSavedButton(save, savedJobs.has(job.id));
            var similar = document.getElementById('detailSimilar');
            similar.replaceChildren();
            jobs.filter(function(candidate){
                return candidate.id !== job.id && candidate.type === job.type && !candidate.isSample;
            })
                .filter(function(candidate){ return candidate.category === job.category || job.type === 'profesional'; })
                .slice(0, 3)
                .forEach(function(candidate){
                    var link = makeElement('a', '', displayJobTitle(candidate));
                    link.href = '#job/' + encodeURIComponent(candidate.id);
                    link.appendChild(makeElement('small', '', candidate.company + ' · ' + candidate.workSystem));
                    similar.appendChild(link);
                });
            if(!similar.children.length) similar.appendChild(makeElement('small', '', 'Belum ada lowongan serupa lainnya.'));
        }
        function renderApplication(job, selectedProgram){
            var form = document.getElementById('careerApplicationForm');
            selectedProgram = selectedProgram || {};
            var previousJobId = form.dataset.activeJobId;
            var changedJob = Boolean(previousJobId && previousJobId !== job.id);
            if(changedJob){
                form.reset();
                ['experienceList','educationList'].forEach(function(listId){
                    var listElement = document.getElementById(listId);
                    while(listElement.children.length > 1) listElement.lastElementChild.remove();
                    listElement.querySelectorAll('input,textarea,select').forEach(function(field){
                        if(field.type === 'checkbox') field.checked = false;
                        else field.value = '';
                    });
                });
                document.getElementById('applicationCvFiles').replaceChildren();
                document.getElementById('applicationCv').closest('.apply-upload').classList.remove('has-file');
                applicationSection.querySelectorAll('#applicationDocumentInputs input[type="file"]').forEach(function(input){
                    input.value = '';
                    input.closest('.apply-upload').classList.remove('has-file');
                    input.closest('.apply-field').querySelector('.apply-file-list').replaceChildren();
                });
            }
            form.dataset.activeJobId = job.id;
            form.action = @json(route('karir.apply.store')) + '/' + encodeURIComponent(job.id);
            var isInternship = job.type === 'magang';
            applicationSection.dataset.isInternship = isInternship ? 'true' : 'false';
            applicationSection.querySelectorAll('[data-internship-only]').forEach(function(element){ element.hidden = !isInternship; });
            applicationSection.querySelectorAll('[data-professional-only]').forEach(function(element){ element.hidden = isInternship; });
            applicationSection.querySelectorAll('[data-internship-required]').forEach(function(field){
                field.required = isInternship && !field.closest('[data-professional-only]');
            });
            applicationSection.querySelectorAll('[data-internship-only] input, [data-internship-only] textarea, [data-internship-only] select').forEach(function(field){
                var repeatItem = field.closest('[data-repeat-item]');
                if(field.name.indexOf('end_period') !== -1 && repeatItem){
                    var currentlyWorking = repeatItem.querySelector('[name$="[currently_working]"]');
                    field.required = isInternship && !field.closest('[data-professional-only]') &&
                        !(currentlyWorking && currentlyWorking.checked);
                }
            });
            document.getElementById('applicationCv').accept = isInternship ? '.pdf' : '.pdf,.doc,.docx';
            var targetPosition = document.getElementById('applicationTargetPosition');
            var internshipMajor = document.getElementById('applicationInternshipMajor');
            document.getElementById('applicationDiplomaField').classList.toggle('full', isInternship);
            updateInternshipPeriodLabels();
            document.getElementById('applicationDiplomaLabel').textContent =
                isInternship ? 'Fotokopi ijazah pendidikan terakhir' : 'Fotokopi ijazah terakhir';
            document.getElementById('applicationDiplomaPrompt').textContent =
                isInternship ? 'Pilih fotokopi ijazah pendidikan terakhir' : 'Pilih fotokopi ijazah terakhir';
            document.getElementById('applicationJobTitle').textContent = displayJobTitle(job) + ' · ' + job.company;
            applicationSection.dataset.jobId = job.id;
            if(previousJobId !== job.id){
                var key = applicationDraftKey(job.id);
                if(form.dataset.applicationSubmitted === 'true'){
                    try{ localStorage.removeItem(key); }catch(error){ console.error('Draft lamaran tidak dapat dihapus:', error); }
                }else{
                    restoreApplicationDraft(job.id);
                }
                form.dataset.applicationSubmitted = 'false';
            }
            document.getElementById('applicationJobId').value = job.id;
            if(isInternship){
                var majorValue = selectedProgram.major || (changedJob ? '' : internshipMajor.value.trim());
                var fieldValue = selectedProgram.field || (changedJob ? '' : targetPosition.value.trim());
                var matchingProgram = (internshipProgramData[majorValue] || []).find(function(program){
                    return program.label === fieldValue && program.job_id === job.id;
                });
                internshipMajor.value = matchingProgram ? majorValue : '';
                targetPosition.value = matchingProgram ? matchingProgram.label : '';
            }else if(!isInternship){
                internshipMajor.value = '';
                targetPosition.value = '';
            }
            document.getElementById('applicationJobTitle').textContent =
                (isInternship ? targetPosition.value || 'Program Magang' : displayJobTitle(job)) + ' · ' + job.company;
            syncInternshipEndMinimum();
            updateInternshipPeriodLabels();
            updateApplicationProgress();
            lastApplicationActivity = Date.now();
            refreshApplicationSession();
        }
        function applicationDraftKey(jobId){
            return 'ewf-application-draft:' + document.getElementById('careerApplicationForm').dataset.userId + ':' + jobId;
        }
        function updateInternshipPeriodLabels(){
            var isPkl = document.getElementById('applicationRegistrationType').value === 'Praktik Kerja Lapangan (PKL)';
            var program = isPkl ? 'PKL' : 'magang';
            document.getElementById('applicationInternshipStartLabel').textContent = 'Periode awal ' + program;
            document.getElementById('applicationInternshipEndLabel').textContent = 'Periode selesai ' + program;
        }
        function syncInternshipEndMinimum(){
            var startPeriod = document.getElementById('applicationInternshipStart').value;
            document.getElementById('applicationInternshipEnd').min = startPeriod || '';
        }
        function cloneRepeatRow(listElement, index){
            var clone = listElement.firstElementChild.cloneNode(true);
            clone.querySelectorAll('input,textarea,select').forEach(function(field){
                field.name = field.name.replace(/\[\d+\]/, '[' + index + ']');
                if(field.type === 'checkbox') field.checked = false;
                else field.value = '';
            });
            clone.querySelectorAll('[data-internship-only]').forEach(function(element){
                element.hidden = applicationSection.dataset.isInternship !== 'true';
            });
            clone.querySelectorAll('[data-professional-only]').forEach(function(element){
                element.hidden = applicationSection.dataset.isInternship === 'true';
            });
            listElement.appendChild(clone);
            var currentWork = clone.querySelector('[name$="[currently_working]"]');
            if(currentWork) syncEndPeriodRequirement(currentWork);
            return clone;
        }
        function restoreApplicationDraft(jobId){
            var raw;
            try{ raw = localStorage.getItem(applicationDraftKey(jobId)); }
            catch(error){
                console.error('Draft lamaran tidak dapat dibaca:', error);
                document.getElementById('applicationDraftStatus').textContent = 'Draft browser tidak dapat dibaca.';
                return;
            }
            if(!raw){
                restoreApplicationDraftFiles(jobId, false);
                return;
            }
            try{
                var savedFields = JSON.parse(raw);
                if(!Array.isArray(savedFields)) throw new TypeError('Format draft tidak valid.');
                ['experiences','education'].forEach(function(kind){
                    var largestIndex = savedFields.reduce(function(max, entry){
                        var match = entry.name.indexOf(kind + '[') === 0 ? entry.name.match(/\[(\d+)\]/) : null;
                        return match ? Math.max(max, Number(match[1])) : max;
                    }, 0);
                    var listElement = document.getElementById(kind === 'experiences' ? 'experienceList' : 'educationList');
                    while(listElement.children.length <= largestIndex) cloneRepeatRow(listElement, listElement.children.length);
                });
                var fieldsByName = {};
                applicationForm.querySelectorAll('[name]').forEach(function(field){
                    (fieldsByName[field.name] || (fieldsByName[field.name] = [])).push(field);
                });
                var offsets = {};
                savedFields.forEach(function(entry){
                    var fields = fieldsByName[entry.name] || [];
                    var offset = offsets[entry.name] || 0;
                    var field = fields[offset];
                    offsets[entry.name] = offset + 1;
                    if(!field || field.type === 'file' || field.name === 'job_id') return;
                    if(field.type === 'checkbox') field.checked = Boolean(entry.checked);
                    else field.value = entry.value;
                });
                applicationForm.querySelectorAll('[name$="[currently_working]"]').forEach(syncEndPeriodRequirement);
                document.getElementById('applicationDraftStatus').textContent = 'Data formulir dipulihkan. Memeriksa dokumen draft...';
                updateApplicationProgress();
                restoreApplicationDraftFiles(jobId, true);
            }catch(error){
                console.error('Draft lamaran memiliki format yang tidak valid:', error);
                document.getElementById('applicationDraftStatus').textContent = 'Draft tidak dapat dipulihkan.';
            }
        }
        function openApplicationDraftDatabase(){
            return new Promise(function(resolve,reject){
                if(!window.indexedDB){
                    reject(new Error('Browser ini tidak mendukung penyimpanan berkas draft.'));
                    return;
                }
                var request = window.indexedDB.open('ewf-career-application-drafts', 1);
                request.onupgradeneeded = function(){
                    var database = request.result;
                    if(!database.objectStoreNames.contains('documents')) database.createObjectStore('documents');
                };
                request.onsuccess = function(){ resolve(request.result); };
                request.onerror = function(){ reject(request.error || new Error('Database draft tidak dapat dibuka.')); };
            });
        }
        function saveApplicationDraftFiles(key){
            var files = {cv: cvInput.files[0] || null, supporting: {}};
            applicationSection.querySelectorAll('#applicationDocumentInputs input[type="file"]').forEach(function(input){
                files.supporting[input.name] = input.files[0] || null;
            });
            return openApplicationDraftDatabase().then(function(database){
                return new Promise(function(resolve,reject){
                    var transaction;
                    try{
                        transaction = database.transaction('documents', 'readwrite');
                        transaction.objectStore('documents').put(files, key);
                    }catch(error){
                        database.close();
                        reject(error);
                        return;
                    }
                    transaction.oncomplete = function(){ database.close(); resolve(); };
                    transaction.onerror = function(){ database.close(); reject(transaction.error || new Error('Dokumen draft tidak dapat disimpan.')); };
                    transaction.onabort = function(){ database.close(); reject(transaction.error || new Error('Penyimpanan dokumen draft dibatalkan.')); };
                });
            });
        }
        function restoreApplicationDraftFiles(jobId, hasFormDraft){
            var key = applicationDraftKey(jobId);
            openApplicationDraftDatabase().then(function(database){
                return new Promise(function(resolve,reject){
                    var transaction;
                    var savedFiles;
                    try{
                        transaction = database.transaction('documents', 'readonly');
                        var request = transaction.objectStore('documents').get(key);
                        request.onsuccess = function(){ savedFiles = request.result; };
                    }catch(error){
                        database.close();
                        reject(error);
                        return;
                    }
                    transaction.oncomplete = function(){ database.close(); resolve(savedFiles); };
                    transaction.onerror = function(){ database.close(); reject(transaction.error || new Error('Dokumen draft tidak dapat dibaca.')); };
                    transaction.onabort = function(){ database.close(); reject(transaction.error || new Error('Pembacaan dokumen draft dibatalkan.')); };
                });
            }).then(function(savedFiles){
                if(!savedFiles){
                    if(hasFormDraft) document.getElementById('applicationDraftStatus').textContent = 'Data formulir dipulihkan. Dokumen draft lama belum tersimpan di browser.';
                    return;
                }
                var restoredCount = 0;
                var restoreFile = function(input,file,target){
                    if(!file) return;
                    if(typeof DataTransfer !== 'function') throw new Error('Browser ini tidak mendukung pemulihan berkas draft.');
                    var transfer = new DataTransfer();
                    transfer.items.add(file);
                    input.files = transfer.files;
                    renderSelectedFiles(input,target);
                    restoredCount++;
                };
                restoreFile(cvInput, savedFiles.cv, document.getElementById('applicationCvFiles'));
                applicationSection.querySelectorAll('#applicationDocumentInputs input[type="file"]').forEach(function(input){
                    restoreFile(input, savedFiles.supporting && savedFiles.supporting[input.name], input.closest('.apply-field').querySelector('.apply-file-list'));
                });
                document.getElementById('applicationDraftStatus').textContent = restoredCount
                    ? 'Draft formulir dan ' + restoredCount + ' dokumen berhasil dipulihkan dari browser ini.'
                    : 'Draft formulir dipulihkan. Belum ada berkas yang tersimpan di draft ini.';
            }).catch(function(error){
                console.error('Dokumen draft tidak dapat dipulihkan:', error);
                document.getElementById('applicationDraftStatus').textContent = 'Data formulir dipulihkan, tetapi dokumen draft tidak dapat dibaca dari browser.';
            });
        }
        var applicationDraftSaveAttempt = 0;
        function saveApplicationDraft(){
            var fields = Array.from(applicationForm.querySelectorAll('[name]'))
                .filter(function(field){ return field.type !== 'file' && field.name !== 'job_id'; })
                .map(function(field){ return {name: field.name, value: field.value, checked: field.type === 'checkbox' ? field.checked : undefined}; });
            var key = applicationDraftKey(applicationForm.dataset.activeJobId);
            var attempt = ++applicationDraftSaveAttempt;
            try{
                localStorage.setItem(key, JSON.stringify(fields));
            }catch(error){
                console.error('Draft lamaran tidak dapat disimpan:', error);
                document.getElementById('applicationDraftStatus').textContent = 'Draft gagal disimpan. Periksa ruang penyimpanan browser.';
                return;
            }
            saveApplicationDraftFiles(key).then(function(){
                if(attempt === applicationDraftSaveAttempt){
                    document.getElementById('applicationDraftStatus').textContent = 'Draft formulir dan dokumen tersimpan di browser ini.';
                }
            }).catch(function(error){
                console.error('Dokumen draft tidak dapat disimpan:', error);
                if(attempt === applicationDraftSaveAttempt){
                    document.getElementById('applicationDraftStatus').textContent = error.name === 'QuotaExceededError'
                        ? 'Data formulir tersimpan, tetapi ruang browser tidak cukup untuk menyimpan dokumen.'
                        : 'Data formulir tersimpan, tetapi dokumen gagal disimpan di browser.';
                }
            });
        }
        function syncEndPeriodRequirement(field){
            var row = field.closest('[data-repeat-item]');
            var endPeriod = row.querySelector('[name$="[end_period]"]');
            if(endPeriod) endPeriod.required = applicationSection.dataset.isInternship === 'true' && !field.checked;
        }
        function updateApplicationProgress(){
            var isInternship = applicationSection.dataset.isInternship === 'true';
            var experienceRows = Array.from(document.querySelectorAll('#experienceList [data-repeat-item]'));
            var educationRows = Array.from(document.querySelectorAll('#educationList [data-repeat-item]'));
            var isVisible = function(field){ return !field.disabled && !field.closest('[hidden]'); };
            var isFilled = function(field){
                if(!field) return false;
                if(field.type === 'checkbox') return field.checked;
                if(field.type === 'file') return field.files.length > 0;
                return field.value.trim() !== '';
            };
            var profileFields = ['applicationName','applicationEmail','applicationPhone','applicationLocation','applicationBirthDate']
                .map(function(id){ return document.getElementById(id); }).filter(isVisible);
            var progressFields = profileFields.slice();
            var registrationFields = ['applicationRegistrationType','applicationInternshipMajor','applicationTargetPosition','applicationInternshipStart','applicationInternshipEnd']
                .map(function(id){ return document.getElementById(id); }).filter(isVisible);
            if(isInternship){
                progressFields = progressFields
                    .concat(Array.from(document.querySelectorAll('#educationList [data-internship-required]')).filter(isVisible))
                    .concat(registrationFields);
            }else{
                ['#experienceList [name$="[company]"]','#experienceList [name$="[position]"]','#experienceList [name$="[description]"]',
                    '#educationList [name$="[institution]"]','#educationList [name$="[major]"]','#educationList [name$="[year]"]']
                    .forEach(function(selector){
                        progressFields = progressFields.concat(Array.from(document.querySelectorAll(selector)).filter(isVisible));
                    });
            }
            progressFields.push(document.getElementById('applicationSkills'), document.getElementById('applicationCv'), document.getElementById('applicationConsent'));
            progressFields = progressFields.filter(isVisible);
            var completedFields = progressFields.filter(isFilled);
            var percent = progressFields.length ? Math.round(completedFields.length / progressFields.length * 100) : 0;
            var progressValue = document.getElementById('applicationProgressValue');
            progressValue.textContent = percent + '%';
            progressValue.style.setProperty('--progress', percent + '%');
            document.getElementById('applicationProgressText').textContent = percent + '%';
            document.getElementById('applicationProgressBar').style.width = percent + '%';
            document.getElementById('applicationProgressDescription').textContent = isInternship
                ? 'Kelengkapan dihitung dari profil, pendidikan, pilihan jurusan dan bidang, periode program, keahlian, CV, dan persetujuan.'
                : 'Kelengkapan dihitung dari profil, pengalaman, pendidikan, keahlian, CV, dan persetujuan.';
            var optionalRowsComplete = function(rows, fieldNames){
                var filledRows = rows.filter(function(row){
                    return fieldNames.some(function(name){
                        var field = row.querySelector('[name$="[' + name + ']"]');
                        return field && field.value.trim() !== '';
                    });
                });
                return filledRows.length > 0 && filledRows.every(function(row){
                    return fieldNames.every(function(name){
                        var field = row.querySelector('[name$="[' + name + ']"]');
                        return field && field.value.trim() !== '';
                    });
                });
            };
            var allRequiredFilled = function(row){
                return ['level','institution','major','status','start_year','completion_year'].every(function(name){
                    var field = row.querySelector('[name$="[' + name + ']"]');
                    return isFilled(field);
                });
            };
            var experienceComplete = !isInternship && optionalRowsComplete(experienceRows, ['company','position']);
            var educationComplete = isInternship
                ? educationRows.length > 0 && educationRows.every(allRequiredFilled)
                : optionalRowsComplete(educationRows, ['institution','major']);
            var profileComplete = profileFields.every(isFilled);
            var planComplete = registrationFields.length === 4 && registrationFields.every(isFilled);
            var skillsField = document.getElementById('applicationSkills');
            var consentField = document.getElementById('applicationConsent');
            var registrationComplete = !isInternship || planComplete;
            var cv = document.getElementById('applicationCv');
            document.querySelectorAll('[data-check]').forEach(function(item){
                var checked;
                if(item.dataset.check === 'applicationExperience') checked = experienceComplete;
                else if(item.dataset.check === 'applicationEducation') checked = educationComplete;
                else if(item.dataset.check === 'applicationRegistration') checked = registrationComplete;
                else if(item.dataset.check === 'applicationSkills') checked = skillsField.value.trim() !== '';
                else if(item.dataset.check === 'applicationCv') checked = cv.files.length > 0;
                else if(item.dataset.check === 'applicationConsent') checked = consentField.checked;
                else if(item.dataset.check === 'applicationName') checked = profileComplete;
                else{
                    var field = document.getElementById(item.dataset.check);
                    checked = field && (field.type === 'checkbox' ? field.checked : field.type === 'file' ? field.files.length > 0 : field.value.trim() !== '');
                }
                item.hidden = (item.hasAttribute('data-internship-only') && !isInternship) || (item.hasAttribute('data-professional-only') && isInternship);
                item.classList.toggle('done', Boolean(checked));
                item.querySelector('i').className = checked ? 'bi bi-check-circle-fill' : 'bi bi-circle';
            });
            var cvCount = cv.files.length;
            document.getElementById('applicationCvStatus').className = cvCount ? 'bi bi-check-circle-fill' : 'bi bi-circle';
            var availableSupportingInputs = Array.from(document.querySelectorAll('#applicationDocumentInputs input[type="file"]'))
                .filter(function(input){ return !input.closest('[hidden]'); });
            var supportingCount = availableSupportingInputs.filter(function(input){ return input.files.length > 0; }).length;
            document.getElementById('applicationDocumentCount').textContent =
                (cvCount + supportingCount) + '/' + (1 + availableSupportingInputs.length);
            document.getElementById('applicationSupportingCount').textContent =
                supportingCount + '/' + availableSupportingInputs.length;
        }
        function renderRoute(){
            var hash = location.hash.slice(1);

            // reset halaman Tersimpan, lalu tampilkan lagi bila route-nya #saved
            root.classList.remove('is-saved');
            savedSection.hidden = true;
            applicationSection.hidden = true;
            if(hash === 'saved'){
                root.classList.remove('is-detail', 'is-explorer', 'is-apply');
                root.classList.add('is-saved');
                explorer.hidden = true;
                detail.hidden = true;
                savedSection.hidden = false;
                renderSaved();
                window.scrollTo({top: 0, behavior: 'smooth'});
                return;
            }

            var applyMatch = hash.match(/^apply\/([^?]+)(?:\?(.*))?$/);
            if(applyMatch){
                var applicationJobId = decodeURIComponent(applyMatch[1]);
                var applicationJob = jobs.find(function(job){ return job.id === applicationJobId; });
                if(!applicationJob){ location.hash = '#jobs'; return; }
                var applicationQuery = new URLSearchParams(applyMatch[2] || '');
                if(!@json(auth()->check())){
                    var applyUrl = '/karir' + location.search + '#apply/' + encodeURIComponent(applicationJob.id) +
                        (applicationQuery.toString() ? '?' + applicationQuery.toString() : '');
                    location.replace(@json(route('login')) + '?next=' + encodeURIComponent(applyUrl));
                    return;
                }
                root.classList.remove('is-detail', 'is-explorer');
                root.classList.add('is-apply');
                explorer.hidden = true;
                detail.hidden = true;
                applicationSection.hidden = false;
                renderApplication(applicationJob, {
                    major: applicationQuery.get('major') || '',
                    field: applicationQuery.get('field') || ''
                });
                window.scrollTo({top: 0, behavior: 'smooth'});
                return;
            }

            var jobMatch = hash.match(/^job\/([^?]+)(?:\?(.*))?$/);
            var jobsMatch = hash.match(/^jobs(?:\?(.*))?$/);
            if(jobMatch){
                var jobId = decodeURIComponent(jobMatch[1]);
                var selected = jobs.find(function(job){ return job.id === jobId; });
                if(!selected){ location.hash = '#jobs'; return; }
                root.classList.remove('is-explorer', 'is-apply');
                root.classList.add('is-detail');
                explorer.hidden = true;
                detail.hidden = false;
                var detailQuery = new URLSearchParams(jobMatch[2] || '');
                renderDetail(selected, {
                    major: detailQuery.get('major') || '',
                    field: detailQuery.get('field') || ''
                });
                window.scrollTo({top: 0, behavior: 'smooth'});
                return;
            }
            if(jobsMatch){
                var params = new URLSearchParams(jobsMatch[1] || '');
                activeCategory = params.get('category') || '';
                var requestedType = params.get('type') || '';
                typeSelect.value = ['profesional', 'magang'].indexOf(requestedType) !== -1 ? requestedType : '';
                configureCareerFilters(activeCategory);
                root.classList.remove('is-detail', 'is-apply');
                root.classList.add('is-explorer');
                detail.hidden = true;
                explorer.hidden = false;
                document.querySelector('#careerExplorer .career-heading h1').textContent =
                    activeCategory ? 'Lowongan ' + (categorySelect.selectedOptions[0]?.textContent || categoryNames[activeCategory] || 'Karir') :
                        typeSelect.value === 'magang' ? 'Program magang' :
                            typeSelect.value === 'profesional' ? 'Lowongan kerja profesional' : 'Temukan peluang karier';
                updateResults();
                window.scrollTo({top: 0, behavior: 'smooth'});
                return;
            }
            root.classList.remove('is-explorer', 'is-detail', 'is-apply');
            explorer.hidden = true;
            detail.hidden = true;
        }

        root.querySelector('#explorerSearch').addEventListener('submit', function(event){
            event.preventDefault();
            rememberSearch(queryInput.value);
            updateResults();
        });
        categorySelect.addEventListener('change', function(){
            activeCategory = categorySelect.value;
            document.querySelector('#careerExplorer .career-heading h1').textContent =
                activeCategory ? 'Lowongan ' + (categorySelect.selectedOptions[0]?.textContent || categoryNames[activeCategory] || 'Karir') :
                    typeSelect.value === 'magang' ? 'Program magang' :
                        typeSelect.value === 'profesional' ? 'Lowongan kerja profesional' : 'Temukan peluang karier';
            var params = new URLSearchParams();
            if(activeCategory) params.set('category', activeCategory);
            if(typeSelect.value) params.set('type', typeSelect.value);
            var target = '#jobs' + (params.toString() ? '?' + params.toString() : '');
            history.replaceState(null, '', location.pathname + location.search + target);
            updateResults();
        });
        typeSelect.addEventListener('change', function(){
            activeCategory = '';
            configureCareerFilters('');
            updateResults();
        });
        document.getElementById('explorerSort').addEventListener('change', updateResults);
        queryInput.addEventListener('input', updateResults);
        document.querySelector('.filter-clear').addEventListener('click', function(){
            categorySelect.value = '';
            typeSelect.value = '';
            queryInput.value = '';
            document.getElementById('explorerSort').value = 'title';
            activeCategory = '';
            openExplorer('');
        });
        root.addEventListener('click', function(event){
            var clearHistoryButton = event.target.closest('[data-clear-search-history]');
            if(clearHistoryButton && root.contains(clearHistoryButton)){
                try{
                    localStorage.removeItem(searchStorageKey);
                }catch(error){
                    console.warn('Riwayat pencarian tidak dapat dihapus:', error);
                    return;
                }
                searchHistory = [];
                renderSearchHistory();
                return;
            }
            var searchButton = event.target.closest('[data-search-term]');
            if(!searchButton || !root.contains(searchButton)) return;
            var term = searchButton.dataset.searchTerm;
            var isHomeSearch = Boolean(searchButton.closest('.home-popular-terms'));
            rememberSearch(term);
            if(isHomeSearch){
                root.querySelector('.search [name="q"]').value = term;
                queryInput.value = term;
                var homeType = root.querySelector('.search [name="tipe"]');
                var homeCategory = root.querySelector('.search [name="bidang"]');
                if(homeType) openExplorer(homeCategory ? homeCategory.value : '', homeType.value);
                return;
            }
            queryInput.value = term;
            updateResults();
            queryInput.focus();
        });
        root.querySelector('.search').addEventListener('submit', function(event){
            var careerType = this.querySelector('[name="tipe"]');
            if(!careerType) return;
            event.preventDefault();
            var term = this.querySelector('[name="q"]').value;
            queryInput.value = term;
            rememberSearch(term);
            var careerCategory = this.querySelector('[name="bidang"]');
            openExplorer(careerCategory ? careerCategory.value : '', careerType.value);
        });
        root.querySelectorAll('.tabs a[data-career-pane]').forEach(function(link){
            link.addEventListener('click', function(){
                var tab = document.querySelector('#krSwitch button[data-t="' + link.dataset.careerPane + '"]');
                if(tab) tab.click();
                root.querySelectorAll('.tabs a[data-career-pane]').forEach(function(item){
                    item.classList.toggle('on', item === link);
                });
            });
        });
        // tombol "Jelajahi Lowongan Kerja" / "Jelajahi Program Magang" -> turun ke daftar dan buka tab yang sesuai
        root.querySelectorAll('a[data-explore]').forEach(function(link){
            link.addEventListener('click', function(event){
                event.preventDefault();
                var type = link.dataset.explore;
                var tab = document.querySelector('#krSwitch button[data-t="' + type + '"]');
                if(tab) tab.click();
                root.querySelectorAll('.tabs a[data-career-pane]').forEach(function(item){
                    item.classList.toggle('on', item.dataset.careerPane === type);
                });
                var target = document.getElementById('lowongan');
                if(target) requestAnimationFrame(function(){ target.scrollIntoView({behavior: 'smooth', block: 'start'}); });
            });
        });

        /* ===== Simpan lowongan: tombol di detail, daftar eksplor, dan ikon bookmark di kartu beranda ===== */
        function toggleSaved(id){
            if(savedJobs.has(id)) savedJobs.delete(id); else savedJobs.add(id);
            persistSaved();
            syncSavedUI();
        }
        function syncSavedUI(){
            document.querySelectorAll('[data-saved-count]').forEach(function(el){ el.textContent = savedJobs.size; });
            root.querySelectorAll('[data-save-job]').forEach(function(b){ setSavedButton(b, savedJobs.has(b.dataset.saveJob)); });
            root.querySelectorAll('#lowongan .job[data-job-id] .mark').forEach(function(m){
                var on = savedJobs.has(m.closest('.job').dataset.jobId);
                m.classList.toggle('bi-bookmark-fill', on);
                m.classList.toggle('bi-bookmark', !on);
                m.classList.toggle('on', on);
            });
            if(!savedSection.hidden) renderSaved();
        }
        function renderSaved(){
            var items = jobs.filter(function(j){ return savedJobs.has(j.id); });
            var box = document.getElementById('savedJobs');
            box.replaceChildren();
            items.forEach(function(j){ box.appendChild(buildListingCard(j)); });
            document.getElementById('savedCount').textContent = items.length + ' lowongan tersimpan';
            document.getElementById('savedEmpty').hidden = items.length !== 0;
            document.getElementById('clearSaved').hidden = items.length === 0;
        }
        document.getElementById('clearSaved').addEventListener('click', function(){
            savedJobs.clear();
            persistSaved();
            syncSavedUI();
        });
        root.addEventListener('click', function(event){
            var saveButton = event.target.closest('[data-save-job]');
            if(saveButton){ toggleSaved(saveButton.dataset.saveJob); return; }
            var mark = event.target.closest('.job .mark');   // ikon bookmark di kartu beranda
            if(mark) toggleSaved(mark.closest('.job').dataset.jobId);
        });

        document.querySelectorAll('.detail-back').forEach(function(link){
            link.addEventListener('click', function(event){
                event.preventDefault();
                openExplorer(activeCategory);
            });
        });
        document.getElementById('detailApply').addEventListener('click', function(){
            var jobId = detail.dataset.jobId;
            var selectedJob = jobs.find(function(job){ return job.id === jobId; });
            if(selectedJob && selectedJob.type === 'magang'){
                var major = detailMajorSelect.value;
                var field = detailFieldSelect.value;
                if(!major || !field) return;
                var selection = new URLSearchParams({major: major, field: field});
                location.hash = '#apply/' + encodeURIComponent(jobId) + '?' + selection.toString();
                return;
            }
            if(jobId) location.hash = '#apply/' + encodeURIComponent(jobId);
        });
        document.getElementById('detailShare').addEventListener('click', function(){
            var shareData = {title: document.getElementById('detailTitle').textContent, url: location.href};
            if(navigator.share){
                navigator.share(shareData).catch(function(error){
                    if(error.name !== 'AbortError') console.error('Gagal membagikan lowongan:', error);
                });
                return;
            }
            if(navigator.clipboard){
                navigator.clipboard.writeText(shareData.url).then(function(){
                    document.getElementById('detailShare').innerHTML = '<i class="bi bi-check2"></i> Tautan disalin';
                }).catch(function(error){ console.error('Gagal menyalin tautan lowongan:', error); });
            }
        });
        var applicationForm = document.getElementById('careerApplicationForm');
        applicationForm.addEventListener('formdata', function(event){
            var selectedJobId = applicationSection.dataset.jobId;
            if(selectedJobId) event.formData.set('job_id', selectedJobId);
        });
        var sessionRefreshUrl = @json(route('karir.session.refresh'));
        var lastApplicationActivity = Date.now();
        var lastSessionRefresh = 0;
        var sessionRefreshRequest = null;
        var applicationSessionWarning = document.getElementById('applicationSessionWarning');
        function refreshApplicationSession(){
            if(sessionRefreshRequest) return sessionRefreshRequest;
            sessionRefreshRequest = fetch(sessionRefreshUrl, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: {'Accept': 'application/json'}
            }).then(function(response){
                if(!response.ok || response.redirected){
                    applicationSessionWarning.hidden = false;
                    return false;
                }
                lastSessionRefresh = Date.now();
                applicationSessionWarning.hidden = true;
                return true;
            }).catch(function(error){
                console.warn('Sesi formulir lamaran tidak dapat diperbarui:', error);
                applicationSessionWarning.hidden = false;
                return false;
            }).finally(function(){
                sessionRefreshRequest = null;
            });
            return sessionRefreshRequest;
        }
        function recordApplicationActivity(){
            lastApplicationActivity = Date.now();
            if(!applicationSection.hidden && lastApplicationActivity - lastSessionRefresh > 5 * 60 * 1000){
                refreshApplicationSession();
            }
        }
        ['pointerdown','keydown'].forEach(function(eventName){
            applicationForm.addEventListener(eventName, recordApplicationActivity, {passive:true});
        });
        window.setInterval(function(){
            var recentlyActive = Date.now() - lastApplicationActivity < 10 * 60 * 1000;
            if(!applicationSection.hidden && document.visibilityState === 'visible' &&
                recentlyActive && Date.now() - lastSessionRefresh >= 5 * 60 * 1000){
                refreshApplicationSession();
            }
        }, 60 * 1000);
        applicationForm.addEventListener('input', updateApplicationProgress);
        var draftSaveTimer;
        applicationForm.addEventListener('input', function(){
            recordApplicationActivity();
            clearTimeout(draftSaveTimer);
            draftSaveTimer = setTimeout(saveApplicationDraft, 700);
        });
        applicationForm.addEventListener('change', function(event){
            recordApplicationActivity();
            if(event.target.matches('[name$="[currently_working]"]')) syncEndPeriodRequirement(event.target);
            if(event.target.id === 'applicationRegistrationType') updateInternshipPeriodLabels();
            if(event.target.id === 'applicationInternshipStart') syncInternshipEndMinimum();
            updateApplicationProgress();
            clearTimeout(draftSaveTimer);
            draftSaveTimer = setTimeout(saveApplicationDraft, 700);
        });
        var submitConfirmed = false;
        applicationForm.addEventListener('submit', function(event){
            var selectedJobId = applicationSection.dataset.jobId;
            if(selectedJobId) document.getElementById('applicationJobId').value = selectedJobId;
            if(!submitConfirmed){
                event.preventDefault();
                openApplicationPreview();
                return;
            }
            if(Date.now() - lastSessionRefresh > 5 * 60 * 1000){
                event.preventDefault();
                refreshApplicationSession().then(function(sessionIsActive){
                    if(sessionIsActive) applicationForm.requestSubmit();
                });
                return;
            }
            saveApplicationDraft();
            var submitButton = document.getElementById('applicationSubmit');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mengirim berkas...';
        });
        document.getElementById('saveApplicationDraft').addEventListener('click', saveApplicationDraft);
        document.getElementById('confirmApplicationSubmit').addEventListener('click', function(){
            if(!applicationForm.reportValidity()) return;
            submitConfirmed = true;
            document.getElementById('applicationPreviewDialog').close();
            applicationForm.requestSubmit();
        });

        applicationSection.addEventListener('click', function(event){
            var addButton = event.target.closest('[data-add-row]');
            if(addButton){
                var kind = addButton.dataset.addRow;
                var listId = kind === 'experience' ? 'experienceList' : 'educationList';
                var listElement = document.getElementById(listId);
                if(listElement.children.length >= 5){
                    document.getElementById('applicationDraftStatus').textContent = 'Maksimal 5 riwayat untuk setiap bagian.';
                    return;
                }
                cloneRepeatRow(listElement, listElement.children.length);
                updateApplicationProgress();
                saveApplicationDraft();
                return;
            }
            var removeButton = event.target.closest('[data-remove-row]');
            if(removeButton){
                var item = removeButton.closest('[data-repeat-item]');
                var listElement = item.parentElement;
                if(listElement.children.length === 1){
                    item.querySelectorAll('input,textarea,select').forEach(function(field){
                        if(field.type === 'checkbox') field.checked = false;
                        else field.value = '';
                    });
                }else{
                    item.remove();
                    Array.from(listElement.children).forEach(function(row, index){
                        row.querySelectorAll('input,textarea,select').forEach(function(field){
                            field.name = field.name.replace(/\[\d+\]/, '[' + index + ']');
                        });
                    });
                }
                updateApplicationProgress();
                saveApplicationDraft();
            }
        });

        function renderSelectedFiles(input, target){
            target.replaceChildren();
            input.closest('.apply-upload').classList.toggle('has-file', input.files.length > 0);
            Array.from(input.files).forEach(function(file){
                var item = makeElement('div', 'apply-file-item');
                item.appendChild(makeElement('i', 'bi bi-file-earmark-check'));
                item.appendChild(makeElement('span', '', file.name + ' · ' + (file.size / 1024 / 1024).toFixed(2) + ' MB'));
                target.appendChild(item);
            });
            updateApplicationProgress();
        }
        var cvInput = document.getElementById('applicationCv');
        cvInput.addEventListener('change', function(){ renderSelectedFiles(cvInput, document.getElementById('applicationCvFiles')); });
        applicationSection.querySelectorAll('#applicationDocumentInputs input[type="file"]').forEach(function(input){
            input.addEventListener('change', function(){ renderSelectedFiles(input, input.closest('.apply-field').querySelector('.apply-file-list')); });
        });
        applicationSection.querySelectorAll('.apply-upload').forEach(function(zone){
            zone.addEventListener('dragover', function(event){ event.preventDefault(); zone.classList.add('dragover'); });
            zone.addEventListener('dragleave', function(){ zone.classList.remove('dragover'); });
            zone.addEventListener('drop', function(event){
                event.preventDefault();
                zone.classList.remove('dragover');
                var input = zone.querySelector('input[type="file"]');
                if(!input || !event.dataTransfer.files.length) return;
                input.files = event.dataTransfer.files;
                input.dispatchEvent(new Event('change', {bubbles: true}));
            });
        });
        function addPreviewGroup(title, entries){
            var content = document.getElementById('applicationPreviewContent');
            var group = makeElement('div', 'apply-preview-group');
            group.appendChild(makeElement('h3', '', title));
            var grid = makeElement('dl', 'apply-preview-grid');
            entries.forEach(function(entry){
                var row = makeElement('div', 'apply-preview-row');
                row.appendChild(makeElement('dt', '', entry[0]));
                row.appendChild(makeElement('dd', '', entry[1] || 'Belum diisi'));
                grid.appendChild(row);
            });
            group.appendChild(grid);
            content.appendChild(group);
        }
        function fieldValue(id){
            var field = document.getElementById(id);
            return field ? field.value.trim() : '';
        }
        function rowPreview(listId, labels){
            return Array.from(document.querySelectorAll('#' + listId + ' [data-repeat-item]')).map(function(row, index){
                return labels.map(function(item){
                    var field = row.querySelector('[name$="[' + item[0] + ']"]');
                    var value = field ? (field.type === 'checkbox' ? (field.checked ? 'Ya' : 'Tidak') : field.value.trim()) : '';
                    return [(index + 1) + '. ' + item[1], value];
                });
            }).reduce(function(entries, rowEntries){ return entries.concat(rowEntries); }, []);
        }
        function openApplicationPreview(){
            var content = document.getElementById('applicationPreviewContent');
            content.replaceChildren();
            var isInternship = applicationSection.dataset.isInternship === 'true';
            var job = jobs.find(function(item){ return item.id === applicationSection.dataset.jobId; });
            document.getElementById('applicationPreviewJobTitle').textContent = isInternship
                ? fieldValue('applicationTargetPosition') || 'Program Magang'
                : job ? displayJobTitle(job) : 'Posisi yang dilamar';
            document.getElementById('applicationPreviewCompany').textContent = job ? job.company : '';
            document.getElementById('applicationPreviewKind').textContent = isInternship ? 'Magang / PKL' : 'Lowongan kerja';
            document.getElementById('applicationPreviewDescription').textContent = isInternship
                ? 'Periksa profil, pendidikan, periode program, dan dokumen magang/PKL Anda.'
                : 'Periksa profil, pengalaman, pendidikan, dan dokumen lamaran kerja Anda.';
            var profile = [
                ['Nama lengkap', fieldValue('applicationName')],
                ['Alamat email', fieldValue('applicationEmail')],
                ['Nomor WhatsApp', fieldValue('applicationPhone')],
                ['Kota domisili', fieldValue('applicationLocation')]
            ];
            profile.push(['Tanggal lahir', fieldValue('applicationBirthDate')], ['Jenis kelamin', fieldValue('applicationGender')]);
            profile.push(['Portofolio / LinkedIn', fieldValue('applicationPortfolio')]);
            addPreviewGroup('Profil', profile);
            if(isInternship){
                addPreviewGroup('Pendidikan', rowPreview('educationList', [
                    ['level','Jenjang'],['institution','Sekolah/perguruan tinggi'],['major','Jurusan'],['status','Status pendidikan'],['start_year','Tahun masuk'],['completion_year','Tahun lulus'],['gpa','IPK/nilai rata-rata']
                ]));
                addPreviewGroup('Informasi pendaftaran', [
                    ['Jenis pendaftaran',fieldValue('applicationRegistrationType')],
                    ['Jurusan program',fieldValue('applicationInternshipMajor')],
                    ['Bidang magang',fieldValue('applicationTargetPosition')],
                    ['Periode awal',fieldValue('applicationInternshipStart')],
                    ['Periode selesai',fieldValue('applicationInternshipEnd')]
                ]);
            }else{
                addPreviewGroup('Pengalaman', rowPreview('experienceList', [
                    ['company','Perusahaan/organisasi'],['position','Posisi'],['period','Periode'],['description','Deskripsi']
                ]));
                addPreviewGroup('Pendidikan', rowPreview('educationList', [
                    ['institution','Sekolah/perguruan tinggi'],['major','Jurusan/program studi'],['year','Tahun lulus'],['gpa','IPK/nilai rata-rata']
                ]));
            }
            addPreviewGroup('Keahlian', [['Keahlian relevan',fieldValue('applicationSkills')]]);
            var documentLabels = {
                transcript: 'Transkrip nilai / rapor',
                cover_letter: 'Surat pengantar magang / PKL',
                portfolio: 'Portofolio',
                ktp: 'Fotokopi KTP',
                diploma: isInternship ? 'Fotokopi ijazah pendidikan terakhir' : 'Fotokopi ijazah terakhir'
            };
            var documents = [['CV / Curriculum Vitae',cvInput.files[0] ? cvInput.files[0].name : 'Belum dipilih']];
            Array.from(document.querySelectorAll('#applicationDocumentInputs input[type="file"]'))
                .filter(function(input){ return !input.closest('[hidden]'); })
                .forEach(function(input){
                    var type = input.name.match(/^documents\[([^\]]+)\]$/);
                    if(type) documents.push([documentLabels[type[1]] || 'Dokumen pendukung', input.files[0] ? input.files[0].name : 'Belum diunggah']);
                });
            addPreviewGroup('Dokumen', documents);
            document.getElementById('applicationPreviewDialog').showModal();
        }
        document.getElementById('applicationPreview').addEventListener('click', openApplicationPreview);
        document.getElementById('closeApplicationPreview').addEventListener('click', function(){
            document.getElementById('applicationPreviewDialog').close();
        });
        window.addEventListener('hashchange', renderRoute);
        syncSavedUI();
        renderRoute();
    })();

    (function(){
        var form = document.getElementById('trackingSearchForm');
        if(!form) return;
        var input = form.querySelector('[name="q"]');
        var feedback = document.getElementById('trackingSearchStatus');
        form.addEventListener('submit', function(event){
            event.preventDefault();
            var query = input.value.trim().toLocaleLowerCase('id-ID');
            var trackers = Array.from(document.querySelectorAll('.career-tracking-only .tracker'));
            trackers.forEach(function(tracker){
                tracker.querySelectorAll('.step').forEach(function(step){
                    step.classList.toggle('tracking-step-match', Boolean(query) && step.textContent.toLocaleLowerCase('id-ID').indexOf(query) !== -1);
                });
            });
            if(!query){
                feedback.textContent = 'Masukkan posisi, nama pelamar, atau tahapan yang ingin dicari.';
                return;
            }
            var match = trackers.find(function(tracker){
                return tracker.textContent.toLocaleLowerCase('id-ID').indexOf(query) !== -1;
            });
            if(match){
                feedback.textContent = 'Lamaran ditemukan. Status seleksi Anda ditampilkan di bawah.';
                match.scrollIntoView({behavior: 'smooth', block: 'center'});
            }else{
                feedback.textContent = 'Tidak ada kecocokan pada status lamaran Anda.';
            }
        });
    })();

    /* ===== TOMBOL AKSI TRACKER: Unduh PDF, Kalender (lihat jadwal), WhatsApp Helpdesk ===== */
    (function(){
        var box = document.getElementById('trackActions'); if(!box) return;
        var tracker = box.closest('.tracker');
        var d = box.dataset;

        function download(blob, name){
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = name;
            document.body.appendChild(a);
            a.click();
            setTimeout(function(){ URL.revokeObjectURL(a.href); a.remove(); }, 800);
        }
        function clean(t){
            return String(t == null ? '' : t).replace(/[–—·•]/g, '-').replace(/[‘’]/g, "'").replace(/[“”]/g, '"').replace(/[^\x20-\x7e]/g, '');
        }
        function esc(t){ return clean(t).replace(/\\/g, '\\\\').replace(/\(/g, '\\(').replace(/\)/g, '\\)'); }
        function bytes(s){ var a = new Uint8Array(s.length); for(var i = 0; i < s.length; i++) a[i] = s.charCodeAt(i) & 255; return a; }

        /* ---------- PDF: tabel rapi + logo, dibuat langsung di browser (tanpa pustaka tambahan) ---------- */
        // lebar huruf Helvetica (per 1000 unit) untuk karakter 32..126, dipakai agar teks tabel membungkus dengan tepat
        var WR = [278,278,355,556,556,889,667,191,333,333,389,584,278,333,278,278,556,556,556,556,556,556,556,556,556,556,278,278,584,584,584,556,1015,667,667,722,722,667,611,778,722,278,500,667,556,833,722,778,667,778,722,667,611,722,667,944,667,667,611,278,278,278,469,556,333,556,556,500,556,556,278,556,556,222,222,500,222,833,556,556,556,556,333,500,278,556,500,722,500,500,500,334,260,334,584];
        var WB = [278,333,474,556,556,889,722,238,333,333,389,584,278,333,278,278,556,556,556,556,556,556,556,556,556,556,333,333,584,584,584,611,975,722,722,722,722,667,611,778,722,278,556,722,611,833,722,778,667,778,722,667,611,722,667,944,667,667,611,333,278,333,584,556,333,556,611,556,611,556,333,611,611,278,278,556,278,889,611,611,611,611,389,556,333,611,556,778,556,556,500,389,280,389,584];
        function tw(str, size, bold){
            var t = bold ? WB : WR, w = 0;
            for(var i = 0; i < str.length; i++){ var c = str.charCodeAt(i) - 32; w += (c >= 0 && c < t.length) ? t[c] : 556; }
            return w * size / 1000;
        }
        function wrap(text, size, bold, maxW){
            var words = clean(text).split(/\s+/), lines = [], cur = '';
            words.forEach(function(w){
                var test = cur ? cur + ' ' + w : w;
                if(tw(test, size, bold) > maxW && cur){ lines.push(cur); cur = w; } else cur = test;
            });
            lines.push(cur);
            return lines;
        }

        var OR = '0.91 0.40 0.10', DARK = '0.11 0.10 0.09', MUT = '0.44 0.40 0.37', CREAM = '1 0.965 0.925', LINE = '0.93 0.85 0.77', GREEN = '0.05 0.50 0.32', WHITE = '1 1 1', ZEBRA = '0.995 0.985 0.972';

        function buildPdf(model, logo){
            var W = 595, H = 842, M = 40, CW = W - 2 * M, BOTTOM = H - 56;
            var pages = [], ops, y;
            function newPage(){ ops = []; pages.push(ops); y = M; }
            function Y(top){ return (H - top).toFixed(2); }
            function fill(x, top, w, h, rgb){ ops.push(rgb + ' rg ' + x.toFixed(2) + ' ' + Y(top + h) + ' ' + w.toFixed(2) + ' ' + h.toFixed(2) + ' re f'); }
            function box_(x, top, w, h){ ops.push(LINE + ' RG 0.7 w ' + x.toFixed(2) + ' ' + Y(top + h) + ' ' + w.toFixed(2) + ' ' + h.toFixed(2) + ' re S'); }
            function text(x, base, str, size, bold, rgb){ ops.push('BT /' + (bold ? 'F2' : 'F1') + ' ' + size + ' Tf ' + rgb + ' rg 1 0 0 1 ' + x.toFixed(2) + ' ' + Y(base) + ' Tm (' + esc(str) + ') Tj ET'); }

            newPage();
            // ---- kop surat: logo + nama perusahaan ----
            var logoW = 0;
            if(logo){
                var lh = 42; logoW = lh * logo.w / logo.h;
                ops.push('q ' + logoW.toFixed(2) + ' 0 0 ' + lh + ' ' + M + ' ' + Y(y + lh) + ' cm /Im1 Do Q');
            }
            var tx = M + (logo ? logoW + 12 : 0);
            text(tx, y + 17, 'PT EQUITYWORLD FUTURES', 15, true, DARK);
            text(tx, y + 32, 'Pialang Berjangka - Terdaftar & Diawasi BAPPEBTI', 8.5, false, MUT);
            var stamp = model.date; text(W - M - tw(stamp, 8.5, false), y + 17, stamp, 8.5, false, MUT);
            y += 54;
            fill(M, y, CW, 2.5, OR); y += 26;

            text(M, y + 8, 'Ringkasan Status Seleksi', 19, true, DARK); y += 20;
            text(M, y + 8, 'Ringkasan perkembangan proses seleksi dan tahapan dokumen kandidat.', 9, false, MUT); y += 30;

            // ---- tabel 1: data kandidat ----
            text(M, y + 8, 'DATA KANDIDAT', 10, true, OR); y += 16;
            var c1 = 150, size = 10, lead = 13.5, pad = 7;
            model.info.forEach(function(r, idx){
                var lines = wrap(r[1], size, r[0] === 'Posisi Dilamar', CW - c1 - 20), h = lines.length * lead + 2 * pad;
                fill(M, y, c1, h, CREAM); if(idx % 2) fill(M + c1, y, CW - c1, h, ZEBRA);
                box_(M, y, c1, h); box_(M + c1, y, CW - c1, h);
                text(M + 10, y + pad + 9.5, r[0], size, true, DARK);
                lines.forEach(function(l, i){ text(M + c1 + 10, y + pad + 9.5 + i * lead, l, size, r[0] === 'Posisi Dilamar' || r[0] === 'Status Kandidat', r[0] === 'Status Kandidat' ? OR : DARK); });
                y += h;
            });
            y += 26;

            // ---- tabel 2: tahapan seleksi ----
            var cols = [26, 80, 140, 185, 84], heads = ['No', 'Tahap', 'Tahapan Seleksi', 'Keterangan', 'Jadwal / Waktu'];
            function tableHead(){
                var h = 24, x = M;
                fill(M, y, CW, h, OR);
                heads.forEach(function(t, i){ text(x + 7, y + 15.5, t, 9, true, WHITE); x += cols[i]; });
                y += h;
            }
            text(M, y + 8, 'TAHAPAN SELEKSI', 10, true, OR); y += 16;
            tableHead();
            var fs = 9, ld = 12.2, pd = 8;
            model.steps.forEach(function(st, idx){
                var cells = [
                    [{t: String(idx + 1), b: true}],
                    [{t: st.tahap, b: true}, {t: st.status, b: true, c: st.status === 'Selesai' ? GREEN : (st.status === 'Berlangsung' ? OR : MUT)}],
                    [{t: st.stage, b: true}, {t: st.title}],
                    [{t: st.desc}],
                    [{t: st.when, b: true}]
                ];
                var wrapped = cells.map(function(segs, ci){
                    var out = [];
                    segs.forEach(function(sg){ wrap(sg.t, fs, !!sg.b, cols[ci] - 14).forEach(function(l){ out.push({t: l, b: sg.b, c: sg.c}); }); });
                    return out;
                });
                var rows = Math.max.apply(null, wrapped.map(function(w){ return w.length; })), h = rows * ld + 2 * pd;
                if(y + h > BOTTOM){ newPage(); y = M; tableHead(); }
                var x = M;
                fill(M, y, CW, h, idx % 2 ? ZEBRA : WHITE);
                wrapped.forEach(function(lines, ci){
                    box_(x, y, cols[ci], h);
                    lines.forEach(function(l, i){ text(x + 7, y + pd + 8.6 + i * ld, l.t, fs, !!l.b, l.c || DARK); });
                    x += cols[ci];
                });
                y += h;
            });

            // ---- catatan ----
            y += 22;
            if(y + 44 > BOTTOM){ newPage(); y = M; }
            fill(M, y, CW, 40, CREAM); box_(M, y, CW, 40);
            fill(M, y, 4, 40, OR);
            text(M + 16, y + 17, 'Catatan', 9, true, DARK);
            text(M + 16, y + 30, 'Status dapat berubah sewaktu-waktu. Untuk pertanyaan seputar proses seleksi, hubungi Helpdesk HR melalui WhatsApp.', 8.5, false, MUT);

            // ---- footer tiap halaman ----
            pages.forEach(function(p, i){
                ops = p;
                ops.push(LINE + ' RG 0.7 w ' + M + ' ' + Y(H - 38) + ' m ' + (W - M) + ' ' + Y(H - 38) + ' l S');
                text(M, H - 26, 'PT Equityworld Futures - Dokumen dibuat otomatis dari halaman Karir', 8, false, MUT);
                var pg = 'Halaman ' + (i + 1) + ' dari ' + pages.length; text(W - M - tw(pg, 8, false), H - 26, pg, 8, false, MUT);
            });

            // ---- rakit file PDF (biner, agar JPEG logo bisa disematkan) ----
            var parts = [], len = 0, offs = [];
            function put(u8){ parts.push(u8); len += u8.length; }
            function obj(id, head, stream){
                offs[id] = len;
                put(bytes(id + ' 0 obj\n' + head));
                if(stream){ put(bytes('\nstream\n')); put(stream); put(bytes('\nendstream')); }
                put(bytes('\nendobj\n'));
            }
            put(bytes('%PDF-1.4\n'));
            var base = logo ? 6 : 5;
            obj(1, '<< /Type /Catalog /Pages 2 0 R >>');
            obj(2, '<< /Type /Pages /Kids [' + pages.map(function(_, i){ return (base + 2 * i) + ' 0 R'; }).join(' ') + '] /Count ' + pages.length + ' >>');
            obj(3, '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');
            obj(4, '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>');
            if(logo) obj(5, '<< /Type /XObject /Subtype /Image /Width ' + logo.w + ' /Height ' + logo.h + ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' + logo.bytes.length + ' >>', logo.bytes);
            pages.forEach(function(p, i){
                var s = bytes(p.join('\n'));
                obj(base + 2 * i, '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' + W + ' ' + H + '] /Resources << /Font << /F1 3 0 R /F2 4 0 R >>' + (logo ? ' /XObject << /Im1 5 0 R >>' : '') + ' >> /Contents ' + (base + 2 * i + 1) + ' 0 R >>');
                obj(base + 2 * i + 1, '<< /Length ' + s.length + ' >>', s);
            });
            var total = base + 2 * pages.length, xref = len, x = 'xref\n0 ' + total + '\n0000000000 65535 f \n';
            for(var i = 1; i < total; i++) x += ('0000000000' + offs[i]).slice(-10) + ' 00000 n \n';
            put(bytes(x + 'trailer\n<< /Size ' + total + ' /Root 1 0 R >>\nstartxref\n' + xref + '\n%%EOF'));
            return new Blob(parts, {type: 'application/pdf'});
        }

        // muat logo EWF dan ubah ke JPEG (latar putih) agar bisa dimasukkan ke PDF; bila gagal, PDF tetap dibuat tanpa logo
        function loadLogo(){
            return new Promise(function(resolve){
                if(!d.logo){ resolve(null); return; }
                var img = new Image();
                img.onload = function(){
                    try{
                        var scale = Math.min(1, 480 / img.naturalWidth), w = Math.round(img.naturalWidth * scale), h = Math.round(img.naturalHeight * scale);
                        var cv = document.createElement('canvas'); cv.width = w; cv.height = h;
                        var cx = cv.getContext('2d'); cx.fillStyle = '#fff'; cx.fillRect(0, 0, w, h); cx.drawImage(img, 0, 0, w, h);
                        cv.toBlob(function(b){
                            if(!b){ resolve(null); return; }
                            b.arrayBuffer().then(function(buf){ resolve({bytes: new Uint8Array(buf), w: w, h: h}); }, function(){ resolve(null); });
                        }, 'image/jpeg', 0.92);
                    } catch(e){ resolve(null); }
                };
                img.onerror = function(){ resolve(null); };
                img.src = d.logo;
            });
        }

        function doPdf(){
            var prog = tracker.querySelector('.tr-head .card .row span:last-child');
            var tag = tracker.querySelector('.tr-head .tag');
            var model = {
                date: 'Dibuat: ' + new Date().toLocaleString('id-ID', {dateStyle: 'long', timeStyle: 'short'}),
                info: [
                    ['Nama Pelamar', d.name], ['Posisi Dilamar', d.position],
                    ['Status Kandidat', tag ? tag.textContent.trim() : 'Kandidat Aktif'],
                    ['Kemajuan Seleksi', prog ? prog.textContent.trim() : '']
                ],
                steps: []
            };
            tracker.querySelectorAll('.steps .step').forEach(function(st){
                var label = st.querySelector('.st'), stat = st.querySelector('.stat'), desc = st.querySelector('.desc');
                var when = st.querySelector('.when'), title = st.querySelector('b');
                var parts = (label ? label.textContent.trim() : '').split(/\s+[-–]\s+/);
                model.steps.push({
                    tahap: parts[0] || '', stage: parts[1] || '',
                    status: stat ? stat.textContent.trim() : '',
                    title: title ? title.textContent.trim() : '',
                    desc: desc ? desc.textContent.trim() : '-',
                    when: when ? when.textContent.trim() : '-'
                });
            });
            return loadLogo().then(function(logo){
                var filename = d.name.toLocaleLowerCase('id-ID').replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'pelamar';
                download(buildPdf(model, logo), 'Status-Pelacakan-' + filename + '.pdf');
                return 'PDF berhasil diunduh';
            });
        }

        function openCalendar(){
            if(document.getElementById('krCal')) return 'Kalender dibuka';
            var BLN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            var HR = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
            var ev = [];
            tracker.querySelectorAll('.steps .step[data-date]').forEach(function(st){
                var dt = st.dataset.date, m = dt.split('T');
                var ymd = m[0].split('-').map(Number);
                ev.push({key: m[0], y: ymd[0], mo: ymd[1] - 1, d: ymd[2], time: m[1] || '',
                    tahap: (st.querySelector('.st') || {textContent: ''}).textContent.split(/\s+[-–]\s+/)[0].trim(),
                    title: (st.querySelector('b') || {textContent: ''}).textContent.trim(),
                    status: (st.querySelector('.stat') || {textContent: ''}).textContent.trim(),
                    state: st.classList.contains('done') ? 'done' : st.classList.contains('active') ? 'active' : 'wait'});
            });
            if(!ev.length) return 'Belum ada jadwal';
            var cur = ev.filter(function(e){ return e.state === 'active'; })[0] || ev[0];
            var vy = cur.y, vm = cur.mo, sel = cur.key;

            if(!document.getElementById('krCalCss')){
                var css = document.createElement('style'); css.id = 'krCalCss';
                css.textContent =
'.kr-cal{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:18px;background:rgba(70,36,10,.38);backdrop-filter:blur(5px);-webkit-backdrop-filter:blur(5px);font-family:\'Plus Jakarta Sans\',system-ui,sans-serif;opacity:0;transition:opacity .25s}'+
'.kr-cal.on{opacity:1}'+
'.kr-cal *{box-sizing:border-box;font-family:inherit}.kr-cal button{font-family:inherit}'+
'.kr-cal-box{width:min(860px,100%);max-height:calc(100vh - 36px);overflow:auto;display:grid;grid-template-columns:1.15fr 1fr;background:#fff;border-radius:26px;box-shadow:0 36px 80px rgba(120,60,15,.3),0 6px 0 #f1c9a6;transform:translateY(24px) scale(.97);transition:transform .35s cubic-bezier(.2,.8,.2,1);color:#2a1a0e}'+
'.kr-cal.on .kr-cal-box{transform:none}'+
'.kr-cal-l{padding:24px 24px 20px}.kr-cal-r{padding:24px;background:linear-gradient(180deg,#fff6ee,#ffeedd);border-radius:0 26px 26px 0;display:flex;flex-direction:column;gap:10px}'+
'.kr-cal-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:8px}'+
'.kr-cal-hd h3{margin:0;font-size:19px;font-weight:800;flex:1;text-align:center}'+
'.kr-cal-nav,.kr-cal-x{width:36px;height:36px;border:1px solid #ffdcc0;border-radius:12px;background:#fff;color:#c2561a;font-size:16px;cursor:pointer;box-shadow:0 3px 0 #f4d3b5;transition:.2s;display:grid;place-items:center;line-height:1}'+
'.kr-cal-nav:hover,.kr-cal-x:hover{background:linear-gradient(135deg,#ffd7b5,#fbab6d);color:#fff;transform:translateY(-1px)}'+
'.kr-cal-g{display:grid;grid-template-columns:repeat(7,1fr);gap:5px}'+
'.kr-cal-w{font-size:11px;font-weight:800;color:#b08a6e;text-align:center;padding:4px 0}'+
'.kr-cal-d{position:relative;aspect-ratio:1;border:0;border-radius:12px;background:#fff8f2;color:#4a3320;font-size:13.5px;font-weight:600;cursor:default;display:grid;place-items:center;transition:.2s}'+
'.kr-cal-d.o{background:transparent;color:#cdb8a6}'+
'.kr-cal-d.e{cursor:pointer;background:#fff;box-shadow:0 3px 0 #f1d6bf;border:1px solid #ffe0c6}'+
'.kr-cal-d.e:hover{transform:translateY(-2px)}'+
'.kr-cal-d.e i{position:absolute;bottom:5px;width:6px;height:6px;border-radius:50%;background:#c9a98e}'+
'.kr-cal-d.e.active{background:linear-gradient(135deg,#ffd7b5,#fbab6d);color:#fff;border-color:#fbab6d;box-shadow:0 4px 0 #eba26e}'+
'.kr-cal-d.e.active i{background:#fff}'+
'.kr-cal-d.e.wait i{background:transparent;border:1.5px solid #fbab6d}'+
'.kr-cal-d.sel{outline:2px solid #f59a5a;outline-offset:1px}'+
'.kr-cal-lg{display:flex;flex-wrap:wrap;gap:12px;margin-top:14px;font-size:11.5px;color:#8a6a52}'+
'.kr-cal-lg span{display:inline-flex;align-items:center;gap:5px}.kr-cal-lg i{width:9px;height:9px;border-radius:50%;display:inline-block}'+
'.kr-cal-r h4{margin:0 0 2px;font-size:15px;font-weight:800;display:flex;justify-content:space-between;align-items:center}'+
'.kr-ev{display:flex;gap:12px;align-items:center;text-align:left;width:100%;padding:11px 12px;border:1px solid #ffe0c6;border-radius:16px;background:#fff;cursor:pointer;box-shadow:0 4px 0 #f4d9c1;transition:.2s;color:inherit}'+
'.kr-ev:hover{transform:translateY(-2px)}.kr-ev.sel{border-color:#fbab6d;background:#fff3e8}'+
'.kr-ev-dt{flex:none;width:48px;text-align:center;border-radius:12px;background:#fff0e2;color:#c2561a;padding:6px 0;line-height:1.1}'+
'.kr-ev-dt b{display:block;font-size:19px}.kr-ev-dt small{font-size:10px;font-weight:700;text-transform:uppercase}'+
'.kr-ev.active .kr-ev-dt{background:linear-gradient(135deg,#ffd7b5,#fbab6d);color:#fff}'+
'.kr-ev-tx{min-width:0;display:flex;flex-direction:column;gap:2px}.kr-ev-tx small{font-size:11px;color:#b0602a;font-weight:700}.kr-ev-tx b{font-size:13.5px}.kr-ev-tx span{font-size:12px;color:#8a6a52}'+
'.kr-cal-note{margin:2px 2px 0;color:#8a6a52;font-size:12px;line-height:1.55}'+
'@media(max-width:720px){.kr-cal-box{grid-template-columns:1fr}.kr-cal-r{border-radius:0 0 26px 26px}}'+
'@media(prefers-reduced-motion:reduce){.kr-cal,.kr-cal-box{transition:none}}';
                document.head.appendChild(css);
            }

            var wrap = document.createElement('div');
            wrap.className = 'kr-cal'; wrap.id = 'krCal';
            wrap.setAttribute('role', 'dialog'); wrap.setAttribute('aria-modal', 'true'); wrap.setAttribute('aria-label', 'Kalender jadwal seleksi');
            wrap.innerHTML = '<div class="kr-cal-box"><div class="kr-cal-l"><div class="kr-cal-hd"><button type="button" class="kr-cal-nav" data-n="-1" aria-label="Bulan sebelumnya">&#8249;</button><h3></h3><button type="button" class="kr-cal-nav" data-n="1" aria-label="Bulan berikutnya">&#8250;</button></div><div class="kr-cal-g"></div>' +
                '<div class="kr-cal-lg"><span><i style="background:#fbab6d"></i>Berlangsung</span><span><i style="background:#c9a98e"></i>Selesai</span><span><i style="border:1.5px solid #fbab6d"></i>Menunggu</span></div></div>' +
                '<div class="kr-cal-r"><h4>Jadwal Seleksi <button type="button" class="kr-cal-x" aria-label="Tutup">&#10005;</button></h4><div class="kr-cal-list" style="display:flex;flex-direction:column;gap:10px"></div><p class="kr-cal-note">Jadwal seleksi berikutnya akan ditampilkan setelah diumumkan.</p></div></div>';
            document.body.appendChild(wrap);
            var grid = wrap.querySelector('.kr-cal-g'), title = wrap.querySelector('h3'), list = wrap.querySelector('.kr-cal-list');
            var prevFocus = document.activeElement;

            function pad(n){ return (n < 10 ? '0' : '') + n; }
            function draw(){
                title.textContent = BLN[vm] + ' ' + vy;
                var h = HR.map(function(x){ return '<div class="kr-cal-w">' + x + '</div>'; }).join('');
                var first = (new Date(vy, vm, 1).getDay() + 6) % 7, days = new Date(vy, vm + 1, 0).getDate(), prevDays = new Date(vy, vm, 0).getDate();
                var i, key, e;
                for(i = 0; i < first; i++) h += '<div class="kr-cal-d o">' + (prevDays - first + 1 + i) + '</div>';
                for(i = 1; i <= days; i++){
                    key = vy + '-' + pad(vm + 1) + '-' + pad(i);
                    e = ev.filter(function(x){ return x.key === key; })[0];
                    if(e) h += '<button type="button" class="kr-cal-d e ' + e.state + (key === sel ? ' sel' : '') + '" data-k="' + key + '" aria-label="' + i + ' ' + BLN[vm] + ': ' + e.title + '">' + i + '<i></i></button>';
                    else h += '<div class="kr-cal-d">' + i + '</div>';
                }
                var tail = (7 - (first + days) % 7) % 7;
                for(i = 1; i <= tail; i++) h += '<div class="kr-cal-d o">' + i + '</div>';
                grid.innerHTML = h;
                list.innerHTML = ev.map(function(x){
                    return '<button type="button" class="kr-ev ' + x.state + (x.key === sel ? ' sel' : '') + '" data-k="' + x.key + '"><span class="kr-ev-dt"><b>' + x.d + '</b><small>' + BLN[x.mo].slice(0, 3) + '</small></span><span class="kr-ev-tx"><small>' + x.tahap + ' · ' + x.status + '</small><b>' + x.title + '</b><span>' + x.time.replace(':', '.') + ' WIB</span></span></button>';
                }).join('');
            }
            function pick(k){
                var x = ev.filter(function(q){ return q.key === k; })[0]; if(!x) return;
                sel = k; vy = x.y; vm = x.mo; draw();
            }
            function close(){
                wrap.classList.remove('on');
                document.removeEventListener('keydown', onKey);
                setTimeout(function(){ wrap.remove(); }, 250);
                if(prevFocus && prevFocus.focus) prevFocus.focus();
            }
            function onKey(e){ if(e.key === 'Escape') close(); }
            wrap.addEventListener('click', function(e){
                var t = e.target;
                if(t === wrap || t.closest('.kr-cal-x')){ close(); return; }
                var n = t.closest('.kr-cal-nav');
                if(n){ vm += +n.dataset.n; if(vm < 0){ vm = 11; vy--; } if(vm > 11){ vm = 0; vy++; } draw(); return; }
                var k = t.closest('[data-k]'); if(k){ pick(k.dataset.k); return; }
            });
            document.addEventListener('keydown', onKey);
            draw();
            requestAnimationFrame(function(){ wrap.classList.add('on'); wrap.querySelector('.kr-cal-x').focus(); });
            return 'Kalender dibuka';
        }

        function doWa(){
            var text = 'Halo Helpdesk HR, saya ' + d.name + ' dan ingin menanyakan status seleksi posisi ' + d.position + '.';
            window.open('https://wa.me/' + d.wa + '?text=' + encodeURIComponent(text), '_blank', 'noopener');
            return 'Membuka WhatsApp...';
        }

        var actions = {pdf: doPdf, calendar: openCalendar, wa: doWa};

        box.querySelectorAll('.act').forEach(function(btn){
            var sub = btn.querySelector('small'), orig = sub.textContent, timer;
            // efek 3D tipis + cahaya mengikuti kursor
            btn.addEventListener('mousemove', function(e){
                var r = btn.getBoundingClientRect(), x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
                btn.style.setProperty('--mx', (x * 100) + '%');
                btn.style.setProperty('--my', (y * 100) + '%');
                btn.style.setProperty('--ry', ((x - 0.5) * 7) + 'deg');
                btn.style.setProperty('--rx', ((0.5 - y) * 6) + 'deg');
            });
            btn.addEventListener('mouseleave', function(){ btn.style.setProperty('--rx', '0deg'); btn.style.setProperty('--ry', '0deg'); });
            btn.addEventListener('click', function(event){
                // riak mengikuti titik sentuh
                var r = btn.getBoundingClientRect(), size = Math.max(r.width, r.height) * 2;
                var rp = document.createElement('span');
                rp.className = 'rp';
                rp.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (event.clientX - r.left - size / 2) + 'px;top:' + (event.clientY - r.top - size / 2) + 'px';
                btn.appendChild(rp);
                setTimeout(function(){ rp.remove(); }, 750);

                var done = function(msg){
                    sub.textContent = msg;
                    clearTimeout(timer);
                    timer = setTimeout(function(){ sub.textContent = orig; }, 1800);
                };
                try { Promise.resolve(actions[btn.dataset.act]()).then(done, function(){ done('Gagal, coba lagi'); }); }
                catch(e){ done('Gagal, coba lagi'); }
            });
        });
    })();

    /* ===== KONFIRMASI KEHADIRAN: Hadir / Tidak hadir ===== */
    (function(){
        var wrap = document.getElementById('rsvp'); if(!wrap) return;
        var openBtn = document.getElementById('rsvpOpen'), choice = document.getElementById('rsvpChoice'), done = document.getElementById('rsvpDone');
        var actions = document.getElementById('trackActions');
        var key = 'ewf-career-rsvp-' + (actions ? actions.dataset.code : 'default');
        var text = {
            hadir: ['is-yes', 'bi-check-circle-fill', 'Anda mengonfirmasi akan hadir.'],
            tidak: ['is-no', 'bi-x-circle-fill', 'Anda menyatakan tidak dapat hadir. Tim HRD akan menghubungi Anda.']
        };
        function load(){ try{ return localStorage.getItem(key); }catch(e){ return null; } }
        function save(v){ try{ localStorage.setItem(key, v); }catch(e){} }
        function show(value){
            if(!text[value]){ done.hidden = true; openBtn.hidden = false; return; }
            var t = text[value];
            done.className = 'rsvp-done ' + t[0];
            done.querySelector('i').className = 'bi ' + t[1];
            done.querySelector('span').textContent = t[2];
            done.hidden = false; choice.hidden = true; openBtn.hidden = true;
        }
        openBtn.addEventListener('click', function(){
            choice.hidden = !choice.hidden;
            openBtn.setAttribute('aria-expanded', String(!choice.hidden));
        });
        wrap.querySelectorAll('.rsvp-opt').forEach(function(btn){
            btn.addEventListener('click', function(){ save(btn.dataset.rsvp); show(btn.dataset.rsvp); });
        });
        wrap.querySelector('.rsvp-change').addEventListener('click', function(){
            try{ localStorage.removeItem(key); }catch(e){}
            done.hidden = true; openBtn.hidden = false; choice.hidden = false;
            openBtn.setAttribute('aria-expanded', 'true');
        });
        show(load());
    })();

    /* ===== GARIS OREN + LABEL: berpindah mengikuti kursor / kartu yang dipencet (Lowongan Kerja & Magang) ===== */
    (function(){
        var sw = document.getElementById('krSwitch'); if(!sw) return;
        var root = document.querySelector('.krr');

        var hl = document.createElement('div');
        hl.className = 'career-highlight';
        hl.setAttribute('aria-hidden', 'true');
        var hlLabel = document.createElement('span');
        hlLabel.className = 'career-highlight-label';
        hl.appendChild(hlLabel);
        root.appendChild(hl);

        var current = null;   // kartu yang sedang bergaris oren
        var lastRect = null;  // posisi/ukuran garis sebelum pindah
        var chosen = {};      // kartu terakhir yang dipilih di tiap tab

        // posisi kartu relatif terhadap .krr, dihitung dari offset (tidak terpengaruh efek tilt/hover)
        function getRect(el){
            var x = 0, y = 0, n = el;
            while(n && n !== root){ x += n.offsetLeft; y += n.offsetTop; n = n.offsetParent; }
            return {x: x, y: y, w: el.offsetWidth, h: el.offsetHeight};
        }
        // garis ikut miring/terangkat sama seperti kartu saat kursor di atasnya (efek 3D tetap utuh)
        function syncTilt(){
            hl.style.transform = (current && current.style.transform) ? current.style.transform : '';
        }
        function place(card){
            var to = getRect(card);
            hl.style.left = to.x + 'px';
            hl.style.top = to.y + 'px';
            hl.style.width = to.w + 'px';
            hl.style.height = to.h + 'px';
            hlLabel.textContent = card.classList.contains('internship-major-card') || card.dataset.careerType === 'magang'
                ? 'LOWONGAN MAGANG'
                : 'LOWONGAN KERJA';
            syncTilt();
            return to;
        }

        // posisi garis saat ini (termasuk bila sedang bergeser), supaya perpindahan berikutnya tidak melompat
        function visualRect(){
            if(lastRect && hl.getAnimations && hl.getAnimations().length){
                var cs = getComputedStyle(hl), t = (cs.translate || '').split(' ');
                var tx = parseFloat(t[0]) || 0, ty = parseFloat(t[1]) || 0;
                return {x: lastRect.x + tx, y: lastRect.y + ty, w: parseFloat(cs.width) || lastRect.w, h: parseFloat(cs.height) || lastRect.h};
            }
            return lastRect;
        }

        function setFeaturedCard(card, animate){
            var from = visualRect();
            if(hl.getAnimations) hl.getAnimations().forEach(function(a){ a.cancel(); });
            root.querySelectorAll('.job[data-job-id], .internship-major-card').forEach(function(item){
                item.classList.remove('pri');
                item.removeAttribute('aria-current');
                var lg = item.querySelector('.logo');
                if(lg) lg.classList.remove('dark');
                var btn = item.querySelector('.bot .btn');
                if(btn) btn.classList.add('ghost');   // tombol kartu lain: putih
            });
            if(!card){
                current = null;
                hl.hidden = true;
                return;
            }

            hl.hidden = false;
            current = card;
            card.classList.add('pri');
            card.setAttribute('aria-current', 'true');
            var logo = card.querySelector('.logo');
            if(logo) logo.classList.add('dark');
            var activeBtn = card.querySelector('.bot .btn');
            if(activeBtn) activeBtn.classList.remove('ghost');   // tombol kartu aktif: oren

            var to = place(card);
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if(animate && from && !reduce && hl.animate){
                var ease = 'cubic-bezier(.2,.8,.2,1)';
                hl.animate([
                    {translate: (from.x - to.x) + 'px ' + (from.y - to.y) + 'px', width: from.w + 'px', height: from.h + 'px'},
                    {translate: '0px 0px', width: to.w + 'px', height: to.h + 'px'}
                ], {duration: 450, easing: ease});
                hlLabel.animate([
                    {transform: 'translateY(-6px) scale(.9)', opacity: .4},
                    {transform: 'translateY(0) scale(1)', opacity: 1}
                ], {duration: 450, easing: ease});
            }
            lastRect = to;

            var pane = card.closest('[data-pane]');
            if(pane) chosen[pane.dataset.pane] = card;
        }

        function activatePane(type){
            var selectedButton = sw.querySelector('button[data-t="' + type + '"]');
            if(!selectedButton) return;
            sw.dataset.active = type;
            sw.querySelectorAll('button').forEach(function(button){
                var active = button === selectedButton;
                button.classList.toggle('on', active);
                button.setAttribute('aria-pressed', String(active));
            });
            document.querySelectorAll('.krr [data-pane]').forEach(function(pane){
                pane.style.display = pane.dataset.pane === type ? '' : 'none';
            });
            lastRect = null; // pindah tab: tanpa animasi lintas panel
            var preferredCard = chosen[type];
            if(preferredCard && !preferredCard.offsetWidth) preferredCard = null;
            var defaultCard = preferredCard || (type === 'job'
                ? document.querySelector('.krr [data-pane="job"] .job[data-job-id]')
                : document.querySelector('.krr [data-pane="mg"] .internship-major-card:not([hidden])'));
            setFeaturedCard(defaultCard, false);
        }

        // hitung ulang posisi tanpa animasi (resize, font/gambar selesai dimuat, kembali dari halaman eksplor)
        function relayout(){
            if(current && current.isConnected){
                if(current.offsetWidth){
                    lastRect = null;
                    setFeaturedCard(current, false);
                }else{
                    current = null;
                    lastRect = null;
                    hl.hidden = true;
                }
            }
        }

        activatePane(sw.querySelector('button.on')?.dataset.t || 'job');

        sw.addEventListener('click', function(e){
            var b = e.target.closest('button'); if(!b) return;
            activatePane(b.dataset.t);
        });

        // kursor masuk ke kartu -> garis + label langsung ikut pindah (tidak perlu klik)
        root.addEventListener('mouseover', function(event){
            var card = event.target.closest('.job[data-job-id], .internship-major-card');
            if(!card || card === current) return;
            var pane = card.closest('[data-pane]');
            if(!pane || pane.style.display === 'none') return;
            setFeaturedCard(card, true);
        });
        // dipencet / disentuh (layar sentuh) pada kartu atau tombolnya -> kartu itu jadi aktif
        root.addEventListener('pointerdown', function(event){
            var card = event.target.closest('.job[data-job-id], .internship-major-card');
            if(!card || card === current) return;
            var pane = card.closest('[data-pane]');
            if(!pane || pane.style.display === 'none') return;
            if(event.target.closest('input,select,textarea')) return;
            setFeaturedCard(card, true);
        });

        // klik di bagian mana pun pada kartu (kecuali tombol/link) -> garis + label ikut pindah
        root.addEventListener('click', function(event){
            var card = event.target.closest('.job[data-job-id], .internship-major-card');
            if(!card || card === current) return;
            var pane = card.closest('[data-pane]');
            if(!pane || pane.style.display === 'none') return;
            if(event.target.closest('a,button,input,select,textarea,.mark')) return;
            setFeaturedCard(card, true);
        });

        // sinkron efek tilt kartu aktif ke garis
        root.addEventListener('mousemove', function(e){
            if(current && current.contains(e.target)) requestAnimationFrame(syncTilt);
        });
        root.addEventListener('mouseout', function(e){
            if(current && current.contains(e.target)) requestAnimationFrame(syncTilt);
        });

        window.addEventListener('resize', relayout);
        window.addEventListener('load', relayout);
        window.addEventListener('hashchange', function(){ requestAnimationFrame(relayout); });
        if(document.fonts && document.fonts.ready) document.fonts.ready.then(relayout);
        if('ResizeObserver' in window) new ResizeObserver(relayout).observe(root);
    })();

    /* efek 3D tilt, parallax hero, dan reveal saat scroll */
    (function(){
        var root = document.querySelector('.krr');
        if(!root || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        root.classList.add('js');

        function tilt(el, max){
            el.addEventListener('mousemove', function(e){
                var r = el.getBoundingClientRect(), x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
                el.style.setProperty('--mx', (x * 100) + '%');
                el.style.setProperty('--my', (y * 100) + '%');
                el.style.transform = 'perspective(900px) rotateX(' + ((0.5 - y) * max) + 'deg) rotateY(' + ((x - 0.5) * max) + 'deg) translateY(-6px)';
            });
            el.addEventListener('mouseleave', function(){ el.style.transform = ''; });
        }
        root.querySelectorAll('.card').forEach(function(el){ tilt(el, 9); });
        root.querySelectorAll('.path').forEach(function(el){ tilt(el, 4); });

        var hero = root.querySelector('.hero'), fl = root.querySelector('.float');
        if(hero && fl){
            hero.addEventListener('mousemove', function(e){
                var r = hero.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - 0.5, y = (e.clientY - r.top) / r.height - 0.5;
                fl.style.transform = 'perspective(1000px) rotateY(' + (x * 14) + 'deg) rotateX(' + (-y * 12) + 'deg) rotate(1.5deg)';
            });
            hero.addEventListener('mouseleave', function(){ fl.style.transform = ''; });
        }

        if(!('IntersectionObserver' in window)) return;
        var io = new IntersectionObserver(function(list){
            list.forEach(function(en){
                if(!en.isIntersecting) return;
                var t = en.target;
                t.classList.add('in');
                io.unobserve(t);
                setTimeout(function(){ t.classList.remove('rv', 'in'); }, 1600);
            });
        }, {threshold: 0.12});
        root.querySelectorAll('.trust .card,.cat,.head-row,.path,.job,.internship-major-card,.track-in,.tracker,.step,.cta').forEach(function(el){
            var i = Array.prototype.indexOf.call(el.parentNode.children, el);
            el.style.setProperty('--d', ((i % 5) * 0.09) + 's');
            el.classList.add('rv');
            io.observe(el);
        });
    })();
</script>
</body>
</html>
