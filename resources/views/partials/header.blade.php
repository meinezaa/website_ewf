{{-- resources/views/partials/header.blade.php --}}
<style>
  @import url("{{ asset('css/site-shell.css') }}");
  @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
</style>

{{-- Penyesuaian header: logo mentok kiri, Masuk/Daftar mentok kanan --}}
<style>
  :root{--shell-pad:clamp(20px,3.2vw,56px)}
  body .site-header .container{
    max-width:none;width:100%;margin-left:0;margin-right:0;
    padding-left:var(--shell-pad);padding-right:var(--shell-pad);box-sizing:border-box;
  }
  body .site-header .nav .container{display:flex;align-items:center;justify-content:space-between}
  body .site-header .nav-actions form{margin:0}
  body .site-header .nav-actions form .btn{font:inherit;cursor:pointer}
  body .site-header .nav-actions .account-link{
    display:grid;place-items:center;width:42px;height:42px;border:1px solid #f1d8c3;border-radius:50%;
    background:linear-gradient(145deg,#fff,#fff4e9);color:#d9631d;font-size:23px;
    box-shadow:0 5px 14px rgba(120,60,10,.1);transition:transform .25s,box-shadow .25s;
  }
  body .site-header .nav-actions .account-link:hover{
    transform:translateY(-2px);box-shadow:0 8px 18px rgba(120,60,10,.16);
  }
  body .site-header .ticker-wrap{font-size:13px}
  body .site-header .ticker{min-height:44px}
  body .site-header .ticker .tag{font-size:10px}
  @media(max-width:600px){
    body .site-header .ticker-wrap{font-size:12px}
    body .site-header .ticker{min-height:42px}
  }
</style>

<div class="site-header">
  @php
    $currentLocation = request()->getPathInfo().(request()->getQueryString() ? '?'.request()->getQueryString() : '');
    $aboutUrl = Route::has('tentang.kami') ? route('tentang.kami') : route('home').'#tentang-perusahaan';
    $activitiesUrl = Route::has('kegiatan') ? route('kegiatan') : (Route::has('tentang.kami') ? route('tentang.kami').'#fasilitas' : route('home').'#galeri-magang');
    $careerUrl = Route::has('karir') ? route('karir') : route('home').'#karir';
    $faqUrl = Route::has('faq') ? route('faq') : route('home').'#faq';
    $loginUrl = Route::has('login') ? route('login', ['next' => $currentLocation]) : route('home').'#karir';
    $registerUrl = Route::has('daftar') ? route('daftar', ['next' => $currentLocation]) : route('home').'#karir';
    $profileUrl = Route::has('profile') ? route('profile') : route('home').'#karir';
    $careerController = \App\Http\Controllers\CareerApplicationController::class;
    $careerOpenings = class_exists($careerController) && method_exists($careerController, 'announcementOpenings')
      ? $careerController::announcementOpenings()
      : [];
    $professionalOpenings = $careerOpenings['professional'] ?? [];
    $internshipOpenings = $careerOpenings['internship'] ?? [];
    $professionalNews = $professionalOpenings
      ? 'Lowongan kerja dibuka: '.implode(', ', $professionalOpenings).'. Lihat detail di halaman Karir.'
      : 'Lihat informasi peluang kerja dan pendaftaran staf melalui halaman Karir.';
    $internshipNews = $internshipOpenings
      ? 'Program Magang & PKL dibuka: '.implode(', ', $internshipOpenings).'. Daftar melalui halaman Karir.'
      : 'Kenali program Magang & PKL dan pantau informasi pendaftarannya di halaman Karir.';
  @endphp
  <header class="nav">
    <div class="container">
      <a href="{{ route('home') }}" class="brand">
        <img src="{{ asset('image/' . rawurlencode('logo galeri magang.png')) }}" alt="Logo Galeri Magang">
        <div class="name"><span class="brand-galeri">GALERI</span> <span class="brand-magang">MAGANG</span></div>
      </a>
      <nav class="menu" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ $aboutUrl }}" class="{{ request()->routeIs('tentang.kami') ? 'active' : '' }}">Tentang Kami</a>
        <a href="{{ $activitiesUrl }}" class="{{ request()->routeIs('kegiatan') ? 'active' : '' }}">Kegiatan</a>
        <a href="{{ $careerUrl }}" class="{{ request()->routeIs('karir') ? 'active' : '' }}">Karir</a>
        <a href="{{ $faqUrl }}" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
      </nav>
      <div class="nav-actions">
        @auth
          @php($accountPhotoUrl = auth()->user()->profilePhotoUrl())
          <a href="{{ $profileUrl }}" class="login account-link" aria-label="Akun saya" title="Akun saya">
            @if($accountPhotoUrl)
              <img class="account-nav-avatar" src="{{ $accountPhotoUrl }}" alt="">
            @else
              <i class="bi bi-person-circle" aria-hidden="true"></i>
            @endif
          </a>
        @else
          <a href="{{ $loginUrl }}" class="login" data-return-current>Masuk</a>
          <a href="{{ $registerUrl }}" class="btn btn-primary" data-return-current>Daftar</a>
        @endauth
      </div>
    </div>
  </header>

  <div class="ticker-wrap">
    <div class="container">
      <div class="ticker">
        <span class="tag"><i></i>INFO KARIR</span>
        <div class="ticker-viewport" aria-label="Informasi lowongan kerja dan program magang terbaru">
          <div class="ticker-track">
            <div class="ticker-group">
              <span class="t1">• {{ $professionalNews }}</span>
              <span class="t2">{{ $internshipNews }}</span>
            </div>
            <div class="ticker-group" aria-hidden="true">
              <span class="t1">• {{ $professionalNews }}</span>
              <span class="t2">{{ $internshipNews }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('click', event => {
  const link = event.target.closest('a[data-return-current]');
  if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
  const destination = new URL(link.href, window.location.href);
  destination.searchParams.set('next', window.location.pathname + window.location.search + window.location.hash);
  link.href = destination.href;
});
</script>
