<section class="hrd-view is-visible" id="view-dashboard" data-title="Dashboard" data-dashboard-snapshot="{{ route('hrd.dashboard.snapshot') }}">
    @php
        $hour = now()->hour;
        $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
        $pendingEnd = $statusStats['pending_percent'];
        $reviewEnd = $pendingEnd + $statusStats['in_review_percent'];
        $acceptedEnd = $reviewEnd + $statusStats['accepted_percent'];
    @endphp
    <div class="hrd-page-heading">
        <div>
            <span class="hrd-overline"><i class="bi bi-sun" aria-hidden="true"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
            <h1>{{ $greeting }}, {{ $hrdUser->name }} <span>👋</span></h1>
            <p>Berikut ringkasan aktivitas rekrutmen dan tim Anda hari ini.</p>
        </div>
        <button class="hrd-button hrd-button--secondary" type="button" data-export data-export-url="{{ route('hrd.export', ['section' => 'dashboard']) }}"><i class="bi bi-download" aria-hidden="true"></i> Unduh laporan</button>
    </div>

    <section class="hrd-welcome-banner">
        <div class="hrd-banner-content">
            <span class="hrd-banner-pill"><i class="bi bi-stars" aria-hidden="true"></i> RINGKASAN REKRUTMEN</span>
            <h2>Temukan talenta yang tepat,<br>bangun tim yang hebat.</h2>
            <p>Pantau proses seleksi dan perkembangan kandidat dalam satu tempat.</p>
            <button class="hrd-banner-link" type="button" data-open-view="staff-applicants">Lihat pendaftar</button>
        </div>
        <div class="hrd-banner-art" aria-hidden="true">
            <span class="hrd-art-orbit hrd-art-orbit--one"></span><span class="hrd-art-orbit hrd-art-orbit--two"></span>
            <div class="hrd-art-card hrd-art-card--back"><i class="bi bi-person-check"></i><span></span><span></span></div>
            <div class="hrd-art-card hrd-art-card--front"><span class="hrd-art-avatar"><i class="bi bi-person-fill"></i></span><span class="hrd-art-line"></span><span class="hrd-art-line short"></span><b><i class="bi bi-stars"></i> Rekrutmen</b></div>
            <span class="hrd-art-spark hrd-art-spark--a"><i class="bi bi-stars"></i></span><span class="hrd-art-spark hrd-art-spark--b"><i class="bi bi-plus-lg"></i></span>
        </div>
    </section>

    <section class="hrd-stat-grid" aria-label="Ringkasan statistik">
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Total pendaftar staf</span><span class="hrd-stat-icon hrd-stat-icon--blue"><i class="bi bi-person-workspace" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="staff_total">{{ number_format($statusStats['staff_total'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend" data-dashboard-stat="staff_month">{{ number_format($statusStats['staff_month'], 0, ',', '.') }} bulan ini</span><span>pendaftar staf</span><svg viewBox="0 0 84 26" aria-hidden="true"><path d="M1 22C12 18 13 20 21 14S34 19 43 12s11 2 19-4 12 3 21-7" /></svg></div>
        </article>
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Total pendaftar magang</span><span class="hrd-stat-icon hrd-stat-icon--blue"><i class="bi bi-mortarboard" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="magang_total">{{ number_format($statusStats['magang_total'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend" data-dashboard-stat="magang_month">{{ number_format($statusStats['magang_month'], 0, ',', '.') }} bulan ini</span><span>pendaftar magang</span><svg viewBox="0 0 84 26" aria-hidden="true"><path d="M1 22C12 18 13 20 21 14S34 19 43 12s11 2 19-4 12 3 21-7" /></svg></div>
        </article>
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Total pendaftar PKL</span><span class="hrd-stat-icon hrd-stat-icon--blue"><i class="bi bi-mortarboard" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="pkl_total">{{ number_format($statusStats['pkl_total'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend" data-dashboard-stat="pkl_month">{{ number_format($statusStats['pkl_month'], 0, ',', '.') }} bulan ini</span><span>pendaftar PKL</span><svg viewBox="0 0 84 26" aria-hidden="true"><path d="M1 22C12 18 13 20 21 14S34 19 43 12s11 2 19-4 12 3 21-7" /></svg></div>
        </article>
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Menunggu pemeriksaan</span><span class="hrd-stat-icon hrd-stat-icon--amber"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="pending">{{ number_format($statusStats['pending'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend hrd-trend--amber" data-dashboard-stat="pending_percent">{{ number_format($statusStats['pending_percent'], 1, ',', '.') }}% pendaftar</span><span>perlu diperiksa</span><svg class="amber" viewBox="0 0 84 26" aria-hidden="true"><path d="M1 19C10 22 17 10 25 15s14-7 23-3 13-9 20-5 10 0 15-6" /></svg></div>
        </article>
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Sedang diproses</span><span class="hrd-stat-icon hrd-stat-icon--violet"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="in_review">{{ number_format($statusStats['in_review'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend hrd-trend--violet" data-dashboard-stat="in_review_percent">{{ number_format($statusStats['in_review_percent'], 1, ',', '.') }}% pendaftar</span><span>dalam tahap seleksi</span><svg class="violet" viewBox="0 0 84 26" aria-hidden="true"><path d="M1 20C9 14 15 18 23 10s14 9 23 2 13 0 21-7 10 3 16-2" /></svg></div>
        </article>
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Diterima / selesai</span><span class="hrd-stat-icon hrd-stat-icon--green"><i class="bi bi-person-check" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="accepted">{{ number_format($statusStats['accepted'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend hrd-trend--green" data-dashboard-stat="accepted_percent">{{ number_format($statusStats['accepted_percent'], 1, ',', '.') }}% pendaftar</span><span>diterima atau selesai</span><svg class="green" viewBox="0 0 84 26" aria-hidden="true"><path d="M1 23C10 18 16 22 23 13s13 5 21-1 14 5 22-4 10 1 17-7" /></svg></div>
        </article>
        <article class="hrd-stat-card">
            <div class="hrd-stat-top"><span>Ditolak</span><span class="hrd-stat-icon hrd-stat-icon--rose"><i class="bi bi-person-x" aria-hidden="true"></i></span></div>
            <strong class="hrd-stat-number" data-dashboard-stat="rejected">{{ number_format($statusStats['rejected'], 0, ',', '.') }}</strong>
            <div class="hrd-stat-foot"><span class="hrd-trend hrd-trend--rose" data-dashboard-stat="rejected_percent">{{ number_format($statusStats['rejected_percent'], 1, ',', '.') }}% pendaftar</span><span>belum sesuai kriteria</span><svg class="rose" viewBox="0 0 84 26" aria-hidden="true"><path d="M1 7C10 11 16 5 23 12s13-2 21 4 14-1 22 4 10-1 17 4" /></svg></div>
        </article>
    </section>

    <div class="hrd-dashboard-grid">
        <section class="hrd-panel hrd-chart-panel">
            <div class="hrd-panel-heading">
                <div><h2>Tren pendaftar</h2><p>Perkembangan pendaftar berdasarkan tanggal pendaftaran</p></div>
                <label class="hrd-select-wrap"><select id="hrdTrendPeriod" aria-label="Periode grafik"><option value="6">6 bulan terakhir</option><option value="12">12 bulan terakhir</option></select><i class="bi bi-chevron-down" aria-hidden="true"></i></label>
            </div>
            <div class="hrd-chart-legend"><span><i class="legend-blue"></i>Staf</span><span><i class="legend-light"></i>Magang</span><span><i class="legend-pkl"></i>PKL</span><span><i class="legend-unclassified"></i>Belum dikategorikan</span><b id="hrdTrendComparison">Berdasarkan data pendaftaran</b></div>
            <div class="hrd-chart">
                <div class="hrd-chart-y" id="hrdTrendYAxis"><span>0</span><span>0</span><span>0</span><span>0</span><span>0</span></div>
                <div class="hrd-chart-plot">
                    <div class="hrd-chart-grid"><i></i><i></i><i></i><i></i><i></i></div>
                    <svg viewBox="0 0 700 210" preserveAspectRatio="none" role="img" aria-label="Grafik jumlah pendaftar dari database">
                        <defs><linearGradient id="staffArea" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#557cae" stop-opacity=".20"/><stop offset="1" stop-color="#557cae" stop-opacity="0"/></linearGradient><linearGradient id="magangArea" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#a9c2de" stop-opacity=".28"/><stop offset="1" stop-color="#a9c2de" stop-opacity="0"/></linearGradient></defs>
                        <path class="hrd-area hrd-area--staff" d="M0 210 L700 210 Z"/>
                        <path class="hrd-area hrd-area--magang" d="M0 210 L700 210 Z"/>
                        <path class="hrd-line hrd-line--staff" d="M0 210 L700 210"/>
                        <path class="hrd-line hrd-line--magang" d="M0 210 L700 210"/>
                        <path class="hrd-line hrd-line--pkl" d="M0 210 L700 210"/>
                        <path class="hrd-line hrd-line--unclassified" d="M0 210 L700 210"/>
                        <circle id="hrdTrendPointStaff" cx="0" cy="210" r="5" class="hrd-chart-point hrd-chart-point--staff"/>
                        <circle id="hrdTrendPointMagang" cx="0" cy="210" r="5" class="hrd-chart-point hrd-chart-point--magang"/>
                        <circle id="hrdTrendPointPkl" cx="0" cy="210" r="5" class="hrd-chart-point hrd-chart-point--pkl"/>
                        <circle id="hrdTrendPointUnclassified" cx="0" cy="210" r="5" class="hrd-chart-point hrd-chart-point--unclassified"/>
                    </svg>
                    <div class="hrd-chart-x" id="hrdTrendXAxis"></div>
                </div>
            </div>
            <script type="application/json" id="hrdTrendData">@json($trendData)</script>
        </section>

        <section class="hrd-panel hrd-status-panel">
            <div class="hrd-panel-heading"><div><h2>Status pendaftar</h2><p><span data-dashboard-stat="total">{{ number_format($statusStats['total'], 0, ',', '.') }}</span> pendaftar terdaftar</p></div></div>
            <div class="hrd-donut-wrap"><div class="hrd-donut" id="hrdStatusDonut" style="background: conic-gradient(#b29262 0 {{ $pendingEnd }}%, #6487ad {{ $pendingEnd }}% {{ $reviewEnd }}%, #76a98e {{ $reviewEnd }}% {{ $acceptedEnd }}%, #ad7373 {{ $acceptedEnd }}% 100%);"><div><strong data-dashboard-stat="total">{{ number_format($statusStats['total'], 0, ',', '.') }}</strong><span>Total</span></div></div><div class="hrd-donut-note">Ringkasan semua pendaftar</div></div>
            <div class="hrd-table-wrap"><table class="hrd-table hrd-status-breakdown"><thead><tr><th>KATEGORI</th><th>MENUNGGU</th><th>DI PROSES</th><th>DITERIMA</th><th>SELESAI</th><th>DITOLAK</th><th>TOTAL</th></tr></thead><tbody id="hrdStatusBreakdown">
                @foreach ($categoryStatusRows as $row)
                    <tr><td>{{ $row['label'] }}</td><td>{{ number_format($row['pending'], 0, ',', '.') }}</td><td>{{ number_format($row['in_review'], 0, ',', '.') }}</td><td>{{ number_format($row['accepted'], 0, ',', '.') }}</td><td>{{ number_format($row['completed'], 0, ',', '.') }}</td><td>{{ number_format($row['rejected'], 0, ',', '.') }}</td><td><strong>{{ number_format($row['total'], 0, ',', '.') }}</strong></td></tr>
                @endforeach
            </tbody></table></div>
        </section>
    </div>

    <section class="hrd-panel hrd-recent-panel">
        <div class="hrd-panel-heading hrd-recent-heading"><div><h2>Pendaftar terbaru</h2><p>Kandidat terbaru dari semua jalur pendaftaran</p></div><button class="hrd-text-link" type="button" data-open-view="staff-applicants">Lihat semua</button></div>
        <div class="hrd-table-wrap"><table class="hrd-table"><thead><tr><th>NAMA KANDIDAT</th><th>POSISI / PROGRAM</th><th>TANGGAL DAFTAR</th><th>STATUS</th><th></th></tr></thead><tbody id="hrdRecentApplications">
            @forelse ($recentApplications as $application)
                <tr data-name="{{ $application['name'] }}" data-position="{{ $application['position'] }}" data-period="{{ $application['period'] }}" data-status="{{ $application['status'] }}">
                    <td><div class="hrd-person-cell"><span class="hrd-avatar hrd-avatar--blue">@if ($application['photo_url'])<img src="{{ $application['photo_url'] }}" alt="" loading="lazy" decoding="async">@else{{ $application['initials'] }}@endif</span><span><strong>{{ $application['name'] }}</strong><small>{{ $application['email'] }}</small></span></div></td>
                    <td>{{ $application['position'] }}<small class="hrd-cell-sub">{{ $application['type_label'] }} · {{ $application['location'] }}</small></td>
                    <td>{{ $application['joined'] }}</td>
                    <td><span class="hrd-badge {{ $application['status_class'] }}" data-status-badge><i></i>{{ $application['status_label'] }}</span></td>
                    <td><button class="hrd-row-action" type="button" data-detail="{{ $application['name'] }}" data-role="{{ $application['type_label'] }} · {{ $application['position'] }}" data-status="{{ $application['status'] }}" data-status-label="{{ $application['status_label'] }}" data-status-url="{{ $application['status_url'] }}" data-tracking-url="{{ $application['tracking_url'] }}" data-interview-url="{{ $application['interview_url'] }}" data-type="{{ $application['type'] }}" data-initials="{{ $application['initials'] }}" data-organization="{{ $application['organization'] }}" data-period="{{ $application['period'] }}" data-date="{{ $application['joined'] }}" data-email="{{ $application['email'] }}" data-documents="{{ json_encode($application['documents'], JSON_HEX_APOS | JSON_HEX_QUOT) }}">Detail <i class="bi bi-arrow-up-right"></i></button></td>
                </tr>
            @empty
                <tr class="hrd-empty-row"><td colspan="5">Belum ada pendaftar di database.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
</section>
