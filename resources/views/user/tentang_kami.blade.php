{{--
    File: resources/views/user/tentang_kami.blade.php
    Route: Route::view('/tentang-kami', 'user.tentang_kami')->name('tentang.kami');

    SEMUA GAMBAR ada di public/image. Kalau nama file Anda berbeda,
    cukup ubah nama file pada array $img di bawah ini (satu tempat saja).
--}}
@php
    $img = [
        'logo'     => 'logo galeri magang.png',
        'hero_bg'  => 'bg tentang kami.png',
        'bappebti' => 'logo bappebti.png',
        'jfx'      => 'logo jfx.png',
        'kbi'      => 'logo kbi.png',
        'bi'       => 'logo bi.png',
        'ojk'      => 'logo ojk.png',
        'flag_id'  => 'bendera indonesia.png',
        'flag_en'  => 'bendera inggris.png',
        'val1'     => 'pengembangan 1.png',
        'val2'     => 'pengembangan 2.png',
        'val3'     => 'pengembangan 4.png',
        'val4'     => 'pengembangan 3.png',
        'fas1'     => 'fasilitas 1.png',
        'fas2'     => 'fasilitas 2.jpg',
        'fas3'     => 'fasilitas 3.png',
        'fas4'     => 'fasilitas 4.png',
        'fas5'     => 'fasilitas 5.jpg',
        'fas6'     => 'fasilitas 6.png',
        'adv1'     => 'keunggulan 1.jpg',
        'adv2'     => 'keunggulan 2.jpg',
        'adv3'     => 'keunggulan 3.jpg',
        'adv_bg'   => 'bg keunggulan.png',
    ];
    $i = fn ($k) => asset('image/' . rawurlencode($img[$k]));

    $values = [
        ['Pengalaman Dunia Kerja','Lingkungan Profesional','Memberikan kesempatan kepada peserta untuk mengenal lingkungan kerja profesional dan terlibat langsung dalam aktivitas perusahaan.','val1','M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z'],
        ['Pengembangan Keterampilan','Keahlian Sesuai Bidang','Mendorong peserta untuk meningkatkan kemampuan, pengetahuan, dan keterampilan sesuai bidang magang yang diikuti.','val2','M12 12a4 4 0 100-8 4 4 0 000 8zm-8 9a8 8 0 0116 0'],
        ['Pembentukan Karakter','Disiplin & Kerja Sama','Membangun sikap disiplin, tanggung jawab, komunikasi, kerja sama, dan kepercayaan diri dalam lingkungan profesional.','val4','M9 12l2 2 4-4M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z'],
        ['Persiapan Karir','Potensi & Relasi','Membantu peserta mengenali potensi diri, memperluas relasi, dan mempersiapkan langkah menuju dunia kerja.','val3','M9 18h6m-5 3h4M12 3a6 6 0 00-4 10.5V16h8v-2.5A6 6 0 0012 3z'],
    ];

    // Badan regulasi: BAPPEBTI, OJK, dan BI
    $regulators = [
        ['Badan Pengawas Perdagangan Berjangka Komoditi (BAPPEBTI)', 'bappebti', 'Badan Pengawas Perdagangan Berjangka Komoditi (BAPPEBTI) merupakan lembaga pemerintah di bawah Kementerian Perdagangan Republik Indonesia yang memiliki tugas melaksanakan pembinaan, pengaturan, dan pengawasan terhadap kegiatan perdagangan berjangka komoditi. BAPPEBTI berperan penting dalam memastikan kegiatan perdagangan berjangka berjalan sesuai dengan ketentuan peraturan perundang-undangan yang berlaku. Melalui penerapan regulasi, pengawasan, serta penegakan ketentuan, BAPPEBTI berupaya menciptakan lingkungan perdagangan yang tertib, transparan, dan berintegritas, sekaligus melindungi kepentingan masyarakat dan menjaga kepercayaan pelaku pasar. Keberadaan BAPPEBTI juga mendukung perkembangan industri perdagangan berjangka komoditi di Indonesia agar berlangsung secara sehat, bertanggung jawab, dan sesuai dengan standar yang telah ditetapkan.'],
        ['Otoritas Jasa Keuangan (OJK)', 'ojk', 'Otoritas Jasa Keuangan (OJK) merupakan lembaga independen yang memiliki kewenangan dalam menyelenggarakan sistem pengaturan dan pengawasan terintegrasi terhadap kegiatan di sektor jasa keuangan di Indonesia. OJK berperan dalam menjaga stabilitas sistem keuangan, mendorong terselenggaranya kegiatan jasa keuangan yang transparan, adil, teratur, dan akuntabel, serta memastikan perlindungan terhadap kepentingan konsumen dan masyarakat. Melalui pelaksanaan fungsi pengaturan, pengawasan, dan penegakan ketentuan yang berlaku, OJK berupaya menciptakan industri jasa keuangan yang sehat, berintegritas, dan berkelanjutan. Keberadaan OJK juga menjadi bagian penting dalam meningkatkan kepercayaan masyarakat terhadap sektor keuangan serta mendukung pertumbuhan dan perkembangan perekonomian nasional.'],
        ['Bank Indonesia (BI)', 'bi', 'Bank Indonesia (BI) merupakan bank sentral Republik Indonesia yang memiliki tujuan utama menjaga kestabilan nilai rupiah, baik terhadap barang dan jasa maupun terhadap mata uang negara lain. Dalam menjalankan mandatnya, BI berperan melalui pelaksanaan kebijakan moneter, pengaturan dan pengelolaan sistem pembayaran, serta kebijakan makroprudensial untuk menjaga stabilitas sistem keuangan. Melalui berbagai kebijakan dan instrumen yang diterapkan, Bank Indonesia berupaya mengendalikan inflasi, menjaga kelancaran transaksi pembayaran, serta mendukung ketahanan sektor keuangan nasional. Selain itu, BI turut berkontribusi dalam menciptakan kondisi ekonomi yang stabil dan berkelanjutan guna mendukung pertumbuhan ekonomi serta meningkatkan kepercayaan masyarakat terhadap sistem keuangan Indonesia.'],
    ];

    $advantages = [
        ['Informasi Perusahaan yang Kredibel', 'Menghadirkan informasi perusahaan yang terstruktur dan mudah diakses, mulai dari profil, legalitas, hingga berbagai informasi resmi PT Equityworld Futures.', 'adv1', 'Informasi Kredibel'],
        ['Administrasi Digital yang Efisien', 'Mendukung proses pendaftaran staff dan peserta magang melalui sistem online, dilengkapi pengisian data serta pengiriman dokumen secara digital.', 'adv2', 'Administrasi Digital'],
        ['Transparansi Informasi Pendaftaran', 'Memberikan kemudahan bagi calon staff dan peserta magang dalam memantau perkembangan pengajuan melalui informasi status pendaftaran yang tersedia di website.', 'adv3', 'Transparansi Pendaftaran'],
    ];
    $mentors = [
        ['Fechy Everpongoh', 'Head of Division', 'image/mentor 1.jpeg'],
        ['Lila', 'Mentor'],
        ['Firsa', 'Mentor'],
    ];
    $orbitIcons = ['grid', 'user', 'doc'];

    $fas = [
        ['PROFIL PERUSAHAAN','1. Informasi Profil Perusahaan','Menyediakan informasi lengkap mengenai PT Equityworld Futures, mulai dari profil perusahaan, sejarah, visi dan misi, hingga informasi kontak dan lokasi kantor untuk membantu pengunjung mengenal perusahaan lebih dekat.','fas1'],
        ['KARIER & STAFF','2. Lowongan Kerja dan Pendaftaran Calon Staff','Menyediakan informasi mengenai kesempatan berkarier di PT Equityworld Futures, termasuk posisi yang tersedia, deskripsi pekerjaan, tanggung jawab, kualifikasi, serta persyaratan yang dibutuhkan. Calon staff juga dapat melakukan pendaftaran secara online dengan mengisi data diri, riwayat pendidikan, pengalaman kerja, dan mengunggah dokumen persyaratan.','fas2'],
        ['PROGRAM MAGANG & PKL','3. Program Magang dan PKL','Memberikan kesempatan kepada mahasiswa dan siswa SMA/SMK untuk memperoleh pengalaman kerja secara langsung melalui program magang dan Praktik Kerja Lapangan (PKL). Peserta dapat memilih bidang yang sesuai dengan minat dan latar belakang pendidikan.','fas3'],
        ['PENGEMBANGAN KOMPETENSI','4. Bimbingan dan Pengembangan Kompetensi','Memberikan kesempatan kepada peserta magang dan PKL untuk mengembangkan keterampilan, memperoleh pengalaman praktis, serta membangun sikap profesional melalui arahan dan pendampingan selama program berlangsung.','fas4'],
        ['HASIL SELEKSI','5. Informasi Hasil Seleksi','Memudahkan calon staff dan peserta magang maupun PKL untuk memantau perkembangan proses pendaftaran dan tahapan seleksi melalui website.','fas5'],
        ['LOA & SERTIFIKAT','6. LOA dan Sertifikat Magang','Memberikan Letter of Acceptance (LOA) kepada peserta yang dinyatakan diterima dalam program magang atau PKL serta sertifikat setelah peserta menyelesaikan program sesuai ketentuan yang berlaku.','fas6'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Tentang Kami | PT Equityworld Futures</title>
@vite(['resources/css/app.css'])
@include('user.partials.navigation-transitions')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script>document.documentElement.classList.add('js')</script>

</head>
<body class="about-page">

@include('user.partials.header')
@if(session('account_deleted'))
    <p role="status">Akun Anda berhasil dihapus. Silakan daftar kembali jika ingin menggunakan layanan.</p>
@endif

{{-- clip-path lengkung atas (ke dalam & mengalir) untuk kartu nilai --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <clipPath id="flow-top" clipPathUnits="objectBoundingBox">
      <path d="M0,0.07 Q0,0.03 0.05,0.034 C0.30,0.085 0.66,0.10 0.95,0.012 Q1,0 1,0.045 L1,0.955 Q1,1 0.95,1 L0.05,1 Q0,1 0,0.955 Z"/>
    </clipPath>
  </defs>
</svg>

{{-- ===== ICON SPRITE ===== --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <symbol id="shield" viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></symbol>
  <symbol id="lock" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></symbol>
  <symbol id="award" viewBox="0 0 24 24"><circle cx="12" cy="9" r="5"/><path d="M8.5 13.5L7 21l5-3 5 3-1.5-7.5"/></symbol>
  <symbol id="doc" viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/></symbol>
  <symbol id="user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/></symbol>
  <symbol id="bulb" viewBox="0 0 24 24"><path d="M9 18h6M10 21h4M12 3a6 6 0 00-4 10.5V16h8v-2.5A6 6 0 0012 3z"/></symbol>
  <symbol id="check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></symbol>
  <symbol id="phone" viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></symbol>
  <symbol id="mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></symbol>
  <symbol id="pin" viewBox="0 0 24 24"><path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></symbol>
  <symbol id="grid" viewBox="0 0 24 24"><rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></symbol>
  <symbol id="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></symbol>
</svg>

<div class="stabs" id="stabs">
  <div class="wrap">
    <a href="#profil" class="on">PROFIL PERUSAHAAN</a>
    <a href="#regulasi">BADAN REGULASI</a>
    <a href="#pengembangan-kompetensi">PENGEMBANGAN KOMPETENSI</a>
    <a href="#fasilitas">FASILITAS & LAYANAN</a>
    <a href="#keunggulan">KEUNGGULAN LAYANAN</a>
    <a href="#tim-mentor">TIM MENTOR</a>
  </div>
  <i id="prog"></i>
</div>

{{-- ===== HERO ===== --}}
<section class="hero">
  <div class="bg" style="--about-hero-image: url('{{ $i('hero_bg') }}')"></div><span class="orb o1"></span><span class="orb o2"></span>
  <div class="wrap">
    <span class="pill rv"><i></i> PROFIL PERUSAHAAN · PT EQUITYWORLD FUTURES</span>
    <h1 class="rv" style="--d:.1s">Membangun Kepercayaan, Memimpin Masa Depan Finansial</h1>
    <p class="lead rv" style="--d:.3s">Mengenal lebih dekat PT Equityworld Futures melalui kesempatan pengembangan diri dan karier bagi generasi muda maupun tenaga profesional. Melalui program Galeri Magang dan kesempatan lowongan kerja, PT Equityworld Futures memberikan ruang bagi pelajar SMA/SMK, mahasiswa, serta tenaga profesional untuk mengembangkan keterampilan, memperoleh pengalaman, dan mempersiapkan masa depan di dunia kerja.</p>
    <div class="chips rv zoom" style="--d:.4s">
      <div class="chip"><span class="ic"><svg class="i"><use href="#user"/></svg></span><div><small>KESEMPATAN KARIER</small><b>Staff &amp; Profesional</b><span class="detail">Peluang kerja bagi tenaga profesional dan pencari kerja</span></div></div>
      <div class="chip"><span class="ic"><svg class="i"><use href="#grid"/></svg></span><div><small>PROGRAM MAGANG</small><b>Mahasiswa &amp; SMA/SMK</b><span class="detail">Kesempatan belajar dan memperoleh pengalaman kerja</span></div></div>
      <div class="chip"><span class="ic"><svg class="i"><use href="#doc"/></svg></span><div><small>PENGEMBANGAN DIRI</small><b>Pengalaman &amp; Sertifikat</b><span class="detail">Pembelajaran, pendampingan, dan sertifikat sesuai ketentuan program</span></div></div>
    </div>
    <div class="tabs rv" style="--d:.5s">
      <a href="#profil" class="on">PROFIL PERUSAHAAN</a>
      <a href="#regulasi">BADAN REGULASI</a>
      <a href="#pengembangan-kompetensi">PENGEMBANGAN KOMPETENSI</a>
      <a href="#fasilitas">FASILITAS & LAYANAN</a>
      <a href="#keunggulan">KEUNGGULAN LAYANAN</a>
      <a href="#tim-mentor">TIM MENTOR</a>
    </div>
  </div>
</section>

{{-- ===== PROFIL ===== --}}
<section class="profile" id="profil">
  <div class="wrap">
    <div class="pcard rv">
      <div class="profile-intro">
        <span class="profile-kicker"><i class="bi bi-building"></i> Profil perusahaan</span>
        <h2>Ayo lebih mengenal <span>PT Equityworld Futures</span></h2>
        <p>PT Equityworld Futures merupakan perusahaan yang bergerak di bidang perdagangan komoditas dan instrumen keuangan internasional.</p>
        <p>Perusahaan ini menyediakan fasilitas dan layanan berbasis teknologi untuk mendukung aktivitas perdagangan di pasar global. Selain itu, EWF juga memberikan edukasi serta informasi mengenai perkembangan ekonomi dunia dan pergerakan pasar keuangan.</p>
        <p>Dengan mengutamakan profesionalisme, pelayanan, dan pengembangan pengetahuan, EWF berkomitmen memberikan pengalaman serta wawasan mengenai dunia perdagangan internasional.</p>
        <div class="pengawasan">
          <small>PENGAWASAN & KEANGGOTAAN RESMI:</small>
          <div class="logos">
            <div class="logos-track">
              @for($n = 0; $n < 3; $n++)
              <img src="{{ $i('bappebti') }}" alt="{{ $n === 0 ? 'BAPPEBTI' : '' }}" @if($n > 0) aria-hidden="true" @endif onerror="this.style.display='none'">
              <img src="{{ $i('ojk') }}" alt="{{ $n === 0 ? 'OJK' : '' }}" @if($n > 0) aria-hidden="true" @endif onerror="this.style.display='none'">
              <img src="{{ $i('bi') }}" alt="{{ $n === 0 ? 'Bank Indonesia' : '' }}" @if($n > 0) aria-hidden="true" @endif onerror="this.style.display='none'">
              <img src="{{ $i('jfx') }}" alt="{{ $n === 0 ? 'JFX' : '' }}" @if($n > 0) aria-hidden="true" @endif onerror="this.style.display='none'">
              <img src="{{ $i('kbi') }}" alt="{{ $n === 0 ? 'KBI' : '' }}" @if($n > 0) aria-hidden="true" @endif onerror="this.style.display='none'">
              @endfor
            </div>
          </div>
        </div>
      </div>
      <div class="profile-principles">
        <article class="principle-card rv" style="--d:.08s">
          <h3>VISI PT EQUITYWORLD FUTURES</h3>
          <p>Menjadi perusahaan yang profesional, terpercaya, dan unggul dalam mengembangkan potensi sumber daya manusia serta memberikan kontribusi positif bagi perkembangan dunia usaha di Indonesia.</p>
        </article>
        <article class="principle-card rv" style="--d:.16s">
          <h3>MISI PT EQUITYWORLD FUTURES</h3>
          <ol>
            <li>Membangun lingkungan kerja yang profesional, disiplin, dan berintegritas.</li>
            <li>Mengembangkan kualitas sumber daya manusia melalui edukasi dan pelatihan berkelanjutan.</li>
            <li>Menciptakan peluang pengembangan karier dan peningkatan kompetensi.</li>
            <li>Mengutamakan kualitas pelayanan, transparansi, dan tanggung jawab dalam setiap kegiatan perusahaan.</li>
            <li>Mendorong inovasi dan pemanfaatan teknologi untuk mendukung kemajuan perusahaan.</li>
            <li>Membangun hubungan kerja sama yang baik dengan berbagai institusi pendidikan dan dunia usaha.</li>
          </ol>
        </article>
      </div>
    </div>

    <div class="pcard gallery-profile rv">
      <div class="gallery-intro">
        <span class="profile-kicker"><i class="bi bi-mortarboard"></i> Ruang tumbuh generasi muda</span>
        <h2>Yuk, kenali lebih dekat <span>Galeri Magang</span></h2>
        <p>Galeri Magang merupakan wadah pengembangan potensi generasi muda yang memberikan kesempatan kepada mahasiswa dan pelajar untuk mengenal dunia profesional melalui pengalaman dan pembelajaran secara langsung di lingkungan kerja.</p>
        <p>Program ini dirancang untuk membangun kemampuan, memperluas wawasan, serta membentuk karakter peserta agar memiliki keterampilan, kedisiplinan, tanggung jawab, dan kepercayaan diri dalam menghadapi dunia kerja.</p>
        <p>Melalui Galeri Magang, peserta tidak hanya memperoleh pengalaman, tetapi juga kesempatan untuk mengembangkan potensi diri, membangun relasi profesional, serta mempersiapkan masa depan karier yang lebih baik.</p>
        <div class="gallery-slogan rv" style="--d:.24s"><i class="bi bi-stars" aria-hidden="true"></i><span>Belajar, berkembang, dan mempersiapkan diri menjadi generasi yang siap menghadapi dunia profesional.</span></div>
      </div>
      <div class="profile-principles gallery-principles">
        <article class="principle-card rv" style="--d:.08s">
          <h3>VISI DAN MISI GALERI MAGANG</h3>
          <h4>VISI</h4>
          <p>Menjadi wadah pengembangan generasi muda yang unggul, profesional, berkompeten, dan berintegritas melalui pengalaman kerja nyata serta pembelajaran yang berkelanjutan.</p>
        </article>
        <article class="principle-card rv" style="--d:.16s">
          <h3>MISI</h3>
          <ol>
            <li>Memberikan kesempatan kepada generasi muda untuk mengenal dunia kerja secara langsung.</li>
            <li>Mengembangkan kemampuan, keterampilan, dan potensi peserta magang sesuai dengan bidang masing-masing.</li>
            <li>Membentuk karakter yang disiplin, bertanggung jawab, komunikatif, dan memiliki etos kerja yang baik.</li>
            <li>Menciptakan lingkungan pembelajaran yang aktif, produktif, dan profesional.</li>
            <li>Membekali peserta dengan pengalaman serta keterampilan yang dapat menjadi bekal dalam menghadapi dunia kerja.</li>
            <li>Membangun hubungan dan kerja sama yang positif antara dunia pendidikan dan dunia industri.</li>
            <li>Mendorong peserta magang agar menjadi generasi muda yang mandiri, kreatif, dan siap bersaing di dunia kerja.</li>
          </ol>
        </article>
      </div>
    </div>
  </div>
</section>

{{-- ===== BADAN REGULASI ===== --}}
<section class="reg" id="regulasi">
  <div class="band">
    <div class="wrap">
      <h3 class="rv">Badan Regulasi</h3>
      <p class="rs rv" style="--d:.1s">Badan-badan regulasi utama yang mengawasi industri perdagangan berjangka dan sektor keuangan di Indonesia. Setiap lembaga memiliki peran strategis dalam menjaga stabilitas, transparansi, dan perlindungan nasabah.</p>
    </div>
  </div>
  <div class="wrap">
    <article class="rcard rv zoom" tabindex="0" role="group" aria-label="Informasi badan regulasi" aria-live="polite">
      <div class="rlogo"><img id="regulator-logo" src="{{ $i($regulators[0][1]) }}" alt="{{ $regulators[0][0] }}"></div>
      <div>
        <h4 id="regulator-title">{{ $regulators[0][0] }}</h4>
        <p id="regulator-description">{{ $regulators[0][2] }}</p>
      </div>
      <button class="reg-control prev" type="button" aria-label="Badan regulasi sebelumnya">
        <svg viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
      </button>
      <button class="reg-control next" type="button" aria-label="Badan regulasi berikutnya">
        <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
      </button>
    </article>
    <div class="dots" id="dots" aria-label="Pilih badan regulasi">
      @foreach($regulators as $index => $regulator)
      <button type="button" class="{{ $index === 0 ? 'on' : '' }}" data-index="{{ $index }}" data-title="{{ $regulator[0] }}" data-description="{{ $regulator[2] }}" data-logo="{{ $i($regulator[1]) }}" aria-label="Tampilkan {{ $regulator[0] }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"></button>
      @endforeach
    </div>
  </div>
</section>

{{-- ===== PENGEMBANGAN KOMPETENSI ===== --}}
<div class="vwrap">
<section class="values" id="pengembangan-kompetensi">
  <span class="orb"></span>
  <span class="deco d1"></span><span class="deco d2"></span><span class="deco d3"></span><span class="deco d4"></span>
  <div class="wrap">
    <span class="pill rv"><i></i> PENGEMBANGAN KOMPETENSI</span>
    <h2 class="big-h rv" style="--d:.1s"><em>Aspek Pengembangan.</em> Membangun Potensi dan Mempersiapkan<br>Generasi Muda Menghadapi Dunia Kerja.</h2>
    <span class="sub-pill rv" style="--d:.2s"><span class="d"><svg class="i"><use href="#check"/></svg></span> Program magang untuk menumbuhkan kompetensi, karakter, dan kesiapan kerja.</span>
    <div class="vgrid">
      @foreach($values as $v)
      <div class="vcard rv" style="--d:.{{ $loop->index * 15 }}s">
        <div class="vc-in">
          <img class="vc-img" src="{{ $i($v[3]) }}" alt="{{ $v[0] }}">
          <div class="vc-shade"></div>
          <div class="vc-glow"></div>
          <div class="vc-body">
            <div class="ic"><svg class="i" viewBox="0 0 24 24"><path d="{{ $v[4] }}"/></svg></div>
            <h5>{{ $v[0] }}</h5>
            <span class="tg">{{ $v[1] }}</span>
            <p>{{ $v[2] }}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section></div>

{{-- ===== FASILITAS & LAYANAN ===== --}}
<section class="fas" id="fasilitas">
  <div class="wrap">
    <h3 class="rv">Fasilitas <span>&</span> Peluang Pengembangan Karier</h3>
    <p class="fs rv" style="--d:.1s">Dukungan informasi, pendaftaran, pengembangan kompetensi, dan persiapan karier dalam satu platform.</p>
    <div class="tags rv" style="--d:.2s">
      <span><svg class="i"><use href="#check"/></svg> Informasi Profil Perusahaan</span>
      <span><svg class="i"><use href="#check"/></svg> Lowongan Kerja, Magang & PKL</span>
      <span><svg class="i"><use href="#check"/></svg> Bimbingan, Hasil Seleksi & Sertifikat</span>
    </div>
    <div class="car rv zoom" id="car">
      <div class="ring" id="ring">
        @foreach($fas as $k => $f)
        <article class="slide s{{ (($k + intdiv(count($fas), 2)) % count($fas)) - intdiv(count($fas), 2) }}" data-index="{{ $k }}">
          <div class="im"><img src="{{ $i($f[3]) }}" alt="" loading="lazy"></div>
          <div class="tx"><small>{{ $f[0] }}</small><h6>{{ $f[1] }}</h6><p>{{ $f[2] }}</p></div>
        </article>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ===== KEUNGGULAN LAYANAN ===== --}}
<section class="keung" id="keunggulan">
  <div class="wrap">
    <h3 class="rv">Keunggulan <span>Layanan Kami</span></h3>
    <p class="ks rv" style="--d:.1s">Menghadirkan pengalaman layanan digital yang terintegrasi melalui kemudahan akses informasi, proses administrasi, dan transparansi layanan.</p>
    <div class="advantage-panel rv">
      <div class="advantage-orbit" id="advantage-orbit" aria-label="Lingkaran layanan digital, klik ikon untuk memilih keunggulan">
        <span class="orb-circle orb-glow"></span>
        <span class="orb-circle orb-track"></span>
        <span class="orb-circle orb-ring"></span>
        <span class="orb-circle orb-photo" style="--about-adv-image: url('{{ $i('adv_bg') }}')"></span>
        <div class="orb-rotor" id="orb-rotor">
          @for($n = 0; $n < 6; $n++)
          <button type="button" class="orbit-node {{ $n === 0 ? 'on' : '' }}" style="--a:{{ $n * 60 }}deg" data-node="{{ $n }}" aria-label="Tampilkan {{ $advantages[$n % 3][0] }}"><svg class="i"><use href="#{{ $orbitIcons[$n % 3] }}"/></svg></button>
          @endfor
        </div>
      </div>
      <div class="advantage-stack" id="advantage-stack" aria-label="Keunggulan layanan">
        @foreach($advantages as $index => $advantage)
        <button type="button" class="advantage-card a{{ $index }}" data-index="{{ $index }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
          <div class="im"><img src="{{ $i($advantage[2]) }}" alt="" loading="lazy"></div>
          <div class="tx">
            <small>LAYANAN DIGITAL</small>
            <h4>{{ $advantage[0] }}</h4>
            <p>{{ $advantage[1] }}</p>
          </div>
        </button>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ===== TIM MENTOR ===== --}}
<section class="mentors" id="tim-mentor">
  <div class="wrap">
    <h3 class="rv">Tim <span>Mentor Kami</span></h3>
    <p class="mentor-intro rv" style="--d:.1s">Kenali para mentor yang berperan dalam mendukung proses belajar, pengembangan kompetensi, dan kesiapan profesional peserta.</p>
    <div class="mentor-grid">
      @foreach($mentors as $index => $mentor)
      <article class="mentor-card rv" style="--d:.{{ $index * 15 }}s">
        <div class="mentor-photo">
          @if(!empty($mentor[2]))
            <img src="{{ asset($mentor[2]) }}" alt="Foto {{ $mentor[0] }}">
          @else
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/></svg>
          @endif
        </div>
        <h4>{{ $mentor[0] }}</h4>
        <span class="mentor-role">{{ $mentor[1] }}</span>
        <div class="mentor-motivation">
          <span class="mentor-motivation-label">Motivasi</span>
          <blockquote class="mentor-quote" aria-label="Motivasi {{ $mentor[0] }}"></blockquote>
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>

@include('user.partials.footer')

<script>
  // Animasi muncul saat scroll
  (function(){
    const revealElements=document.querySelectorAll('.rv');
    if(!('IntersectionObserver' in window)){
      revealElements.forEach(element=>element.classList.add('in'));
      return;
    }

    const revealObserver=new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(!entry.isIntersecting) return;
        entry.target.classList.add('in');
        revealObserver.unobserve(entry.target);
      });
    },{threshold:.12,rootMargin:'0px 0px -40px 0px'});

    revealElements.forEach(element=>revealObserver.observe(element));
  })();

  // Subtle 3D tilt for the two profile cards.
  (function(){
    if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    document.querySelectorAll('.pcard').forEach(card=>{
      card.addEventListener('pointermove',event=>{
        if(event.pointerType==='touch') return;
        const rect=card.getBoundingClientRect();
        const x=(event.clientX-rect.left)/rect.width-.5;
        const y=(event.clientY-rect.top)/rect.height-.5;
        card.style.setProperty('--tilt-x',(-y*1.6).toFixed(2)+'deg');
        card.style.setProperty('--tilt-y',(x*2).toFixed(2)+'deg');
      });
      card.addEventListener('pointerleave',()=>{
        card.style.setProperty('--tilt-x','0deg');
        card.style.setProperty('--tilt-y','0deg');
      });
    });
  })();

  // Carousel fasilitas: kartu sejajar bertumpuk, bergantian terus (berputar), mulai dari nomor 1 di tengah
  (function(){
    const car=document.getElementById('car');
    const ring=document.getElementById('ring');
    if(!car||!ring) return;
    const s=[...ring.querySelectorAll('.slide')]; if(!s.length) return;
    const n=s.length;
    const mod=(a,b)=>((a%b)+b)%b;
    let k=0,timer;
    const half=Math.floor(n/2);
    const render=()=>{
      const a=mod(k,n);
      s.forEach((el,i)=>{
        el.className='slide s'+(mod(i-a+half,n)-half);
      });
    };
    const start=()=>{clearInterval(timer);timer=setInterval(()=>{k+=1;render()},6000)};
    const stop=()=>clearInterval(timer);
    s.forEach((el,i)=>el.addEventListener('click',()=>{
      const d=mod(i-mod(k,n)+Math.floor(n/2),n)-Math.floor(n/2);
      if(d!==0){k+=d;render()}
      start();
    }));
    car.addEventListener('mouseenter',stop);
    car.addEventListener('mouseleave',start);
    render();
    const observer=new IntersectionObserver(entries=>{
      if(entries[0].isIntersecting) start();
      else stop();
    },{threshold:.2});
    observer.observe(car);
  })();

  // Slider badan regulasi
  (function(){
    const dots=[...document.querySelectorAll('#dots button')];
    const logo=document.getElementById('regulator-logo');
    const title=document.getElementById('regulator-title');
    const description=document.getElementById('regulator-description');
    const card=document.querySelector('.rcard');
    if(!dots.length||!logo||!title||!description||!card) return;
    let active=0,timer;
    const show=index=>{
      active=(index+dots.length)%dots.length;
      const dot=dots[active];
      title.textContent=dot.dataset.title;
      description.textContent=dot.dataset.description;
      logo.src=dot.dataset.logo;
      logo.alt=dot.dataset.title;
      dots.forEach((item,i)=>{
        item.classList.toggle('on',i===active);
        item.setAttribute('aria-pressed',String(i===active));
      });
    };
    const start=()=>{clearInterval(timer);timer=setInterval(()=>show(active+1),4500)};
    dots.forEach((dot,index)=>dot.addEventListener('click',()=>{show(index);start()}));
    card.addEventListener('click',event=>{
      if(event.target.closest('.reg-control')) return;
      show(active+1);
      start();
    });
    card.addEventListener('keydown',event=>{
      if(event.target!==card||(event.key!=='Enter'&&event.key!==' ')) return;
      event.preventDefault();
      show(active+1);
      start();
    });
    card.querySelector('.reg-control.prev').addEventListener('click',()=>{show(active-1);start()});
    card.querySelector('.reg-control.next').addEventListener('click',()=>{show(active+1);start()});
    card.addEventListener('mouseenter',()=>clearInterval(timer));
    card.addEventListener('mouseleave',start);
    start();
  })();

  // Tab sticky + scrollspy + progres scroll
  (function(){
    const bar=document.getElementById('stabs'), ht=document.querySelector('.hero .tabs'), prog=document.getElementById('prog');
    const header=document.querySelector('.site-header');
    const ids=['profil','regulasi','pengembangan-kompetensi','fasilitas','keunggulan','tim-mentor'];
    const links=[...document.querySelectorAll('.tabs a,.stabs a')];
    function setOffsets(){
      const headerHeight=header?header.getBoundingClientRect().height:0;
      document.documentElement.style.setProperty('--site-header-height',headerHeight+'px');
      document.documentElement.style.setProperty('--site-anchor-offset',(headerHeight+bar.offsetHeight+16)+'px');
    }
    function spy(){
      const headerHeight=header?header.getBoundingClientRect().height:0;
      bar.classList.toggle('show',ht.getBoundingClientRect().bottom<headerHeight+20);
      let cur='profil';
      const activeOffset=headerHeight+(bar.classList.contains('show')?bar.offsetHeight:0)+20;
      ids.forEach(id=>{const el=document.getElementById(id); if(el&&el.getBoundingClientRect().top<=activeOffset) cur=id});
      links.forEach(a=>a.classList.toggle('on',a.getAttribute('href')==='#'+cur));
      const h=document.documentElement.scrollHeight-innerHeight;
      prog.style.width=(h>0?scrollY/h*100:0)+'%';
    }
    addEventListener('scroll',spy,{passive:true});
    addEventListener('resize',()=>{setOffsets();spy()});
    setOffsets(); spy();
  })();

  // Kartu nilai inti: tilt 3D mengikuti kursor
  (function(){
    document.querySelectorAll('.vcard').forEach(card=>{
      const inner=card.querySelector('.vc-in'); if(!inner) return;
      card.addEventListener('pointermove',e=>{
        const r=card.getBoundingClientRect(),x=(e.clientX-r.left)/r.width,y=(e.clientY-r.top)/r.height;
        inner.style.setProperty('--ry',((x-.5)*18)+'deg');
        inner.style.setProperty('--rx',(-(y-.5)*14)+'deg');
        inner.style.setProperty('--mx',(x*100)+'%');
        inner.style.setProperty('--my',(y*100)+'%');
        inner.style.setProperty('--px',((x-.5)*2).toFixed(3));
        inner.style.setProperty('--py',((y-.5)*2).toFixed(3));
      });
      card.addEventListener('pointerleave',()=>{
        ['--rx','--ry','--px','--py'].forEach(p=>inner.style.setProperty(p,'0'));
        inner.style.setProperty('--rx','0deg');inner.style.setProperty('--ry','0deg');
      });
    });
  })();

  // Kartu mentor: tilt 3D mengikuti posisi kursor
  (function(){
    if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    document.querySelectorAll('.mentor-card').forEach(card=>{
      card.addEventListener('pointermove',e=>{
        if(e.pointerType==='touch') return;
        const r=card.getBoundingClientRect();
        const x=(e.clientX-r.left)/r.width-.5;
        const y=(e.clientY-r.top)/r.height-.5;
        card.style.setProperty('--tilt-x',(-y*10).toFixed(2)+'deg');
        card.style.setProperty('--tilt-y',(x*12).toFixed(2)+'deg');
      });
      card.addEventListener('pointerleave',()=>{
        card.style.setProperty('--tilt-x','0deg');
        card.style.setProperty('--tilt-y','0deg');
      });
    });
  })();

  // Keunggulan: lingkaran (bisa diputar/diklik) terhubung ke kartu bertumpuk
  (function(){
    const stack=document.getElementById('advantage-stack');
    const orbit=document.getElementById('advantage-orbit');
    const rotor=document.getElementById('orb-rotor');
    if(!stack||!orbit||!rotor) return;
    const cards=[...stack.querySelectorAll('.advantage-card')];
    const nodes=[...rotor.querySelectorAll('.orbit-node')];
    const n=cards.length;
    let k=0,timer,dragged=false;
    const mod=(a,b)=>((a%b)+b)%b;
    function render(){
      const a=mod(k,n);
      cards.forEach((card,i)=>{
        const p=mod(i-a,n);
        card.classList.remove('a0','a1','a2','out');
        card.classList.add('a'+p);
        card.setAttribute('aria-pressed',String(p===0));
      });
      rotor.style.setProperty('--rot',(-k*60)+'deg');
      nodes.forEach(node=>node.classList.toggle('on',+node.dataset.node===mod(k,6)));
    }
    function move(delta){k+=delta;render();start()}
    const next=()=>{
      cards[mod(k,n)].classList.add('out');
      setTimeout(()=>{k+=1;render()},350);
    };
    function start(){clearInterval(timer);timer=setInterval(next,4600)}
    nodes.forEach(node=>node.addEventListener('click',()=>{
      if(dragged) return;
      const target=+node.dataset.node;
      const d=mod(target-mod(k,6)+3,6)-3;
      if(d!==0) move(d);
    }));
    cards.forEach((card,i)=>card.addEventListener('click',()=>{
      const p=mod(i-mod(k,n),n);
      if(p===0) return move(1);
      move(p===1?1:-1);
    }));
    let startY=null;
    orbit.addEventListener('pointerdown',e=>{startY=e.clientY;dragged=false});
    addEventListener('pointerup',e=>{
      if(startY===null) return;
      const dy=e.clientY-startY; startY=null;
      if(Math.abs(dy)>30){dragged=true;move(dy<0?1:-1);setTimeout(()=>dragged=false,60)}
    });
    [stack,orbit].forEach(el=>{
      el.addEventListener('mouseenter',()=>clearInterval(timer));
      el.addEventListener('mouseleave',start);
    });
    stack.addEventListener('focusin',()=>clearInterval(timer));
    stack.addEventListener('focusout',e=>{if(!stack.contains(e.relatedTarget)) start()});
    render();
    start();
  })();

</script>
</body>
</html>
