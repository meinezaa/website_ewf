@extends('admin.admin')
@section('title', 'Activity Log')

@section('content')
<style>
  .activity-page{padding-bottom:28px}.activity-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin:34px 0 24px}.activity-head h1{margin:0 0 7px;font-size:30px;letter-spacing:-.04em}.activity-head p{margin:0;color:var(--muted);font-size:15px}.monitor-pill{display:inline-flex;align-items:center;gap:8px;padding:8px 13px;border-radius:999px;background:#e8f6ef;color:#218152;font-size:12px;font-weight:800}.monitor-pill:before{content:"";width:7px;height:7px;border-radius:50%;background:#26a269;box-shadow:0 0 0 4px #d2efdf}.activity-panel{background:var(--surface);border:1px solid var(--line);border-radius:22px;padding:24px;box-shadow:0 12px 34px rgba(34,63,96,.05)}.activity-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:28px}.activity-stat{padding:18px 20px;border:1px solid var(--line);border-radius:16px;background:linear-gradient(135deg,#fff,#f8faff)}.activity-stat strong{display:block;color:var(--accent);font-size:27px;line-height:1.1;letter-spacing:-.04em}.activity-stat span{display:block;margin-top:8px;color:var(--muted);font-size:12px;font-weight:600}.activity-section-title{font-size:16px;margin:0 0 13px}.activity-filters{display:grid;grid-template-columns:1fr 1fr 1.5fr auto;gap:10px;margin-bottom:26px}.activity-filters select,.activity-filters input{width:100%;height:44px;padding:0 13px;border:1px solid var(--line);border-radius:11px;background:#fff;outline:none}.activity-filters select:focus,.activity-filters input:focus{border-color:#8aa8d0;box-shadow:0 0 0 3px #e9f0fa}.activity-filters button,.activity-clear{height:44px;padding:0 16px;border:0;border-radius:11px;background:var(--accent);color:#fff;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer}.activity-list-title{font-size:16px;margin:0 0 4px}.activity-row{display:grid;grid-template-columns:38px minmax(0,1fr) auto;gap:12px;align-items:start;padding:19px 2px;border-bottom:1px solid #edf1f7}.activity-row:last-child{border-bottom:0}.activity-icon{width:34px;height:34px;display:grid;place-items:center;border-radius:11px;background:#edf4ff;color:#315f9d;font-size:17px;font-weight:800}.activity-icon.login,.activity-icon.register{background:#e6f7ee;color:#218152}.activity-icon.change{background:#e7f2ff;color:#2375bd}.activity-icon.user{background:#f2ecff;color:#8053bf}.activity-icon.logout{background:#fff0e8;color:#ae603c}.activity-main{min-width:0}.activity-main h3{font-size:14px;margin:1px 0 4px}.activity-meta,.activity-detail{color:var(--muted);font-size:12px;line-height:1.7;overflow-wrap:anywhere}.activity-badge{white-space:nowrap;padding:5px 10px;border-radius:999px;background:#edf2f8;color:#51647e;font-size:11px;font-weight:800}.activity-badge.login,.activity-badge.register{background:#e6f7ee;color:#218152}.activity-badge.change{background:#e7f2ff;color:#2375bd}.activity-badge.user{background:#f2ecff;color:#8053bf}.activity-badge.logout{background:#fff0e8;color:#ae603c}.activity-empty{padding:34px 12px;text-align:center;color:var(--muted)}.activity-pagination{margin-top:18px}.activity-pagination nav{display:flex;justify-content:space-between;align-items:center;gap:14px}.activity-pagination nav>div:first-child{color:var(--muted);font-size:12px}.activity-pagination nav>div:last-child span,.activity-pagination nav>div:last-child a{display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 9px;margin-left:4px;border:1px solid var(--line);border-radius:9px;text-decoration:none}.activity-pagination nav>div:last-child span[aria-current=page]{background:var(--accent);color:white;border-color:var(--accent)}
  @media(max-width:800px){.activity-page{padding:0 16px 24px}.activity-panel{padding:17px}.activity-filters{grid-template-columns:1fr 1fr}.activity-filters input{grid-column:1/-1}.activity-filters button{grid-column:1/-1}.activity-summary{gap:8px}.activity-stat{padding:14px}.activity-stat strong{font-size:22px}}
  @media(max-width:520px){.activity-head{margin-top:24px}.activity-head h1{font-size:25px}.activity-summary{grid-template-columns:1fr}.activity-stat{display:flex;align-items:center;justify-content:space-between}.activity-stat span{margin:0;text-align:right}.activity-row{grid-template-columns:32px minmax(0,1fr)}.activity-badge{grid-column:2;justify-self:start;grid-row:2}}
</style>

<section class="activity-page">
  <div class="activity-head">
    <div>
      <h1>Activity Log</h1>
      <p>Riwayat aktivitas seluruh pengguna website.</p>
    </div>
    <span class="monitor-pill">Monitoring</span>
  </div>

  <div class="activity-panel">
    <div class="activity-summary" aria-label="Ringkasan aktivitas">
      <div class="activity-stat"><strong>{{ number_format($totalActivities, 0, ',', '.') }}</strong><span>Total aktivitas</span></div>
      <div class="activity-stat"><strong>{{ number_format($todayActivities, 0, ',', '.') }}</strong><span>Aktivitas hari ini</span></div>
      <div class="activity-stat"><strong>{{ number_format($activeUsers, 0, ',', '.') }}</strong><span>Pengguna aktif hari ini</span></div>
    </div>

    <h2 class="activity-section-title">Filter aktivitas</h2>
    <form class="activity-filters" method="GET" action="{{ route('admin.logs') }}">
      <select name="role" aria-label="Filter role">
        <option value="">Semua role</option>
        @foreach ($roles as $role)
          <option value="{{ $role }}" @selected(mb_strtolower($filters['role'] ?? '') === mb_strtolower($role))>{{ $role }}</option>
        @endforeach
      </select>
      <select name="activity" aria-label="Filter jenis aktivitas">
        <option value="">Semua aktivitas</option>
        @foreach (['login' => 'Login', 'logout' => 'Logout', 'registrasi' => 'Registrasi', 'pendaftaran' => 'Pendaftaran', 'perubahan' => 'Perubahan', 'sertifikat' => 'Sertifikat', 'pengguna' => 'Pengguna'] as $key => $label)
          <option value="{{ $key }}" @selected(($filters['activity'] ?? '') === $key)>{{ $label }}</option>
        @endforeach
      </select>
      <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau aktivitas..." aria-label="Cari nama atau aktivitas">
      <button type="submit">Terapkan</button>
    </form>

    <h2 class="activity-list-title">Riwayat Aktivitas</h2>
    @forelse ($logs as $log)
      @php
        $text = (string) $log->activity;
        $lower = mb_strtolower($text);
        $role = $log->user?->role?->role_name ?? 'Pengguna';
        if (str_contains($lower, 'login')) {
            $category = 'Login'; $tone = 'login'; $title = $role.' berhasil login'; $icon = 'IN';
        } elseif (str_contains($lower, 'logout')) {
            $category = 'Logout'; $tone = 'logout'; $title = $role.' logout'; $icon = 'OUT';
        } elseif (str_contains($lower, 'sertifikat') || str_contains($lower, 'certificate')) {
            $category = 'Sertifikat'; $tone = 'change'; $title = $text; $icon = 'OK';
        } elseif (str_contains($lower, 'membuat akun baru') || str_contains($lower, 'registrasi')) {
            $category = 'Registrasi'; $tone = 'register'; $title = 'Membuat akun baru'; $icon = '+';
        } elseif (str_contains($lower, 'akun hrd') || str_contains($lower, 'akun applicant')) {
            $category = 'Pengguna'; $tone = 'user'; $title = $text; $icon = 'U';
        } elseif (str_contains($lower, 'lamaran') || str_contains($lower, 'pendaftaran')) {
            $category = 'Pendaftaran'; $tone = 'change'; $title = $text; $icon = 'P';
        } elseif (str_contains($lower, 'status') || str_contains($lower, 'memperbarui') || str_contains($lower, 'mengubah') || str_contains($lower, 'mengirim')) {
            $category = 'Perubahan'; $tone = 'change'; $title = $text; $icon = 'OK';
        } else {
            $category = 'Aktivitas'; $tone = ''; $title = $text; $icon = '*';
        }
      @endphp
      <article class="activity-row">
        <span class="activity-icon {{ $tone }}" aria-hidden="true">{{ $icon }}</span>
        <div class="activity-main">
          <h3>{{ $title }}</h3>
          <div class="activity-meta">{{ $role }} &bull; {{ $log->user?->email ?? 'Akun tidak tersedia' }}</div>
          <div class="activity-meta">{{ $log->created_at?->copy()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H.i') ?? 'Waktu tidak tersedia' }} WIB</div>
          @if ($title !== $text && !in_array($category, ['Login', 'Logout', 'Registrasi'], true))
            <div class="activity-detail">{{ $text }}</div>
          @endif
        </div>
        <span class="activity-badge {{ $tone }}">{{ $category }}</span>
      </article>
    @empty
      <div class="activity-empty">Belum ada aktivitas yang cocok dengan filter.</div>
    @endforelse

    <div class="activity-pagination">{{ $logs->links() }}</div>
  </div>
</section>
@endsection
