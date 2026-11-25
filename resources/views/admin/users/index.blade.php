@extends('admin.admin')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="hello">
  <div>
    <h1>Kelola Pengguna</h1>
    <p class="sub">Kelola akun HRD dan status akun pelamar. Akun admin dikelola melalui halaman profil.</p>
  </div>
  <a class="btn" href="{{ route('admin.users.create') }}">Tambah akun HRD</a>
</div>

<div class="card">
  <div class="card-head"><h2>Pengguna</h2></div>
  <div style="overflow-x:auto">
    <table>
      <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      @forelse ($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td><span class="tag {{ $user->role_class }}">{{ $user->role_label }}</span></td>
          <td><span class="tag {{ $user->status === 'active' ? 'on' : 'off' }}">{{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span></td>
          <td>
            <div class="user-actions">
              @if(mb_strtolower($user->role_label) === 'hrd')
                <a class="btn sm" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun HRD ini? Akun tidak dapat digunakan untuk masuk setelah dihapus.')">
                  @csrf
                  @method('DELETE')
                  <button class="btn sm danger" type="submit">Hapus</button>
                </form>
              @elseif(mb_strtolower($user->role_label) === 'applicant')
                <form action="{{ route('admin.users.status', $user) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button class="btn sm {{ $user->status === 'active' ? 'ghost' : '' }}" type="submit">{{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                </form>
              @else
                <span class="muted">—</span>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="empty">Belum ada akun HRD atau Applicant.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  {{ $users->links() }}
</div>
@endsection
