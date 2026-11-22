{{-- resources/views/partials/footer.blade.php --}}


{{-- Penyesuaian footer: gradasi putih -> peach -> oranye yang menyatu dengan halaman, lebar sejajar header --}}


<footer class="site-footer" @if(request()->routeIs('tentang.kami')) data-page="about" @endif>
  <div class="container">
    <div class="foot-logo">
      <img src="{{ asset('image/' . rawurlencode('logo galeri magang.png')) }}" alt="Galeri Magang">
      <b><span class="brand-galeri">GALERI</span> <span class="brand-magang">MAGANG</span></b>
    </div>
    <div class="foot-grid">
      <div>
        <h6>Informasi</h6>
        <ul>
          <li><a href="{{ route('home') }}">Beranda</a></li>
          <li><a href="{{ route('tentang.kami') }}">Tentang Kami</a></li>
          <li><a href="{{ route('tentang.kami') }}#fasilitas">Kegiatan</a></li>
          <li><a href="{{ route('karir') }}">Karir</a></li>
          <li><a href="{{ route('faq') }}">FAQ</a></li>
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