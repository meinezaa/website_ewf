{{-- resources/views/login.blade.php --}}
@php
    $hrdLogin = $hrdLogin ?? false;
    $foto = asset('image/bg%20tentang%20kami.png');
    $logo = asset('image/logo%20ewf.png');
    $urlDaftar = route('daftar', request()->only('next'));
    $urlKarir = \Illuminate\Support\Facades\Route::has('karir') ? route('karir') : url('/karir');
    $loginAction = $hrdLogin ? route('hrd.login.store') : route('login.store');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $hrdLogin ? 'Masuk HRD' : 'Masuk ke Akun' }} - PT Equityworld Futures</title>
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
    <script type="speculationrules">{"prerender":[{"source":"list","urls":[@json($urlDaftar)],"eagerness":"immediate"}]}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/auth-transitions.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="auth-page auth-login">

<div class="stage">
    <main class="shell" id="shell">
        <section class="form-side">
            <div>
                <h1>{{ $hrdLogin ? 'Masuk HRD' : 'Masuk ke Akun' }}</h1>
                <p class="sub">{{ $hrdLogin ? 'Masuk dengan akun HRD untuk membuka dashboard pengelolaan.' : 'Akses portal karier resmi PT Equityworld Futures untuk memperoleh informasi peluang karier, mengajukan pendaftaran, dan memantau perkembangan tahapan seleksi secara mudah dan transparan.' }}</p>
            </div>

            <div class="tabs" id="tabs">
                <a href="#" class="on" aria-current="page">Login</a>
                <a href="{{ $urlDaftar }}" data-go="daftar">Daftar</a>
            </div>

            <div>
                <div class="notice @if(str_contains((string) request('next'), '#apply/')) show @endif" id="notice"><i class="bi bi-info-circle-fill"></i><span id="noticeText">Masuk terlebih dahulu untuk melanjutkan pengisian berkas lamaran.</span></div>
                @if($errors->any())
                    <div class="err show" role="alert">{{ $errors->first() }}</div>
                @else
                    <div class="err" id="err" role="alert"></div>
                @endif
                @if(session('status'))
                    <div class="notice success show" role="status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>{{ session('status') }}</span></div>
                @endif
            </div>

            <form id="loginForm" action="{{ $loginAction }}" method="POST">
                @csrf
                @if(request()->filled('next'))<input type="hidden" name="next" value="{{ request('next') }}">@endif
                <label class="lbl" for="email">EMAIL</label>
                <div class="fld" id="fEmail"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" required></div>

                <label class="lbl" for="pass">KATA SANDI</label>
                <div class="fld" id="fPass"><i class="bi bi-key"></i><input id="pass" name="password" type="password" autocomplete="current-password" placeholder="Masukkan sandi" required>
                    <button type="button" class="eye" id="eye" aria-label="Tampilkan sandi"><i class="bi bi-eye"></i></button></div>
                <a class="forgot-link" href="{{ route('password.request', request()->only('next')) }}">Lupa kata sandi?</a>

                <button class="btn" type="submit" id="submitBtn"><span>Masuk</span></button>
            </form>

            @if($hrdLogin)
                <p class="switch">Masuk sebagai pengguna? <a href="{{ route('login') }}">Login akun pengguna</a></p>
            @else
                <p class="switch">Belum punya akun? <a href="{{ $urlDaftar }}" data-go="daftar">Buat akun</a></p>
                <p class="switch">Akses khusus HRD? <a href="{{ route('hrd.login') }}">Masuk HRD</a></p>
            @endif
        </section>

        <aside class="photo-side">
            <span class="blur" style="--login-photo-image: url('{{ $foto }}')"></span>
            <svg class="wave" viewBox="0 0 96 700" preserveAspectRatio="none" aria-hidden="true"><path d="M0,0 L40,0 C110,110 20,250 62,380 C100,500 40,600 20,700 L0,700 Z" fill="#fff"/><path d="M40,0 C110,110 20,250 62,380 C100,500 40,600 20,700" fill="none" stroke="#fde7d3" stroke-width="2"/></svg>
            <div class="col">
                <div class="holder">
                    <span class="tag">PT EQUITYWORLD FUTURES</span>
                </div>
                <p class="caption"><b>PT Equityworld Futures</b>{{ $hrdLogin ? 'Masuk ke dashboard HRD untuk mengelola profil dan memantau proses rekrutmen.' : 'Kelola profil dan CV untuk mengakses serta mengikuti berbagai peluang karier tanpa dikenakan biaya pendaftaran.' }}</p>
            </div>
        </aside>
    </main>
</div>
<div id="toast" role="status"></div>

<script>
(function(){
    var $ = function(id){ return document.getElementById(id); };
    document.querySelectorAll('[data-go="daftar"]').forEach(function(a){
        a.addEventListener('click', function(e){
            e.preventDefault();
            location.assign(a.href);
        });
    });
    $('eye').addEventListener('click', function(){
        var p = $('pass'), show = p.type === 'password';
        p.type = show ? 'text' : 'password';
        this.innerHTML = '<i class="bi bi-eye' + (show ? '-slash' : '') + '"></i>';
    });
})();
</script>
</body>
</html>
