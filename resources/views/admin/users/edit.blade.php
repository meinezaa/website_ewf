@extends('admin.admin')
@section('title', 'Edit Akun HRD')

@section('content')
<div class="hello">
  <div>
    <h1>Edit Akun HRD</h1>
    <p class="sub">Perbarui informasi akun HRD. Biarkan kata sandi kosong jika tidak ingin menggantinya.</p>
  </div>
</div>

<form class="card form-card" method="POST" action="{{ route('admin.users.update', $user) }}">
  @csrf
  @method('PUT')
  <div class="f">
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="150">
  </div>
  <div class="f">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255">
  </div>
  <div class="f">
    <label for="phone">Nomor telepon</label>
    <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" maxlength="40">
  </div>
  <div class="f">
    <label for="status">Status akun</label>
    <select id="status" name="status" required>
      <option value="active" @selected(old('status', $user->status) === 'active')>Aktif</option>
      <option value="inactive" @selected(old('status', $user->status) !== 'active')>Nonaktif</option>
    </select>
    <small>Akun nonaktif tidak dapat digunakan untuk masuk ke panel.</small>
  </div>
  <div class="f">
    <label for="password">Kata sandi baru <span class="muted">(opsional)</span></label>
    <div class="password-input-wrap">
      <input id="password" name="password" type="password" autocomplete="new-password">
      <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi baru" aria-controls="password" aria-pressed="false">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><path class="eye-slash" d="m3 3 18 18"/></svg>
      </button>
    </div>
  </div>
  <div class="f">
    <label for="password_confirmation">Konfirmasi kata sandi baru</label>
    <div class="password-input-wrap">
      <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
      <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-controls="password_confirmation" aria-pressed="false">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><path class="eye-slash" d="m3 3 18 18"/></svg>
      </button>
    </div>
  </div>
  <div class="user-edit-actions">
    <a class="btn ghost" href="{{ route('admin.users.index') }}">Batal</a>
    <button class="btn" type="submit">Simpan perubahan</button>
  </div>
</form>
@endsection
