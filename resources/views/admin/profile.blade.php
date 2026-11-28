@extends('admin.admin')
@section('title', 'Profil Admin')

@section('content')
<div class="hello">
  <div>
    <h1>Profil Admin</h1>
    <p class="sub">Kelola identitas dan informasi kontak akun panel admin Anda.</p>
  </div>
  <span class="tag admin">{{ auth()->user()->role_label }}</span>
</div>

<div class="admin-profile-layout">
  <section class="card admin-profile-card admin-profile-card--identity">
    <div class="admin-profile-heading">
      <div class="admin-profile-avatar" data-admin-avatar>
        @if($profilePhotoUrl)
          <img src="{{ $profilePhotoUrl }}" alt="Foto profil {{ auth()->user()->name }}">
        @else
          <span>{{ auth()->user()->initials }}</span>
        @endif
      </div>
      <div>
        <h2>{{ auth()->user()->name }}</h2>
        <p class="muted">Administrator panel Galeri Magang</p>
        <label class="btn sm admin-photo-button" for="adminProfilePhoto">Ganti foto</label>
        <input id="adminProfilePhoto" class="admin-photo-input" type="file" accept="image/jpeg,image/png,image/webp">
        <small id="adminPhotoStatus" class="muted" aria-live="polite"></small>
      </div>
    </div>
    <div id="adminPhotoError" class="alert err" role="alert" hidden></div>
    <div class="admin-profile-meta" aria-label="Ringkasan akun admin">
      <div class="admin-profile-meta-item">
        <span>Peran</span>
        <strong>{{ auth()->user()->role_label }}</strong>
      </div>
      <div class="admin-profile-meta-item">
        <span>Bergabung sejak</span>
        <strong>{{ auth()->user()->created_at?->locale('id')->translatedFormat('d F Y') ?? '—' }}</strong>
      </div>
    </div>
    <p class="admin-profile-tip">Foto profil ditampilkan pada navigasi panel admin.</p>
  </section>

  <section class="card admin-profile-card">
    <h2>Informasi akun</h2>
    <p class="muted admin-profile-description">Perubahan nama dan nomor kontak hanya berlaku untuk akun admin Anda.</p>
    <form id="adminProfileUpdateForm" class="admin-profile-form" action="{{ route('admin.profile.update') }}" method="POST">
      @csrf
      @method('PUT')
      <div class="f">
        <label for="adminName">Nama admin</label>
        <input id="adminName" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" maxlength="150" required>
      </div>
      <div class="f full-field">
        <label for="adminEmail">Email akun</label>
        <input id="adminEmail" type="email" value="{{ auth()->user()->email }}" readonly>
        <small>Email digunakan untuk masuk dan tidak dapat diubah dari halaman ini.</small>
      </div>
      <div class="f">
        <label for="adminPhone">Nomor WhatsApp</label>
        <input id="adminPhone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" autocomplete="tel" maxlength="40" placeholder="Contoh: +62 812...">
      </div>
    </form>
    <div class="admin-profile-actions">
      <button class="btn" type="submit" form="adminProfileUpdateForm">Simpan perubahan</button>
      <form id="adminProfileLogoutForm" action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn ghost" type="submit">Keluar dari akun</button>
      </form>
    </div>
  </section>
</div>

<script>
(() => {
  const input = document.getElementById('adminProfilePhoto');
  const avatar = document.querySelector('[data-admin-avatar]');
  const status = document.getElementById('adminPhotoStatus');
  const error = document.getElementById('adminPhotoError');
  if (!input || !avatar) return;
  input.addEventListener('change', async () => {
    const file = input.files?.[0];
    if (!file) return;
    error.hidden = true;
    status.textContent = 'Menyimpan foto...';
    const data = new FormData();
    data.append('profile_photo', file);
    data.append('_token', @json(csrf_token()));
    try {
      const response = await fetch(@json(route('admin.profile.photo.update')), {method:'POST', body:data, headers:{'Accept':'application/json'}});
      const payload = await response.json();
      if (!response.ok) throw new Error(payload.errors?.profile_photo?.[0] || payload.message || 'Foto gagal disimpan.');
      avatar.replaceChildren();
      const image = document.createElement('img');
      image.src = payload.photo_url;
      image.alt = 'Foto profil ' + @json(auth()->user()->name);
      avatar.append(image);
      status.textContent = 'Foto profil berhasil diperbarui.';
    } catch (exception) {
      error.textContent = exception.message || 'Foto gagal disimpan.';
      error.hidden = false;
      status.textContent = '';
    } finally {
      input.value = '';
    }
  });
})();
</script>
@endsection
