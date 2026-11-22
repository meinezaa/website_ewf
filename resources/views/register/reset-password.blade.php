@php
    $foto = asset('image/bg%20tentang%20kami.png');
    $logo = asset('image/' . rawurlencode('logo galeri magang.png'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Kata Sandi - PT Equityworld Futures</title>
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
        <h1>Buat kata sandi baru</h1>
        <p class="password-description">Pilih kata sandi baru untuk mengamankan akun Anda. Gunakan minimal 8 karakter yang terdiri dari huruf dan angka.</p>

        @if ($errors->any())
            <div class="password-errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="password-form" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">EMAIL</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" placeholder="nama@email.com" required autofocus>
            <label for="password">KATA SANDI BARU</label>
            <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8" required>
            <label for="password_confirmation">ULANGI KATA SANDI</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Masukkan kembali kata sandi" minlength="8" required>
            <button class="password-submit" type="submit">Simpan kata sandi baru</button>
        </form>

        <a class="password-back" href="{{ route('login') }}">Kembali ke halaman login</a>
    </main>
    <aside class="password-visual" aria-label="Informasi keamanan akun">
        <span class="password-visual-curve" aria-hidden="true"></span>
        <strong>Akun aman, perjalanan karier berlanjut.</strong>
        <p>Setelah kata sandi diperbarui, masuk kembali menggunakan kata sandi baru Anda.</p>
    </aside>
</body>
</html>
