
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT Equityworld Futures')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
    @stack('styles')
</head>
<body>
    @include('user.partials.header')

    @yield('content')

    @include('user.partials.footer')

    @stack('scripts')
</body>
</html>
