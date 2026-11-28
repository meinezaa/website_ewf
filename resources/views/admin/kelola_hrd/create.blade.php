@extends('admin.admin')
@section('title', 'Tambah Akun HRD')

@section('content')
<div class="hello">
  <div>
    <h1>Tambah Akun HRD</h1>
    <p class="sub">Buat akun HRD untuk membantu pengelolaan proses seleksi.</p>
  </div>
</div>

<form class="card hrd-create-form" method="POST" action="{{ route('admin.users.store') }}">
  @csrf
  <div class="form-section-heading">
    <div class="form-section-icon">HR</div>
    <div>
      <h2>Informasi akun</h2>
      <p>Masukkan data yang akan digunakan HRD untuk masuk ke panel.</p>
    </div>
  </div>

  <div class="hrd-create-grid">
    <div class="f">
      <label for="name">Nama lengkap</label>
      <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Contoh: Andi Pratama" required maxlength="150">
    </div>
    <div class="f">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@perusahaan.com" required maxlength="255">
    </div>
    <div class="f full-field">
      <label for="phone">Nomor telepon</label>
      <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" placeholder="Contoh: +62 812..." maxlength="40">
    </div>
    <div class="f">
      <label for="password">Kata sandi</label>
      <div class="password-input-wrap">
        <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Buat kata sandi" required>
        <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-controls="password" aria-pressed="false">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><path class="eye-slash" d="m3 3 18 18"/></svg>
        </button>
      </div>
      <small>Minimal 8 karakter, berisi huruf dan angka.</small>
    </div>
    <div class="f">
      <label for="password_confirmation">Konfirmasi kata sandi</label>
      <div class="password-input-wrap">
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi kata sandi" required>
        <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-controls="password_confirmation" aria-pressed="false">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><path class="eye-slash" d="m3 3 18 18"/></svg>
        </button>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <a class="btn ghost" href="{{ route('admin.users.index') }}">Batal</a>
    <button class="btn" type="submit">Buat akun HRD</button>
  </div>
</form>
@endsection
