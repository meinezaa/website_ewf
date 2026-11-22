<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Kegiatan Galeri Magang Equityworld Futures.">
  <title>Kegiatan | Galeri Magang</title>
  @vite(['resources/css/app.css', 'resources/css/kegiatan.css', 'resources/js/app.js'])
  @include('partials.navigation-transitions')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
</head>
<body class="font-sans antialiased">
  @include('partials.header')

  <main aria-label="Konten kegiatan">
    <div class="kg-page" id="kgPage">

      <!-- ================= HERO ================= -->
      <section class="kg-hero" aria-labelledby="kgTitle">
        <div class="kg-wrap">
          <div class="kg-hero-grid">

            <div class="kg-hero-copy">
              <span class="kg-eyebrow"><i></i>KEGIATAN GALERI MAGANG</span>

              <h1 class="kg-title" id="kgTitle" aria-label="Cerita di Balik Setiap Kegiatan">
                <span class="kg-line" aria-hidden="true"><span class="kg-word" style="--i:0">Cerita</span> <span class="kg-word" style="--i:1">di</span> <span class="kg-word" style="--i:2">Balik</span></span>
                <span class="kg-line" aria-hidden="true"><span class="kg-word" style="--i:3">Setiap</span> <span class="kg-script" style="--i:4">Kegiatan</span></span>
              </h1>

              <p class="kg-lead">Dari orientasi hari pertama, sesi belajar bersama mentor, sampai momen kebersamaan tim. Lihat langsung bagaimana peserta Galeri Magang tumbuh di PT Equityworld Futures.</p>

              <div class="kg-hero-cta">
                <a href="#kg-galeri" class="kg-btn kg-btn--primary">Lihat Galeri</a>
                <a href="#kg-jejak" class="kg-btn kg-btn--ghost">Jejak Kegiatan</a>
              </div>

              <div class="kg-stats">
                <div class="kg-stat"><b><span data-count="25">1</span><em>+</em></b><span>Kegiatan</span></div>
                <div class="kg-stat"><b><span data-count="35">1</span><em>+</em></b><span>Peserta</span></div>
                <div class="kg-stat"><b><span data-count="7">1</span></b><span>Kota Kantor</span></div>
              </div>
            </div>

            <div class="kg-collage" id="kgCollage" aria-hidden="true">
              <div class="kg-photo kg-c1">
                <img src="{{ asset('image/kegiatan 3.jpeg') }}" alt="">
              </div>
              <div class="kg-photo kg-c2">
                <img src="{{ asset('image/magang 11.jpeg') }}" alt="">
              </div>
              <div class="kg-photo kg-c3">
                <img src="{{ asset('image/magang 5.jpeg') }}" alt="">
              </div>
              <div class="kg-chip kg-chip--a"><div class="kg-ci">📸</div><div><b>Momen Terbaik</b><span>Dokumentasi kegiatan</span></div></div>
              <div class="kg-chip kg-chip--b"><div class="kg-ci">🤝</div><div><b>Belajar Bersama</b><span>Mentor &amp; peserta</span></div></div>
            </div>

          </div>
        </div>
      </section>

      <!-- ================= MARQUEE ================= -->
      <div class="kg-marquee" aria-hidden="true">
        <div class="kg-marquee-track">
          <span>Orientasi</span><span>Mentoring</span><span>Pelatihan</span><span>Gathering</span><span>Presentasi</span><span>Sertifikasi</span>
          <span>Orientasi</span><span>Mentoring</span><span>Pelatihan</span><span>Gathering</span><span>Presentasi</span><span>Sertifikasi</span>
        </div>
      </div>

      <!-- ================= KEGIATAN UNGGULAN ================= -->
      <section class="kg-section" aria-labelledby="kgFeatTitle">
        <div class="kg-wrap">
          <div class="kg-head" data-kg-reveal>
            <span class="kg-eyebrow"><i></i>SOROTAN</span>
            <h2 class="kg-h2" id="kgFeatTitle">Kegiatan <span class="kg-script">Unggulan</span></h2>
            <p class="kg-sub">Momen yang paling berkesan dari program magang terbaru.</p>
          </div>

          <article class="kg-feature" data-kg-reveal="zoom">
            <div class="kg-photo kg-feature-gallery" id="kgFeatureGallery">
              <button class="kg-feature-switch" id="kgFeatureSwitch" type="button" aria-label="Tampilkan foto kedua" aria-pressed="false">
                <img class="kg-feature-image kg-feature-back" src="{{ asset('image/magang 13.jpeg') }}" alt="Peserta magang bersama pembimbing, foto kedua">
                <img class="kg-feature-image kg-feature-front" src="{{ asset('image/magang 7.jpeg') }}" alt="Peserta magang bersama pembimbing di kantor Surabaya Trillium">
              </button>
            </div>
            <div class="kg-feature-body">
              <span class="kg-tag"><i aria-hidden="true"></i>Terbaru</span>
              <h3>Orientasi &amp; Penyambutan Peserta Magang 2026</h3>
              <p>Momen kebersamaan 24 peserta magang bersama pembimbing di kantor Surabaya Trillium.</p>
              <ul class="kg-meta">
                <li>📅 Agustus 2026</li>
                <li>📍 Surabaya Trillium</li>
                <li>👥 24 Peserta</li>
              </ul>
              <a href="#kg-galeri" class="kg-btn kg-btn--primary">Lihat Dokumentasi</a>
            </div>
          </article>
        </div>
      </section>

      <!-- ================= GALERI ================= -->
      <section class="kg-section" id="kg-galeri" aria-labelledby="kgGalTitle">
        <div class="kg-wrap">
          <div class="kg-head" data-kg-reveal>
            <span class="kg-eyebrow"><i></i>GALERI KEGIATAN</span>
            <h2 class="kg-h2" id="kgGalTitle">Semua <span class="kg-script">Momen</span> Kami</h2>
            <p class="kg-sub">Dari belajar dan berlatih hingga merayakan pencapaian bersama, setiap momen menjadi bagian berharga dari perjalanan kita.</p>
          </div>
          <div class="kg-grid" id="kgGrid">

            <figure class="kg-card kg-card--big" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto jalan-jalan ke PRJ bersama mentor">
              <div class="kg-photo">
                <img src="{{ asset('image/kegiatan 12.jpeg') }}" alt="Peserta magang dan mentor menikmati acara PRJ bersama">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Sehari di PRJ Bersama Mentor</b><span>Jajan, berbagi cerita, dan menikmati waktu bersama.</span></figcaption>
            </figure>

            <figure class="kg-card" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto Sesi Mentoring">
              <div class="kg-photo">
                <img src="{{ asset('image/mentoring.jpeg') }}" alt="Peserta magang mengikuti sesi mentoring bersama pembimbing">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Belajar Bersama Mentor</b><span>Berbagi ilmu, pengalaman, dan semangat untuk terus berkembang.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--wide" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto penyerahan hadiah lomba">
              <div class="kg-photo">
                <img src="{{ asset('image/kegiatan 10.jpeg') }}" alt="Peserta menerima hadiah setelah memenangkan lomba">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Apresiasi untuk Para Pemenang</b><span>Kerja keras dan semangat berkompetisi mendapat penghargaan.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--wide" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto makan bersama">
              <div class="kg-photo">
                <img src="{{ asset('image/kegiatan 9.jpeg') }}" alt="Peserta dan mentor makan bersama dalam suasana akrab">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Makan Bersama</b><span>Obrolan ringan dan tawa yang mengeratkan kebersamaan.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--wide" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto kunjungan kantor">
              <div class="kg-photo">
                <img src="{{ asset('image/kunjungan kantor.jpeg') }}" alt="Peserta berkunjung dan mengenal lingkungan kantor">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Mengenal Lebih Dekat Dunia Kerja</b><span>Kunjungan kantor menjadi ruang untuk belajar dan bertanya.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--wide" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto perayaan Hari Kemerdekaan Indonesia">
              <div class="kg-photo">
                <img src="{{ asset('image/kegiatan 6.jpeg') }}" alt="Peserta merayakan Hari Kemerdekaan Indonesia bersama">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Merayakan Hari Kemerdekaan</b><span>Semangat kebersamaan dalam balutan merah putih.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--wide" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto presentasi akhir peserta magang">
              <div class="kg-photo">
                <img src="{{ asset('image/magang 3.jpeg') }}" alt="Peserta menyampaikan presentasi akhir program magang">
              </div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Presentasi Akhir</b><span>Merangkum proses belajar dan langkah yang sudah ditempuh.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--wide" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto bermain badminton bersama">
              <div class="kg-photo"><img src="{{ asset('image/kegiatan 4.jpeg') }}" alt="Peserta bermain badminton bersama"></div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Badminton Bareng</b><span>Bergerak aktif, saling menyemangati, dan menikmati waktu bersama.</span></figcaption>
            </figure>

            <figure class="kg-card kg-card--tall" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto penyerahan hadiah lomba">
              <div class="kg-photo"><img src="{{ asset('image/kegiatan 5.jpeg') }}" alt="Peserta menerima hadiah atas pencapaian lomba"></div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Apresiasi Prestasi</b><span>Setiap usaha layak dirayakan.</span></figcaption>
            </figure>

            <figure class="kg-card" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto kebersamaan peserta magang">
              <div class="kg-photo"><img src="{{ asset('image/kegiatan 7.jpeg') }}" alt="Peserta magang berkumpul dalam momen kebersamaan"></div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Momen Kebersamaan</b><span>Kenangan kecil yang terasa berarti saat dijalani bersama.</span></figcaption>
            </figure>

            <figure class="kg-card" data-kg-reveal tabindex="0" role="button" aria-label="Buka foto penyerahan hadiah lomba">
              <div class="kg-photo"><img src="{{ asset('image/kegiatan 11.jpeg') }}" alt="Pemenang lomba menerima hadiah bersama mentor"></div>
              <span class="kg-zoom"><svg viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg></span>
              <figcaption class="kg-cap"><b>Merayakan Pencapaian</b><span>Bangga atas usaha dan hasil yang diraih bersama.</span></figcaption>
            </figure>

          </div>
        </div>
      </section>

      <!-- ================= TIMELINE ================= -->
      <section class="kg-section" id="kg-jejak" aria-labelledby="kgTlTitle">
        <div class="kg-wrap">
          <div class="kg-head" data-kg-reveal>
            <span class="kg-eyebrow"><i></i>JEJAK KEGIATAN</span>
            <h2 class="kg-h2" id="kgTlTitle">Perjalanan <span class="kg-script">Magang</span> Kami</h2>
            <p class="kg-sub">Urutan kegiatan dari awal hingga akhir program.</p>
          </div>

          <div class="kg-timeline" id="kgTimeline">
            <div class="kg-tl-line"><div class="kg-tl-fill" id="kgTlFill"></div></div>

            <div class="kg-step" data-kg-step>
              <span class="kg-step-dot"></span>
              <div class="kg-step-card" data-kg-reveal="left">
                <h3>Orientasi &amp; Perkenalan</h3>
                <p>Peserta mengenal perusahaan, budaya kerja, dan tim yang akan mendampingi selama program.</p>
              </div>
              <div class="kg-photo" data-kg-reveal="right"><img src="{{ asset('image/magang 9.jpeg') }}" alt="Peserta magang mengikuti orientasi dan berkenalan bersama"></div>
            </div>

            <div class="kg-step" data-kg-step>
              <span class="kg-step-dot"></span>
              <div class="kg-step-card" data-kg-reveal="right">
                <h3>Pelatihan &amp; Mentoring</h3>
                <p>Belajar materi dasar dan praktik langsung bersama mentor berpengalaman di setiap divisi.</p>
              </div>
              <div class="kg-photo" data-kg-reveal="left"><img src="{{ asset('image/mentoring.jpeg') }}" alt="Mentor menjelaskan materi kepada peserta magang"></div>
            </div>

            <div class="kg-step" data-kg-step>
              <span class="kg-step-dot"></span>
              <div class="kg-step-card" data-kg-reveal="left">
                <h3>Proyek &amp; Kebersamaan</h3>
                <p>Mengerjakan proyek nyata sambil membangun relasi lewat kegiatan tim.</p>
              </div>
              <div class="kg-photo" data-kg-reveal="right"><img src="{{ asset('image/kegiatan 9.jpeg') }}" alt="Peserta dan mentor makan bersama dalam suasana akrab"></div>
            </div>

            <div class="kg-step" data-kg-step>
              <span class="kg-step-dot"></span>
              <div class="kg-step-card" data-kg-reveal="right">
                <time>Akhir Program</time>
                <h3>Presentasi &amp; Sertifikat</h3>
                <p>Peserta mempresentasikan hasil belajar dan menerima sertifikat penyelesaian program.</p>
              </div>
              <div class="kg-photo kg-timeline-gallery" id="kgFinalGallery" data-kg-reveal="left">
                <button class="kg-timeline-switch" id="kgFinalPhotoSwitch" type="button" aria-label="Tampilkan foto kedua" aria-pressed="false">
                  <img class="kg-timeline-image kg-timeline-back" src="{{ asset('image/magang 6.jpeg') }}" alt="Peserta magang bersama mentor, foto kedua">
                  <img class="kg-timeline-image kg-timeline-front" src="{{ asset('image/magang 11.jpeg') }}" alt="Peserta magang bersama mentor, foto pertama">
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= CTA ================= -->
      @guest
      <section class="kg-cta-sec" aria-label="Ajakan bergabung">
        <div class="kg-wrap">
          <div class="kg-cta" data-kg-reveal="zoom">
            <span class="kg-eyebrow"><i></i>GABUNG BERSAMA KAMI</span>
            <h2 style="margin-top:18px">Siap Jadi Bagian dari <em>Cerita</em> Berikutnya?</h2>
            <p>Daftar program magang atau posisi staf di PT Equityworld Futures dan rasakan sendiri pengalamannya.</p>
            <div class="kg-hero-cta">
              <a href="{{ route('daftar', ['next' => '/karir#lowongan']) }}" class="kg-btn kg-btn--primary">Daftar Sekarang</a>
              <a href="/" class="kg-btn kg-btn--ghost">Kembali ke Beranda</a>
            </div>
          </div>
        </div>
      </section>
      @endguest

      <!-- ================= LIGHTBOX ================= -->
      <div class="kg-lb" id="kgLb" role="dialog" aria-modal="true" aria-label="Pratinjau foto kegiatan">
        <div class="kg-lb-box">
          <div class="kg-photo kg-lb-photo" id="kgLbPhoto"></div>
          <div class="kg-lb-info">
            <div><b id="kgLbTitle"></b><span id="kgLbSub"></span></div>
            <div class="kg-lb-count" id="kgLbCount"></div>
          </div>
          <button class="kg-lb-close" type="button" id="kgLbClose" aria-label="Tutup">✕</button>
          <button class="kg-lb-prev" type="button" id="kgLbPrev" aria-label="Foto sebelumnya">Sebelumnya</button>
          <button class="kg-lb-next" type="button" id="kgLbNext" aria-label="Foto berikutnya">Berikutnya</button>
        </div>
      </div>

    </div>
  </main>

  @include('partials.footer')

  <script>
  (function(){
    var page = document.getElementById('kgPage');
    if (!page) return;
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    page.classList.add('kg-js');

    /* ---------- tukar foto sorotan saat diklik ---------- */
    var featureGallery = document.getElementById('kgFeatureGallery');
    var featureSwitch = document.getElementById('kgFeatureSwitch');
    if (featureGallery && featureSwitch) {
      featureSwitch.addEventListener('click', function(){
        var showSecond = featureGallery.classList.toggle('is-second');
        featureSwitch.setAttribute('aria-pressed', String(showSecond));
        featureSwitch.setAttribute('aria-label', showSecond ? 'Tampilkan foto pertama' : 'Tampilkan foto kedua');
      });
    }

    var finalGallery = document.getElementById('kgFinalGallery');
    var finalPhotoSwitch = document.getElementById('kgFinalPhotoSwitch');
    if (finalGallery && finalPhotoSwitch) {
      finalPhotoSwitch.addEventListener('click', function(){
        var showSecond = finalGallery.classList.toggle('is-second');
        finalPhotoSwitch.setAttribute('aria-pressed', String(showSecond));
        finalPhotoSwitch.setAttribute('aria-label', showSecond ? 'Tampilkan foto pertama' : 'Tampilkan foto kedua');
      });
    }

    /* ---------- muncul saat scroll ---------- */
    var targets = page.querySelectorAll('[data-kg-reveal],[data-kg-step]');
    function settle(el){
      if (!el.classList.contains('kg-card')) return;
      setTimeout(function(){
        el.classList.add('kg-settled');
        el.style.removeProperty('--kg-d');
      }, 1300);
    }
    if ('IntersectionObserver' in window && !reduce) {
      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
          if (e.isIntersecting) {
            e.target.classList.add('kg-in');
            settle(e.target);
            io.unobserve(e.target);
          }
        });
      }, {threshold: .15, rootMargin: '0px 0px -40px 0px'});
      var gridIdx = 0;
      targets.forEach(function(el){
        if (el.classList.contains('kg-card')) { el.style.setProperty('--kg-d', (gridIdx++ % 4) * 90 + 'ms'); }
        io.observe(el);
      });
    } else {
      targets.forEach(function(el){ el.classList.add('kg-in','kg-settled'); });
    }

    /* ---------- hitung angka naik ---------- */
    var counters = page.querySelectorAll('[data-count]');
    function runCount(el){
      var end = parseInt(el.dataset.count, 10), start = null, dur = 1600;
      if (reduce) { el.textContent = end; return; }
      function tick(t){
        if (start === null) start = t;
        var p = Math.min((t - start) / dur, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(1 + (end - 1) * eased);
        if (p < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    }
    setTimeout(function(){ counters.forEach(runCount); }, 1400);

    /* ---------- kolase hero ikut gerakan mouse ---------- */
    var collage = document.getElementById('kgCollage');
    if (collage && !reduce && window.matchMedia('(hover:hover)').matches) {
      document.querySelector('.kg-hero').addEventListener('mousemove', function(e){
        var r = this.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - .5;
        var y = (e.clientY - r.top) / r.height - .5;
        collage.style.transform = 'translate(' + (x * -16) + 'px,' + (y * -12) + 'px)';
      });
    }

    /* ---------- galeri ---------- */
    var cards = Array.prototype.slice.call(page.querySelectorAll('.kg-card'));

    /* ---------- timeline: garis terisi saat scroll ---------- */
    var tl = document.getElementById('kgTimeline');
    var fill = document.getElementById('kgTlFill');
    function updateLine(){
      if (!tl || !fill) return;
      var r = tl.getBoundingClientRect();
      var vh = window.innerHeight;
      var p = (vh * .6 - r.top) / r.height;
      fill.style.transform = 'scaleY(' + Math.max(0, Math.min(1, p)) + ')';
    }
    if (!reduce) {
      window.addEventListener('scroll', updateLine, {passive: true});
      window.addEventListener('resize', updateLine);
      updateLine();
    } else if (fill) { fill.style.transform = 'scaleY(1)'; }

    /* ---------- lightbox ---------- */
    var lb = document.getElementById('kgLb');
    var lbPhoto = document.getElementById('kgLbPhoto');
    var lbTitle = document.getElementById('kgLbTitle');
    var lbSub = document.getElementById('kgLbSub');
    var lbCount = document.getElementById('kgLbCount');
    var current = 0, list = [], lastFocus = null;

    function visibleCards(){ return cards.filter(function(c){ return !c.hidden; }); }
    function render(){
      var card = list[current];
      lbPhoto.innerHTML = card.querySelector('.kg-photo').innerHTML;
      lbTitle.textContent = card.querySelector('.kg-cap b').textContent;
      lbSub.textContent = card.querySelector('.kg-cap span').textContent;
      lbCount.textContent = (current + 1) + ' / ' + list.length;
    }
    function openLb(card){
      list = visibleCards();
      current = list.indexOf(card);
      if (current < 0) return;
      lastFocus = document.activeElement;
      render();
      lb.classList.add('is-open');
      document.body.style.overflow = 'hidden';
      document.getElementById('kgLbClose').focus();
    }
    function closeLb(){
      lb.classList.remove('is-open');
      document.body.style.overflow = '';
      if (lastFocus) lastFocus.focus();
    }
    function step(d){ current = (current + d + list.length) % list.length; render(); }

    cards.forEach(function(card){
      card.addEventListener('click', function(){ openLb(card); });
      card.addEventListener('keydown', function(e){
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openLb(card); }
      });
    });
    document.getElementById('kgLbClose').addEventListener('click', closeLb);
    document.getElementById('kgLbPrev').addEventListener('click', function(){ step(-1); });
    document.getElementById('kgLbNext').addEventListener('click', function(){ step(1); });
    lb.addEventListener('click', function(e){ if (e.target === lb) closeLb(); });
    document.addEventListener('keydown', function(e){
      if (!lb.classList.contains('is-open')) return;
      if (e.key === 'Escape') closeLb();
      if (e.key === 'ArrowRight') step(1);
      if (e.key === 'ArrowLeft') step(-1);
    });
  })();
  </script>
</body>
</html>
