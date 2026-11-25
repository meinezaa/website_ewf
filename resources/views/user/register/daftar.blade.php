{{-- resources/views/daftar.blade.php --}}
@php
    $foto = asset('image/bg%20tentang%20kami.png');
    $logo = asset('image/logo%20ewf.png');
    $urlLogin = route('login', request()->only('next'));
    $urlKarir = \Illuminate\Support\Facades\Route::has('karir') ? route('karir') : url('/karir');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru - PT Equityworld Futures</title>
    {{-- pastikan halaman sudah siap sebelum transisi dimulai (mencegah kedip/patah di awal) --}}
    <link rel="expect" href="#shell" blocking="render">
    {{-- saat tiba lewat transisi, animasi masuk form ditangani transisi (bukan animasi halaman) --}}
    <script>
        addEventListener('pagereveal', function(e){
            if(e.viewTransition) document.documentElement.classList.add('vt-nav');
        });
    </script>
    {{-- muat foto latar lebih awal & siapkan halaman tujuan di latar belakang agar transisi tidak tersendat --}}
    <link rel="preload" as="image" href="{{ $foto }}">
    @vite(['resources/css/app.css', 'resources/css/auth-transitions.css'])
    <script type="speculationrules">{"prerender":[{"source":"list","urls":[@json($urlLogin)],"eagerness":"immediate"}]}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body class="auth-page auth-register">

<div class="stage">
    <main class="shell" id="shell">
        <aside class="photo-side">
            <span class="blur" style="--register-photo-image: url('{{ $foto }}')"></span>
            <svg class="wave" viewBox="0 0 96 700" preserveAspectRatio="none" aria-hidden="true"><path d="M0,0 L40,0 C110,110 20,250 62,380 C100,500 40,600 20,700 L0,700 Z" fill="#fff"/><path d="M40,0 C110,110 20,250 62,380 C100,500 40,600 20,700" fill="none" stroke="#fde7d3" stroke-width="2"/></svg>
            <div class="col">
                <div class="holder">
                    <span class="tag">PT EQUITYWORLD FUTURES</span>
                </div>
                <p class="caption"><b>PT Equityworld Futures</b>Gunakan satu profil dan CV untuk melamar berbagai posisi, tanpa biaya.</p>
            </div>
        </aside>

        <section class="form-side">
            <div>
                <h1>Daftar Akun Baru</h1>
                <p class="sub">Buat akun pelamar untuk mengirim lamaran dan memantau setiap tahapan seleksi di PT Equityworld Futures.</p>
            </div>

            <div class="tabs to-daftar" id="tabs">
                <a href="{{ $urlLogin }}" data-go="login">Login</a>
                <a href="#" class="on" aria-current="page">Daftar</a>
            </div>

            <div>
                <div class="notice @if(str_contains((string) request('next'), '#apply/')) show @endif" id="notice"><i class="bi bi-info-circle-fill"></i><span id="noticeText">Buat akun terlebih dahulu, lalu Anda dapat melanjutkan pengisian berkas lamaran.</span></div>
                @if($errors->any())
                    <div class="err show" role="alert">{{ $errors->first() }}</div>
                @else
                    <div class="err" id="err" role="alert"></div>
                @endif
            </div>

            <form id="regForm" action="{{ route('daftar.store') }}" method="POST">
                @csrf
                @if(request()->filled('next'))<input type="hidden" name="next" value="{{ request('next') }}">@endif
                <label class="lbl" for="name">NAMA LENGKAP SESUAI KTP</label>
                <div class="fld" id="fName"><i class="bi bi-person"></i><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Contoh: Hendra Wijaya Pratama" required minlength="3" maxlength="150"></div>

                <div class="row2">
                    <div><label class="lbl" for="email">EMAIL AKTIF</label>
                        <div class="fld" id="fEmail"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@gmail.com" required maxlength="255"></div></div>
                    <div><label class="lbl" for="wa">NOMOR WHATSAPP / HP</label>
                        <div class="fld" id="fWa"><i class="bi bi-telephone"></i><input id="wa" name="phone" type="tel" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" placeholder="+62 812-3456-7890" required maxlength="40"></div></div>
                </div>

                <label class="lbl" for="pass">KATA SANDI KEAMANAN</label>
                <div class="fld" id="fPass"><i class="bi bi-lock"></i><input id="pass" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter (huruf dan angka)" required minlength="8">
                    <button type="button" class="eye" data-eye="pass" aria-label="Tampilkan sandi"><i class="bi bi-eye"></i></button></div>
                <div class="meter" id="meter" aria-live="polite"><i></i><i></i><i></i><i></i><em id="meterLbl"></em></div>

                <label class="lbl" for="pass2">KONFIRMASI KATA SANDI</label>
                <div class="fld" id="fPass2"><i class="bi bi-arrow-repeat"></i><input id="pass2" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi kata sandi Anda" required minlength="8">
                    <button type="button" class="eye" data-eye="pass2" aria-label="Tampilkan sandi"><i class="bi bi-eye"></i></button></div>

                <button class="btn" type="submit" id="submitBtn"><span>Daftar</span></button>
            </form>

            <p class="switch"><i class="bi bi-box-arrow-in-right" style="color:var(--o)"></i> Sudah memiliki akun? <a href="{{ $urlLogin }}" data-go="login">Login di sini</a></p>
        </section>
    </main>
</div>
<div id="toast" role="status"></div>

<script>
(function(){
    function $(id){ return document.getElementById(id); }
    document.querySelectorAll('[data-go="login"]').forEach(function(a){
        a.addEventListener('click', function(e){
            e.preventDefault();
            location.assign(a.href);
        });
    });

    document.querySelectorAll('[data-eye]').forEach(function(b){
        b.addEventListener('click', function(){
            var i = $(b.dataset.eye), show = i.type === 'password';
            i.type = show ? 'text' : 'password';
            b.innerHTML = '<i class="bi bi-eye' + (show ? '-slash' : '') + '"></i>';
        });
    });

    var frame = $('frame'), side = document.querySelector('.photo-side');
    if(frame && side && !matchMedia('(prefers-reduced-motion: reduce)').matches){
        side.addEventListener('mousemove', function(e){
            var r = side.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
            frame.style.transform = 'perspective(900px) rotateY(' + (x * 16) + 'deg) rotateX(' + (-y * 14) + 'deg)';
        });
        side.addEventListener('mouseleave', function(){ frame.style.transform = ''; });
    }

    // kekuatan sandi
    var labels = ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'];
    function strength(p){
        if(!p) return 0;
        var s = 0;
        if(p.length >= 8) s++;
        if(/[A-Za-z]/.test(p) && /\d/.test(p)) s++;
        if(/[^A-Za-z0-9]/.test(p)) s++;
        if(p.length >= 12) s++;
        return Math.max(s, p.length ? 1 : 0);
    }
    $('pass').addEventListener('input', function(){
        var n = strength(this.value);
        document.querySelectorAll('#meter i').forEach(function(b, i){ b.classList.toggle('on', i < n); });
        $('meterLbl').textContent = labels[n];
    });

})();
</script>
</body>
</html>