<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Membangun Kepercayaan, Memimpin Masa Depan Finansial')
            ->assertSee('class="site-header"', false)
            ->assertSee('class="site-footer"', false)
            ->assertSee('css/site-shell.css')
            ->assertSeeText(['Beranda', 'Tentang Kami', 'Kegiatan', 'Karir', 'FAQ'])
            ->assertDontSee('>Produk</a>', false)
            ->assertDontSee('>Berita</a>', false)
            ->assertDontSee('>Edukasi</a>', false)
            ->assertSeeText([
                'Profil PT Equityworld Futures',
                'Pendaftaran Calon Staff',
                'Program Magang Mahasiswa',
                'Program PKL SMA/SMK',
                'Pelacakan Status Pendaftaran',
            ])
            ->assertDontSee('Informasi Lowongan Kerja')
            ->assertDontSee('Konsultasi Bidang Perdagangan Berjangka')
            ->assertDontSee('Pemberitahuan Jadwal Bursa')
            ->assertSee('https://www.tiktok.com/@galeriamagang.id', false)
            ->assertSee('https://www.instagram.com/galerimagang.id/', false)
            ->assertSee('class="bi bi-tiktok"', false)
            ->assertSee('class="bi bi-instagram"', false)
            ->assertSeeText(['TikTok galeriamagang.id', 'Instagram galerimagang.id'])
            ->assertSeeText([
                'Fasilitas',
                'Peluang Pengembangan Karier',
                'Informasi Profil Perusahaan',
                'Lowongan Kerja, Magang',
                'Bimbingan, Hasil Seleksi',
                '1. Informasi Profil Perusahaan',
                'Menyediakan informasi lengkap mengenai PT Equityworld Futures, mulai dari profil perusahaan, sejarah, visi dan misi, hingga informasi kontak dan lokasi kantor untuk membantu pengunjung mengenal perusahaan lebih dekat.',
                '2. Lowongan Kerja dan Pendaftaran Calon Staff',
                'Menyediakan informasi mengenai kesempatan berkarier di PT Equityworld Futures, termasuk posisi yang tersedia, deskripsi pekerjaan, tanggung jawab, kualifikasi, serta persyaratan yang dibutuhkan. Calon staff juga dapat melakukan pendaftaran secara online dengan mengisi data diri, riwayat pendidikan, pengalaman kerja, dan mengunggah dokumen persyaratan.',
                '3. Program Magang dan PKL',
                'Memberikan kesempatan kepada mahasiswa dan siswa SMA/SMK untuk memperoleh pengalaman kerja secara langsung melalui program magang dan Praktik Kerja Lapangan (PKL). Peserta dapat memilih bidang yang sesuai dengan minat dan latar belakang pendidikan.',
                '4. Bimbingan dan Pengembangan Kompetensi',
                'Memberikan kesempatan kepada peserta magang dan PKL untuk mengembangkan keterampilan, memperoleh pengalaman praktis, serta membangun sikap profesional melalui arahan dan pendampingan selama program berlangsung.',
                '5. Informasi Hasil Seleksi',
                'Memudahkan calon staff dan peserta magang maupun PKL untuk memantau perkembangan proses pendaftaran dan tahapan seleksi melalui website.',
                '6. LOA dan Sertifikat Magang',
                'Memberikan Letter of Acceptance (LOA) kepada peserta yang dinyatakan diterima dalam program magang atau PKL serta sertifikat setelah peserta menyelesaikan program sesuai ketentuan yang berlaku.',
            ])
            ->assertDontSee('Fasilitas & Layanan Digital')
            ->assertDontSee('Terdaftar & Diawasi BAPPEBTI')
            ->assertDontSee('Sistem Informasi Transaksi Nasional (SITNA)')
            ->assertDontSee('data-lang="id"', false)
            ->assertDontSee('data-lang="en"', false)
            ->assertDontSee('id="tgl"', false)
            ->assertDontSee('id="c-wib"', false)
            ->assertDontSee('id="faq"', false)
            ->assertSee('logo%20galeri%20magang.png', false)
            ->assertSeeText('GALERI MAGANG')
            ->assertSee('fasilitas%201.png', false)
            ->assertSee('fasilitas%206.png', false)
            ->assertSee('nilai%201.png', false)
            ->assertSee('nilai%202.png', false)
            ->assertSee('nilai%203.png', false)
            ->assertSee('nilai%204.png', false)
            ->assertSee('keunggulan%201.jpg', false)
            ->assertDontSee('Klik kartu untuk mengocok')
            ->assertDontSee('Layanan Digital Terpadu')
            ->assertDontSee('CORE CORPORATE VALUES');
    }

    public function test_the_about_page_is_available_at_its_named_url(): void
    {
        $response = $this->get('/tentang-kami');

        $response->assertOk()
            ->assertSee('Membangun Kepercayaan, Memimpin Masa Depan Finansial')
            ->assertSeeText('Mengenal lebih dekat PT Equityworld Futures melalui kesempatan pengembangan diri dan karier bagi generasi muda maupun tenaga profesional. Melalui program Galeri Magang dan kesempatan lowongan kerja, PT Equityworld Futures memberikan ruang bagi pelajar SMA/SMK, mahasiswa, serta tenaga profesional untuk mengembangkan keterampilan, memperoleh pengalaman, dan mempersiapkan masa depan di dunia kerja.')
        ->assertSeeText([
            'VISI PT EQUITYWORLD FUTURES',
            'Menjadi perusahaan yang profesional, terpercaya, dan unggul dalam mengembangkan potensi sumber daya manusia serta memberikan kontribusi positif bagi perkembangan dunia usaha di Indonesia.',
            'MISI PT EQUITYWORLD FUTURES',
            'Membangun lingkungan kerja yang profesional, disiplin, dan berintegritas.',
            'Mengembangkan kualitas sumber daya manusia melalui edukasi dan pelatihan berkelanjutan.',
            'Menciptakan peluang pengembangan karier dan peningkatan kompetensi.',
            'Mengutamakan kualitas pelayanan, transparansi, dan tanggung jawab dalam setiap kegiatan perusahaan.',
            'Mendorong inovasi dan pemanfaatan teknologi untuk mendukung kemajuan perusahaan.',
            'Membangun hubungan kerja sama yang baik dengan berbagai institusi pendidikan dan dunia usaha.',
            'GALERI MAGANG',
            'VISI DAN MISI GALERI MAGANG',
            'Menjadi wadah pengembangan generasi muda yang unggul, profesional, berkompeten, dan berintegritas melalui pengalaman kerja nyata serta pembelajaran yang berkelanjutan.',
            'MISI',
            'Memberikan kesempatan kepada generasi muda untuk mengenal dunia kerja secara langsung.',
            'Mengembangkan kemampuan, keterampilan, dan potensi peserta magang sesuai dengan bidang masing-masing.',
            'Membentuk karakter yang disiplin, bertanggung jawab, komunikatif, dan memiliki etos kerja yang baik.',
            'Menciptakan lingkungan pembelajaran yang aktif, produktif, dan profesional.',
            'Membekali peserta dengan pengalaman serta keterampilan yang dapat menjadi bekal dalam menghadapi dunia kerja.',
            'Membangun hubungan dan kerja sama yang positif antara dunia pendidikan dan dunia industri.',
            'Mendorong peserta magang agar menjadi generasi muda yang mandiri, kreatif, dan siap bersaing di dunia kerja.',
        ])
            ->assertSeeText([
                'Ayo lebih mengenal PT Equityworld Futures',
                'PT Equityworld Futures merupakan perusahaan yang bergerak di bidang perdagangan komoditas dan instrumen keuangan internasional.',
                'Perusahaan ini menyediakan fasilitas dan layanan berbasis teknologi untuk mendukung aktivitas perdagangan di pasar global. Selain itu, EWF juga memberikan edukasi serta informasi mengenai perkembangan ekonomi dunia dan pergerakan pasar keuangan.',
                'Dengan mengutamakan profesionalisme, pelayanan, dan pengembangan pengetahuan, EWF berkomitmen memberikan pengalaman serta wawasan mengenai dunia perdagangan internasional.',
                'Yuk, kenali lebih dekat Galeri Magang',
                'Galeri Magang merupakan wadah pengembangan potensi generasi muda yang memberikan kesempatan kepada mahasiswa dan pelajar untuk mengenal dunia profesional melalui pengalaman dan pembelajaran secara langsung di lingkungan kerja.',
                'Program ini dirancang untuk membangun kemampuan, memperluas wawasan, serta membentuk karakter peserta agar memiliki keterampilan, kedisiplinan, tanggung jawab, dan kepercayaan diri dalam menghadapi dunia kerja.',
                'Melalui Galeri Magang, peserta tidak hanya memperoleh pengalaman, tetapi juga kesempatan untuk mengembangkan potensi diri, membangun relasi profesional, serta mempersiapkan masa depan karier yang lebih baik.',
                'Belajar, berkembang, dan mempersiapkan diri menjadi generasi yang siap menghadapi dunia profesional.',
            ])
            ->assertDontSee('Menjadi perusahaan pialang berjangka yang terpercaya, dengan standar layanan profesional dan edukasi pasar terdepan di Indonesia.')
            ->assertDontSee('Menyediakan layanan perdagangan berjangka yang profesional, aman, dan transparan bagi nasabah.')
            ->assertDontSee('Mengenal lebih dekat Galeri Magang PT Equityworld Futures')
            ->assertDontSee('mitra investasi berjangka terpercaya Anda sejak 2005')
            ->assertDontSee('Legalitas Bisnis PT')
            ->assertDontSee('Landasan hukum pendirian, izin usaha pialang berjangka')
            ->assertDontSee('Sistem Informasi Transaksi Nasional (SITNA)')
            ->assertDontSee('class="lcard', false)
            ->assertDontSee('01 • AKTA RESMI 2005')
            ->assertDontSee('13 • REGULASI BANK INDONESIA')
            ->assertDontSee('#legalitas')
            ->assertSee('href="#pengembangan-kompetensi"', false)
            ->assertSee('href="#tim-mentor"', false)
            ->assertSeeInOrder([
                '<section class="reg" id="regulasi">',
                '<section class="values" id="pengembangan-kompetensi">',
                '<section class="fas" id="fasilitas">',
                '<section class="keung" id="keunggulan">',
                '<section class="mentors" id="tim-mentor">',
            ], false)
            ->assertSeeText([
                'Tim Mentor Kami',
                'Kenali para mentor yang berperan dalam mendukung proses belajar, pengembangan kompetensi, dan kesiapan profesional peserta.',
                'Fechy Everpongoh',
                'Head of Division',
                'Lila',
                'Firsa',
                'Motivasi',
            ])
            ->assertSee('role="img" aria-label="Foto Fechy Everpongoh akan ditambahkan menyusul"', false)
            ->assertDontSee('Kepercayaan dibangun secara konsisten')
            ->assertSeeText([
                'KESEMPATAN KARIER',
                'Staff & Profesional',
                'Peluang kerja bagi tenaga profesional dan pencari kerja',
                'PROGRAM MAGANG',
                'Mahasiswa & SMA/SMK',
                'Kesempatan belajar dan memperoleh pengalaman kerja',
                'PENGEMBANGAN DIRI',
                'Pengalaman & Sertifikat',
                'Pembelajaran, pendampingan, dan sertifikat sesuai ketentuan program',
            ])
            ->assertDontSee('Jenjang Peserta')
            ->assertDontSee('IZIN BAPPEBTI')
            ->assertDontSee('100% Segregated Account')
            ->assertDontSee('logo%20aspebtindo.png', false)
            ->assertDontSee('Aspebtindo')
            ->assertSeeText([
                'PENGEMBANGAN KOMPETENSI',
                'Aspek Pengembangan.',
                'Membangun Potensi dan Mempersiapkan',
                'Generasi Muda Menghadapi Dunia Kerja.',
                'Pengalaman Dunia Kerja',
                'Lingkungan Profesional',
                'Memberikan kesempatan kepada peserta untuk mengenal lingkungan kerja profesional dan terlibat langsung dalam aktivitas perusahaan.',
                'Pengembangan Keterampilan',
                'Keahlian Sesuai Bidang',
                'Mendorong peserta untuk meningkatkan kemampuan, pengetahuan, dan keterampilan sesuai bidang magang yang diikuti.',
                'Pembentukan Karakter',
                'Disiplin & Kerja Sama',
                'Membangun sikap disiplin, tanggung jawab, komunikasi, kerja sama, dan kepercayaan diri dalam lingkungan profesional.',
                'Persiapan Karir',
                'Potensi & Relasi',
                'Membantu peserta mengenali potensi diri, memperluas relasi, dan mempersiapkan langkah menuju dunia kerja.',
            ])
            ->assertDontSee('NILAI-NILAI INTI PERUSAHAAN')
            ->assertDontSee('Integritas Profesional')
            ->assertSee('class="site-header"', false)
            ->assertSee('class="site-footer"', false)
            ->assertDontSee('class="clock"', false);

        $this->assertSame(2, substr_count($response->getContent(), 'class="pcard'));
    }

    public function test_the_career_page_is_available_from_the_about_page_navigation(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.route('karir').'"', false)
            ->assertSee('href="'.route('tentang.kami').'#fasilitas"', false)
            ->assertSee('href="'.route('faq').'"', false);

        $this->get('/karir')
            ->assertOk()
            ->assertSee('class="site-header"', false)
            ->assertSee('class="site-footer"', false)
            ->assertSee('css/site-shell.css')
            ->assertSee('Daftar Lowongan');
    }

    public function test_the_education_page_is_available_from_site_navigation(): void
    {
        $this->get(route('edukasi'))
            ->assertOk()
            ->assertSee('Satu Ruang untuk Memahami')
            ->assertSee('Mekanisme Perdagangan Berjangka')
            ->assertSee('Multilateral (JFX)')
            ->assertSee('Bursa Terorganisir (JFX)')
            ->assertSee('Parameter / Aspek')
            ->assertSee('Tempat Pelaksanaan')
            ->assertSee('Murni melalui lelang tawar-menawar terbuka (Open Auction / Order Book).')
            ->assertSee('Penjamin: PT KBI (Persero)')
            ->assertSee('Kuotasi harga mengacu pada pasar global internasional.')
            ->assertSee('aria-label="Navigasi topik edukasi"', false)
            ->assertSee('class="dock-card">Mekanisme Perdagangan', false)
            ->assertDontSee('class="dock-card"><span class="ico"', false)
            ->assertDontSee('Jelajahi')
            ->assertSee('HSIV9')
            ->assertSee('Jakarta Composite Index (Spot)')
            ->assertSee('Loco London Gold merupakan perdagangan emas yang mengacu pada pasar emas internasional di London.')
            ->assertSee('Faktor yang Memengaruhi Harga Emas')
            ->assertSee('Permintaan &amp; Penawaran', false)
            ->assertSee('XAU')
            ->assertSee('USD')
            ->assertSee('Summer Time')
            ->assertSee('Winter Time')
            ->assertSee('Jadwal perdagangan saat Summer')
            ->assertSee('Jadwal perdagangan saat Winter')
            ->assertSee('Jadwal Sesi Perdagangan')
            ->assertSee('06.00 – 03.30')
            ->assertSee('06.00 – 04.30')
            ->assertSee('HKK – Pagi')
            ->assertSee('08.15 – 11.00')
            ->assertSee('HKK – Sore')
            ->assertSee('HKK – AHFT')
            ->assertSee('12.00 – 15.30')
            ->assertSee('AHFT (After Hour Futures Trading)')
            ->assertSee('JPK – Sesi I')
            ->assertSee('06.30 – 13.25')
            ->assertSee('JPK – Sesi II')
            ->assertSee('13.25 – 03.45')
            ->assertDontSee('HKR')
            ->assertDontSee('JPR')
            ->assertDontSee('Sedang Berlaku')
            ->assertDontSee('Tidak Aktif')
            ->assertDontSee('Sekitar 1 sampai 2 minggu di bulan Maret dan Oktober/November')
            ->assertDontSee('class="time-zone rv"', false)
            ->assertSee('--border-angle')
            ->assertDontSee('Edukasi Perdagangan Berjangka')
            ->assertDontSee('Dasar Perdagangan Berjangka')
            ->assertDontSee('Mulai Belajar')
            ->assertDontSee('Lihat Jadwal Bursa')
            ->assertSee('class="site-header"', false)
            ->assertSee('class="site-footer"', false)
            ->assertDontSee('Jakarta <b id="c-wib"', false)
            ->assertDontSee('Hong Kong <b id="c-hkg"', false)
            ->assertDontSee('Jepang <b id="c-jst"', false)
            ->assertDontSee('New York <b id="c-nyc"', false);
    }
}
