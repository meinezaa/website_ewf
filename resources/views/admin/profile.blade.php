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
  <section class="card admin-profile-card">
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
  </section>

  <section class="card admin-profile-card">
    <h2>Informasi akun</h2>
    <p class="muted admin-profile-description">Perubahan nama dan nomor kontak hanya berlaku untuk akun admin Anda.</p>
    <form action="{{ route('admin.profile.update') }}" method="POST">
      @csrf
      @method('PUT')
      <div class="f">
        <label for="adminName">Nama admin</label>
        <input id="adminName" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" maxlength="150" required>
      </div>
      <div class="f">
        <label for="adminEmail">Email akun</label>
        <input id="adminEmail" type="email" value="{{ auth()->user()->email }}" readonly>
        <small>Email digunakan untuk masuk dan tidak dapat diubah dari halaman ini.</small>
      </div>
      <div class="f">
        <label for="adminPhone">Nomor WhatsApp</label>
        <input id="adminPhone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" autocomplete="tel" maxlength="40" placeholder="Contoh: +62 812...">
      </div>
      <div class="row"><span>Peran</span><b>{{ auth()->user()->role_label }}</b></div>
      <div class="row"><span>Bergabung</span><b>{{ auth()->user()->created_at?->locale('id')->translatedFormat('d F Y') ?? '—' }}</b></div>
      <div class="admin-profile-actions">
        <button class="btn" type="submit">Simpan perubahan</button>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="btn ghost" type="submit">Keluar dari akun</button>
        </form>
      </div>
    </form>
  </section>
</div>

<style>
  .admin-profile-layout{display:grid;grid-template-columns:minmax(260px,.8fr) minmax(320px,1.2fr);gap:16px;align-items:start}
  .admin-profile-card{padding:22px}
  .admin-profile-card h2{margin-bottom:8px}
  .admin-profile-heading{display:flex;align-items:center;gap:16px}
  .admin-profile-avatar{width:84px;height:84px;border-radius:18px;overflow:hidden;display:grid;place-items:center;flex:none;background:var(--accent-soft);color:var(--accent);font-size:25px;font-weight:700}
  .admin-profile-avatar img{width:100%;height:100%;object-fit:cover}
  .admin-profile-heading h2{font-size:18px;margin:0 0 3px}
  .admin-photo-button{margin-top:10px}
  .admin-photo-input{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0)}
  .admin-photo-input:focus-visible + .muted{outline:2px solid var(--accent)}
  .admin-profile-description{margin:-4px 0 18px}
  .admin-profile-actions{display:flex;justify-content:flex-end;align-items:center;gap:10px;margin-top:18px}
  .admin-profile-actions .foot{margin:0}
  .admin-profile-actions form{margin:0}
  @media(max-width:760px){.admin-profile-layout{grid-template-columns:1fr}}
</style>
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
