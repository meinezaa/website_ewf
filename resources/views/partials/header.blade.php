{{-- resources/views/partials/header.blade.php --}}


{{-- Penyesuaian header: logo mentok kiri, Masuk/Daftar mentok kanan --}}


<div class="site-header">
  @php
    $currentLocation = request()->getPathInfo().(request()->getQueryString() ? '?'.request()->getQueryString() : '');
    $careerOpenings = \App\Http\Controllers\CareerApplicationController::announcementOpenings();
    $professionalNews = $careerOpenings['professional']
      ? 'Lowongan kerja dibuka: '.implode(', ', $careerOpenings['professional']).'. Lihat detail di halaman Karir.'
      : 'Belum ada lowongan kerja yang dibuka saat ini. Kunjungi halaman Karir untuk informasi terbaru.';
    $internshipNews = $careerOpenings['internship']
      ? 'Program Magang & PKL dibuka: '.implode(', ', $careerOpenings['internship']).'. Daftar melalui halaman Karir.'
      : 'Belum ada program Magang & PKL yang dibuka saat ini. Kunjungi halaman Karir untuk informasi terbaru.';
  @endphp
  <header class="nav">
    <div class="container">
      <a href="{{ route('home') }}" class="brand">
        <img src="{{ asset('image/' . rawurlencode('logo galeri magang.png')) }}" alt="Galeri Magang">
        <div class="name"><span class="brand-galeri">GALERI</span> <span class="brand-magang">MAGANG</span></div>
      </a>
      <nav class="menu" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('tentang.kami') }}" class="{{ request()->routeIs('tentang.kami') ? 'active' : '' }}">Tentang Kami</a>
        <a href="{{ route('tentang.kami') }}#fasilitas">Kegiatan</a>
        <a href="{{ route('karir') }}" class="{{ request()->routeIs('karir', 'karir.documents') ? 'active' : '' }}">Karir</a>
        <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
      </nav>
      <div class="nav-actions">
        @auth
          @php($accountPhotoUrl = auth()->user()->profilePhotoUrl())
          <a href="{{ route('profile') }}" class="login account-link" aria-label="Akun saya" title="Akun saya">
            @if($accountPhotoUrl)
              <img class="account-nav-avatar" src="{{ $accountPhotoUrl }}" alt="">
            @else
              <i class="bi bi-person-circle" aria-hidden="true"></i>
            @endif
          </a>
        @else
          <a href="{{ route('login', ['next' => $currentLocation]) }}" class="login" data-return-current>Masuk</a>
          <a href="{{ route('daftar', ['next' => $currentLocation]) }}" class="btn btn-primary" data-return-current>Daftar</a>
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