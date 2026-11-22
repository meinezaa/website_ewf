@php
    $faqCategories = [
        ['id' => 'umum', 'title' => 'Informasi Umum Perusahaan', 'icon' => 'bi-building'],
        ['id' => 'lowongan', 'title' => 'Lowongan Kerja', 'icon' => 'bi-briefcase'],
        ['id' => 'magang', 'title' => 'Program Magang & PKL', 'icon' => 'bi-mortarboard'],
        ['id' => 'seleksi', 'title' => 'Pendaftaran & Seleksi', 'icon' => 'bi-clipboard-check'],
        ['id' => 'fasilitas', 'title' => 'Fasilitas Peserta', 'icon' => 'bi-stars'],
        ['id' => 'kegiatan', 'title' => 'Kegiatan & Informasi Tambahan', 'icon' => 'bi-calendar-event'],
    ];

    $faqItems = [
        ['category' => 'umum', 'question' => 'Apa itu PT Equityworld Futures?', 'answer' => 'PT Equityworld Futures (EWF) merupakan perusahaan yang bergerak di bidang perdagangan berjangka komoditi. Website ini menyediakan informasi mengenai profil perusahaan, kesempatan berkarier, serta program pengembangan peserta melalui Galeri Magang.'],
        ['category' => 'umum', 'question' => 'Apa tujuan website PT Equityworld Futures?', 'answer' => 'Website ini bertujuan memberikan kemudahan bagi masyarakat untuk mengenal perusahaan, memperoleh informasi lowongan kerja, mendaftar sebagai calon staff, serta mendapatkan informasi dan melakukan pendaftaran program magang maupun PKL.'],
        ['category' => 'umum', 'question' => 'Apa itu Galeri Magang?', 'answer' => 'Galeri Magang merupakan wadah pengembangan potensi generasi muda yang memberikan kesempatan kepada mahasiswa dan pelajar untuk mengenal dunia profesional melalui pengalaman dan pembelajaran secara langsung di lingkungan kerja.'],
        ['category' => 'umum', 'question' => 'Di mana saya dapat mengetahui informasi kantor PT Equityworld Futures?', 'answer' => 'Informasi mengenai alamat dan lokasi kantor cabang dapat dilihat melalui halaman profil perusahaan atau bagian kontak pada website.'],
        ['category' => 'lowongan', 'question' => 'Apakah PT Equityworld Futures membuka lowongan kerja?', 'answer' => 'Informasi lowongan kerja dapat dilihat melalui halaman Karier. Posisi yang tersedia, deskripsi pekerjaan, kualifikasi, dan persyaratan ditampilkan sesuai informasi lowongan yang dipublikasikan.'],
        ['category' => 'lowongan', 'question' => 'Bagaimana cara melamar pekerjaan di PT Equityworld Futures?', 'answer' => 'Calon staff dapat memilih posisi yang tersedia pada halaman Karier, membaca persyaratan, kemudian mengisi formulir pendaftaran online dan mengunggah dokumen yang diminta.'],
        ['category' => 'lowongan', 'question' => 'Apa saja dokumen yang perlu disiapkan untuk melamar pekerjaan?', 'answer' => 'Dokumen yang perlu disiapkan menyesuaikan persyaratan setiap posisi. Secara umum, calon staff dapat diminta menyiapkan CV, fotokopi Kartu Keluarga (KK), portofolio, dan dokumen pendukung lainnya.'],
        ['category' => 'lowongan', 'question' => 'Apakah saya dapat melamar lebih dari satu posisi?', 'answer' => 'Tidak. Setiap pelamar hanya diperbolehkan memilih dan mengajukan lamaran untuk satu posisi. Pastikan posisi yang dipilih sesuai dengan minat, kemampuan, dan kualifikasi yang dimiliki.'],
        ['category' => 'magang', 'question' => 'Siapa saja yang dapat mengikuti program Galeri Magang?', 'answer' => 'Program Galeri Magang ditujukan bagi mahasiswa perguruan tinggi serta siswa SMA/SMK yang ingin mendapatkan pengalaman dan pembelajaran secara langsung di lingkungan kerja profesional.'],
        ['category' => 'magang', 'question' => 'Apa perbedaan program magang dan PKL?', 'answer' => 'Program magang umumnya ditujukan bagi mahasiswa perguruan tinggi, sedangkan PKL (Praktik Kerja Lapangan) ditujukan bagi siswa SMA/SMK. Keduanya memberikan kesempatan untuk memperoleh pengalaman kerja sesuai kebutuhan dan ketentuan masing-masing program.'],
        ['category' => 'magang', 'question' => 'Apakah peserta dapat memilih bidang magang sesuai jurusan?', 'answer' => 'Ya. Peserta dapat memilih bidang yang sesuai dengan minat dan latar belakang pendidikannya. Namun, penerimaan tetap mempertimbangkan kualifikasi dan kebutuhan perusahaan.'],
        ['category' => 'magang', 'question' => 'Berapa lama durasi program magang dan PKL?', 'answer' => 'Durasi program mengikuti ketentuan perusahaan serta kebutuhan peserta dan institusi pendidikan. Informasi durasi dapat dilihat pada detail program yang tersedia.'],
        ['category' => 'magang', 'question' => 'Bagaimana sistem pelaksanaan program magang dan PKL?', 'answer' => 'Pelaksanaan program mengikuti ketentuan perusahaan. Peserta akan memperoleh pengalaman melalui kegiatan dan tugas yang berkaitan dengan bidang penempatan serta mendapatkan arahan selama mengikuti program.'],
        ['category' => 'magang', 'question' => 'Apakah peserta mendapatkan pembimbing selama program berlangsung?', 'answer' => 'Peserta mendapatkan arahan dan pendampingan dari pihak pembimbing atau tim terkait untuk membantu proses pembelajaran dan pengembangan keterampilan selama program berlangsung.'],
        ['category' => 'seleksi', 'question' => 'Bagaimana cara mendaftar program magang atau PKL?', 'answer' => 'Calon peserta dapat membuka halaman Karier atau Program Magang, memilih program yang sesuai, membaca persyaratan, kemudian mengisi formulir pendaftaran dan mengunggah dokumen yang dibutuhkan.'],
        ['category' => 'seleksi', 'question' => 'Apa saja dokumen yang diperlukan untuk mendaftar magang atau PKL?', 'answer' => 'Dokumen yang diperlukan dapat berbeda sesuai program. Secara umum, peserta dapat diminta menyiapkan CV, identitas diri, informasi pendidikan, surat pengantar dari kampus atau sekolah apabila dipersyaratkan, serta dokumen pendukung lainnya.'],
        ['category' => 'seleksi', 'question' => 'Bagaimana tahapan seleksi peserta magang dan PKL?', 'answer' => 'Secara umum, proses seleksi terdiri dari beberapa tahapan:', 'list_type' => 'ol', 'list' => ['Pemeriksaan berkas dan dokumen.', 'Seleksi dan peninjauan oleh HRD.', 'Wawancara dengan tim rekrutmen.', 'Pengumuman hasil seleksi.']],
        ['category' => 'seleksi', 'question' => 'Bagaimana cara mengetahui status pendaftaran?', 'answer' => 'Peserta dapat masuk ke akun masing-masing melalui website, kemudian membuka halaman status pendaftaran untuk melihat perkembangan proses seleksi.'],
        ['category' => 'seleksi', 'question' => 'Apakah saya akan mendapatkan pemberitahuan hasil seleksi?', 'answer' => 'Informasi hasil seleksi dapat dilihat melalui akun peserta pada website. Pemberitahuan tambahan dapat diberikan sesuai mekanisme komunikasi yang diterapkan perusahaan.'],
        ['category' => 'seleksi', 'question' => 'Bagaimana jika terdapat kesalahan pada data pendaftaran?', 'answer' => 'Peserta dapat menghubungi pihak HRD atau kontak resmi perusahaan untuk mendapatkan arahan terkait perbaikan data pendaftaran.'],
        ['category' => 'fasilitas', 'question' => 'Apa saja manfaat yang diperoleh selama mengikuti program Galeri Magang?', 'answer' => 'Peserta berkesempatan memperoleh:', 'list' => ['Pengalaman mengenal lingkungan kerja profesional.', 'Pengembangan keterampilan sesuai bidang.', 'Bimbingan dan pendampingan.', 'Pengalaman bekerja sama dalam tim.', 'Pengembangan portofolio dan relasi profesional.']],
        ['category' => 'fasilitas', 'question' => 'Apakah peserta mendapatkan Letter of Acceptance (LOA)?', 'answer' => 'Peserta yang dinyatakan diterima dalam program magang atau PKL dapat memperoleh Letter of Acceptance (LOA) sebagai dokumen penerimaan sesuai ketentuan perusahaan.'],
        ['category' => 'fasilitas', 'question' => 'Apakah peserta mendapatkan sertifikat setelah menyelesaikan program?', 'answer' => 'Ya. Peserta yang telah menyelesaikan program sesuai ketentuan perusahaan dapat memperoleh sertifikat sebagai bukti telah mengikuti kegiatan magang atau PKL.'],
        ['category' => 'fasilitas', 'question' => 'Apakah kegiatan magang dapat dikonversi menjadi SKS?', 'answer' => 'Konversi kegiatan magang menjadi SKS bergantung pada kebijakan masing-masing perguruan tinggi dan kesesuaian program dengan ketentuan akademik yang berlaku.'],
        ['category' => 'kegiatan', 'question' => 'Di mana saya dapat melihat kegiatan Galeri Magang?', 'answer' => 'Informasi dan dokumentasi kegiatan peserta dapat dilihat melalui halaman Kegiatan pada website serta media sosial resmi Galeri Magang.'],
        ['category' => 'kegiatan', 'question' => 'Apa saja informasi yang tersedia pada halaman Kegiatan?', 'answer' => 'Halaman Kegiatan menampilkan dokumentasi aktivitas, pengalaman peserta, program pembelajaran, dan berbagai kegiatan yang dilaksanakan dalam lingkungan Galeri Magang.'],
    ];

    $faqCounts = array_fill_keys(array_column($faqCategories, 'id'), 0);
    foreach ($faqItems as $faqItem) {
        $faqCounts[$faqItem['category']]++;
    }
    $faqTotal = count($faqItems);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pusat Bantuan &amp; FAQ | PT Equityworld Futures</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.navigation-transitions')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <main class="faq-page">
        <section class="faq-hero">
            <div class="faq-wrap faq-hero-inner">
                <div class="faq-hero-copy" data-faq-reveal>
                    <span class="faq-eyebrow"><i aria-hidden="true"></i>PUSAT INFORMASI</span>
                    <h1>Pusat Bantuan <span>&amp; FAQ</span></h1>
                    <p>Temukan jawaban atas pertanyaan seputar PT Equityworld Futures, lowongan kerja, serta program Magang dan PKL di Galeri Magang.</p>
                </div>
                <div class="faq-visual" aria-hidden="true" data-faq-reveal>
                    <span class="faq-visual-orbit"></span>
                    <span class="faq-visual-mark">?</span>
                    <span class="faq-visual-note"><b>{{ $faqTotal }}</b> jawaban tersedia</span>
                </div>
            </div>
        </section>

        <section class="faq-content faq-wrap" aria-label="Pertanyaan yang sering diajukan">
            <div class="faq-toolbar" data-faq-reveal>
                <div class="faq-search-row">
                    <div class="faq-search">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>
                        <input id="faq-search" type="search" aria-label="Cari pertanyaan atau topik" placeholder="Cari pertanyaan atau topik..." autocomplete="off">
                        <button class="faq-clear" id="faq-clear" type="button" aria-label="Hapus pencarian" hidden>
                            <svg viewBox="0 0 24 24" width="17" height="17" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </button>
                    </div>
                    <button class="faq-expand" id="faq-expand" type="button" aria-pressed="false">Buka semua jawaban</button>
                </div>
                <p class="faq-filter-label">Jelajahi berdasarkan topik</p>
                <div class="faq-filters" role="group" aria-label="Filter kategori pertanyaan">
                    <button class="faq-filter is-active" type="button" data-filter="all" aria-pressed="true">
                        Semua topik <span class="faq-filter-count">{{ $faqTotal }}</span>
                    </button>
                    @foreach($faqCategories as $category)
                    <button class="faq-filter" type="button" data-filter="{{ $category['id'] }}" aria-pressed="false">
                        {{ $category['title'] }} <span class="faq-filter-count">{{ $faqCounts[$category['id']] }}</span>
                    </button>
                    @endforeach
                </div>
                <div class="faq-results" aria-live="polite">
                    <span id="faq-result-count">Menampilkan <strong>{{ $faqTotal }}</strong> dari {{ $faqTotal }} pertanyaan</span>
                    <span>Jawaban diperbarui sesuai informasi website</span>
                </div>
            </div>

            <div class="faq-groups" id="faq-groups">
                @foreach($faqCategories as $category)
                <section class="faq-group" data-group="{{ $category['id'] }}">
                    <div class="faq-group-heading" data-faq-reveal>
                        <span class="faq-group-icon" aria-hidden="true"><i class="bi {{ $category['icon'] }}"></i></span>
                        <div>
                            <h2>{{ $category['title'] }}</h2>
                            <p>Pilih pertanyaan untuk membaca jawabannya.</p>
                        </div>
                        <span class="faq-group-count">{{ $faqCounts[$category['id']] }} pertanyaan</span>
                    </div>
                    <div class="faq-list">
                        @foreach($faqItems as $index => $item)
                            @if($item['category'] === $category['id'])
                            <details class="faq-item" data-category="{{ $item['category'] }}" data-faq-reveal>
                                <summary class="faq-question" aria-controls="faq-answer-{{ $index + 1 }}">
                                    <span class="faq-number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="faq-question-text">{{ $item['question'] }}</span>
                                    <span class="faq-chevron" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                                    </span>
                                </summary>
                                <div class="faq-answer" id="faq-answer-{{ $index + 1 }}">
                                    <div class="faq-answer-inner">
                                        <p>{{ $item['answer'] }}</p>
                                        @if(!empty($item['list']))
                                            @if(($item['list_type'] ?? 'ul') === 'ol')
                                            <ol>
                                            @else
                                            <ul>
                                            @endif
                                                @foreach($item['list'] as $listItem)
                                                <li>{{ $listItem }}</li>
                                                @endforeach
                                            @if(($item['list_type'] ?? 'ul') === 'ol')
                                            </ol>
                                            @else
                                            </ul>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </details>
                            @endif
                        @endforeach
                    </div>
                </section>
                @endforeach
            </div>

            <div class="faq-empty" id="faq-empty" hidden>
                <span class="faq-empty-icon" aria-hidden="true"><i class="bi bi-search"></i></span>
                <h2>Pertanyaan belum ditemukan</h2>
                <p>Coba kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>

            <section class="faq-contact" data-faq-reveal>
                <div>
                    <h2>Masih ada yang ingin ditanyakan?</h2>
                    <p>Kunjungi halaman Karier untuk melihat informasi posisi dan program yang tersedia.</p>
                </div>
                <a class="faq-hero-link" href="{{ route('karir') }}">Kunjungi halaman Karier</a>
            </section>
        </section>
    </main>

    @include('partials.footer')

    <script>
        (function(){
            const search=document.getElementById('faq-search');
            const clear=document.getElementById('faq-clear');
            const filters=[...document.querySelectorAll('.faq-filter')];
            const items=[...document.querySelectorAll('.faq-item')];
            const groups=[...document.querySelectorAll('.faq-group')];
            const resultCount=document.getElementById('faq-result-count');
            const empty=document.getElementById('faq-empty');
            const expand=document.getElementById('faq-expand');
            let activeCategory='all';

            function visibleItems(){
                return items.filter(item=>!item.hidden);
            }

            function updateExpandLabel(){
                const visible=visibleItems();
                const allOpen=visible.length>0&&visible.every(item=>item.open);
                expand.textContent=allOpen?'Tutup semua jawaban':'Buka semua jawaban';
                expand.setAttribute('aria-pressed',String(allOpen));
                expand.disabled=visible.length===0;
            }

            function filterFaq(){
                const query=search.value.trim().toLocaleLowerCase('id-ID');
                let visibleCount=0;
                items.forEach(item=>{
                    const categoryMatches=activeCategory==='all'||item.dataset.category===activeCategory;
                    const searchMatches=!query||item.textContent.toLocaleLowerCase('id-ID').includes(query);
                    item.hidden=!(categoryMatches&&searchMatches);
                    if(item.hidden) item.open=false;
                    else visibleCount++;
                });
                groups.forEach(group=>{
                    group.hidden=group.querySelectorAll('.faq-item:not([hidden])').length===0;
                });
                filters.forEach(button=>{
                    const selected=button.dataset.filter===activeCategory;
                    button.classList.toggle('is-active',selected);
                    button.setAttribute('aria-pressed',String(selected));
                });
                resultCount.innerHTML='Menampilkan <strong>'+visibleCount+'</strong> dari '+items.length+' pertanyaan';
                empty.hidden=visibleCount!==0;
                clear.hidden=search.value.length===0;
                updateExpandLabel();
            }

            filters.forEach(button=>button.addEventListener('click',()=>{
                activeCategory=button.dataset.filter;
                filterFaq();
            }));
            search.addEventListener('input',filterFaq);
            clear.addEventListener('click',()=>{
                search.value='';
                search.focus();
                filterFaq();
            });
            expand.addEventListener('click',()=>{
                const visible=visibleItems();
                const shouldOpen=visible.some(item=>!item.open);
                visible.forEach(item=>{item.open=shouldOpen});
                updateExpandLabel();
            });
            items.forEach(item=>item.addEventListener('toggle',updateExpandLabel));
            filterFaq();
        })();

        const faqRevealTargets=document.querySelectorAll('.faq-page [data-faq-reveal]');
        if('IntersectionObserver' in window&&!window.matchMedia('(prefers-reduced-motion: reduce)').matches){
            document.documentElement.classList.add('faq-motion-ready');
            const faqRevealObserver=new IntersectionObserver(entries=>{
                entries.forEach(entry=>{
                    if(entry.isIntersecting){
                        entry.target.classList.add('is-visible');
                        faqRevealObserver.unobserve(entry.target);
                    }
                });
            },{threshold:.12,rootMargin:'0px 0px -32px 0px'});
            faqRevealTargets.forEach(target=>faqRevealObserver.observe(target));
        }
    </script>
</body>
</html>