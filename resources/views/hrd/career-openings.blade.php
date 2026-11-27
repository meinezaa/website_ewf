<section class="hrd-view hrd-openings" id="view-{{ $viewId }}" data-title="{{ $heading }}" hidden>
    @php
        $openings = $careerOpenings->where('type', $openingType);
        $majorGroups = $openingType === 'internship'
            ? $openings->groupBy(fn ($opening) => trim((string) $opening->major) ?: 'Jurusan belum ditentukan')
            : collect();
    @endphp

    <header class="hrd-openings-heading">
        <div><span class="hrd-overline">REKRUTMEN</span><h1>{{ $openingType === 'internship' ? 'Lowongan Magang & PKL' : 'Lowongan Kerja' }}</h1></div>
    </header>

    @if($errors->any())<div class="hrd-openings-error" role="alert">{{ $errors->first() }}</div>@endif

    @if($openingType === 'professional')
        <section class="hrd-panel hrd-opening-form-card">
            <div class="hrd-opening-card-heading"><div><h2>Tambah Lowongan</h2><p>Lengkapi informasi posisi yang akan dibuka.</p></div></div>
            <form method="POST" action="{{ route('hrd.openings.store', ['type' => $openingType]) }}" class="hrd-opening-form">
                @csrf
                @include('hrd.career-opening-fields', ['opening' => null, 'major' => null, 'isEdit' => false])
            </form>
        </section>
    @endif

    <div class="hrd-opening-tools">
        <label class="hrd-opening-search"><span class="hrd-opening-search-icon" aria-hidden="true">⌕</span><input type="search" data-opening-search placeholder="Cari {{ $openingType === 'internship' ? 'jurusan atau bidang' : 'posisi atau lokasi' }}..." aria-label="Cari lowongan"></label>
        @if($openingType === 'internship')
            <label class="hrd-opening-filter"><span>Filter jurusan</span><select data-opening-major-filter aria-label="Filter jurusan"><option value="">Semua jurusan</option>@foreach($majorGroups as $major => $majorOpenings)<option value="{{ $major }}">{{ $major }}</option>@endforeach</select></label>
        @endif
        <label class="hrd-opening-filter"><span>Filter status</span><select data-opening-status aria-label="Filter status lowongan"><option value="">Semua status</option><option value="open">Dibuka</option><option value="closed">Ditutup</option></select></label>
    </div>

    @if($openingType === 'professional')
        <div class="hrd-opening-list" data-opening-list>
            @forelse($openings as $opening)
                <article class="hrd-panel hrd-opening-card" data-opening-item data-status="{{ $opening->status }}" data-search-text="{{ strtolower($opening->title.' '.$opening->location.' '.$opening->work_system.' '.$opening->education) }}">
                    <header class="hrd-opening-card-top"><div><h2>{{ $opening->title }}</h2></div><span class="hrd-opening-status {{ $opening->status === 'open' ? 'is-open' : 'is-closed' }}">{{ $opening->status === 'open' ? 'Dibuka' : 'Ditutup' }}</span></header>
                    <div class="hrd-opening-meta"><span>{{ $opening->location }}</span><span>{{ $opening->work_system }}</span><span>{{ $opening->education }}</span></div>
                    <details class="hrd-opening-edit"><summary>Edit</summary><form method="POST" action="{{ route('hrd.openings.update', $opening) }}" class="hrd-opening-form hrd-opening-edit-form">@csrf @method('PUT') @include('hrd.career-opening-fields', ['opening' => $opening, 'major' => null, 'isEdit' => true])</form></details>
                    <form method="POST" action="{{ route('hrd.openings.destroy', $opening) }}" class="hrd-opening-delete-form" onsubmit="return confirm('Hapus lowongan ini? Data pendaftaran yang sudah tersimpan tidak dihapus.')">@csrf @method('DELETE')<button class="hrd-button hrd-button--secondary" type="submit">Hapus</button></form>
                </article>
            @empty
                <div class="hrd-panel hrd-opening-empty" data-opening-empty>Belum ada lowongan pada kategori ini.</div>
            @endforelse
            <div class="hrd-panel hrd-opening-empty" data-opening-no-results hidden>Tidak ada lowongan yang sesuai dengan pencarian atau filter.</div>
        </div>
    @else
        <details class="hrd-panel hrd-opening-new-major">
            <summary>Tambah Jurusan</summary>
            <div class="hrd-opening-new-major-content">
                <p>Buat jurusan baru sekaligus tambahkan bidang magang pertamanya.</p>
                <form method="POST" action="{{ route('hrd.openings.store', ['type' => $openingType]) }}" class="hrd-opening-form">@csrf
                    @include('hrd.career-opening-fields', ['opening' => null, 'major' => null, 'isEdit' => false])
                </form>
            </div>
        </details>
        <div class="hrd-opening-majors" data-opening-list>
            @forelse($majorGroups as $major => $majorOpenings)
                <article class="hrd-panel hrd-opening-major-card" data-opening-major data-major="{{ $major }}">
                    <header class="hrd-opening-major-heading"><div><h2>{{ $major }}</h2><p><span data-major-visible-count>{{ $majorOpenings->count() }}</span> bidang magang</p></div><span class="hrd-opening-major-count">{{ $majorOpenings->count() }}</span></header>
                    <details class="hrd-opening-major-manage">
                        <summary>Kelola lowongan</summary>
                        <div class="hrd-opening-major-content">
                            <details class="hrd-opening-add"><summary>Tambah Lowongan</summary>
                                <form method="POST" action="{{ route('hrd.openings.store', ['type' => $openingType]) }}" class="hrd-opening-form hrd-opening-add-form">@csrf
                                    @include('hrd.career-opening-fields', ['opening' => null, 'major' => $major, 'isEdit' => false])
                                </form>
                            </details>
                            <div class="hrd-opening-fields-list">
                                @foreach($majorOpenings as $opening)
                                    <article class="hrd-opening-field-card" data-opening-item data-status="{{ $opening->status }}" data-search-text="{{ strtolower($major.' '.$opening->title.' '.$opening->location.' '.$opening->work_system.' '.$opening->education) }}">
                                        <header class="hrd-opening-field-heading"><h3>{{ $opening->title }}</h3><span class="hrd-opening-status {{ $opening->status === 'open' ? 'is-open' : 'is-closed' }}">{{ $opening->status === 'open' ? 'Dibuka' : 'Ditutup' }}</span></header>
                                        <details class="hrd-opening-detail"><summary>Lihat detail</summary>
                                            <div class="hrd-opening-detail-content">
                                                <div class="hrd-opening-meta"><span>{{ $opening->location }}</span><span>{{ $opening->work_system }}</span><span>{{ $opening->education }}</span>@if($opening->duration)<span>{{ $opening->duration }}</span>@endif</div>
                                                <section><h4>Deskripsi pekerjaan</h4><p>{{ $opening->description }}</p></section>
                                                <section><h4>Kualifikasi</h4><p class="hrd-opening-preline">{{ $opening->qualifications }}</p></section>
                                                <section><h4>Benefit</h4><p>{{ $opening->benefits }}</p></section>
                                                <details class="hrd-opening-edit"><summary>Edit</summary><form method="POST" action="{{ route('hrd.openings.update', $opening) }}" class="hrd-opening-form hrd-opening-edit-form">@csrf @method('PUT') @include('hrd.career-opening-fields', ['opening' => $opening, 'major' => null, 'isEdit' => true])</form></details>
                                                <form method="POST" action="{{ route('hrd.openings.destroy', $opening) }}" class="hrd-opening-delete-form" onsubmit="return confirm('Hapus lowongan ini? Data pendaftaran yang sudah tersimpan tidak dihapus.')">@csrf @method('DELETE')<button class="hrd-button hrd-button--secondary" type="submit">Hapus</button></form>
                                            </div>
                                        </details>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </details>
                </article>
            @empty
                <div class="hrd-panel hrd-opening-empty" data-opening-empty>Belum ada lowongan magang/PKL. Tambahkan lowongan pertama untuk membuat jurusan.</div>
            @endforelse
            <div class="hrd-panel hrd-opening-empty" data-opening-no-results hidden>Tidak ada jurusan atau bidang yang sesuai dengan pencarian atau filter.</div>
        </div>
    @endif
</section>

@once
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.hrdOpeningFiltersBound) return;
        window.hrdOpeningFiltersBound = true;
        document.querySelectorAll('.hrd-openings').forEach(view => {
            const search = view.querySelector('[data-opening-search]');
            const status = view.querySelector('[data-opening-status]');
            const major = view.querySelector('[data-opening-major-filter]');
            const noResults = view.querySelector('[data-opening-no-results]');
            const applyFilters = () => {
                const term = (search?.value || '').trim().toLocaleLowerCase('id');
                const selectedStatus = status?.value || '';
                const selectedMajor = major?.value || '';
                view.querySelectorAll('[data-opening-item]').forEach(item => {
                    const matchesText = (item.dataset.searchText || item.textContent).toLocaleLowerCase('id').includes(term);
                    const matchesStatus = !selectedStatus || item.dataset.status === selectedStatus;
                    item.hidden = !(matchesText && matchesStatus);
                });
                view.querySelectorAll('[data-opening-major]').forEach(card => {
                    const matchingFields = [...card.querySelectorAll('[data-opening-item]')].filter(item => !item.hidden).length;
                    const hasSearchMatch = card.querySelector('[data-opening-item]') ? matchingFields > 0 : card.textContent.toLocaleLowerCase('id').includes(term);
                    card.hidden = (!!selectedMajor && card.dataset.major !== selectedMajor) || !hasSearchMatch || (!!selectedStatus && matchingFields === 0);
                    const count = card.querySelector('[data-major-visible-count]');
                    if (count) count.textContent = matchingFields;
                });
                const majorCards = [...view.querySelectorAll('[data-opening-major]')];
                const visibleCount = majorCards.length
                    ? majorCards.filter(card => !card.hidden).reduce((total, card) => total + card.querySelectorAll('[data-opening-item]:not([hidden])').length, 0)
                    : view.querySelectorAll('[data-opening-item]:not([hidden])').length;
                const totalItems = view.querySelectorAll('[data-opening-item]').length;
                if (noResults) noResults.hidden = visibleCount > 0 || totalItems === 0;
            };
            search?.addEventListener('input', applyFilters);
            status?.addEventListener('change', applyFilters);
            major?.addEventListener('change', applyFilters);
        });
    });
</script>
@endonce
