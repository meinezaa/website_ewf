<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Pelamar - PT Equityworld Futures</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    @include('user.partials.header')
    <main class="account">
        <div class="account-inner">
            @php
                $profileInitials = collect(preg_split('/\s+/', trim((string) auth()->user()->name)) ?: [])
                    ->filter()
                    ->take(2)
                    ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
                    ->implode('');
            @endphp
            <div class="account-heading" data-scroll-reveal>
                <div class="account-avatar" data-profile-photo-avatar>
                    @if($profilePhotoUrl)
                        <img src="{{ $profilePhotoUrl }}" alt="Foto profil {{ auth()->user()->name }}">
                    @else
                        <span class="account-avatar-initials" aria-label="Inisial {{ auth()->user()->name }}">{{ $profileInitials }}</span>
                    @endif
                    <input class="account-photo-input" id="profilePhoto" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="profilePhotoStatus" data-profile-initials="{{ $profileInitials }}">
                    <label class="account-photo-picker" for="profilePhoto" aria-label="Pilih foto profil" title="Pilih foto profil"><i class="bi bi-camera-fill" aria-hidden="true"></i></label>
                </div>
                <div class="account-heading-copy">
                    <span class="account-eyebrow"><i class="bi bi-person-badge" aria-hidden="true"></i> PROFIL PELAMAR</span>
                    <h1>Halo, {{ auth()->user()->name }}</h1>
                    <p>Kelola informasi akun dan pantau perjalanan lamaran Anda.</p>
                    <span class="account-photo-hint"><i class="bi bi-camera" aria-hidden="true"></i> Pilih ikon kamera untuk mengubah foto profil</span>
                    <span class="account-photo-status" id="profilePhotoStatus" aria-live="polite"></span>
                </div>
            </div>
            <div class="account-alert error" id="profilePhotoError" role="alert" hidden></div>

            @if(session('profile_updated'))
                <div class="account-alert success" role="status">Informasi akun berhasil diperbarui.</div>
            @endif
            @if($errors->any())
                <div class="account-alert error" role="alert">{{ $errors->first() }}</div>
            @endif

            <div class="account-grid">
                <section class="account-card" data-scroll-reveal>
                    <h2>Informasi akun</h2>
                    <p>Data ini membantu tim rekrutmen menghubungi Anda. Email akun tidak dapat diubah dari halaman ini.</p>
                    <form class="account-form" id="profileUpdateForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="account-field full">
                            <label for="profileName">Nama lengkap</label>
                            <input id="profileName" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required maxlength="150">
                        </div>
                        <div class="account-field">
                            <label for="profileEmail">Email</label>
                            <input id="profileEmail" type="email" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="account-field">
                            <label for="profilePhone">Nomor WhatsApp</label>
                            <input id="profilePhone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" autocomplete="tel" maxlength="40">
                        </div>
                        <div class="account-field">
                            <label for="profileGender">Jenis kelamin</label>
                            <select id="profileGender" name="gender">
                                <option value="" hidden disabled @selected(!old('gender', auth()->user()->gender ?? ''))></option>
                                <option value="perempuan" @selected(old('gender', auth()->user()->gender ?? '') === 'perempuan')>Perempuan</option>
                                <option value="laki-laki" @selected(old('gender', auth()->user()->gender ?? '') === 'laki-laki')>Laki-laki</option>
                            </select>
                        </div>
                        <div class="account-field">
                            <label for="profileLocation">Kota domisili</label>
                            <input id="profileLocation" name="location" value="{{ old('location', auth()->user()->location) }}" autocomplete="address-level2" maxlength="150">
                        </div>
                        <button class="account-button" type="submit">Simpan perubahan</button>
                    </form>
                </section>

                <section class="account-card account-history-card" data-scroll-reveal>
                    <h2>Riwayat lamaran</h2>
                    <p>Lamaran yang dikirim dari akun ini akan tercatat di sini.</p>
                    @if($applications->isEmpty())
                        <div class="account-empty">
                            <i class="bi bi-briefcase" aria-hidden="true"></i>
                            <div class="account-empty-content">
                                <span>Belum ada lamaran. Jelajahi lowongan yang tersedia dan kirim lamaran pertama Anda.</span>
                                <a class="account-browse" href="{{ route('karir') }}#jobs">Jelajahi karir</a>
                            </div>
                        </div>
                    @else
                        <div class="account-applications">
                            @foreach($applications as $application)
                                <article class="account-application">
                                    <b>{{ $application->job_title }}</b>
                                    <span>Tanggal submit: {{ $application->created_at->format('d M Y') }}</span>
                                </article>
                            @endforeach
                            <a class="account-browse" href="{{ route('karir') }}#status-pendaftaran"><i class="bi bi-broadcast"></i> Lacak status pendaftaran</a>
                        </div>
                    @endif
                </section>
            </div>
            <section class="account-card account-security-card" data-scroll-reveal>
                <div class="account-security-heading">
                    <h2>Keamanan akun</h2>
                    <p>Kelola sesi dan penghapusan akun Anda.</p>
                </div>
                <div class="account-security-actions">
                    <section class="account-security-action account-logout-action" id="logoutAccountCard">
                        <div class="account-security-copy">
                            <span class="account-security-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                            <div>
                                <h3>Keluar akun</h3>
                                <p>Keluar dari akun di perangkat ini. Anda dapat masuk kembali kapan saja.</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="account-button secondary" type="submit"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar akun</button>
                        </form>
                    </section>
                    <section class="account-security-action account-delete-action" id="deleteAccountCard">
                        <div class="account-security-copy">
                            <span class="account-security-icon danger"><i class="bi bi-trash" aria-hidden="true"></i></span>
                            <div>
                                <h3>Hapus akun permanen</h3>
                                <p class="account-delete-note">Penghapusan akun juga menghapus lamaran kerja/magang dan dokumen terkait. Anda harus mendaftar kembali untuk menggunakan layanan.</p>
                            </div>
                        </div>
                        <form class="account-delete-form" action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Hapus akun beserta seluruh lamaran dan dokumen terkait secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <div class="account-field">
                                <label for="deleteAccountPassword">Konfirmasi dengan kata sandi saat ini</label>
                                <input id="deleteAccountPassword" name="password" type="password" autocomplete="current-password" required>
                            </div>
                            <button class="account-button danger" type="submit"><i class="bi bi-trash" aria-hidden="true"></i> Hapus akun permanen</button>
                        </form>
                    </section>
                </div>
            </section>
        </div>
    </main>
    @include('user.partials.footer')
    <script>
        (function(){
            var revealItems = Array.from(document.querySelectorAll('[data-scroll-reveal]'));
            if(!revealItems.length) return;

            if(window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)){
                revealItems.forEach(function(item){ item.classList.add('is-visible'); });
                return;
            }

            document.documentElement.classList.add('scroll-reveal-ready');
            var observer = new IntersectionObserver(function(entries){
                entries.forEach(function(entry){
                    if(!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, {threshold: 0.14, rootMargin: '0px 0px -36px 0px'});

            revealItems.forEach(function(item){ observer.observe(item); });
        })();

        (function(){
            var input = document.getElementById('profilePhoto');
            if(!input) return;

            var status = document.getElementById('profilePhotoStatus');
            var error = document.getElementById('profilePhotoError');
            var photoName = document.getElementById('profileName').value;
            var profileInitials = input.dataset.profileInitials || '';
            var avatar = document.querySelector('[data-profile-photo-avatar]');
            var picker = document.querySelector('.account-photo-picker');
            var savedImage = avatar.querySelector('img');
            var savedPhotoUrl = savedImage ? savedImage.getAttribute('src') : null;
            var previewUrl = null;

            function watchProfileAvatar(image){
                image.addEventListener('error', function(){
                    if(image.getAttribute('src') === savedPhotoUrl) savedPhotoUrl = null;
                    renderAvatar(null);
                }, {once:true});
                if(image.complete && image.naturalWidth === 0) image.dispatchEvent(new Event('error'));
            }

            function renderAvatar(src){
                var image = document.createElement('img');
                var visual = image;
                if(src){
                    watchProfileAvatar(image);
                    image.src = src;
                    image.alt = 'Foto profil ' + photoName;
                }else{
                    visual = document.createElement('span');
                    visual.className = 'account-avatar-initials';
                    visual.textContent = profileInitials;
                    visual.setAttribute('aria-label', 'Inisial ' + photoName);
                }
                avatar.replaceChildren(visual, input, picker);
            }

            if(savedImage) watchProfileAvatar(savedImage);

            function updateHeaderAvatar(src){
                if(!src) return;
                var link = document.querySelector('.site-header .account-link');
                if(!link) return;
                var image = document.createElement('img');
                image.className = 'account-nav-avatar';
                image.alt = '';
                link.replaceChildren(image);
                image.src = src;
            }

            function restoreSavedAvatar(){
                if(previewUrl){
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }
                renderAvatar(savedPhotoUrl);
            }

            input.addEventListener('change', function(){
                var photo = input.files && input.files[0];
                if(!photo){
                    input.setCustomValidity('');
                    return;
                }

                if(['image/jpeg', 'image/png', 'image/webp'].indexOf(photo.type) === -1 || photo.size > 2 * 1024 * 1024){
                    input.value = '';
                    input.setCustomValidity('');
                    error.textContent = 'Pilih gambar berformat JPG, PNG, atau WebP dengan ukuran yang sesuai.';
                    error.hidden = false;
                    restoreSavedAvatar();
                    return;
                }

                input.setCustomValidity('');
                error.hidden = true;
                if(previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = URL.createObjectURL(photo);
                renderAvatar(previewUrl);
                status.textContent = 'Foto profil sedang disimpan.';
                input.disabled = true;
                picker.setAttribute('aria-disabled', 'true');

                var formData = new FormData();
                formData.append('profile_photo', photo);
                formData.append('_token', document.querySelector('#profileUpdateForm input[name="_token"]').value);

                fetch(@json(route('profile.photo.update')), {
                    method: 'POST',
                    headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                    body: formData
                }).then(function(response){
                    return response.json().then(function(payload){
                        if(!response.ok){
                            var validationMessage = payload.errors && payload.errors.profile_photo && payload.errors.profile_photo[0];
                            throw new Error(validationMessage || payload.message || 'Foto profil gagal disimpan.');
                        }
                        if(typeof payload.photo_url !== 'string' || !payload.photo_url){
                            throw new Error('Server tidak mengembalikan alamat foto profil.');
                        }
                        return payload.photo_url;
                    });
                }).then(function(photoUrl){
                    savedPhotoUrl = photoUrl;
                    restoreSavedAvatar();
                    updateHeaderAvatar(photoUrl);
                    input.value = '';
                    status.textContent = 'Foto profil berhasil disimpan.';
                }).catch(function(uploadError){
                    input.value = '';
                    restoreSavedAvatar();
                    error.textContent = uploadError instanceof Error ? uploadError.message : 'Foto profil gagal disimpan. Coba lagi.';
                    error.hidden = false;
                    status.textContent = 'Foto profil gagal disimpan.';
                }).finally(function(){
                    input.disabled = false;
                    picker.removeAttribute('aria-disabled');
                });
            });
        })();
    </script>
</body>
</html>
