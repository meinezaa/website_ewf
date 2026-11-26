<section class="hrd-view" id="view-documents" data-title="Sertifikat & LoA" hidden>
    <div class="hrd-page-heading">
        <div><span class="hrd-overline">DOKUMEN PESERTA MAGANG</span><h1>Sertifikat &amp; LoA</h1><p>Buat LoA untuk peserta yang diterima atau terbitkan sertifikat digital setelah program selesai.</p></div>
        <button class="hrd-button hrd-button--primary" type="button" data-open-document-form>Buat dokumen</button>
    </div>
    <div class="hrd-list-summary">
        <div><strong>{{ number_format($documentSummary['total'], 0, ',', '.') }}</strong><span>Total dokumen terbit</span></div>
        <div><strong>{{ number_format($documentSummary['pending'], 0, ',', '.') }}</strong><span>Peserta tanpa dokumen</span></div>
        <div><strong>{{ number_format($documentSummary['certificates'], 0, ',', '.') }}</strong><span>Sertifikat terbit</span></div>
        <div><strong>{{ number_format($documentSummary['loas'], 0, ',', '.') }}</strong><span>LoA terbit</span></div>
    </div>
    <section class="hrd-panel hrd-list-panel">
        <div class="hrd-list-toolbar">
            <div><h2>Dokumen yang tersimpan</h2><p>LoA yang dibuat HRD tersedia di akun Karir peserta terkait.</p></div>
            <div class="hrd-filters"><label class="hrd-filter-search"><i class="bi bi-search"></i><input class="hrd-local-search" type="search" placeholder="Cari nama penerima..."></label><label class="hrd-select-wrap"><select class="hrd-status-filter" aria-label="Filter dokumen"><option value="">Semua jenis</option><option value="Sertifikat Magang">Sertifikat Magang</option><option value="Surat Penerimaan (LoA)">Surat Penerimaan (LoA)</option></select><i class="bi bi-chevron-down"></i></label></div>
        </div>
        <div class="hrd-table-wrap"><table class="hrd-table"><thead><tr><th>NAMA PENERIMA</th><th>JENIS DOKUMEN</th><th>PROGRAM / PERIODE</th><th>TANGGAL</th><th>STATUS</th><th></th></tr></thead><tbody>
            @forelse ($documentRows as $document)
                <tr data-name="{{ $document['application']['name'] }}" data-position="{{ $document['label'] }}">
                    <td><div class="hrd-person-cell"><span class="hrd-avatar hrd-avatar--mint">{{ $document['application']['initials'] }}</span><span><strong>{{ $document['application']['name'] }}</strong><small>{{ $document['application']['organization'] }}</small></span></div></td>
                    <td><span class="hrd-document-type"><i class="bi {{ $document['type'] === 'certificate' ? 'bi-award' : 'bi-envelope-paper' }}"></i> {{ $document['label'] }}</span><small class="hrd-cell-sub">{{ $document['filename'] }}</small></td>
                    <td>{{ $document['application']['position'] }} &bull; {{ $document['application']['period'] }}</td>
                    <td>{{ $document['application']['joined'] }}</td>
                    <td><span class="hrd-badge badge-accepted"><i></i>Tersimpan</span></td>
                    <td><a class="hrd-row-action" href="{{ $document['url'] }}">{{ $document['type'] === 'loa' || $document['generated'] ? 'Lihat' : 'Unduh' }} <i class="bi {{ $document['type'] === 'loa' || $document['generated'] ? 'bi-eye' : 'bi-download' }}"></i></a></td>
                </tr>
            @empty
                <tr class="hrd-empty-row"><td colspan="6">Belum ada sertifikat atau LoA yang diterbitkan.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
</section>

<dialog class="hrd-document-form-dialog" id="hrdDocumentFormDialog" aria-labelledby="hrdDocumentFormTitle">
    <button class="hrd-dialog-close" type="button" data-close-dialog aria-label="Tutup"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
    <span class="hrd-dialog-eyebrow">DOKUMEN PESERTA MAGANG</span>
    <h2 id="hrdDocumentFormTitle">Buat LoA atau sertifikat</h2>
    <p class="hrd-dialog-description">LoA dan sertifikat yang diterbitkan akan tersedia di akun Karir peserta terkait.</p>
    <form id="hrdDocumentForm" class="hrd-document-form" action="{{ route('hrd.documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label data-applicant-field>Peserta penerima
            <select name="internship_registration_id" data-document-applicant required @disabled($documentCandidates->isEmpty())>
                <option value="">Pilih peserta</option>
                @foreach ($documentCandidates as $candidate)
                    <option value="{{ $candidate['id'] }}"
                        data-program-completed="{{ $candidate['program_completed'] ? 'true' : 'false' }}"
                        data-name="{{ $candidate['name'] }}"
                        data-school="{{ $candidate['university'] }}"
                        data-faculty="{{ $candidate['faculty'] }}"
                        data-program="{{ $candidate['study_program'] }}"
                        data-field="{{ $candidate['internship_field'] }}"
                        data-start="{{ $candidate['period_start'] }}"
                        data-end="{{ $candidate['period_end'] }}"
                        @selected((string) old('internship_registration_id') === (string) $candidate['id'])>{{ $candidate['name'] }} &bull; {{ $candidate['position'] }} &bull; {{ $candidate['status_label'] }}</option>
                @endforeach
            </select>
        </label>
        <label>Jenis dokumen<select name="document_type" required data-document-type><option value="loa" @selected(old('document_type', 'loa') === 'loa')>Surat Penerimaan (LoA)</option><option value="certificate" data-certificate-option @selected(old('document_type') === 'certificate')>Sertifikat Magang</option></select></label>

        <div class="hrd-loa-fields" data-loa-fields>
            <section class="hrd-loa-participant-picker" aria-label="Pilih peserta LoA">
                <label>Sekolah / perguruan tinggi peserta
                    <select data-loa-school data-old-school="{{ mb_strtolower(trim((string) old('school_name'))) }}" required>
                        <option value="">Pilih sekolah terlebih dahulu</option>
                        @foreach ($loaSchools as $school)
                            <option value="{{ $school['key'] }}" data-school-name="{{ $school['name'] }}">{{ $school['label'] }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="hrd-loa-candidate-list" data-loa-candidate-list hidden>
                    @foreach ($documentCandidates as $candidate)
                        <label class="hrd-loa-candidate" data-loa-candidate-option hidden>
                            <input type="checkbox" value="{{ $candidate['id'] }}" data-loa-candidate
                                data-school-key="{{ $candidate['school_key'] }}"
                                data-name="{{ $candidate['name'] }}"
                                data-program="{{ $candidate['study_program'] }}"
                                data-faculty="{{ $candidate['faculty'] }}"
                                data-field="{{ $candidate['internship_field'] }}"
                                data-start="{{ $candidate['period_start'] }}"
                                data-end="{{ $candidate['period_end'] }}">
                            <span><strong>{{ $candidate['name'] }}</strong><small>{{ $candidate['study_program'] ?: $candidate['position'] }}</small></span>
                        </label>
                    @endforeach
                </div>
                @error('participants')<small class="hrd-form-error" data-loa-server-error>{{ $message }}</small>@enderror
                <p class="hrd-form-error" data-loa-client-error role="alert" hidden></p>
                <p class="hrd-form-note">Pilih maksimal 5 peserta dari sekolah yang sama. NPM / NIS diisi untuk setiap peserta.</p>
                <div class="hrd-loa-participant-fields" data-loa-participant-fields></div>
            </section>

            <div class="hrd-loa-grid">
                <label>Universitas / sekolah<input name="school_name" value="{{ old('school_name') }}" maxlength="180" required></label>
                <label>Fakultas<input name="faculty" value="{{ old('faculty') }}" maxlength="180"></label>
                <label>Bidang magang<input name="internship_field" value="{{ old('internship_field') }}" maxlength="180" required></label>
                <label>Perusahaan<input name="company_name" value="{{ old('company_name', 'PT Equityworld Futures') }}" maxlength="180" required></label>
                <label>Lokasi perusahaan<input name="company_location" value="{{ old('company_location', 'Surabaya') }}" maxlength="150"></label>
                <label>Kota penerbitan surat<input name="letter_city" value="{{ old('letter_city', 'Surabaya') }}" maxlength="150" required></label>
                <label>Tanggal surat<input name="letter_date" type="date" value="{{ old('letter_date', today()->toDateString()) }}" required></label>
                <label>Periode mulai<input name="period_start" type="date" value="{{ old('period_start') }}" required></label>
                <label>Periode selesai<input name="period_end" type="date" value="{{ old('period_end') }}" required></label>
                <label>Hari kerja<input name="work_days" value="{{ old('work_days', 'Senin - Jumat') }}" maxlength="100" required></label>
                <label>Jam kerja<input name="work_hours" value="{{ old('work_hours', '08.00 - 17.00 WIB') }}" maxlength="100" required></label>
                <label>Jabatan penandatangan<input name="signer_title" value="{{ old('signer_title', 'Supervisor Magang') }}" maxlength="120" required></label>
                <label>Nama penandatangan<input name="signer_name" value="{{ old('signer_name') }}" maxlength="150" required></label>
            </div>
            <label class="hrd-loa-body-label">Keterangan surat<textarea name="letter_body" rows="4" maxlength="700" required>{{ old('letter_body', 'Dengan ini menerangkan bahwa peserta tersebut benar mengikuti program magang di perusahaan kami pada periode yang tercantum di atas.') }}</textarea></label>
            <p class="hrd-form-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Setelah disimpan, peserta dapat melihat dan mengunduh LoA dari halaman Karir.</p>
        </div>

        <div data-certificate-fields hidden>
            <p class="hrd-form-note">Nama penerima mengikuti data peserta yang dipilih: <strong data-certificate-recipient>—</strong></p>
            <div class="hrd-loa-grid">
                <label>Periode magang mulai<input name="certificate_period_start" type="date" value="{{ old('certificate_period_start') }}" required>@error('certificate_period_start')<small class="hrd-form-error">{{ $message }}</small>@enderror</label>
                <label>Periode magang selesai<input name="certificate_period_end" type="date" value="{{ old('certificate_period_end') }}" required>@error('certificate_period_end')<small class="hrd-form-error">{{ $message }}</small>@enderror</label>
                <label>Tanggal penerbitan<input name="certificate_issued_at" type="date" value="{{ old('certificate_issued_at', today()->toDateString()) }}" required>@error('certificate_issued_at')<small class="hrd-form-error">{{ $message }}</small>@enderror</label>
                <label>Jabatan penandatangan<input name="certificate_signer_title" value="{{ old('certificate_signer_title', 'MENTOR MAGANG') }}" maxlength="120" required>@error('certificate_signer_title')<small class="hrd-form-error">{{ $message }}</small>@enderror</label>
                <label>Nama penandatangan<input name="certificate_signer_name" value="{{ old('certificate_signer_name') }}" maxlength="150" required>@error('certificate_signer_name')<small class="hrd-form-error">{{ $message }}</small>@enderror</label>
            </div>
            <p class="hrd-form-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Nomor sertifikat dibuat otomatis. Sertifikat dapat dilihat dan diunduh peserta sebagai PDF atau PNG.</p>
        </div>
        <button class="hrd-button hrd-button--primary" type="submit" @disabled($documentCandidates->isEmpty())><i class="bi bi-check2-circle" aria-hidden="true"></i> Simpan dan terbitkan</button>
    </form>
</dialog>

<script>
    (() => {
        const form = document.getElementById('hrdDocumentForm');
        const applicant = form?.querySelector('[data-document-applicant]');
        const type = form?.querySelector('[data-document-type]');
        const loaFields = form?.querySelector('[data-loa-fields]');
        const certificateFields = form?.querySelector('[data-certificate-fields]');
        const certificateOption = form?.querySelector('[data-certificate-option]');
        const applicantField = form?.querySelector('[data-applicant-field]');
        const loaSchool = form?.querySelector('[data-loa-school]');
        const candidateList = form?.querySelector('[data-loa-candidate-list]');
        const candidateOptions = [...(form?.querySelectorAll('[data-loa-candidate-option]') || [])];
        const candidateChoices = [...(form?.querySelectorAll('[data-loa-candidate]') || [])];
        const participantFields = form?.querySelector('[data-loa-participant-fields]');
        const clientError = form?.querySelector('[data-loa-client-error]');
        const oldParticipants = @json(old('participants', []));
        if (!form || !applicant || !type || !loaFields || !certificateFields) return;

        const syncForm = (prefill = false) => {
            const selected = applicant.options[applicant.selectedIndex];
            const isCompleted = selected?.dataset.programCompleted === 'true';
            const recipient = certificateFields.querySelector('[data-certificate-recipient]');
            if (recipient) recipient.textContent = selected?.dataset.name || '—';
            const hasCompletedCandidate = [...applicant.options].some(option => option.dataset.programCompleted === 'true');
            certificateOption.disabled = !hasCompletedCandidate;
            if (selected?.value && !isCompleted && type.value === 'certificate') type.value = 'loa';
            const isLoa = type.value === 'loa';
            applicantField.hidden = isLoa;
            applicant.disabled = isLoa;
            applicant.required = !isLoa;
            loaFields.hidden = !isLoa;
            certificateFields.hidden = isLoa;
            loaFields.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.required) input.dataset.loaRequired = 'true';
                input.disabled = !isLoa;
                input.required = isLoa && input.dataset.loaRequired === 'true';
            });
            certificateFields.querySelectorAll('input').forEach(input => {
                input.disabled = isLoa;
                input.required = !isLoa;
            });

            if (prefill && selected?.value) {
                const fields = {
                    school_name: 'school', faculty: 'faculty', internship_field: 'field',
                    period_start: 'start', period_end: 'end',
                    certificate_period_start: 'start', certificate_period_end: 'end'
                };
                Object.entries(fields).forEach(([name, key]) => {
                    const input = form.elements.namedItem(name);
                    if (input && selected.dataset[key] !== undefined) input.value = selected.dataset[key];
                });
                const selectedSchool = (selected.dataset.school || '').trim().toLowerCase();
                const schoolOption = [...loaSchool.options].find(option =>
                    (option.dataset.schoolName || '').trim().toLowerCase() === selectedSchool
                );
                if (schoolOption && selectedSchool) {
                    loaSchool.value = schoolOption.value;
                    updateSchoolCandidates();
                }
            }
        };
        applicant.addEventListener('change', () => syncForm(true));
        type.addEventListener('change', () => syncForm());

        const validationMessages = @json($errors->getMessages());
        if (clientError && Object.keys(validationMessages).some(key => key.startsWith('participants.'))) {
            clientError.textContent = 'Periksa kembali data peserta yang dipilih.';
            clientError.hidden = false;
        }

        const oldSchool = loaSchool?.dataset.oldSchool;
        if (oldSchool) {
            const matchingSchool = [...loaSchool.options].find(option =>
                (option.dataset.schoolName || '').trim().toLowerCase() === oldSchool
            );
            if (matchingSchool) loaSchool.value = matchingSchool.value;
        }

        const oldParticipantsById = new Map(
            (Array.isArray(oldParticipants) ? oldParticipants : []).map(participant => [
                String(participant.internship_registration_id),
                participant
            ])
        );

        const renderParticipantFields = () => {
            const previousValues = new Map(
                [...participantFields.querySelectorAll('[data-loa-participant-id]')].map(row => [
                    row.dataset.loaParticipantId,
                    Object.fromEntries([...row.querySelectorAll('[data-participant-field]')].map(input => [
                        input.dataset.participantField,
                        input.value
                    ]))
                ])
            );
            const selectedCandidates = candidateChoices.filter(choice => choice.checked);
            participantFields.replaceChildren();

            selectedCandidates.forEach((choice, index) => {
                const id = String(choice.value);
                const participant = previousValues.get(id) || oldParticipantsById.get(id) || {};
                const row = document.createElement('fieldset');
                row.className = 'hrd-loa-participant';
                row.dataset.loaParticipantId = id;
                const legend = document.createElement('legend');
                legend.textContent = 'Peserta ' + (index + 1) + ': ' + (choice.dataset.name || 'Peserta');
                row.appendChild(legend);

                const registrationId = document.createElement('input');
                registrationId.type = 'hidden';
                registrationId.name = `participants[${index}][internship_registration_id]`;
                registrationId.value = id;
                row.appendChild(registrationId);

                [
                    ['student_number', 'NPM / NIS', participant.student_number || '', false],
                    ['full_name', 'Nama lengkap', choice.dataset.name || '', Boolean(choice.dataset.name)],
                    ['study_program', 'Program studi', choice.dataset.program || '', Boolean(choice.dataset.program)]
                ].forEach(([field, labelText, value, readOnly]) => {
                    const label = document.createElement('label');
                    label.textContent = labelText;
                    const input = document.createElement('input');
                    input.name = `participants[${index}][${field}]`;
                    input.value = value;
                    input.maxLength = field === 'student_number' ? 50 : 180;
                    input.required = true;
                    input.readOnly = readOnly;
                    input.dataset.participantField = field;
                    label.appendChild(input);
                    row.appendChild(label);
                });
                participantFields.appendChild(row);
            });
        };

        const updateSchoolCandidates = () => {
            const schoolKey = loaSchool.value;
            candidateList.hidden = !schoolKey;
            candidateOptions.forEach(option => {
                const choice = option.querySelector('[data-loa-candidate]');
                const isVisible = choice.dataset.schoolKey === schoolKey;
                option.hidden = !isVisible;
                if (!isVisible) choice.checked = false;
            });
            const selectedOption = loaSchool.options[loaSchool.selectedIndex];
            const schoolName = form.elements.namedItem('school_name');
            if (schoolName && selectedOption?.value) schoolName.value = selectedOption.dataset.schoolName || '';
            renderParticipantFields();
        };

        loaSchool.addEventListener('change', () => {
            updateSchoolCandidates();
            if (clientError) clientError.hidden = true;
        });
        candidateChoices.forEach(choice => choice.addEventListener('change', () => {
            const checked = candidateChoices.filter(candidate => candidate.checked);
            if (checked.length > 5) {
                choice.checked = false;
                if (clientError) {
                    clientError.textContent = 'Dalam satu LoA maksimal 5 peserta.';
                    clientError.hidden = false;
                }
                return;
            }
            if (checked.length === 1) {
                const fields = {
                    faculty: 'faculty',
                    internship_field: 'field',
                    period_start: 'start',
                    period_end: 'end'
                };
                Object.entries(fields).forEach(([name, key]) => {
                    const input = form.elements.namedItem(name);
                    if (input && choice.dataset[key] !== undefined) input.value = choice.dataset[key];
                });
            }
            renderParticipantFields();
            if (clientError) clientError.hidden = true;
        }));

        if (loaSchool.value) {
            candidateChoices.forEach(choice => {
                const oldParticipant = oldParticipantsById.get(String(choice.value));
                choice.checked = choice.dataset.schoolKey === loaSchool.value && Boolean(oldParticipant);
            });
        }
        updateSchoolCandidates();
        syncForm(false);

        const dialog = document.getElementById('hrdDocumentFormDialog');
        if (dialog && ({{ $errors->hasAny(['internship_registration_id', 'document_type', 'participants', 'school_name', 'faculty', 'internship_field', 'company_name', 'company_location', 'letter_city', 'period_start', 'period_end', 'work_days', 'work_hours', 'letter_date', 'letter_body', 'signer_title', 'signer_name', 'certificate_period_start', 'certificate_period_end', 'certificate_issued_at', 'certificate_signer_title', 'certificate_signer_name', 'file']) || collect($errors->keys())->contains(fn ($key) => str_starts_with($key, 'participants.')) ? 'true' : 'false' }})) {
            dialog.showModal();
        }
    })();
</script>
