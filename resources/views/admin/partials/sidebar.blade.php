<aside class="side" id="adminSide">
  <button class="side-toggle" type="button" id="sideToggle" aria-label="Ciutkan sidebar" aria-expanded="true">
    <span class="side-toggle-glyph" aria-hidden="true"></span>
  </button>

  <a class="admin-brand" href="{{ route('admin.dashboard') }}" aria-label="Galeri Magang, dashboard admin">
    <img src="{{ asset('image/' . rawurlencode('logo galeri magang.png')) }}" alt="">
    <span><b>Galeri</b><strong>Magang</strong><small>ADMIN PANEL</small></span>
  </a>

  <nav class="nav" aria-label="Menu admin">
    <p class="nav-label">Ruang Kerja</p>
    <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">
      @include('admin.partials.icon', ['name' => 'dashboard'])<span>Dashboard</span>
    </a>

    <p class="nav-label">Manajemen</p>
    <a href="{{ route('admin.users.index') }}" title="Kelola Pengguna" class="{{ request()->routeIs('admin.users.*') ? 'on' : '' }}">
      @include('admin.partials.icon', ['name' => 'users'])<span>Kelola Pengguna</span>
    </a>
    <a href="{{ route('admin.logs') }}" title="Activity Log" class="{{ request()->routeIs('admin.logs') ? 'on' : '' }}">
      @include('admin.partials.icon', ['name' => 'clock'])<span>Activity Log</span>
    </a>

    <p class="nav-label">Preferensi</p>
    <a href="{{ route('admin.profile') }}" title="Profil" class="{{ request()->routeIs('admin.profile*') ? 'on' : '' }}">
      @include('admin.partials.icon', ['name' => 'user'])<span>Profil</span>
    </a>
  </nav>

  <div class="me">
    <a class="me-profile" href="{{ route('admin.profile') }}" aria-label="Profil {{ auth()->user()->name }}">
      @if(auth()->user()->profilePhotoUrl())
        <img class="av-photo" src="{{ auth()->user()->profilePhotoUrl() }}" alt="">
      @else
        <span class="av">{{ auth()->user()->initials }}</span>
      @endif
      <span class="me-copy"><b>{{ auth()->user()->name }}</b><small>Administrator</small></span>
    </a>
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button class="side-logout" type="submit" aria-label="Keluar dari akun" title="Keluar">
        @include('admin.partials.icon', ['name' => 'logout'])<span>Keluar</span>
      </button>
    </form>
  </div>
</aside>

{{-- Ciutkan / lebarkan sidebar, pilihan disimpan di browser --}}
<script>
(function () {
  var body = document.body, btn = document.getElementById('sideToggle');
  try { if (localStorage.getItem('adminSideCollapsed') === '1') body.classList.add('side-collapsed'); } catch (e) {}
  function sync() {
    var c = body.classList.contains('side-collapsed');
    btn.setAttribute('aria-expanded', c ? 'false' : 'true');
    btn.setAttribute('aria-label', c ? 'Lebarkan sidebar' : 'Ciutkan sidebar');
  }
  sync();
  btn.addEventListener('click', function () {
    body.classList.toggle('side-collapsed');
    try { localStorage.setItem('adminSideCollapsed', body.classList.contains('side-collapsed') ? '1' : '0'); } catch (e) {}
    sync();
  });
})();
</script>
