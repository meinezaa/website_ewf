<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f7fb">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard HRD | Galeri Magang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="hrd-body">
    @php
        $hrdPhotoUrl = $hrdUser->profilePhotoUrl();
        $hrdCoverUrl = $hrdUser->profileCoverUrl();
        $hrdInitials = collect(explode(' ', trim($hrdUser->name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->implode('');
    @endphp
    <div class="hrd-app">
        <aside class="hrd-sidebar" id="hrdSidebar">
            <div class="hrd-sidebar-header">
                <a class="hrd-brand" href="#dashboard" data-open-view="dashboard" aria-label="Galeri Magang">
                    <span class="hrd-brand-mark"><img src="{{ asset('image/' . rawurlencode('logo galeri magang.png')) }}" alt=""></span>
                    <span class="hrd-brand-copy"><strong><span class="hrd-brand-galeri">GALERI</span> <span class="hrd-brand-magang">MAGANG</span></strong><small>PT EQUITYWORLD FUTURES</small></span>
                </a>
            </div>
            <div class="hrd-workspace-label">RUANG KERJA</div>
            <nav class="hrd-navigation" aria-label="Navigasi HRD">
                <button class="hrd-nav-link is-active" data-view="dashboard" data-label="Dashboard" data-section="Ruang Kerja" type="button" aria-label="Dashboard" title="Dashboard"><i class="bi bi-grid-1x2" aria-hidden="true"></i><span>Dashboard</span></button>
                <div class="hrd-nav-caption">REKRUTMEN</div>
                <button class="hrd-nav-link" data-view="staff-applicants" data-label="Pendaftaran Staf" data-section="Rekrutmen" type="button" aria-label="Pendaftaran Staf" title="Pendaftaran Staf"><i class="bi bi-person-workspace" aria-hidden="true"></i><span>Pendaftaran Staf</span><em data-dashboard-stat="staff_pending">{{ $statusStats['staff_pending'] }}</em></button>
                <button class="hrd-nav-link" data-view="intern-applicants" data-label="Pendaftaran Magang/PKL" data-section="Rekrutmen" type="button" aria-label="Pendaftaran Magang/PKL" title="Pendaftaran Magang/PKL"><i class="bi bi-mortarboard" aria-hidden="true"></i><span>Pendaftaran Magang/PKL</span><em data-dashboard-stat="internship_pending">{{ $statusStats['internship_pending'] }}</em></button>
                <button class="hrd-nav-link" data-view="career-openings" data-label="Manajemen Lowongan Kerja" data-section="Rekrutmen" type="button"><i class="bi bi-briefcase" aria-hidden="true"></i><span>Manajemen Lowongan Kerja</span></button>
                <button class="hrd-nav-link" data-view="internship-openings" data-label="Manajemen Lowongan Magang/PKL" data-section="Rekrutmen" type="button"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i><span>Manajemen Lowongan Magang/PKL</span></button>
                <div class="hrd-nav-caption">ORGANISASI</div>
                <button class="hrd-nav-link" data-view="staff-data" data-label="Data Staf" data-section="Organisasi" type="button" aria-label="Data Staf" title="Data Staf"><i class="bi bi-people" aria-hidden="true"></i><span>Data Staf</span></button>
                <button class="hrd-nav-link" data-view="intern-data" data-label="Data Peserta Magang/PKL" data-section="Organisasi" type="button" aria-label="Data Peserta Magang/PKL" title="Data Peserta Magang/PKL"><i class="bi bi-person-vcard" aria-hidden="true"></i><span>Data Peserta Magang/PKL</span></button>
                <button class="hrd-nav-link" data-view="documents" data-label="Sertifikat & LoA" data-section="Organisasi" type="button" aria-label="Sertifikat dan LoA" title="Sertifikat &amp; LoA"><i class="bi bi-file-earmark-check" aria-hidden="true"></i><span>Sertifikat &amp; LoA</span></button>
                <div class="hrd-nav-caption">PREFERENSI</div>
                <button class="hrd-nav-link" data-view="profile" data-label="Profil Saya" data-section="Preferensi" type="button" aria-label="Profil Saya" title="Profil Saya"><i class="bi bi-person-gear" aria-hidden="true"></i><span>Profil Saya</span></button>
            </nav>
            <div class="hrd-sidebar-bottom">
                <div class="hrd-sidebar-user">
                    <span class="hrd-avatar hrd-avatar--navy" data-profile-avatar>
                        @if ($hrdPhotoUrl)<img src="{{ $hrdPhotoUrl }}" alt="">@else{{ $hrdInitials }}@endif
                    </span>
                    <span class="hrd-user-copy"><strong>{{ $hrdUser->name }}</strong><small>HRD</small></span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="hrd-logout-button" type="submit"><i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Keluar</span></button>
                    </form>
                </div>
            </div>
        </aside>
        <button class="hrd-sidebar-toggle" id="hrdSidebarCollapse" type="button" aria-label="Kecilkan sidebar" aria-controls="hrdSidebar" aria-expanded="true" title="Kecilkan sidebar">
            <i id="hrdSidebarArrow" class="bi bi-chevron-left" aria-hidden="true"></i>
        </button>

        <div class="hrd-main">
            <header class="hrd-topbar">
                <button class="hrd-mobile-menu" id="hrdMenuToggle" type="button" aria-label="Buka navigasi"><i class="bi bi-list" aria-hidden="true"></i></button>
                <div class="hrd-breadcrumb"><span id="hrdBreadcrumbSection">Ruang Kerja</span><i class="bi bi-chevron-right" aria-hidden="true"></i><strong id="hrdBreadcrumb">Dashboard</strong></div>
                <div class="hrd-topbar-actions">
                    <label class="hrd-global-search"><i class="bi bi-search" aria-hidden="true"></i><input id="hrdSearch" type="search" placeholder="Cari nama, posisi, atau institusi..." aria-label="Cari pendaftar"><kbd>Ctrl K</kbd></label>
                    <button class="hrd-avatar hrd-avatar--navy hrd-avatar--top hrd-profile-shortcut" type="button" data-open-view="profile" data-profile-avatar aria-label="Buka profil saya" title="Profil Saya">
                        @if ($hrdPhotoUrl)<img src="{{ $hrdPhotoUrl }}" alt="">@else{{ $hrdInitials }}@endif
                    </button>
                </div>
            </header>

            <main class="hrd-content">
                @include('hrd.dashboard')
                @include('hrd.staff-applicants')
                @include('hrd.intern-applicants')
                @include('hrd.career-openings', ['openingType' => 'professional', 'viewId' => 'career-openings', 'heading' => 'Manajemen Lowongan Kerja'])
                @include('hrd.career-openings', ['openingType' => 'internship', 'viewId' => 'internship-openings', 'heading' => 'Manajemen Lowongan Magang/PKL'])
                @include('hrd.staff-data')
                @include('hrd.intern-data')
                @include('hrd.documents')
                @include('hrd.profile')
                <footer class="hrd-footer"><span>© 2026 PT Equityworld Futures</span></footer>
            </main>
        </div>
    </div>
    <div class="hrd-sidebar-scrim" id="hrdSidebarScrim"></div>
    <div class="hrd-toast" id="hrdToast" role="status" aria-live="polite"></div>
    <dialog class="hrd-detail-dialog" id="hrdDetailDialog" aria-labelledby="hrdDetailName">
        <button class="hrd-dialog-close" type="button" data-close-dialog aria-label="Tutup"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
        <span class="hrd-dialog-eyebrow">DETAIL PENDAFTAR</span>
        <div class="hrd-detail-identity"><span class="hrd-avatar hrd-avatar--blue hrd-dialog-avatar" id="hrdDetailInitials"></span><div><h2 id="hrdDetailName">Nama Kandidat</h2><p id="hrdDetailRole">Posisi kandidat</p><span class="hrd-dialog-status" id="hrdDetailStatus"></span></div></div>
        <div class="hrd-detail-facts">
            <div><small>EMAIL</small><strong id="hrdDetailEmail">-</strong></div>
            <div><small>ASAL SEKOLAH / PERGURUAN TINGGI</small><strong id="hrdDetailInstitution">-</strong></div>
            <div><small>PENDIDIKAN / PERIODE</small><strong id="hrdDetailPeriod">-</strong></div>
            <div><small>TANGGAL DAFTAR</small><strong id="hrdDetailDate">-</strong></div>
        </div>
        <section class="hrd-detail-documents">
            <div class="hrd-detail-section-heading"><div><h3>Berkas persyaratan</h3><p>Berkas asli yang tersimpan di server.</p></div><span class="hrd-file-count" id="hrdDetailFileCount">0 berkas</span></div>
            <div class="hrd-file-list" id="hrdDetailFileList"><p>Dokumen belum tersedia.</p></div>
        </section>
        <section class="hrd-interview-panel" aria-labelledby="hrdInterviewHeading">
            <h3 id="hrdInterviewHeading">Seleksi wawancara</h3>
            <p class="hrd-interview-attendance" id="hrdInterviewAttendance">Konfirmasi kehadiran belum tersedia.</p>
            <form id="hrdInterviewForm">
                <label for="hrdInterviewAt">Tanggal dan jam wawancara</label>
                <input id="hrdInterviewAt" name="interview_at" type="datetime-local" required>
                <label for="hrdInterviewInformation">Informasi jadwal</label>
                <textarea id="hrdInterviewInformation" name="interview_information" rows="3" maxlength="2000" required placeholder="Tulis lokasi atau tautan, kontak, dan arahan wawancara."></textarea>
                <button class="hrd-button hrd-button--primary" type="submit">Simpan jadwal</button>
                <small id="hrdInterviewError" role="alert" hidden></small>
            </form>
            <ol class="hrd-tracking-events" id="hrdTrackingEvents" aria-label="Riwayat tahapan pendaftaran"></ol>
        </section>
        <form class="hrd-status-update" id="hrdStatusForm"><label for="hrdStatusSelect">Pembaruan tahap seleksi</label><div><select id="hrdStatusSelect" name="status"><option value="pending">Verifikasi berkas</option><option value="in_review">Seleksi wawancara / dalam seleksi</option><option value="accepted">Diterima</option><option value="rejected">Tidak lolos</option></select><button class="hrd-button hrd-button--primary" type="submit">Simpan tahap</button></div><small id="hrdStatusError" role="alert" hidden></small></form>
    </dialog>
    <dialog class="hrd-preview-dialog" id="hrdDocumentPreviewDialog" aria-labelledby="hrdDocumentPreviewTitle">
        <div class="hrd-preview-header">
            <div><h2 id="hrdDocumentPreviewTitle">Pratinjau berkas</h2><p id="hrdDocumentPreviewFilename"></p></div>
            <button class="hrd-dialog-close" type="button" data-close-dialog aria-label="Tutup pratinjau"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
        </div>
        <div class="hrd-preview-stage" id="hrdDocumentPreviewStage"></div>
    </dialog>

    <script>
        (() => {
            const sidebar = document.getElementById('hrdSidebar');
            const sidebarCollapse = document.getElementById('hrdSidebarCollapse');
            const sidebarArrow = document.getElementById('hrdSidebarArrow');
            const profilePhotoInput = document.getElementById('hrdProfilePhoto');
            const coverPhotoInput = document.getElementById('hrdCoverPhoto');
            const coverPhotoStatus = document.getElementById('hrdCoverPhotoStatus');
            const coverPhotoError = document.getElementById('hrdCoverPhotoError');
            const coverPhotoPicker = document.querySelector('.hrd-cover-photo-button');
            const coverPhotoEdit = document.querySelector('.hrd-cover-photo-edit');
            const profileCover = document.getElementById('hrdProfileCover');
            const profilePhotoStatus = document.getElementById('hrdProfilePhotoStatus');
            const profilePhotoError = document.getElementById('hrdProfilePhotoError');
            const profilePhotoPicker = document.querySelector('.hrd-profile-photo-button');
            const scrim = document.getElementById('hrdSidebarScrim');
            const search = document.getElementById('hrdSearch');
            const toast = document.getElementById('hrdToast');
            const dialog = document.getElementById('hrdDetailDialog');
            const documentPreviewDialog = document.getElementById('hrdDocumentPreviewDialog');
            const documentPreviewStage = document.getElementById('hrdDocumentPreviewStage');
            const documentFormDialog = document.getElementById('hrdDocumentFormDialog');
            const statusForm = document.getElementById('hrdStatusForm');
            const interviewForm = document.getElementById('hrdInterviewForm');
            const interviewAtInput = document.getElementById('hrdInterviewAt');
            const interviewInformationInput = document.getElementById('hrdInterviewInformation');
            const interviewAttendance = document.getElementById('hrdInterviewAttendance');
            const trackingEventsList = document.getElementById('hrdTrackingEvents');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let savedProfilePhotoUrl = profilePhotoInput.dataset.savedPhotoUrl || '';
            let profilePhotoPreviewUrl = '';
            let savedProfileCoverUrl = coverPhotoInput.dataset.savedCoverUrl || '';
            let coverPhotoPreviewUrl = '';
            const profileInitials = profilePhotoInput.dataset.profileInitials;
            let toastTimer;

            const notify = message => {
                toast.textContent = message;
                toast.classList.add('is-visible');
                window.clearTimeout(toastTimer);
                toastTimer = window.setTimeout(() => toast.classList.remove('is-visible'), 3000);
            };

            if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                const revealTargets = document.querySelectorAll(
                    '.hrd-view .hrd-page-heading, .hrd-view .hrd-welcome-banner, ' +
                    '.hrd-view .hrd-stat-card, .hrd-view .hrd-panel, ' +
                    '.hrd-view .hrd-list-summary > div'
                );
                revealTargets.forEach((element, index) => {
                    element.dataset.hrdReveal = '';
                    element.style.setProperty('--hrd-reveal-delay', `${(index % 3) * 75}ms`);
                });
                document.documentElement.classList.add('hrd-motion-ready');
                const revealObserver = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.14, rootMargin: '0px 0px -24px 0px' });
                revealTargets.forEach(element => revealObserver.observe(element));
            }

            const renderProfileAvatars = photoUrl => {
                document.querySelectorAll('[data-profile-avatar]').forEach(avatar => {
                    if (!photoUrl) {
                        avatar.textContent = profileInitials;
                        return;
                    }
                    const image = document.createElement('img');
                    image.src = photoUrl;
                    image.alt = '';
                    avatar.replaceChildren(image);
                });
            };

            const renderProfileCover = coverUrl => {
                let image = profileCover.querySelector('.hrd-profile-cover-image');
                if (!coverUrl) {
                    if (image) image.remove();
                    return;
                }
                if (!image) {
                    image = document.createElement('img');
                    image.className = 'hrd-profile-cover-image';
                    image.alt = '';
                    profileCover.prepend(image);
                }
                image.src = coverUrl;
            };

            const uploadProfileImage = async (input, file, fieldName, responseKey, imageLabel) => {
                const formData = new FormData();
                formData.append(fieldName, file);
                const response = await fetch(input.dataset.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                let payload;
                try {
                    payload = await response.json();
                } catch {
                    throw new Error(`Server mengirim respons yang tidak dapat dibaca saat mengunggah ${imageLabel}.`);
                }
                if (!response.ok) {
                    const validationMessage = payload.errors?.[fieldName]?.[0];
                    throw new Error(validationMessage || (response.status < 500 && payload.message) || `${imageLabel} gagal disimpan. Coba lagi atau hubungi administrator.`);
                }
                const imageUrl = payload[responseKey];
                if (typeof imageUrl !== 'string' || !imageUrl) {
                    throw new Error(`Server tidak mengembalikan alamat ${imageLabel}.`);
                }
                return imageUrl;
            };

            const isValidProfileImage = file =>
                ['image/jpeg', 'image/png', 'image/webp'].includes(file.type) && file.size <= 2 * 1024 * 1024;
            const invalidProfileImageMessage = 'Pilih foto berformat JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.';

            profilePhotoInput.addEventListener('change', async () => {
                const photo = profilePhotoInput.files[0];
                if (!photo) return;
                if (!isValidProfileImage(photo)) {
                    profilePhotoInput.value = '';
                    profilePhotoError.textContent = invalidProfileImageMessage;
                    profilePhotoError.hidden = false;
                    profilePhotoStatus.textContent = 'Foto profil belum diunggah.';
                    return;
                }

                profilePhotoError.hidden = true;
                profilePhotoStatus.textContent = 'Foto profil sedang diunggah...';
                profilePhotoInput.disabled = true;
                profilePhotoPicker.setAttribute('aria-disabled', 'true');
                profilePhotoPreviewUrl = URL.createObjectURL(photo);
                renderProfileAvatars(profilePhotoPreviewUrl);
                try {
                    savedProfilePhotoUrl = await uploadProfileImage(profilePhotoInput, photo, 'profile_photo', 'photo_url', 'foto profil');
                    renderProfileAvatars(savedProfilePhotoUrl);
                    profilePhotoStatus.textContent = 'Foto profil berhasil disimpan.';
                    notify('Foto profil berhasil disimpan.');
                } catch (uploadError) {
                    renderProfileAvatars(savedProfilePhotoUrl);
                    profilePhotoError.textContent = uploadError instanceof Error ? uploadError.message : 'Foto profil gagal disimpan. Coba lagi.';
                    profilePhotoError.hidden = false;
                    profilePhotoStatus.textContent = 'Foto profil gagal disimpan.';
                } finally {
                    URL.revokeObjectURL(profilePhotoPreviewUrl);
                    profilePhotoPreviewUrl = '';
                    profilePhotoInput.value = '';
                    profilePhotoInput.disabled = false;
                    profilePhotoPicker.removeAttribute('aria-disabled');
                }
            });

            coverPhotoInput.addEventListener('change', async () => {
                const photo = coverPhotoInput.files[0];
                if (!photo) return;
                if (!isValidProfileImage(photo)) {
                    coverPhotoInput.value = '';
                    coverPhotoError.textContent = invalidProfileImageMessage;
                    coverPhotoError.hidden = false;
                    coverPhotoStatus.textContent = 'Foto header belum diunggah.';
                    return;
                }

                coverPhotoError.hidden = true;
                coverPhotoStatus.textContent = 'Foto header sedang diunggah...';
                coverPhotoInput.disabled = true;
                if (coverPhotoPicker) coverPhotoPicker.setAttribute('aria-disabled', 'true');
                coverPhotoEdit.setAttribute('aria-disabled', 'true');
                coverPhotoPreviewUrl = URL.createObjectURL(photo);
                renderProfileCover(coverPhotoPreviewUrl);
                try {
                    savedProfileCoverUrl = await uploadProfileImage(coverPhotoInput, photo, 'cover_photo', 'cover_url', 'foto header');
                    renderProfileCover(savedProfileCoverUrl);
                    if (coverPhotoPicker) coverPhotoPicker.hidden = true;
                    coverPhotoEdit.hidden = false;
                    coverPhotoStatus.textContent = 'Foto header berhasil disimpan.';
                    notify('Foto header berhasil disimpan.');
                } catch (uploadError) {
                    renderProfileCover(savedProfileCoverUrl);
                    coverPhotoError.textContent = uploadError instanceof Error ? uploadError.message : 'Foto header gagal disimpan. Coba lagi.';
                    coverPhotoError.hidden = false;
                    coverPhotoStatus.textContent = 'Foto header gagal disimpan.';
                } finally {
                    URL.revokeObjectURL(coverPhotoPreviewUrl);
                    coverPhotoPreviewUrl = '';
                    coverPhotoInput.value = '';
                    coverPhotoInput.disabled = false;
                    if (coverPhotoPicker) coverPhotoPicker.removeAttribute('aria-disabled');
                    coverPhotoEdit.removeAttribute('aria-disabled');
                }
            });

            const closeSidebar = () => {
                sidebar.classList.remove('is-open');
                scrim.classList.remove('is-visible');
            };
            const setSidebarCollapsed = collapsed => {
                if (window.innerWidth <= 720) collapsed = false;
                sidebar.classList.toggle('is-collapsed', collapsed);
                document.body.classList.toggle('is-sidebar-collapsed', collapsed);
                sidebarArrow.className = collapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
                sidebarCollapse.setAttribute('aria-expanded', String(!collapsed));
                sidebarCollapse.setAttribute('aria-label', collapsed ? 'Perbesar sidebar' : 'Kecilkan sidebar');
                sidebarCollapse.title = collapsed ? 'Perbesar sidebar' : 'Kecilkan sidebar';
            };
            sidebarCollapse.addEventListener('click', () => setSidebarCollapsed(!sidebar.classList.contains('is-collapsed')));
            window.addEventListener('resize', () => {
                if (window.innerWidth <= 720 && sidebar.classList.contains('is-collapsed')) setSidebarCollapsed(false);
            });

            const showView = name => {
                document.querySelectorAll('.hrd-view').forEach(view => {
                    const active = view.id === `view-${name}`;
                    view.hidden = !active;
                    view.classList.toggle('is-visible', active);
                });
                let activeLink = null;
                document.querySelectorAll('.hrd-nav-link[data-view]').forEach(link => {
                    const active = link.dataset.view === name;
                    link.classList.toggle('is-active', active);
                    if (active) {
                        link.setAttribute('aria-current', 'page');
                        activeLink = link;
                    } else {
                        link.removeAttribute('aria-current');
                    }
                });
                const activeView = document.getElementById(`view-${name}`);
                if (!activeView) return;
                const pageTitle = activeLink?.dataset.label || activeView.dataset.title;
                document.getElementById('hrdBreadcrumbSection').textContent = activeLink?.dataset.section || 'Ruang Kerja';
                document.getElementById('hrdBreadcrumb').textContent = pageTitle;
                document.title = `${pageTitle} | Galeri Magang`;
                if (search) search.value = '';
                window.history.replaceState(null, '', `${window.location.pathname}${window.location.search}#${name}`);
                closeSidebar();
            };
            document.querySelectorAll('.hrd-nav-link[data-view]').forEach(link => link.addEventListener('click', () => showView(link.dataset.view)));
            document.querySelectorAll('[data-open-view]').forEach(button => button.addEventListener('click', () => showView(button.dataset.openView)));
            const viewNames = new Set([...document.querySelectorAll('.hrd-view')].map(view => view.id.replace('view-', '')));
            const requestedView = window.location.hash.slice(1) || new URLSearchParams(window.location.search).get('view') || 'dashboard';
            showView(viewNames.has(requestedView) ? requestedView : 'dashboard');
            document.getElementById('hrdMenuToggle').addEventListener('click', () => {
                sidebar.classList.toggle('is-open');
                scrim.classList.toggle('is-visible');
            });
            scrim.addEventListener('click', closeSidebar);

            const activeView = () => document.querySelector('.hrd-view.is-visible');
            const filterRows = (view, query, status) => {
                if (!view) return;
                view.querySelectorAll('.hrd-table').forEach(table => {
                    const rows = [...table.querySelectorAll('tbody tr')].filter(row => !row.classList.contains('hrd-empty-row'));
                    let visibleCount = 0;
                    rows.forEach(row => {
                        const rowText = `${row.dataset.name || ''} ${row.dataset.position || ''} ${row.innerText}`.toLowerCase();
                        const rowStatus = row.dataset.status || '';
                        row.hidden = !(rowText.includes(query.toLowerCase()) && (!status || rowStatus === status));
                        if (!row.hidden) visibleCount++;
                    });
                    let emptyRow = table.querySelector('.hrd-empty-row');
                    if (!emptyRow) {
                        emptyRow = document.createElement('tr');
                        emptyRow.className = 'hrd-empty-row';
                        emptyRow.innerHTML = `<td colspan="${table.tHead.rows[0].cells.length}"></td>`;
                        table.tBodies[0].appendChild(emptyRow);
                    }
                    emptyRow.hidden = visibleCount > 0;
                    const emptyCell = emptyRow.querySelector('td');
                    if (emptyCell && rows.length) emptyCell.textContent = 'Tidak ada data yang cocok dengan pencarian atau filter.';
                    const pagination = view.querySelector('.hrd-pagination > span');
                    if (pagination) {
                        if (!pagination.dataset.defaultContent) pagination.dataset.defaultContent = pagination.innerHTML;
                        pagination.innerHTML = query || status
                            ? `Menampilkan <strong>${visibleCount ? `1–${visibleCount}` : '0'}</strong> dari <strong>${visibleCount}</strong> hasil pada halaman ini`
                            : pagination.dataset.defaultContent;
                    }
                });
            };
            search.addEventListener('input', () => {
                const view = activeView();
                filterRows(view, search.value, view?.querySelector('.hrd-status-filter')?.value || '');
            });
            document.querySelectorAll('.hrd-local-search').forEach(input => input.addEventListener('input', () => {
                const view = input.closest('.hrd-view');
                filterRows(view, input.value, view.querySelector('.hrd-status-filter')?.value || '');
                search.value = input.value;
            }));
            document.querySelectorAll('.hrd-status-filter').forEach(select => select.addEventListener('change', () => {
                const view = select.closest('.hrd-view');
                filterRows(view, view.querySelector('.hrd-local-search')?.value || search.value, select.value);
            }));

            const statusLabels = {
                pending: 'Perlu ditinjau',
                in_review: 'Dalam seleksi',
                accepted: 'Diterima',
                rejected: 'Ditolak',
                completed: 'Selesai Magang'
            };
            const statusClasses = {
                pending: 'badge-review',
                in_review: 'badge-process',
                accepted: 'badge-accepted',
                rejected: 'badge-rejected',
                completed: 'badge-accepted'
            };

            const fileList = document.getElementById('hrdDetailFileList');
            document.addEventListener('click', event => {
                const button = event.target.closest('[data-preview-document]');
                if (!button) return;

                document.getElementById('hrdDocumentPreviewTitle').textContent = button.dataset.previewLabel || 'Pratinjau berkas';
                document.getElementById('hrdDocumentPreviewFilename').textContent = button.dataset.previewFilename || '';
                documentPreviewStage.replaceChildren();
                if (button.dataset.previewMimeType?.startsWith('image/')) {
                    const image = document.createElement('img');
                    image.className = 'hrd-preview-image';
                    image.src = button.dataset.previewUrl;
                    image.alt = button.dataset.previewLabel || 'Pratinjau berkas';
                    image.addEventListener('error', () => {
                        documentPreviewStage.textContent = 'Berkas gambar tidak dapat ditampilkan.';
                    }, { once: true });
                    documentPreviewStage.appendChild(image);
                } else {
                    const frame = document.createElement('iframe');
                    frame.className = 'hrd-preview-frame';
                    frame.src = button.dataset.previewUrl;
                    frame.title = button.dataset.previewLabel || 'Pratinjau dokumen PDF';
                    documentPreviewStage.appendChild(frame);
                }
                documentPreviewDialog.showModal();
            });
            documentPreviewDialog.addEventListener('close', () => documentPreviewStage.replaceChildren());
            document.addEventListener('click', event => {
                const button = event.target.closest('[data-detail]');
                if (!button) return;
                document.getElementById('hrdDetailName').textContent = button.dataset.detail;
                document.getElementById('hrdDetailRole').textContent = button.dataset.role;
                document.getElementById('hrdDetailInitials').textContent = button.dataset.initials || '';
                document.getElementById('hrdDetailEmail').textContent = button.dataset.email || 'Email belum tersedia';
                document.getElementById('hrdDetailInstitution').textContent = button.dataset.organization || 'Belum dicantumkan';
                document.getElementById('hrdDetailPeriod').textContent = button.dataset.period || 'Belum dicantumkan';
                document.getElementById('hrdDetailDate').textContent = button.dataset.date || 'Belum dicantumkan';
                const status = button.dataset.status || 'pending';
                const statusLabel = button.dataset.statusLabel || statusLabels[status] || status;
                document.getElementById('hrdDetailStatus').innerHTML = `<span class="hrd-badge ${statusClasses[status] || statusClasses.pending}"><i></i>${statusLabel}</span>`;
                const statusSelect = document.getElementById('hrdStatusSelect');
                const completedOption = statusSelect.querySelector('option[value="completed"]');
                if (button.dataset.type === 'internship' && !completedOption) {
                    statusSelect.add(new Option(statusLabels.completed, 'completed'));
                } else if (button.dataset.type !== 'internship' && completedOption) {
                    completedOption.remove();
                }
                statusSelect.value = status;
                statusForm.dataset.url = button.dataset.statusUrl;
                statusForm.dataset.trackingUrl = button.dataset.trackingUrl;
                interviewForm.dataset.url = button.dataset.interviewUrl;
                document.getElementById('hrdStatusError').hidden = true;
                document.getElementById('hrdStatusError').textContent = '';
                document.getElementById('hrdInterviewError').hidden = true;
                document.getElementById('hrdInterviewError').textContent = '';
                let documents = [];
                try {
                    documents = JSON.parse(button.dataset.documents || '[]');
                } catch {
                    notify('Informasi berkas pendaftar tidak dapat dibaca.');
                }
                fileList.replaceChildren();
                document.getElementById('hrdDetailFileCount').textContent = `${documents.length} berkas`;
                if (!documents.length) {
                    const emptyMessage = document.createElement('p');
                    emptyMessage.textContent = 'Tidak ada berkas yang tersimpan untuk pendaftar ini.';
                    fileList.appendChild(emptyMessage);
                } else {
                    documents.forEach(documentInfo => {
                        const row = document.createElement('div');
                        row.className = 'hrd-file-row';
                        const icon = document.createElement('span');
                        icon.className = 'hrd-file-icon';
                        icon.innerHTML = '<i class="bi bi-file-earmark-text" aria-hidden="true"></i>';
                        const copy = document.createElement('span');
                        copy.className = 'hrd-file-copy';
                        const label = document.createElement('strong');
                        label.textContent = documentInfo.label;
                        const filename = document.createElement('small');
                        filename.textContent = documentInfo.filename;
                        copy.append(label, filename);
                        if (documentInfo.preview_url) {
                            const preview = document.createElement('button');
                            preview.className = 'hrd-row-action';
                            preview.type = 'button';
                            preview.dataset.previewDocument = '';
                            preview.dataset.previewUrl = documentInfo.preview_url;
                            preview.dataset.previewMimeType = documentInfo.preview_mime_type;
                            preview.dataset.previewLabel = documentInfo.label;
                            preview.dataset.previewFilename = documentInfo.filename;
                            preview.textContent = 'Pratinjau';
                            preview.setAttribute('aria-label', `Pratinjau ${documentInfo.label}`);
                            row.append(icon, copy, preview);
                        } else {
                            row.append(icon, copy);
                        }
                        const download = document.createElement('a');
                        download.className = 'hrd-row-action';
                        download.href = documentInfo.url;
                        download.setAttribute('aria-label', `Unduh ${documentInfo.label}`);
                        download.textContent = 'Unduh';
                        row.appendChild(download);
                        fileList.appendChild(row);
                    });
                }
                dialog.showModal();
                const interviewDraft = loadInterviewDraft();
                interviewFormDirty = Boolean(interviewDraft);
                if (interviewDraft) {
                    interviewAtInput.value = interviewDraft.interview_at;
                    interviewInformationInput.value = interviewDraft.interview_information;
                } else {
                    interviewAtInput.value = '';
                    interviewInformationInput.value = '';
                }
                refreshHrdTracking();
            });

            const trackingEventLabels = {
                application_submitted: 'Berkas lamaran dikirim',
                status_changed: 'Tahap seleksi diperbarui',
                interview_scheduled: 'Jadwal wawancara diperbarui',
                attendance_confirmed: 'Konfirmasi kehadiran diterima'
            };
            let interviewFormDirty = false;
            const interviewDraftKey = () => `hrd-interview-draft:${statusForm.dataset.trackingUrl || ''}`;
            const saveInterviewDraft = () => {
                if (!statusForm.dataset.trackingUrl) return;
                try {
                    sessionStorage.setItem(interviewDraftKey(), JSON.stringify({
                        interview_at: interviewAtInput.value,
                        interview_information: interviewInformationInput.value
                    }));
                } catch (error) {
                    console.error('Draf jadwal wawancara tidak dapat disimpan di browser.', error);
                }
            };
            const loadInterviewDraft = () => {
                if (!statusForm.dataset.trackingUrl) return null;
                try {
                    const rawDraft = sessionStorage.getItem(interviewDraftKey());
                    if (!rawDraft) return null;
                    const draft = JSON.parse(rawDraft);
                    if (!draft || typeof draft.interview_at !== 'string' || typeof draft.interview_information !== 'string') {
                        sessionStorage.removeItem(interviewDraftKey());
                        return null;
                    }
                    return draft;
                } catch (error) {
                    console.error('Draf jadwal wawancara tidak dapat dibaca.', error);
                    return null;
                }
            };
            const clearInterviewDraft = () => {
                if (!statusForm.dataset.trackingUrl) return;
                try {
                    sessionStorage.removeItem(interviewDraftKey());
                } catch (error) {
                    console.error('Draf jadwal wawancara tidak dapat dihapus.', error);
                }
            };
            interviewForm.addEventListener('input', () => {
                interviewFormDirty = true;
                saveInterviewDraft();
            });
            const formatTrackingDate = value => value
                ? new Intl.DateTimeFormat('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'short',
                    timeZone: 'Asia/Jakarta'
                }).format(new Date(value)) + ' WIB'
                : 'Waktu tidak tersedia';
            const refreshHrdTracking = async () => {
                if (!statusForm.dataset.trackingUrl) return;
                try {
                    const response = await fetch(statusForm.dataset.trackingUrl, {
                        headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                        cache: 'no-store'
                    });
                    if (!response.ok) throw new Error('Riwayat tahapan tidak dapat dimuat.');
                    const payload = await response.json();
                    document.getElementById('hrdStatusSelect').value = payload.status || 'pending';
                    if (!interviewFormDirty) {
                        interviewAtInput.value = payload.interview_at_local || '';
                        interviewInformationInput.value = payload.interview_information || '';
                    }
                    const activeButton = Array.from(document.querySelectorAll('[data-tracking-url]'))
                        .find(button => button.dataset.trackingUrl === statusForm.dataset.trackingUrl);
                    if (activeButton) {
                        activeButton.dataset.status = payload.status;
                        activeButton.dataset.statusLabel = payload.status_label;
                        const row = activeButton.closest('tr');
                        if (row) {
                            row.dataset.status = payload.status;
                            const badge = row.querySelector('[data-status-badge]');
                            if (badge) {
                                badge.className = `hrd-badge ${statusClasses[payload.status] || statusClasses.pending}`;
                                badge.replaceChildren(
                                    document.createElement('i'),
                                    document.createTextNode(payload.status_label || statusLabels[payload.status] || payload.status)
                                );
                            }
                        }
                    }
                    if (payload.interview_attendance) {
                        interviewAttendance.replaceChildren(document.createTextNode('Konfirmasi pelamar: '));
                        const attendanceStatus = document.createElement('span');
                        attendanceStatus.className = `hrd-attendance-status ${payload.interview_attendance === 'hadir' ? 'is-present' : 'is-absent'}`;
                        attendanceStatus.textContent = payload.interview_attendance === 'hadir' ? 'Hadir' : 'Tidak Hadir';
                        interviewAttendance.appendChild(attendanceStatus);
                        if (payload.interview_attendance === 'tidak_hadir' && payload.interview_attendance_reason) {
                            const attendanceReason = document.createElement('span');
                            attendanceReason.className = 'hrd-attendance-reason';
                            attendanceReason.textContent = `Alasan: ${payload.interview_attendance_reason}`;
                            interviewAttendance.appendChild(attendanceReason);
                        }
                        if (payload.interview_confirmed_at) {
                            const confirmedAt = document.createElement('span');
                            confirmedAt.className = 'hrd-attendance-date';
                            confirmedAt.textContent = formatTrackingDate(payload.interview_confirmed_at);
                            interviewAttendance.appendChild(confirmedAt);
                        }
                    } else {
                        interviewAttendance.textContent = 'Pelamar belum mengonfirmasi kehadiran.';
                    }
                    trackingEventsList.replaceChildren();
                    (payload.events || []).slice().reverse().forEach(item => {
                        const entry = document.createElement('li');
                        const label = document.createElement('strong');
                        label.textContent = trackingEventLabels[item.event_type] || 'Pembaruan tahapan';
                        const date = document.createElement('time');
                        date.dateTime = item.created_at || '';
                        date.textContent = formatTrackingDate(item.created_at);
                        const detail = document.createElement('span');
                        if (item.event_type === 'status_changed') {
                            detail.textContent = `${statusLabels[item.from_status] || item.from_status || 'Status awal'} → ${statusLabels[item.to_status] || item.to_status}`;
                        } else if (item.event_type === 'interview_scheduled') {
                            detail.textContent = [formatTrackingDate(item.interview_at), item.interview_information].filter(Boolean).join(' · ');
                        } else if (item.event_type === 'attendance_confirmed') {
                            detail.className = 'hrd-attendance-event-detail';
                            const attendanceStatus = document.createElement('span');
                            attendanceStatus.className = `hrd-attendance-status ${item.attendance === 'hadir' ? 'is-present' : 'is-absent'}`;
                            attendanceStatus.textContent = item.attendance === 'hadir'
                                ? 'Pelamar menyatakan hadir.'
                                : 'Pelamar menyatakan tidak hadir.';
                            detail.appendChild(attendanceStatus);
                            if (item.attendance === 'tidak_hadir' && item.attendance_reason) {
                                const attendanceReason = document.createElement('span');
                                attendanceReason.className = 'hrd-attendance-reason';
                                attendanceReason.textContent = `Alasan: ${item.attendance_reason}`;
                                detail.appendChild(attendanceReason);
                            }
                        } else {
                            detail.textContent = 'Pendaftaran diterima oleh sistem.';
                        }
                        entry.append(label, date, detail);
                        trackingEventsList.appendChild(entry);
                    });
                } catch (error) {
                    notify(error instanceof Error ? error.message : 'Riwayat tahapan tidak dapat dimuat.');
                }
            };

            statusForm.addEventListener('submit', async event => {
                event.preventDefault();
                const submitButton = statusForm.querySelector('button[type="submit"]');
                const errorMessage = document.getElementById('hrdStatusError');
                submitButton.disabled = true;
                errorMessage.hidden = true;
                try {
                    const response = await fetch(statusForm.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ status: document.getElementById('hrdStatusSelect').value })
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        const validationMessage = payload.errors?.status?.[0];
                        throw new Error(validationMessage || payload.message || 'Status pendaftaran gagal disimpan.');
                    }
                    dialog.close();
                    notify('Status pendaftaran berhasil disimpan.');
                    window.setTimeout(() => window.location.reload(), 500);
                } catch (error) {
                    errorMessage.textContent = error instanceof Error ? error.message : 'Status pendaftaran gagal disimpan.';
                    errorMessage.hidden = false;
                } finally {
                    submitButton.disabled = false;
                }
            });
            interviewForm.addEventListener('submit', async event => {
                event.preventDefault();
                const submitButton = interviewForm.querySelector('button[type="submit"]');
                const errorMessage = document.getElementById('hrdInterviewError');
                submitButton.disabled = true;
                errorMessage.hidden = true;
                try {
                    const response = await fetch(interviewForm.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            interview_at: interviewAtInput.value,
                            interview_information: interviewInformationInput.value
                        })
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        const message = payload.errors?.interview_at?.[0]
                            || payload.errors?.interview_information?.[0]
                            || payload.message;
                        throw new Error(message || 'Jadwal wawancara gagal disimpan.');
                    }
                    clearInterviewDraft();
                    interviewFormDirty = false;
                    interviewAtInput.value = payload.interview_at_local || '';
                    interviewInformationInput.value = payload.interview_information || '';
                    notify('Jadwal wawancara berhasil disimpan.');
                    await refreshHrdTracking();
                } catch (error) {
                    errorMessage.textContent = error instanceof Error ? error.message : 'Jadwal wawancara gagal disimpan.';
                    errorMessage.hidden = false;
                } finally {
                    submitButton.disabled = false;
                }
            });
            window.setInterval(() => {
                if (dialog.open) refreshHrdTracking();
            }, 15000);

            document.querySelectorAll('[data-close-dialog]').forEach(button => button.addEventListener('click', () => button.closest('dialog').close()));
            document.querySelectorAll('dialog').forEach(currentDialog => currentDialog.addEventListener('click', event => {
                if (event.target === currentDialog) currentDialog.close();
            }));
            document.querySelectorAll('[data-open-document-form]').forEach(button => button.addEventListener('click', () => documentFormDialog.showModal()));
            @if ($errors->has('internship_registration_id') || $errors->has('document_type') || $errors->has('certificate_period_start') || $errors->has('certificate_period_end') || $errors->has('certificate_issued_at') || $errors->has('certificate_signer_title') || $errors->has('certificate_signer_name') || $errors->has('file'))
                documentFormDialog.showModal();
            @endif

            const renderTrend = () => {
                const trendElement = document.getElementById('hrdTrendData');
                if (!trendElement) return;
                const allData = JSON.parse(trendElement.textContent);
                const period = Number(document.getElementById('hrdTrendPeriod').value);
                const current = allData.slice(-period);
                const previous = allData.slice(-period * 2, -period);
                const series = ['staff', 'magang', 'pkl', 'unclassified'];
                const largestValue = Math.max(0, ...current.flatMap(item => series.map(key => Number(item[key] || 0))));
                const maxValue = Math.max(4, Math.ceil(largestValue / 4) * 4);
                const chartWidth = 700;
                const chartHeight = 210;
                const baseline = 198;
                const pointFor = (value, index) => {
                    const x = current.length > 1 ? index * chartWidth / (current.length - 1) : chartWidth / 2;
                    const y = baseline - value / maxValue * 174;
                    return `${x},${y}`;
                };
                const linePath = values => values.map((value, index) => `${index ? 'L' : 'M'}${pointFor(value, index)}`).join(' ');
                const areaPath = values => `${linePath(values)} L${chartWidth},${chartHeight} L0,${chartHeight} Z`;
                const valuesBySeries = Object.fromEntries(series.map(key => [key, current.map(item => Number(item[key] || 0))]));
                document.querySelector('.hrd-area--staff').setAttribute('d', areaPath(valuesBySeries.staff));
                document.querySelector('.hrd-area--magang').setAttribute('d', areaPath(valuesBySeries.magang));
                series.forEach(key => document.querySelector(`.hrd-line--${key}`).setAttribute('d', linePath(valuesBySeries[key])));
                const pointIds = {
                    staff: 'hrdTrendPointStaff',
                    magang: 'hrdTrendPointMagang',
                    pkl: 'hrdTrendPointPkl',
                    unclassified: 'hrdTrendPointUnclassified'
                };
                series.forEach(key => {
                    const latestValue = valuesBySeries[key][valuesBySeries[key].length - 1] || 0;
                    const point = pointFor(latestValue, current.length - 1).split(',');
                    const marker = document.getElementById(pointIds[key]);
                    marker.setAttribute('cx', point[0]);
                    marker.setAttribute('cy', point[1]);
                    marker.style.display = latestValue > 0 ? '' : 'none';
                });
                const yAxis = document.getElementById('hrdTrendYAxis');
                yAxis.replaceChildren(...[1, .75, .5, .25, 0].map(value => {
                    const label = document.createElement('span');
                    label.textContent = new Intl.NumberFormat('id-ID').format(Math.round(maxValue * value));
                    return label;
                }));
                const xAxis = document.getElementById('hrdTrendXAxis');
                xAxis.replaceChildren(...current.map(item => {
                    const label = document.createElement('span');
                    label.textContent = item.label;
                    return label;
                }));
                const countTotal = items => items.reduce((sum, item) => sum + series.reduce((subtotal, key) => subtotal + Number(item[key] || 0), 0), 0);
                const currentTotal = countTotal(current);
                const previousTotal = countTotal(previous);
                const comparison = previousTotal
                    ? `${currentTotal >= previousTotal ? '+' : ''}${((currentTotal - previousTotal) / previousTotal * 100).toLocaleString('id-ID', { maximumFractionDigits: 1 })}% dibanding periode sebelumnya`
                    : currentTotal ? 'Ada pendaftar baru pada periode ini' : 'Belum ada pendaftar pada periode ini';
                document.getElementById('hrdTrendComparison').textContent = comparison;
            };
            document.getElementById('hrdTrendPeriod').addEventListener('change', renderTrend);
            renderTrend();

            const dashboardView = document.getElementById('view-dashboard');
            const formatDashboardNumber = value => new Intl.NumberFormat('id-ID').format(Number(value || 0));
            const renderDashboardSnapshot = snapshot => {
                const stats = snapshot.stats;
                const statKeys = ['staff_total', 'staff_month', 'magang_total', 'magang_month', 'magang_completed', 'pkl_total', 'pkl_month', 'pkl_completed', 'staff_pending', 'internship_pending', 'pending', 'in_review', 'accepted', 'completed', 'rejected', 'total'];
                statKeys.forEach(key => {
                    document.querySelectorAll(`[data-dashboard-stat="${key}"]`).forEach(element => {
                        element.textContent = `${formatDashboardNumber(stats[key])}${key.endsWith('_month') ? ' bulan ini' : ''}`;
                    });
                });
                ['pending', 'in_review', 'accepted', 'completed', 'rejected'].forEach(key => {
                    const percentage = document.querySelector(`[data-dashboard-stat="${key}_percent"]`);
                    if (percentage) percentage.textContent = `${Number(stats[`${key}_percent`] || 0).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 })}% pendaftar`;
                });

                const donut = document.getElementById('hrdStatusDonut');
                const total = Number(stats.total || 0);
                if (donut) {
                    const pendingEnd = Number(stats.pending_percent || 0);
                    const reviewEnd = pendingEnd + Number(stats.in_review_percent || 0);
                    const acceptedEnd = reviewEnd + Number(stats.accepted_percent || 0);
                    const completedEnd = acceptedEnd + Number(stats.completed_percent || 0);
                    donut.style.background = total
                        ? `conic-gradient(#b29262 0 ${pendingEnd}%, #6487ad ${pendingEnd}% ${reviewEnd}%, #76a98e ${reviewEnd}% ${acceptedEnd}%, #438d82 ${acceptedEnd}% ${completedEnd}%, #ad7373 ${completedEnd}% 100%)`
                        : 'conic-gradient(#edf0f4 0 100%)';
                }

                const statusBody = document.getElementById('hrdStatusBreakdown');
                statusBody.replaceChildren(...snapshot.category_status_rows.map(row => {
                    const tr = document.createElement('tr');
                    [row.label, row.pending, row.in_review, row.accepted, row.completed, row.rejected, row.total].forEach((value, index) => {
                        const cell = document.createElement('td');
                        if (index === 6) {
                            const strong = document.createElement('strong');
                            strong.textContent = formatDashboardNumber(value);
                            cell.appendChild(strong);
                        } else {
                            cell.textContent = index === 0 ? value : formatDashboardNumber(value);
                        }
                        tr.appendChild(cell);
                    });
                    return tr;
                }));

                const recentBody = document.getElementById('hrdRecentApplications');
                const recentRows = snapshot.recent_applications.map(application => {
                    const tr = document.createElement('tr');
                    tr.dataset.name = application.name;
                    tr.dataset.position = application.position;
                    tr.dataset.period = application.period;
                    tr.dataset.status = application.status;

                    const personCell = document.createElement('td');
                    const person = document.createElement('div');
                    person.className = 'hrd-person-cell';
                    const avatar = document.createElement('span');
                    avatar.className = 'hrd-avatar hrd-avatar--blue';
                    if (application.photo_url) {
                        const profilePhoto = document.createElement('img');
                        profilePhoto.src = application.photo_url;
                        profilePhoto.alt = '';
                        profilePhoto.loading = 'lazy';
                        profilePhoto.decoding = 'async';
                        avatar.appendChild(profilePhoto);
                    } else {
                        avatar.textContent = application.initials;
                    }
                    const identity = document.createElement('span');
                    const name = document.createElement('strong');
                    name.textContent = application.name;
                    const email = document.createElement('small');
                    email.textContent = application.email;
                    identity.append(name, email);
                    person.append(avatar, identity);
                    personCell.appendChild(person);
                    tr.appendChild(personCell);

                    const positionCell = document.createElement('td');
                    positionCell.append(document.createTextNode(application.position));
                    const positionSub = document.createElement('small');
                    positionSub.className = 'hrd-cell-sub';
                    positionSub.textContent = `${application.type_label} · ${application.location}`;
                    positionCell.appendChild(positionSub);
                    tr.appendChild(positionCell);

                    const dateCell = document.createElement('td');
                    dateCell.textContent = application.joined;
                    tr.appendChild(dateCell);

                    const statusCell = document.createElement('td');
                    const badge = document.createElement('span');
                    badge.className = `hrd-badge ${application.status_class}`;
                    badge.dataset.statusBadge = '';
                    const badgeIcon = document.createElement('i');
                    badge.append(badgeIcon, document.createTextNode(application.status_label));
                    statusCell.appendChild(badge);
                    tr.appendChild(statusCell);

                    const actionCell = document.createElement('td');
                    const action = document.createElement('button');
                    action.className = 'hrd-row-action';
                    action.type = 'button';
                    action.textContent = 'Detail ';
                    const actionIcon = document.createElement('i');
                    actionIcon.className = 'bi bi-arrow-up-right';
                    action.appendChild(actionIcon);
                    Object.assign(action.dataset, {
                        detail: application.name,
                        role: `${application.type_label} · ${application.position}`,
                        status: application.status,
                        statusLabel: application.status_label,
                        statusUrl: application.status_url,
                        trackingUrl: application.tracking_url,
                        interviewUrl: application.interview_url,
                        type: application.type,
                        initials: application.initials,
                        organization: application.organization,
                        period: application.period,
                        date: application.joined,
                        email: application.email,
                        documents: JSON.stringify(application.documents || [])
                    });
                    actionCell.appendChild(action);
                    tr.appendChild(actionCell);
                    return tr;
                });
                if (!recentRows.length) {
                    const empty = document.createElement('tr');
                    empty.className = 'hrd-empty-row';
                    const cell = document.createElement('td');
                    cell.colSpan = 5;
                    cell.textContent = 'Belum ada pendaftar di database.';
                    empty.appendChild(cell);
                    recentRows.push(empty);
                }
                recentBody.replaceChildren(...recentRows);

                const trendElement = document.getElementById('hrdTrendData');
                trendElement.textContent = JSON.stringify(snapshot.trend_data);
                renderTrend();
            };
            let dashboardRefreshInProgress = false;
            const refreshDashboard = async () => {
                if (dashboardRefreshInProgress || document.hidden || !dashboardView) return;
                dashboardRefreshInProgress = true;
                try {
                    const response = await fetch(dashboardView.dataset.dashboardSnapshot, {
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    if (!response.ok) throw new Error(`Pembaruan dashboard gagal (${response.status}).`);
                    renderDashboardSnapshot(await response.json());
                } catch (error) {
                    console.error('Gagal memperbarui data dashboard HRD.', error);
                } finally {
                    dashboardRefreshInProgress = false;
                }
            };
            window.setInterval(refreshDashboard, 30000);

            document.querySelectorAll('[data-export]').forEach(button => button.addEventListener('click', () => {
                const view = button.closest('.hrd-view') || activeView();
                if (button.dataset.exportUrl) {
                    const exportUrl = new URL(button.dataset.exportUrl, window.location.origin);
                    const filterForm = view?.querySelector('.hrd-filter-form');
                    if (filterForm) {
                        new FormData(filterForm).forEach((value, key) => {
                            if (typeof value === 'string' && value !== '') exportUrl.searchParams.set(key, value);
                        });
                    }
                    window.location.assign(exportUrl.toString());
                    return;
                }
                const table = view?.querySelector('.hrd-table');
                if (!table) {
                    notify('Tidak ada tabel untuk diunduh pada halaman ini.');
                    return;
                }
                const rows = [...table.querySelectorAll('tr')].filter(row => !row.hidden && !row.classList.contains('hrd-empty-row'));
                const csv = rows.map(row => [...row.cells]
                    .slice(0, -1)
                    .map(cell => `"${cell.innerText.trim().replace(/"/g, '""')}"`)
                    .join(','))
                    .join('\r\n');
                const file = new Blob(['\uFEFF', csv], { type: 'text/csv;charset=utf-8' });
                const url = URL.createObjectURL(file);
                const link = document.createElement('a');
                link.href = url;
                link.download = `${(view.dataset.title || 'hrd').toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${new Date().toISOString().slice(0, 10)}.csv`;
                link.click();
                URL.revokeObjectURL(url);
            }));

            const profileForm = document.getElementById('hrdProfileForm');
            profileForm.addEventListener('submit', event => {
                const name = profileForm.elements.name.value.trim();
                if (!name) {
                    event.preventDefault();
                    profileForm.elements.name.focus();
                    notify('Nama lengkap wajib diisi.');
                    return;
                }
                const submitButton = profileForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Menyimpan...';
            });
            @if (session('profile_updated'))
                notify('Perubahan profil berhasil disimpan ke server.');
            @endif
            @if (session('hrd_document_saved'))
                notify('Dokumen berhasil disimpan ke server.');
            @endif

            document.querySelectorAll('[data-toast]').forEach(button => button.addEventListener('click', () => notify(button.dataset.toast)));
            document.addEventListener('keydown', event => {
                if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
                    event.preventDefault();
                    search.focus();
                }
            });
        })();
    </script>
</body>
</html>
