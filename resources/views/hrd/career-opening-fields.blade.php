<div class="hrd-opening-fields">
    <label class="hrd-opening-field">Nama posisi / bidang<input name="title" required maxlength="180" value="{{ $opening?->title ?? old('title') }}"></label>
    @if($openingType === 'internship')
        @if($isEdit || $major === null)
            <label class="hrd-opening-field">Jurusan<input name="major" required maxlength="180" value="{{ $opening?->major ?? old('major', $major) }}" placeholder="Contoh: Sistem Informasi"></label>
        @else
            <input type="hidden" name="major" value="{{ old('major', $major) }}">
        @endif
    @endif
    <label class="hrd-opening-field">Lokasi kerja<input name="location" required maxlength="180" value="{{ $opening?->location ?? old('location') }}"></label>
    <label class="hrd-opening-field">Sistem kerja<input name="work_system" required maxlength="120" value="{{ $opening?->work_system ?? old('work_system', 'Work From Office (WFO)') }}"></label>
    <label class="hrd-opening-field">Pendidikan minimal<input name="education" required maxlength="180" value="{{ $opening?->education ?? old('education') }}"></label>
    @if($openingType === 'internship')<label class="hrd-opening-field">Durasi<input name="duration" required maxlength="120" value="{{ $opening?->duration ?? old('duration') }}" placeholder="Contoh: 3 bulan"></label>@endif
    <label class="hrd-opening-field">Status<select name="status"><option value="open" @selected(($opening?->status ?? old('status', 'open')) === 'open')>Dibuka</option><option value="closed" @selected(($opening?->status ?? old('status')) === 'closed')>Ditutup</option></select></label>
    <label class="hrd-opening-field hrd-opening-field--wide">Kualifikasi<textarea name="qualifications" required maxlength="5000" rows="3">{{ $opening?->qualifications ?? old('qualifications') }}</textarea></label>
    <label class="hrd-opening-field hrd-opening-field--wide">Deskripsi pekerjaan<textarea name="description" required maxlength="10000" rows="4">{{ $opening?->description ?? old('description') }}</textarea></label>
    <label class="hrd-opening-field hrd-opening-field--wide">Benefit<textarea name="benefits" required maxlength="3000" rows="2">{{ $opening?->benefits ?? old('benefits') }}</textarea></label>
</div>
<div class="hrd-opening-form-actions"><button class="hrd-button hrd-button--primary" type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Lowongan' }}</button></div>
