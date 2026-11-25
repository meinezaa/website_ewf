<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin') | Panel Admin Equityworld</title>
@vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin">

@include('admin.partials.sidebar')

<main>
  <header class="admin-topbar">
    <div class="admin-breadcrumb"><span>Galeri Magang</span><i>/</i><b>@yield('title', 'Admin')</b></div>
    <div class="admin-top-actions">
      <span class="admin-top-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
      <a href="{{ route('admin.profile') }}" class="admin-top-user">
        @if(auth()->user()->profilePhotoUrl())
          <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="">
        @else
          <span>{{ auth()->user()->initials }}</span>
        @endif
        <b>{{ auth()->user()->name }}</b>
      </a>
    </div>
  </header>
  @if (session('success'))<div class="alert" role="status">{{ session('success') }}</div>@endif
  @if (session('error'))<div class="alert err" role="alert">{{ session('error') }}</div>@endif
  @if ($errors->any())<div class="alert err" role="alert">{{ $errors->first() }}</div>@endif

  @yield('content')
</main>

<script>
document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
  button.addEventListener('click', function () {
    var input = document.getElementById(button.getAttribute('data-password-toggle'));
    if (!input) return;
    var isVisible = input.type === 'text';
    input.type = isVisible ? 'password' : 'text';
    button.setAttribute('aria-pressed', String(!isVisible));
    button.setAttribute('aria-label', (isVisible ? 'Tampilkan ' : 'Sembunyikan ') + (input.id === 'password_confirmation' ? 'konfirmasi kata sandi' : 'kata sandi'));
  });
});
</script>

</body>
</html>
