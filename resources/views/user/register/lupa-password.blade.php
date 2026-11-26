@php
    $foto = asset('image/bg%20tentang%20kami.png');
    $logo = asset('image/' . rawurlencode('logo galeri magang.png'));
    $urlLogin = route('login', request()->only('next'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi - PT Equityworld Futures</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="password-page" style="--password-background: url('{{ $foto }}')">
    <main class="password-content">
        <a class="password-brand" href="{{ route('home') }}">
            <img src="{{ $logo }}" alt="">
            <span class="password-brand-name"><span class="password-brand-galeri">GALERI</span> <span class="password-brand-magang">MAGANG</span></span>
        </a>
        <h1>Lupa kata sandi?</h1>
        <p class="password-description">Masukkan email yang terhubung dengan akun Anda. Jika email terdaftar, kami akan mengirim tautan untuk membuat kata sandi baru.</p>

        @if (app()->environment('local') && config('mail.default') === 'log' && ! session('status'))
            <p class="password-mail-notice" role="status">Mode email lokal hanya mencatat tautan reset di log aplikasi. Atur layanan email untuk mengirim email sungguhan.</p>
        @endif
        @if (session('status'))
            <p class="password-message{{ config('mail.default') === 'log' ? ' password-mail-notice' : '' }}" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div class="password-errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="password-form" action="{{ route('password.email') }}" method="POST">
            @csrf
            <label for="email">EMAIL</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" required autofocus>
            <button class="password-submit" type="submit">Kirim tautan reset</button>
        </form>

        <a class="password-back" href="{{ $urlLogin }}">Kembali ke halaman login</a>
    </main>
    <aside class="password-visual" aria-label="Informasi Galeri Magang">
        <span class="password-visual-curve" aria-hidden="true"></span>
        <strong>Selangkah lagi untuk kembali beraktivitas.</strong>
        <p>Atur ulang kata sandi dengan aman melalui tautan yang dikirim ke email akun Anda.</p>
    </aside>
</body>
</html>
