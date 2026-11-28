<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Equityworld Futures Indonesia</title>
@vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
@include('user.partials.navigation-transitions')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>
<body class="font-sans antialiased">

@include('user.partials.header')

<!-- HERO -->
<section class="hero" id="beranda">
  <div class="container">
    <div class="hero-grid">

      <div class="hero-copy">
        <span class="eyebrow hero-badge hr" style="--d:0ms"><i></i>WEBSITE RESMI GALERI MAGANG</span>

        <h1 aria-label="Langkah Kecil Membawa Pengalaman Berharga">
          <span class="line hr" style="--d:120ms">
            <span class="script-title" id="typeTitle">Langkah Kecil</span>
          </span>
          <span class="line plain hr" style="--d:260ms">Membawa</span>
          <span class="line rotator hr" style="--d:400ms" aria-hidden="true"><span class="rotator-word" id="rotatorWord"><span class="grad">Pengalaman Berharga</span></span></span>
        </h1>

        <p class="lead hr" style="--d:540ms">Berkembang di lingkungan kerja yang suportif, belajar langsung dari mentor berpengalaman, dan temukan peluangmu melalui program magang maupun posisi staf di PT Equityworld Futures.</p>

        <div class="hero-cta hr" style="--d:680ms">
          <a href="{{ route('daftar', ['next' => '/#karir']) }}" class="btn btn-primary">Daftar Sekarang</a>
          <a href="#regulator" class="btn btn-outline">Kenali Perusahaan</a>
        </div>

        <div class="hero-note hr" style="--d:800ms">
          <span>Proses 100% online</span>
          <span>Dibimbing oleh mentor profesional</span>
          <span>Sertifikat magang</span>
        </div>
      </div>

      <div class="hero-photo hr" style="--d:300ms">
        <div class="frame">
          <img src="/image/magang%201.jpeg?v={{ filemtime(public_path('image/magang 1.jpeg')) }}" alt="Tim dan peserta magang PT Equityworld Futures" onerror="this.style.visibility='hidden'">
        </div>
        <div class="hero-chip hero-chip--b">
          <div class="ci"><img src="/image/logo%20galeri%20magang.png" alt="Logo Galeri Magang"></div>
          <div><b>Galeri Magang</b><span>Website resmi</span></div>
        </div>
      </div>

    </div>

    <div class="trust-row" id="tentang" role="group" aria-label="Topik tentang Equityworld Futures">
      <button class="trust-tab" type="button" data-story="partner" aria-label="Kenali perusahaan">
        <img class="trust-tab-image" data-story-image alt="" aria-hidden="true">
        <b>Kenali Kami</b><span>TENTANG PERUSAHAAN</span>
      </button>
      <button class="trust-tab" type="button" data-story="opportunity" aria-label="Peluang bergabung sebagai peserta magang atau staf">
        <img class="trust-tab-image" data-story-image alt="" aria-hidden="true">
        <b>Peluang Bergabung</b><span>MAGANG &amp; STAF</span>
      </button>
      <button class="trust-tab" type="button" data-story="education" aria-label="Cerita kegiatan dan aktivitas tim">
        <img class="trust-tab-image" data-story-image alt="" aria-hidden="true">
        <b>Cerita Kegiatan</b><span>AKTIVITAS TIM KAMI</span>
      </button>
      <button class="trust-tab" type="button" data-story="integrity" aria-label="Belajar dan berkembang bersama">
        <img class="trust-tab-image" data-story-image alt="" aria-hidden="true">
        <b>Tumbuh Bersama</b><span>BELAJAR &amp; BERKEMBANG</span>
      </button>
    </div>
  </div>
</section>

<!-- PENGENALAN PERUSAHAAN -->
<section class="company-intro" id="tentang-perusahaan" aria-labelledby="company-intro-title">
  <div class="container">
    <div class="company-intro-grid">
      <div class="company-intro-photo">
        <img src="/image/lobi%20ewf.jpeg?v={{ filemtime(public_path('image/lobi ewf.jpeg')) }}" alt="Lobi kantor PT Equityworld Futures" loading="lazy">
      </div>
      <div class="company-intro-copy">
        <span class="eyebrow"><i></i>TENTANG PERUSAHAAN</span>
        <h2 id="company-intro-title">Mengenal Equityworld Futures</h2>
        <p>PT Equityworld Futures merupakan perusahaan yang bergerak di bidang perdagangan komoditas dan instrumen keuangan internasional. Perusahaan menyediakan fasilitas berbasis teknologi untuk mendukung aktivitas perdagangan di pasar global, serta edukasi dan informasi tentang perkembangan ekonomi dunia dan pergerakan pasar keuangan. Dengan mengutamakan profesionalisme, pelayanan, dan pengembangan pengetahuan, EWF berkomitmen memperluas wawasan mengenai perdagangan internasional.</p>
        <ul class="intro-points">
          <li>Izin Bappebti</li>
          <li>Persetujuan OJK</li>
          <li>Peserta SPA Bank Indonesia</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- REGULATOR -->
<section class="sec" id="regulator">
  <div class="container center">
    <h2 class="h2" style="max-width:720px;margin:0 auto">Diawasi Otoritas Resmi Republik Indonesia</h2>
    <p class="sub">Seluruh aktivitas operasional dan transaksi PT Equityworld Futures beroperasi di bawah payung hukum Perdagangan RI.</p>
    <div class="reg-grid">
      <div class="reg-card">
        <img data-img="bappepti" alt="Bappebti">
        <div class="logo"><img data-img="logo bappepti" alt="Logo Bappebti"></div>
        <div class="txt"><p><b>Izin Pialang Berjangka No. 988/BAPPEBTI/SI/IX/2004</b> di bawah Kementerian Perdagangan RI.</p></div>
      </div>
      <div class="reg-card">
        <img data-img="ojk" alt="OJK">
        <div class="logo"><img data-img="logo ojk" alt="Logo OJK"></div>
        <div class="txt"><p><b>Persetujuan Pialang sebagai Pelaku Derivatif Keuangan</b> sesuai OJK, berdasarkan surat nomor S-27/2022 (PM 02/2020).</p></div>
      </div>
      <div class="reg-card reg-card--bi">
        <img data-img="bi" alt="Bank Indonesia">
        <div class="logo"><img data-img="logo bi" alt="Logo BI"></div>
        <div class="txt"><p><b>Penyelenggara sebagai Pialang Peserta SPA</b> Penempatan PUVA berdasarkan surat Bank Indonesia nomor 27/1/BI/DPPK/GBS/JB.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- JARINGAN KANTOR -->
  <section class="office-section">
    <div class="container center">
      <span class="eyebrow"><i></i>JARINGAN KANTOR NASIONAL</span>
      <h2 class="h2" style="margin-top:14px">Jaringan Kantor Equityworld Futures</h2>
      <p class="sub">Lihat jaringan kantor cabang kami di berbagai kota sebagai gambaran kehadiran dan jangkauan operasional PT Equityworld Futures. Kenali perusahaan lebih dekat sebelum mendaftar magang atau posisi staff.</p>
      <div class="office-featured-wrap">
        <div class="office office--featured"><img data-img="trillium" alt="Gedung Trillium Office &amp; Residence, kantor Surabaya" loading="lazy"><div class="c"><b>Surabaya – Trillium</b><div class="office-featured-address"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><p>Trillium Office &amp; Residence, Jl. Pemuda No. 118–136, Embong Kaliasin, Kec. Genteng, Surabaya 60271, Indonesia</p></div><p class="office-featured-note">Kantor Surabaya Equityworld Futures berlokasi di Trillium Office &amp; Residence dan menjadi bagian dari jaringan kantor nasional perusahaan.</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Trillium Office & Residence, Jl. Pemuda No. 118-136, Embong Kaliasin, Kec. Genteng, Surabaya 60271, Indonesia') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Trillium Office & Residence, Jl. Pemuda No. 118-136, Embong Kaliasin, Kec. Genteng, Surabaya 60271, Indonesia') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Surabaya – Trillium di Google Maps">›</a></div></div></div>
      </div>
      <div class="office-grid">
        <div class="office"><img data-img="cabang jkt ssc" alt="Gedung kantor Equityworld Futures Jakarta – SSC"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><div class="c"><b>Jakarta – SSC</b><p>Sahid Sudirman Center Lt. 9 Unit C, D, H dan Lt. 10 Unit A,B. Jend. Sudirman No. 86, Jakarta Pusat 10220</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Sahid Sudirman Center Lt. 9 Unit C, D, H dan Lt. 10 Unit A, B, Jend. Sudirman No. 86, Jakarta Pusat 10220') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Sahid Sudirman Center Lt. 9 Unit C, D, H dan Lt. 10 Unit A, B, Jend. Sudirman No. 86, Jakarta Pusat 10220') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Jakarta – SSC di Google Maps">›</a></div></div></div>
        <div class="office"><img data-img="ewf cyber 2" alt="Gedung Cyber 2, kantor Equityworld Futures Jakarta"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><div class="c"><b>Jakarta – Cyber 2</b><p>Gedung Cyber 2 Lt. 4, 5, 6, Jl. HR. Rasuna Said Blok X-5 No. 13, Jakarta Selatan 12950</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Gedung Cyber 2 Lt. 4, 5, 6, Jl. HR. Rasuna Said Blok X-5 No. 13, Jakarta Selatan 12950') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Gedung Cyber 2 Lt. 4, 5, 6, Jl. HR. Rasuna Said Blok X-5 No. 13, Jakarta Selatan 12950') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Jakarta – Cyber 2 di Google Maps">›</a></div></div></div>
        <div class="office office--semarang"><img data-img="ewf semarang" alt="Kantor Equityworld Futures Semarang"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><div class="c"><b>Semarang</b><p>Ruko Pemuda Plaza, Blok A Kav. 12 – 13, Jl. Pemuda No. 150, Semarang 50132</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Ruko Pemuda Plaza, Blok A Kav. 12-13, Jl. Pemuda No. 150, Semarang 50132') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Ruko Pemuda Plaza, Blok A Kav. 12-13, Jl. Pemuda No. 150, Semarang 50132') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Semarang di Google Maps">›</a></div></div></div>
        <div class="office"><img data-img="ewf praxis" alt="Gedung Praxis, kantor Equityworld Futures Surabaya"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><div class="c"><b>Surabaya – Praxis</b><p>Gedung Praxis Lt. 3, Jl. Basuki Rahmat No. 33-35, Kec. Genteng, Kota Surabaya, Jawa Timur 60262</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Gedung Praxis Lt. 3, Jl. Basuki Rahmat No. 33-35, Kec. Genteng, Kota Surabaya, Jawa Timur 60262') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Gedung Praxis Lt. 3, Jl. Basuki Rahmat No. 33-35, Kec. Genteng, Kota Surabaya, Jawa Timur 60262') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Surabaya – Praxis di Google Maps">›</a></div></div></div>
        <div class="office"><img data-img="ewf cirebon" alt="Kantor Equityworld Futures Cirebon"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><div class="c"><b>Cirebon</b><p>Komplek Ruko Pekiringan Blok 17, Jl. Pekiringan No. 14 – 15, Cirebon 45132</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Komplek Ruko Pekiringan Blok 17, Jl. Pekiringan No. 14-15, Cirebon 45132') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Komplek Ruko Pekiringan Blok 17, Jl. Pekiringan No. 14-15, Cirebon 45132') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Cirebon di Google Maps">›</a></div></div></div>
        <div class="office"><img data-img="ewf manado" alt="Kantor Equityworld Futures Manado"><div class="pin" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><div class="c"><b>Manado</b><p>Komplek Ruko Mega Style Blok 10 No. 34 – 35, Manado 95125</p><div class="acts"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Komplek Ruko Mega Style Blok 10 No. 34-35, Manado 95125') }}" target="_blank" rel="noopener noreferrer">Lihat di Peta</a><a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('Komplek Ruko Mega Style Blok 10 No. 34-35, Manado 95125') }}" target="_blank" rel="noopener noreferrer" class="arrow" aria-label="Buka lokasi Manado di Google Maps">›</a></div></div></div>
      </div>
    </div>
  </section>

<!-- GALERI MAGANG -->
<section class="gallery-intro" id="galeri-magang" aria-labelledby="gallery-intro-title">
  <div class="container center">
    <span class="eyebrow"><i></i>GALERI MAGANG</span>
    <h2 id="gallery-intro-title" class="gallery-title">
      <span class="gallery-title-line">Ruang untuk Belajar</span>
      <span class="gallery-title-line gallery-title-line--accent">Pengalaman yang Nyata</span>
    </h2>
    <div class="gallery-carousel" aria-label="Galeri kegiatan magang">
      <div class="gallery-next-preview" aria-hidden="true"><img src="/image/magang%208.png?v={{ filemtime(public_path('image/magang 8.png')) }}" alt=""></div>
      <div class="gallery-viewport" tabindex="0" aria-live="polite" aria-label="Foto 1 dari 4. Klik sisi kanan foto atau klik kanan untuk melihat foto berikutnya">
        <div class="gallery-track">
          <figure class="gallery-slide"><img src="/image/magang%209.jpeg?v={{ filemtime(public_path('image/magang 9.jpeg')) }}" alt="Kegiatan peserta Galeri Magang" loading="eager" draggable="false"><figcaption>Belajar dan bertumbuh bersama</figcaption></figure>
          <figure class="gallery-slide"><img src="/image/magang%208.png?v={{ filemtime(public_path('image/magang 8.png')) }}" alt="Suasana kegiatan Galeri Magang" loading="lazy" draggable="false"><figcaption>Mengenal pengalaman di dunia kerja</figcaption></figure>
          <figure class="gallery-slide"><img src="/image/magang%2010.jpeg?v={{ filemtime(public_path('image/magang 10.jpeg')) }}" alt="Aktivitas peserta Galeri Magang" loading="lazy" draggable="false"><figcaption>Mengembangkan potensi dan keterampilan</figcaption></figure>
          <figure class="gallery-slide"><img src="/image/magang%206.jpeg?v={{ filemtime(public_path('image/magang 6.jpeg')) }}" alt="Kebersamaan dalam kegiatan Galeri Magang" loading="lazy" draggable="false"><figcaption>Mempersiapkan langkah karier berikutnya</figcaption></figure>
        </div>
        <div class="gallery-flip-sheet" aria-hidden="true"><img src="/image/magang%209.jpeg?v={{ filemtime(public_path('image/magang 9.jpeg')) }}" alt=""></div>
      </div>
      <div class="gallery-dots" role="group" aria-label="Pilih foto galeri">
        <button class="gallery-dot is-active" type="button" aria-label="Tampilkan foto 1" aria-current="true"></button>
        <button class="gallery-dot" type="button" aria-label="Tampilkan foto 2" aria-current="false"></button>
        <button class="gallery-dot" type="button" aria-label="Tampilkan foto 3" aria-current="false"></button>
        <button class="gallery-dot" type="button" aria-label="Tampilkan foto 4" aria-current="false"></button>
      </div>
    </div>
    <p class="gallery-description">Program Galeri Magang di PT Equityworld Futures memberi mahasiswa dan pelajar kesempatan mengenal dunia profesional melalui pengalaman dan pembelajaran langsung di lingkungan kerja. Peserta didorong mengembangkan keterampilan, kedisiplinan, tanggung jawab, dan kepercayaan diri, sekaligus membangun relasi sebagai bekal untuk menyiapkan karier berikutnya.</p>
  </div>
</section>

<!-- KARIR -->
<section class="karir" id="karir">
  <div class="container">
    <div class="center">
      <span class="eyebrow"><i></i>PROGRAM PENGEMBANGAN TALENTA</span>
      <span class="meta" style="margin-left:10px">• &nbsp;Karyawan, Mahasiswa, dan Fresh Graduate</span>
      <h2 class="h2" style="font-size:46px;margin-top:14px">Wadah Eksplorasi Karier</h2>
      <p class="sub" style="max-width:700px">PT Equityworld Futures membuka kesempatan berkembang untuk karyawan, magang bagi mahasiswa aktif dan lulusan baru. Daftar secara online, ikuti bimbingan profesional, dan pantau status kelolosanmu secara transparan.</p>
    </div>
    @guest
    <div class="karir-more"><a href="{{ route('karir') }}#lowongan" class="btn btn-soft">Informasi Detail</a></div>
    @endguest
    @if(auth()->guest() || $careerRegistration)
    <div class="karir-grid {{ $careerRegistration ? 'has-registration' : '' }}">
      @guest
      <div class="card-l">
        <div class="top"><span class="badge">GELOMBANG 2026 DIBUKA</span><span class="batch">Batch #1 Tahun 2026</span></div>
        <h4>Formulir Pendaftaran Karir Online</h4>
        <p>Dapatkan pengalaman praktis industri, kurikulum terakreditasi BAPPEBTI, serta portofolio nyata yang bernilai tinggi.</p>
        <div class="perks">
          <div class="perk"><div class="i">👤</div><div><b>Mentorship Profesional</b><span>Dibimbing langsung oleh mentor berpengalaman.</span></div></div>
          <div class="perk"><div class="i">🔑</div><div><b>Pengalaman Praktis</b><span>Terlibat langsung dalam aktivitas dan proyek kerja nyata.</span></div></div>
          <div class="perk"><div class="i">🎓</div><div><b>Sertifikat</b><span>Sertifikat sebagai bukti penyelesaian program.</span></div></div>
          <div class="perk"><div class="i">🚀</div><div><b>Jalur Rekrutmen Cepat</b><span>Peluang kerja langsung bagi lulusan terbaik.</span></div></div>
        </div>
        <div class="cta"><small>✓ Proses 100% Online • tinggal Berkas</small><a href="{{ route('daftar', ['next' => '/#karir']) }}" class="btn btn-primary">Daftar Karir Sekarang</a></div>
      </div>
      @endguest
      @if ($careerRegistration)
      <div class="card-r">
        <div class="top"><span class="a">PORTAL INTERAKTIF</span><span class="b">● Live Sync</span></div>
        <h4>Lacak Status Pendaftaran</h4>
        <p>Status pendaftaran karir Anda saat ini. Perkembangan seleksi akan ditampilkan di akun ini.</p>
        <div class="result">
          <div class="n"><div><b>{{ $careerRegistration->full_name }}</b><br><small>{{ $careerRegistration->email }}</small></div><em>{{ \Illuminate\Support\Str::headline($careerRegistration->status ?: 'Menunggu Verifikasi') }}</em></div>
          @php
            $registrationStatus = strtolower(str_replace([' ', '-'], '_', (string) $careerRegistration->status));
            $registrationStage = match (true) {
              in_array($registrationStatus, ['approved', 'diterima', 'lolos', 'accepted', 'onboarding'], true) => 3,
              in_array($registrationStatus, ['interview', 'wawancara'], true) => 2,
              in_array($registrationStatus, ['reviewed', 'verified', 'verifikasi', 'verification', 'in_review'], true) => 1,
              default => 0,
            };
            $registrationSteps = ['Berkas Masuk', 'Verifikasi', 'Wawancara', 'Onboarding'];
          @endphp
          <div class="steps">
            @foreach ($registrationSteps as $stepIndex => $step)
              <div class="{{ $stepIndex < $registrationStage ? 'done' : ($stepIndex === $registrationStage ? 'now' : '') }}"><i>{{ $stepIndex < $registrationStage ? '✓' : ($stepIndex === $registrationStage ? '◔' : '○') }}</i><b>{{ $step }}</b>{{ $stepIndex < $registrationStage ? 'Selesai' : ($stepIndex === $registrationStage ? 'Proses' : 'Menunggu') }}</div>
            @endforeach
          </div>
        </div>
        <div class="note">ⓘ Hanya dilakukan melalui kanal resmi Equityworld Futures.</div>
      </div>
      @endif
    </div>
    @endif
  </div>
</section>

<!-- FOOTER -->
<div class="tail">




  @include('user.partials.footer')
</div>

<script>
/* ===== KONFIGURASI GAMBAR ===== */
const IMG_BASE = '/image/';
const IMG_EXTENSIONS = {
  'ewf cyber 2': '.jpg',
  'bappepti': '.jpg',
  'ojk': '.jpg',
  'bi': '.jpg',
  'ewf semarang': '.png',
  'ewf praxis': '.jpg',
  'ewf cirebon': '.jpeg',
  'ewf manado': '.jpeg',
  'trillium': '.png',
  'magang 7': '.jpeg',
  'kegiatan 3': '.jpeg',
  'magang 12': '.jpeg',
};
function imageUrlFor(imageName){
  const extension = IMG_EXTENSIONS[imageName] || '.png';
  const version = imageName === 'cabang jkt ssc'
    ? `?v={{ filemtime(public_path('image/cabang jkt ssc.png')) }}`
    : imageName === 'ewf semarang'
      ? `?v={{ filemtime(public_path('image/ewf Semarang.png')) }}`
    : '';
  return IMG_BASE + encodeURIComponent(imageName) + extension + version;
}
document.querySelectorAll('img[data-img]').forEach(el => {
  const imageName = el.dataset.img;
  el.src = imageUrlFor(imageName);
  el.addEventListener('error', () => { el.style.visibility = 'hidden'; });
});

/* ===== JUDUL "LANGKAH KECIL": DIKETIK PER HURUF ===== */
(function(){
  const el = document.getElementById('typeTitle');
  if (!el) return;
  const text = el.textContent.trim();
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  el.textContent = '';
  const chars = Array.from(text).map(ch => {
    const s = document.createElement('span');
    s.className = 'tw-char';
    s.textContent = ch;
    el.appendChild(s);
    return s;
  });
  const caret = document.createElement('span');
  caret.className = 'tw-caret';
  el.appendChild(caret);

  let i = 0;
  const type = () => {
    if (i < chars.length) {
      chars[i].classList.add('on');
      chars[i].after(caret);
      i++;
      setTimeout(type, 95 + Math.random() * 70);
    } else {
      caret.classList.add('done');
    }
  };
  setTimeout(type, 900);
})();

/* ===== KATA BERGANTI DI JUDUL HERO ===== */
(function(){
  const wrap = document.getElementById('rotatorWord');
  if (!wrap) return;
  const label = wrap.querySelector('.grad');
  const words = ['Pengalaman Berharga', 'Relasi Profesional', 'Skill Industri', 'Peluang Karir'];
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  let i = 0;
  setTimeout(() => {
    setInterval(() => {
      wrap.classList.remove('is-in');
      wrap.classList.add('is-out');
      setTimeout(() => {
        i = (i + 1) % words.length;
        label.textContent = words[i];
        wrap.classList.remove('is-out');
        void wrap.offsetWidth;
        wrap.classList.add('is-in');
      }, 330);
    }, 3200);
  }, 2400);
})();

/* ===== TOPIK BERGAMBAR ===== */
const trustStories = {
  partner: {
    image: 'trillium',
    alt: 'Gedung Trillium Office & Residence',
    eyebrow: 'Mitra profesional',
    title: 'Mitra Profesional dalam Perdagangan Berjangka',
  },
  opportunity: {
    image: 'magang 7',
    alt: 'Kegiatan peserta magang',
    eyebrow: 'Mengenal peluang',
    title: 'Pahami Peluang Perdagangan Berjangka',
  },
  education: {
    image: 'kegiatan 3',
    alt: 'Kegiatan dan aktivitas tim',
    eyebrow: 'Cerita kegiatan',
    title: 'Cerita Kegiatan dan Aktivitas Tim',
  },
  integrity: {
    image: 'magang 12',
    alt: 'Kegiatan belajar dan berkembang bersama',
    eyebrow: 'Integritas dan profesionalisme',
    title: 'Menjunjung Integritas dalam Setiap Layanan',
  },
};
const trustButtons = Array.from(document.querySelectorAll('.trust-tab'));
let activeStoryIndex = 0;
function showTrustStory(index){
  activeStoryIndex = index;
  trustButtons.forEach((button, buttonIndex) => {
    const active = buttonIndex === index;
    button.classList.toggle('is-active', active);
    button.setAttribute('aria-pressed', String(active));
  });
}
trustButtons.forEach((button, index) => {
  const story = trustStories[button.dataset.story];
  const image = button.querySelector('[data-story-image]');
  image.src = imageUrlFor(story.image);
  image.addEventListener('error', () => {
    image.alt = `Foto untuk topik ${story.eyebrow} tidak dapat dimuat`;
  });
  button.addEventListener('click', () => showTrustStory(index));
});
showTrustStory(0);
if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && trustButtons.length > 1) {
  window.setInterval(() => showTrustStory((activeStoryIndex + 1) % trustButtons.length), 3000);
}

/* ===== GALERI MAGANG: BUKA HALAMAN FOTO ===== */
const galleryViewport = document.querySelector('.gallery-viewport');
if (galleryViewport) {
  const galleryTrack = galleryViewport.querySelector('.gallery-track');
  const gallerySlides = Array.from(galleryTrack.querySelectorAll('.gallery-slide'));
  const galleryDots = Array.from(document.querySelectorAll('.gallery-dot'));
  const previewImage = document.querySelector('.gallery-next-preview img');
  const flipSheet = galleryViewport.querySelector('.gallery-flip-sheet');
  const flipSheetImage = flipSheet.querySelector('img');
  let galleryIndex = 0;
  let isPageTurning = false;
  let turnUnlockTimer = null;

  const showGalleryPhoto = (index, direction = 'next') => {
    if (isPageTurning || gallerySlides.length < 2) return;
    const nextIndex = (index + gallerySlides.length) % gallerySlides.length;
    if (nextIndex === galleryIndex) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!reduceMotion) {
      flipSheetImage.src = gallerySlides[galleryIndex].querySelector('img').src;
      flipSheet.classList.remove('is-turning-next', 'is-turning-previous');
      void flipSheet.offsetWidth;
      isPageTurning = true;
    }

    galleryIndex = nextIndex;
    galleryTrack.style.transform = `translateX(-${galleryIndex * 100}%)`;
    previewImage.src = gallerySlides[(galleryIndex + 1) % gallerySlides.length].querySelector('img').src;
    galleryDots.forEach((dot, dotIndex) => {
      const active = dotIndex === galleryIndex;
      dot.classList.toggle('is-active', active);
      dot.setAttribute('aria-current', String(active));
    });
    galleryViewport.setAttribute('aria-label', `Foto ${galleryIndex + 1} dari ${gallerySlides.length}. Klik sisi kanan foto untuk lanjut atau sisi kiri untuk kembali`);

    if (!reduceMotion) {
      flipSheet.classList.add(direction === 'previous' ? 'is-turning-previous' : 'is-turning-next');
      const finishPageTurn = () => {
        flipSheet.classList.remove('is-turning-next', 'is-turning-previous');
        isPageTurning = false;
        window.clearTimeout(turnUnlockTimer);
      };
      flipSheet.addEventListener('animationend', finishPageTurn, {once:true});
      turnUnlockTimer = window.setTimeout(finishPageTurn, 850);
    }
  };

  galleryDots.forEach((dot, index) => dot.addEventListener('click', () => {
    showGalleryPhoto(index, index < galleryIndex ? 'previous' : 'next');
  }));
  galleryViewport.addEventListener('click', event => {
    const bounds = galleryViewport.getBoundingClientRect();
    if (event.clientX >= bounds.left + bounds.width / 2) showGalleryPhoto(galleryIndex + 1, 'next');
    else showGalleryPhoto(galleryIndex - 1, 'previous');
  });
  galleryViewport.addEventListener('contextmenu', event => {
    event.preventDefault();
    showGalleryPhoto(galleryIndex + 1, 'next');
  });
  galleryViewport.addEventListener('keydown', event => {
    if (event.key === 'ArrowLeft') { event.preventDefault(); showGalleryPhoto(galleryIndex - 1, 'previous'); }
    if (event.key === 'ArrowRight') { event.preventDefault(); showGalleryPhoto(galleryIndex + 1, 'next'); }
  });
}

/* ===== ANIMASI SAAT BAGIAN MASUK KE LAYAR ===== */
if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  const revealTargets = document.querySelectorAll(
    '.sec .container>.center,.karir .center,.karir-more,.karir-grid>*,' +
    '.office-featured-wrap,.office-grid>*,.reg-card,.mem-card,.segregated'
  );
  revealTargets.forEach((element, index) => {
    element.dataset.reveal = '';
    element.style.setProperty('--reveal-delay', `${(index % 3) * 75}ms`);
  });
  document.documentElement.classList.add('motion-ready');
  const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, {threshold:0.14,rootMargin:'0px 0px -24px 0px'});
  revealTargets.forEach(element => revealObserver.observe(element));
}

</script>
</body>
</html>
