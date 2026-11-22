{{-- resources/views/partials/footer.blade.php --}}
@php
  $footerAboutUrl = Route::has('tentang.kami') ? route('tentang.kami') : route('home').'#tentang-perusahaan';
  $footerActivitiesUrl = Route::has('kegiatan') ? route('kegiatan') : (Route::has('tentang.kami') ? route('tentang.kami').'#fasilitas' : route('home').'#galeri-magang');
  $footerCareerUrl = Route::has('karir') ? route('karir') : route('home').'#karir';
  $footerFaqUrl = Route::has('faq') ? route('faq') : route('home').'#faq';
@endphp
<style>
  @import url("{{ asset('css/site-shell.css') }}");
</style>

{{-- Penyesuaian footer: gradasi putih -> peach -> oranye yang menyatu dengan halaman, lebar sejajar header --}}
<style>
  :root{--shell-pad:clamp(20px,3.2vw,56px)}

  body .site-footer[data-page="about"]{--footer-blend-start:#fff}

  body .site-footer{
    position:relative;isolation:isolate;overflow:hidden;margin-top:0;
    padding:220px 0 22px;color:#fff;
    background:linear-gradient(180deg,#ffb783 0%,#fc9d56 20%,#f68536 48%,#ee6a14 78%,#e45a08 100%);
  }
  /* kabut putih di bagian atas: banyak titik warna agar peralihannya tidak ada garis batas */
  body .site-footer::before{
    content:"";position:absolute;left:0;right:0;top:0;height:300px;z-index:0;pointer-events:none;
    background:linear-gradient(180deg,
      var(--footer-blend-start,#fff) 0%,rgba(255,255,255,.98) 8%,rgba(255,251,247,.94) 20%,
      rgba(255,240,226,.82) 38%,rgba(255,224,196,.63) 56%,rgba(255,203,160,.4) 72%,
      rgba(255,182,128,.2) 86%,rgba(255,170,110,.08) 95%,rgba(255,170,110,0) 100%);
  }
  /* cahaya lembut agar oranye terasa berdimensi */
  body .site-footer::after{
    content:"";position:absolute;inset:0;z-index:0;pointer-events:none;
    background:
      radial-gradient(560px 340px at 10% 38%,rgba(255,255,255,.20),transparent 70%),
      radial-gradient(520px 320px at 94% 84%,rgba(255,214,170,.30),transparent 70%);
  }
  body .site-footer .container{
    position:relative;z-index:1;
    max-width:none;width:100%;margin-left:0;margin-right:0;
    padding-left:var(--shell-pad);padding-right:var(--shell-pad);box-sizing:border-box;
  }
  body .site-footer,
  body .site-footer h6,
  body .site-footer p,
  body .site-footer li,
  body .site-footer a,
  body .site-footer small,
  body .site-footer b{color:#fff}
  body .site-footer .foot-logo b .brand-galeri{color:#142b4f}
  body .site-footer .foot-logo b .brand-magang{color:#142b4f}
  body .site-footer h6,
  body .site-footer li,
  body .site-footer p{text-shadow:0 1px 10px rgba(140,50,0,.22)}
  body .site-footer .foot-grid li a{
    position:relative;display:inline-block;
    transition:transform .35s cubic-bezier(.22,.8,.24,1),color .25s ease,text-shadow .25s ease;
  }
  body .site-footer .foot-grid li a::after{
    content:"";position:absolute;left:0;right:0;bottom:-3px;height:1px;border-radius:2px;
    background:linear-gradient(90deg,rgba(255,255,255,.95),rgba(255,255,255,.15));
    transform:scaleX(0);transform-origin:left center;
    transition:transform .35s cubic-bezier(.22,.8,.24,1);
  }
  body .site-footer .foot-grid li a:hover,
  body .site-footer .foot-grid li a:focus-visible{
    transform:translateX(5px);text-shadow:0 0 14px rgba(255,255,255,.55);
  }
  body .site-footer .foot-grid li a:hover::after,
  body .site-footer .foot-grid li a:focus-visible::after{transform:scaleX(1)}
  body .site-footer .foot-grid li a:active{transform:translateX(2px) scale(.97)}
  body .site-footer .foot-grid li a:focus-visible{outline:2px solid rgba(255,255,255,.8);outline-offset:5px;border-radius:2px}
  body .site-footer .social-links{display:flex;flex-direction:column;gap:10px;margin-top:14px}
  body .site-footer .social-links a{display:flex;align-items:center;gap:9px;font-size:13px;font-weight:600;transition:transform .3s ease,text-shadow .3s ease}
  body .site-footer .social-links a:hover,
  body .site-footer .social-links a:focus-visible{transform:translateX(4px);text-shadow:0 0 14px rgba(255,255,255,.55)}
  body .site-footer .social-links a:focus-visible{outline:2px solid rgba(255,255,255,.8);outline-offset:4px;border-radius:2px}
  body .site-footer .social-links i{width:18px;text-align:center;font-size:18px}
  body .site-footer .copy{border-top:1px solid rgba(255,255,255,.34);color:rgba(255,255,255,.95)}

  @media(prefers-reduced-motion:reduce){
    body .site-footer .foot-grid li a,
    body .site-footer .foot-grid li a::after{transition:none}
  }

  @media(max-width:767px){
    body .site-footer{padding-top:160px}
    body .site-footer::before{height:220px}
  }
</style>

<footer class="site-footer" @if(request()->routeIs('tentang.kami')) data-page="about" @endif>
  <div class="container">
    <div class="foot-logo">
      <img src="{{ asset('image/' . rawurlencode('logo galeri magang.png')) }}" alt="Logo Galeri Magang">
      <b><span class="brand-galeri">GALERI</span> <span class="brand-magang">MAGANG</span></b>
    </div>
    <div class="foot-grid">
      <div>
        <h6>Informasi</h6>
        <ul>
          <li><a href="{{ route('home') }}">Beranda</a></li>
          <li><a href="{{ $footerAboutUrl }}">Tentang Kami</a></li>
          <li><a href="{{ $footerActivitiesUrl }}">Kegiatan</a></li>
          <li><a href="{{ $footerCareerUrl }}">Karir</a></li>
          <li><a href="{{ $footerFaqUrl }}">FAQ</a></li>
        </ul>
      </div>
      <div>
        <h6>Kantor Operasional</h6>
        <ul>
          <li>Jakarta – SSC</li>
          <li>Jakarta – CYBER 2</li>
          <li>Semarang</li>
          <li>Surabaya – Praxis</li>
          <li>Cirebon</li>
          <li>Manado</li>
        </ul>
      </div>
      <div>
        <h6>Layanan Kami</h6>
        <ul>
          <li>Profil PT Equityworld Futures</li>
          <li>Pendaftaran Calon Staff</li>
          <li>Program Magang Mahasiswa</li>
          <li>Program PKL SMA/SMK</li>
          <li>Pelacakan Status Pendaftaran</li>
        </ul>
      </div>
      <div>
        <h6>Kontak Kami</h6>
        <p>📞 +62 21 6310 3788</p>
        <p>✉ corporate@equityworld-futures.co.id</p>
        <p>📍 Kantor Surabaya<br>Trillium Office &amp; Residence, Jl. Pemuda No.118–136, Embong Kaliasin, Kec. Genteng, Surabaya 60271, Indonesia</p>
        <div class="social-links" aria-label="Media sosial Galeri Magang">
          <a href="https://www.tiktok.com/@galeriamagang.id" target="_blank" rel="noopener noreferrer" aria-label="TikTok galeriamagang.id">
            <i class="bi bi-tiktok" aria-hidden="true"></i><span>TikTok galeriamagang.id</span>
          </a>
          <a href="https://www.instagram.com/galerimagang.id/" target="_blank" rel="noopener noreferrer" aria-label="Instagram galerimagang.id">
            <i class="bi bi-instagram" aria-hidden="true"></i><span>Instagram galerimagang.id</span>
          </a>
        </div>
      </div>
    </div>
    <div class="copy">© 2026 PT. Equityworld Futures. All rights reserved.</div>
  </div>
</footer>
