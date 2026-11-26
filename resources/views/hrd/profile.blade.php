<section class="hrd-view" id="view-profile" data-title="Profil Saya" hidden>
    <div class="hrd-page-heading"><div><span class="hrd-overline">PREFERENSI · AKUN</span><h1>Profil Saya</h1><p>Kelola informasi profil HRD yang sedang digunakan.</p></div></div>
    <section class="hrd-panel hrd-profile-panel">
        <div class="hrd-profile-cover" id="hrdProfileCover">
            @if ($hrdCoverUrl)<img class="hrd-profile-cover-image" src="{{ $hrdCoverUrl }}" alt="">@endif
            <input class="hrd-cover-photo-input" id="hrdCoverPhoto" name="cover_photo" type="file" accept="image/jpeg,image/png,image/webp" aria-label="Pilih foto header" aria-describedby="hrdCoverPhotoStatus hrdCoverPhotoError" data-upload-url="{{ route('profile.cover-photo.update') }}" data-saved-cover-url="{{ $hrdCoverUrl }}">
            @unless ($hrdCoverUrl)
                <label class="hrd-cover-photo-button" for="hrdCoverPhoto"><i class="bi bi-camera-fill" aria-hidden="true"></i><span>Tambahkan foto header</span></label>
            @endunless
        </div>
        <div class="hrd-profile-body">
            <div class="hrd-profile-avatar-wrap">
                <span class="hrd-avatar hrd-avatar--navy hrd-avatar--profile" data-profile-avatar>
                    @if ($hrdPhotoUrl)<img src="{{ $hrdPhotoUrl }}" alt="">@else{{ $hrdInitials }}@endif
                </span>
                <input class="hrd-profile-photo-input" id="hrdProfilePhoto" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" aria-label="Pilih foto profil" aria-describedby="hrdProfilePhotoStatus hrdProfilePhotoError" data-upload-url="{{ route('profile.photo.update') }}" data-saved-photo-url="{{ $hrdPhotoUrl }}" data-profile-initials="{{ $hrdInitials }}">
                <label class="hrd-profile-photo-button" for="hrdProfilePhoto" aria-label="Ganti foto profil" title="Ganti foto profil"><i class="bi bi-camera-fill" aria-hidden="true"></i></label>
            </div>
            <div class="hrd-profile-info"><h2>{{ $hrdUser->name }}</h2><p>HRD · PT Equityworld Futures</p><span class="hrd-badge badge-accepted"><i></i>Akses HRD</span></div>
            <div class="hrd-profile-photo-feedback">
                <label class="hrd-cover-photo-edit" for="hrdCoverPhoto" @unless ($hrdCoverUrl) hidden @endunless><i class="bi bi-pencil" aria-hidden="true"></i><span>Edit header</span></label>
                <span class="hrd-cover-photo-status" id="hrdCoverPhotoStatus" role="status" aria-live="polite"></span>
                <span class="hrd-cover-photo-error" id="hrdCoverPhotoError" role="alert" hidden></span>
                <span class="hrd-profile-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Klik ikon kamera untuk mengganti foto profil.</span>
                <span class="hrd-profile-photo-status" id="hrdProfilePhotoStatus" role="status" aria-live="polite"></span>
                <span class="hrd-profile-photo-error" id="hrdProfilePhotoError" role="alert" hidden></span>
            </div>
        </div>
        <form id="hrdProfileLogoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
        </form>
        <form class="hrd-profile-form" id="hrdProfileForm" action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="hrd-profile-form-error" role="alert">{{ $errors->first() }}</div>
            @endif
            <label>Nama lengkap<input name="name" type="text" value="{{ old('name', $hrdUser->name) }}" required maxlength="150"></label>
            <label>Email<input name="email" type="email" value="{{ old('email', $hrdUser->email) }}" required maxlength="255"></label>
            <label>Nomor telepon<input name="phone" type="tel" value="{{ $hrdUser->phone ?? '' }}" placeholder="+62 8xx xxxx xxxx"></label>
            <label>Departemen<input name="department" type="text" value="{{ old('department', $hrdUser->department ?? 'HRD') }}" maxlength="100"></label>
            <div class="hrd-profile-form-actions">
                <button class="hrd-button hrd-button--logout" type="submit" form="hrdProfileLogoutForm"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar akun</button>
                <button class="hrd-button hrd-button--primary" type="submit">Simpan perubahan</button>
            </div>
        </form>
    </section>
</section>
