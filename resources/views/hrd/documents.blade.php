<section class="hrd-view" id="view-documents" data-title="Sertifikat & LoA" hidden>
    <div class="hrd-page-heading"><div><span class="hrd-overline">DOKUMEN · SURAT &amp; SERTIFIKAT</span><h1>Sertifikat &amp; LoA</h1><p>Kelola dokumen program yang tersimpan di penyimpanan privat.</p></div><button class="hrd-button hrd-button--primary" type="button" data-open-document-form>Tambah dokumen</button></div>
    <div class="hrd-list-summary"><div><strong>{{ number_format($documentSummary['total'], 0, ',', '.') }}</strong><span>Total dokumen terbit</span></div><div><strong>{{ number_format($documentSummary['pending'], 0, ',', '.') }}</strong><span>Peserta tanpa dokumen</span></div><div><strong>{{ number_format($documentSummary['certificates'], 0, ',', '.') }}</strong><span>Sertifikat terbit</span></div><div><strong>{{ number_format($documentSummary['loas'], 0, ',', '.') }}</strong><span>LoA terbit</span></div></div>
    <section class="hrd-panel hrd-list-panel"><div class="hrd-list-toolbar"><div><h2>Dokumen yang tersimpan</h2><p>File hanya dapat diakses oleh pengguna HRD dan pemilik pendaftaran.</p></div><div class="hrd-filters"><label class="hrd-filter-search"><i class="bi bi-search"></i><input class="hrd-local-search" type="search" placeholder="Cari nama penerima..."></label><label class="hrd-select-wrap"><select class="hrd-status-filter" aria-label="Filter dokumen"><option value="">Semua jenis</option><option value="Sertifikat Magang">Sertifikat Magang</option><option value="Surat Penerimaan (LoA)">Surat Penerimaan (LoA)</option></select><i class="bi bi-chevron-down"></i></label></div></div>
        <div class="hrd-table-wrap"><table class="hrd-table"><thead><tr><th>NAMA PENERIMA</th><th>JENIS DOKUMEN</th><th>PROGRAM / PERIODE</th><th>TANGGAL</th><th>STATUS</th><th></th></tr></thead><tbody>
            @forelse ($documentRows as $document)
                <tr data-name="{{ $document['application']['name'] }}" data-position="{{ $document['label'] }}">
                    <td><div class="hrd-person-cell"><span class="hrd-avatar hrd-avatar--mint">{{ $document['application']['initials'] }}</span><span><strong>{{ $document['application']['name'] }}</strong><small>{{ $document['application']['organization'] }}</small></span></div></td>
                    <td><span class="hrd-document-type"><i class="bi {{ $document['type'] === 'certificate' ? 'bi-award' : 'bi-envelope-paper' }}"></i> {{ $document['label'] }}</span><small class="hrd-cell-sub">{{ $document['filename'] }}</small></td>
                    <td>{{ $document['application']['position'] }} · {{ $document['application']['period'] }}</td>
                    <td>{{ $document['application']['joined'] }}</td>
                    <td><span class="hrd-badge badge-accepted"><i></i>Tersimpan</span></td>
                    <td><a class="hrd-row-action" href="{{ $document['url'] }}">Unduh <i class="bi bi-download"></i></a></td>
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
    <h2 id="hrdDocumentFormTitle">Tambah Sertifikat atau LoA</h2>
    <p class="hrd-dialog-description">LoA dapat diterbitkan setelah pendaftaran disetujui HRD. Sertifikat dapat diterbitkan setelah program selesai.</p>
    <form id="hrdDocumentForm" class="hrd-document-form" action="{{ route('hrd.documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nama peserta
            <select name="internship_registration_id" required @disabled($documentCandidates->isEmpty())>
                <option value="">Pilih peserta</option>
                @foreach ($documentCandidates as $candidate)
                    <option value="{{ $candidate['id'] }}" data-program-completed="{{ $candidate['program_completed'] ? 'true' : 'false' }}">{{ $candidate['name'] }} · {{ $candidate['position'] }} · {{ $candidate['status_label'] }}</option>
                @endforeach
            </select>
        </label>
        @error('internship_registration_id')<small class="hrd-form-error">{{ $message }}</small>@enderror
        <label>Jenis dokumen<select name="document_type" required data-document-type><option value="">Pilih jenis dokumen</option><option value="certificate" data-certificate-option>Sertifikat Magang (program selesai)</option><option value="loa">Surat Penerimaan (LoA)</option></select></label>
        @error('document_type')<small class="hrd-form-error">{{ $message }}</small>@enderror
        <label>Unggah file PDF<input name="file" type="file" accept=".pdf,application/pdf" required></label>
        @error('file')<small class="hrd-form-error">{{ $message }}</small>@enderror
        <p class="hrd-form-note"><i class="bi bi-info-circle" aria-hidden="true"></i> File maksimal 10 MB dan disimpan di penyimpanan privat.</p>
        <button class="hrd-button hrd-button--primary" type="submit" @disabled($documentCandidates->isEmpty())><i class="bi bi-plus-lg" aria-hidden="true"></i> Simpan dokumen</button>
    </form>
</dialog>

<script>
    (() => {
        const applicant = document.querySelector('#hrdDocumentForm [name="internship_registration_id"]');
        const documentType = document.querySelector('#hrdDocumentForm [data-document-type]');
        const certificateOption = document.querySelector('#hrdDocumentForm [data-certificate-option]');
        if (!applicant || !documentType || !certificateOption) return;

        const syncCertificateEligibility = () => {
            const selectedApplicant = applicant.options[applicant.selectedIndex];
            const isCompleted = selectedApplicant?.dataset.programCompleted === 'true';
            certificateOption.disabled = !isCompleted;
            if (!isCompleted && documentType.value === 'certificate') documentType.value = '';
        };

        applicant.addEventListener('change', syncCertificateEligibility);
        syncCertificateEligibility();
    })();
</script>
