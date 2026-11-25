@extends('admin.admin')
@section('title', 'Dashboard')

@section('content')

<div class="hello dashboard-hello">
  <div>
    <h1>{{ $sapa }}, {{ $nama }}</h1>
    <p class="sub">{{ $tanggal }}</p>
    <p class="dashboard-description">Dashboard ini merangkum jumlah pelamar, status akun, aktivitas terbaru, serta akun yang baru dibuat. Gunakan menu di samping untuk mengelola pengguna, menambahkan akun HRD, dan meninjau catatan aktivitas.</p>
  </div>
  <svg class="dashboard-people" viewBox="0 0 380 210" role="img" aria-label="Ilustrasi dua orang bekerja bersama">
    <circle class="pulse-a" cx="245" cy="105" r="95" fill="#fff" opacity=".08"/>
    <circle class="pulse-b" cx="245" cy="105" r="72" fill="#fff" opacity=".06"/>
    <path d="M58 181h270" stroke="#b8d2f2" stroke-width="5" stroke-linecap="round" opacity=".6"/>
    <path d="M113 182v22m160-22v22" stroke="#b8d2f2" stroke-width="5" stroke-linecap="round" opacity=".55"/>
    <path d="M125 161h154v13H125z" fill="#f0b15b"/><path d="M139 174v25m126-25v25" stroke="#f0b15b" stroke-width="5" stroke-linecap="round"/>
    <rect x="177" y="83" width="83" height="59" rx="5" fill="#dcecff"/><rect class="screen" x="183" y="89" width="71" height="45" rx="2" fill="#4c79ad"/>
    <path d="M212 143h14l5 14h-24z" fill="#c1d9f5"/><path d="M198 158h43" stroke="#c1d9f5" stroke-width="4" stroke-linecap="round"/>
    <g class="person person-l">
      <path d="M92 181c1-26 13-43 34-43 19 0 29 12 33 30l3 13" fill="#56a2c9"/>
      <path d="M109 139c-4 14-4 26 2 42m39-42c4 12 7 25 8 42" stroke="#d4e9fc" stroke-width="7" stroke-linecap="round"/>
      <circle cx="127" cy="118" r="17" fill="#e9b58e"/><path d="M110 118c-2-15 7-25 19-25 12 0 19 9 18 20-7-1-12-6-15-11-4 8-12 13-22 16z" fill="#18345c"/>
    </g>
    <g class="person person-r">
      <path d="M282 182c2-32 16-53 39-53 24 0 37 20 39 53" fill="#f0a34e"/>
      <path d="M297 143l-18 25m56-24 15 27" stroke="#f8d6a7" stroke-width="8" stroke-linecap="round"/>
      <circle cx="321" cy="112" r="18" fill="#edbd99"/><path d="M303 111c0-16 8-26 19-26 14 0 21 11 19 24-7-2-13-8-16-14-5 9-12 14-22 16z" fill="#142d52"/>
    </g>
    <path d="M289 182l-9 19m62-19 8 19" stroke="#dcecff" stroke-width="7" stroke-linecap="round"/>
    <path class="plant" d="M72 181v-24m0 9c-13-1-19-9-20-19 12 0 19 7 20 19zm0-10c2-12 9-18 20-18-1 12-8 18-20 18z" fill="#a7c9ed" opacity=".8"/>
  </svg>
</div>

{{-- Kartu statistik --}}
<div class="grid">
  @foreach ($stats as $s)
    <div class="card stat scroll-reveal" style="--i: {{ $loop->index }}">
      <div class="ico">@include('admin.partials.icon', ['name' => $s['icon']])</div>
      <span>{{ $s['label'] }}</span>
      <b data-count="{{ $s['value'] }}">{{ $s['value'] }}</b>
      <small>{{ $s['note'] }}</small>
    </div>
  @endforeach
</div>

{{-- Status akun, aktivitas 7 hari, komposisi role --}}
<div class="three">
  <div class="card scroll-reveal" style="--i: 5">
    <div class="card-head"><h2>Status akun</h2></div>
    <div class="donut-wrap">
      <div class="donut" style="--p: {{ $pct }}"><i data-count="{{ $pct }}" data-suffix="%">{{ $pct }}%</i></div>
      <ul class="legend">
        <li><em class="dot on"></em>Aktif <b>{{ $aktif }}</b></li>
        <li><em class="dot off"></em>Nonaktif <b>{{ $nonaktif }}</b></li>
      </ul>
    </div>
  </div>

  <div class="card scroll-reveal" style="--i: 6">
    <div class="card-head"><h2>Aktivitas 7 hari terakhir</h2><span class="muted">{{ $weekTotal }} total</span></div>
    <div class="week">
      @foreach ($week as $d)
        <div class="day {{ $d['today'] ? 'today' : '' }}" style="--i: {{ $loop->index }}">
          <b>{{ $d['count'] }}</b>
          <div class="col"><i style="height: {{ round($d['count'] / $weekMax * 100) }}%"></i></div>
          <span>{{ $d['label'] }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <div class="card scroll-reveal" style="--i: 7">
    <div class="card-head"><h2>Komposisi role</h2></div>
    @foreach ($roles as $r)
      <div class="rb" style="--i: {{ $loop->index }}">
        <div class="rb-top"><span>{{ $r->label }}</span><b>{{ $r->total }}</b></div>
        <div class="rb-track"><i class="{{ $r->class }}" style="width: {{ $total ? round($r->total / $total * 100) : 0 }}%"></i></div>
      </div>
    @endforeach
  </div>
</div>

{{-- Aktivitas terbaru dan akun terbaru --}}
<div class="two-wide">
  <div class="card scroll-reveal" style="--i: 8">
    <div class="card-head"><h2>Aktivitas terbaru</h2><a href="{{ route('admin.logs') }}">Lihat semua</a></div>
    @if ($recent->isEmpty())
      <p class="muted">Belum ada aktivitas. Riwayat akan muncul saat pengguna login atau admin mengubah data.</p>
    @else
      <ul class="feed">
        @foreach ($recent as $l)
          <li style="--i: {{ $loop->index }}">
            <div class="av">{{ $l->user?->initials ?? '?' }}</div>
            <div>
              <p>{{ $l->activity }}</p>
              <small>{{ $l->user?->name ?? 'Pengguna dihapus' }} ({{ $l->user?->role_label ?? 'Tanpa role' }}) • {{ $l->created_at->locale('id')->diffForHumans() }}</small>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  <div class="card scroll-reveal" style="--i: 9">
    <div class="card-head"><h2>Akun terbaru</h2><a href="{{ route('admin.users.index') }}">Kelola</a></div>
    @if ($newUsers->isEmpty())
      <p class="muted">Belum ada akun.</p>
    @else
      <ul class="feed">
        @foreach ($newUsers as $u)
          <li style="--i: {{ $loop->index }}">
            <div class="av">{{ $u->initials }}</div>
            <div>
              <p>{{ $u->name }}</p>
              <small><span class="tag {{ $u->role_class }}">{{ $u->role_label }}</span>{{ $u->created_at->locale('id')->diffForHumans() }}</small>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>

{{-- Hitung naik untuk angka statistik --}}
<script>
(function () {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  document.querySelectorAll('[data-count]').forEach(function (el) {
    var raw = (el.dataset.count || '').trim();
    if (!/^\d+$/.test(raw)) return;
    var target = parseInt(raw, 10), suffix = el.dataset.suffix || '';
    if (target === 0) return;
    var dur = 1100, delay = 350, start = null;
    el.textContent = '0' + suffix;
    function tick(t) {
      if (start === null) start = t;
      var p = Math.min((t - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased) + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    setTimeout(function () { requestAnimationFrame(tick); }, delay);
  });
})();
</script>

<script>
(function () {
  var cards = document.querySelectorAll('.scroll-reveal');
  if (!cards.length || !('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.documentElement.classList.add('scroll-reveal-enabled');
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-revealed');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -36px 0px' });

  cards.forEach(function (card) { observer.observe(card); });
})();
</script>

@endsection
