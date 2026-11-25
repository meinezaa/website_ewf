<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class HrdDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        config(['cache.default' => 'array']);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');
        Artisan::call('migrate', ['--force' => true]);
        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('activity');
            $table->string('ip_address', 45)->nullable();
        });
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('internship_registration_id')->unique();
            $table->string('certificate_number')->unique();
            $table->string('file_path');
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->date('issued_at')->nullable();
            $table->timestamps();
        });
        Auth::forgetGuards();
    }

    public function test_hrd_user_can_render_the_dashboard_with_live_sections(): void
    {
        $this->actingAs($this->createUser('HRD'));

        $response = $this->get(route('hrd.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('<span class="hrd-brand-galeri">GALERI</span> <span class="hrd-brand-magang">MAGANG</span>', false)
            ->assertSee('Pendaftaran Staf')
            ->assertSee('Pendaftaran Magang')
            ->assertSee('Data Staf')
            ->assertSee('Data Peserta Magang/PKL')
            ->assertSee('Sertifikat & LoA')
            ->assertSee('Total pendaftar staf')
            ->assertSee('Total pendaftar magang')
            ->assertSee('Menunggu pemeriksaan')
            ->assertSee('Sedang diproses')
            ->assertSee('Diterima')
            ->assertSee('Ditolak')
            ->assertSee('Berkas persyaratan')
            ->assertSee('Profil Saya')
            ->assertSee('id="hrdProfilePhoto"', false)
            ->assertSee('aria-label="Ganti foto profil"', false)
            ->assertSee('id="hrdCoverPhoto"', false)
            ->assertSee('Tambahkan foto header')
            ->assertDontSee('Ganti foto header')
            ->assertSee('hrdSidebarCollapse')
            ->assertSee('hrdSidebarArrow')
            ->assertSee('id="hrdBreadcrumbSection">Ruang Kerja</span>', false)
            ->assertSee('data-view="staff-data" data-label="Data Staf" data-section="Organisasi"', false)
            ->assertSee('data-open-view="profile"', false)
            ->assertSee('<button class="hrd-banner-link" type="button" data-open-view="staff-applicants">Lihat pendaftar</button>', false)
            ->assertSee('id="hrdProfileLogoutForm"', false)
            ->assertSee('form="hrdProfileLogoutForm">', false)
            ->assertSee('Keluar akun')
            ->assertDontSee('Sumber Daya Manusia')
            ->assertDontSee('Butuh bantuan?')
            ->assertDontSee('hr.support@ewf.co.id')
            ->assertSee('Pembaruan tahap seleksi')
            ->assertSee('Tambah Sertifikat atau LoA')
            ->assertSee('Simpan dokumen')
            ->assertSee('<button class="hrd-button hrd-button--primary" type="button" data-open-document-form>Tambah dokumen</button>', false)
            ->assertSee('Simpan perubahan')
            ->assertDontSee('bi-check2', false)
            ->assertSee('Keluar')
            ->assertSee('Tren pendaftar')
            ->assertSee('Belum ada pendaftar di database.')
            ->assertDontSee('Aditya Pratama')
            ->assertDontSee('data dummy');
        $this->assertStringNotContainsString('Galeri Magang <i class="bi bi-heart-fill"', $response->getContent());
    }

    public function test_hrd_dashboard_displays_current_registration_records_and_counts(): void
    {
        $this->actingAs($this->createUser('HRD'));
        StaffRegistration::query()->create([
            'full_name' => 'Nadia Kandidat Staf',
            'email' => 'nadia.staff@example.test',
            'phone' => '+628123456789',
            'position' => 'Staf Administrasi',
            'status' => 'pending',
        ]);
        InternshipRegistration::query()->create([
            'full_name' => 'Raka Kandidat Magang',
            'email' => 'raka.intern@example.test',
            'phone' => '+628123456780',
            'university' => 'Universitas Contoh',
            'status' => 'in_review',
        ]);

        $response = $this->get(route('hrd.dashboard'));

        $response->assertOk()
            ->assertSee('Nadia Kandidat Staf')
            ->assertSee('Raka Kandidat Magang')
            ->assertSee('Staf Administrasi')
            ->assertSee('Universitas Contoh')
            ->assertSee('Menunggu pemeriksaan');
        $response->assertSee('data-name="Nadia Kandidat Staf"', false)
            ->assertSee('data-name="Raka Kandidat Magang"', false);

        $filteredContent = $this->get(route('hrd.dashboard', [
            'view' => 'staff-applicants',
            'staff_q' => 'Nadia Kandidat',
            'staff_status' => 'pending',
        ]))->assertOk()->getContent();
        $staffSectionStart = strpos($filteredContent, 'id="view-staff-applicants"');
        $internshipSectionStart = strpos($filteredContent, 'id="view-intern-applicants"', $staffSectionStart);
        $this->assertNotFalse($staffSectionStart);
        $this->assertNotFalse($internshipSectionStart);
        $staffSection = substr($filteredContent, $staffSectionStart, $internshipSectionStart - $staffSectionStart);
        $this->assertStringContainsString('Nadia Kandidat Staf', $staffSection);
        $this->assertStringNotContainsString('Raka Kandidat Magang', $staffSection);
    }

    public function test_recent_applicants_show_profile_photo_or_initials(): void
    {
        $hrd = $this->createUser('HRD');
        $applicant = $this->createUser('Applicant');
        Storage::fake('public');
        $photoPath = 'profile-photos/'.$applicant->getKey().'/avatar.jpg';
        Storage::disk('public')->put($photoPath, 'profile photo');
        $applicant->update(['profile_photo_path' => $photoPath]);

        StaffRegistration::query()->create([
            'applicant_id' => $applicant->getKey(),
            'full_name' => 'Nadia Aulia',
            'email' => $applicant->email,
            'phone' => '+628123456789',
            'status' => 'accepted',
        ]);
        InternshipRegistration::query()->create([
            'applicant_id' => $applicant->getKey(),
            'full_name' => 'Nadia Aulia',
            'email' => $applicant->email,
            'phone' => '+628123456789',
            'registration_type' => 'Magang / Internship',
            'status' => 'accepted',
        ]);
        StaffRegistration::query()->create([
            'full_name' => 'Dewi Anonim',
            'email' => 'dewi.anonymous@example.test',
            'phone' => '+628123456780',
            'status' => 'pending',
        ]);

        $dashboard = $this->actingAs($hrd)
            ->get(route('hrd.dashboard'))
            ->assertOk()
            ->assertSee('<img src="/storage/'.$photoPath.'" alt="" loading="lazy" decoding="async">', false)
            ->assertSee('<span class="hrd-avatar hrd-avatar--blue">DA</span>', false);
        $this->assertGreaterThanOrEqual(6, substr_count($dashboard->getContent(), '<img src="/storage/'.$photoPath.'"'));

        $recentApplicant = collect($this->getJson(route('hrd.dashboard.snapshot'))->assertOk()->json('recent_applications'))
            ->firstWhere('name', 'Nadia Aulia');
        $this->assertSame('/storage/'.$photoPath, $recentApplicant['photo_url']);
    }

    public function test_dashboard_snapshot_separates_magang_pkl_and_refreshes_database_statuses(): void
    {
        $this->actingAs($this->createUser('HRD'));
        StaffRegistration::query()->create([
            'full_name' => 'Nadia Kandidat Staf',
            'email' => 'nadia.category@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
        ]);
        InternshipRegistration::query()->create([
            'full_name' => 'Maya Kandidat Magang',
            'email' => 'maya.magang@example.test',
            'phone' => '+628123456780',
            'university' => 'Universitas Contoh',
            'registration_type' => 'Magang / Internship',
            'status' => 'accepted',
        ]);
        $pkl = InternshipRegistration::query()->create([
            'full_name' => 'Raka Kandidat PKL',
            'email' => 'raka.pkl@example.test',
            'phone' => '+628123456781',
            'university' => 'SMK Contoh',
            'registration_type' => 'Praktik Kerja Lapangan (PKL)',
            'status' => 'pending',
        ]);
        InternshipRegistration::query()->create([
            'full_name' => 'Ayu Belum Terkategori',
            'email' => 'ayu.unknown@example.test',
            'phone' => '+628123456782',
            'status' => 'rejected',
        ]);

        $dashboard = $this->get(route('hrd.dashboard'));
        $dashboard->assertOk()
            ->assertSee('Total pendaftar magang')
            ->assertSee('Total pendaftar PKL')
            ->assertSee('Magang/PKL belum dikategorikan')
            ->assertSee('Raka Kandidat PKL');

        $snapshot = $this->getJson(route('hrd.dashboard.snapshot'));
        $snapshot->assertOk()
            ->assertJsonPath('stats.staff_total', 1)
            ->assertJsonPath('stats.magang_total', 1)
            ->assertJsonPath('stats.pkl_total', 1)
            ->assertJsonPath('stats.unclassified_internship_total', 1)
            ->assertJsonPath('stats.total', 4)
            ->assertJsonPath('stats.pending', 2)
            ->assertJsonPath('stats.accepted', 1)
            ->assertJsonPath('stats.rejected', 1)
            ->assertJsonPath('category_status_rows.1.label', 'Magang')
            ->assertJsonPath('category_status_rows.2.pending', 1)
            ->assertJsonPath('category_status_rows.3.rejected', 1);
        $this->assertSame('PKL', collect($snapshot->json('recent_applications'))
            ->firstWhere('name', 'Raka Kandidat PKL')['type_label']);
        $latestTrend = collect($snapshot->json('trend_data'))->last();
        $this->assertSame(1, $latestTrend['staff']);
        $this->assertSame(1, $latestTrend['magang']);
        $this->assertSame(1, $latestTrend['pkl']);
        $this->assertSame(1, $latestTrend['unclassified']);

        $pkl->update(['status' => 'accepted']);
        $this->getJson(route('hrd.dashboard.snapshot'))
            ->assertOk()
            ->assertJsonPath('stats.pending', 1)
            ->assertJsonPath('stats.accepted', 2)
            ->assertJsonPath('category_status_rows.2.accepted', 1);
    }

    public function test_hrd_can_save_application_status_and_reviewer_to_the_database(): void
    {
        $hrd = $this->createUser('HRD');
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Kandidat Staf',
            'email' => 'nadia.status@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
        ]);

        $this->actingAs($hrd)
            ->postJson(route('hrd.application.status', ['staff', $application->getKey()]), [
                'status' => 'in_review',
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'in_review',
                'label' => 'Dalam seleksi',
                'status_class' => 'badge-process',
            ]);

        $this->assertDatabaseHas('staff_registrations', [
            'id' => $application->getKey(),
            'status' => 'in_review',
            'reviewed_by' => $hrd->getKey(),
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $hrd->getKey(),
            'activity' => 'Status pendaftaran staff #'.$application->getKey().' diperbarui menjadi in_review.',
        ]);
        $this->assertDatabaseHas('career_application_tracking_events', [
            'application_type' => 'staff',
            'application_id' => $application->getKey(),
            'event_type' => 'status_changed',
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'actor_id' => $hrd->getKey(),
        ]);
    }

    public function test_hrd_can_schedule_interview_and_view_applicant_attendance_confirmation(): void
    {
        $hrd = $this->createUser('HRD');
        $applicant = $this->createUser('Applicant');
        $application = InternshipRegistration::query()->create([
            'applicant_id' => $applicant->getKey(),
            'full_name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => '+628123456789',
            'status' => 'in_review',
        ]);
        $interviewAt = now()->addDays(3)->timezone('Asia/Jakarta')->startOfMinute();

        $this->actingAs($hrd)
            ->postJson(route('hrd.application.interview', ['internship', $application->getKey()]), [
                'interview_at' => $interviewAt->format('Y-m-d\TH:i'),
                'interview_information' => 'Wawancara daring melalui tautan yang dikirim HR.',
            ])
            ->assertOk()
            ->assertJsonPath('interview_information', 'Wawancara daring melalui tautan yang dikirim HR.');

        $application->refresh();
        $this->assertSame($interviewAt->utc()->toDateTimeString(), $application->interview_at->toDateTimeString());
        $this->assertDatabaseHas('career_application_tracking_events', [
            'application_type' => 'internship',
            'application_id' => $application->getKey(),
            'event_type' => 'interview_scheduled',
            'interview_information' => 'Wawancara daring melalui tautan yang dikirim HR.',
            'actor_id' => $hrd->getKey(),
        ]);

        $this->actingAs($applicant)
            ->postJson(route('karir.interview.confirm', ['internship', $application->getKey()]), [
                'attendance' => 'hadir',
            ])
            ->assertOk()
            ->assertJsonPath('interview_attendance', 'hadir');

        $this->get(route('karir'))
            ->assertOk()
            ->assertSeeText('Seleksi Wawancara')
            ->assertSeeText('Wawancara daring melalui tautan yang dikirim HR.')
            ->assertSeeText('Konfirmasi Anda: Hadir')
            ->assertSee('class="rsvp-opt yes is-confirmed" data-confirm-attendance="hadir" aria-pressed="true"', false)
            ->assertSee('data-confirm-attendance="tidak_hadir" aria-pressed="false"', false);

        $this->getJson(route('karir.tracking.updates'))
            ->assertOk()
            ->assertJsonStructure(['csrf_token', 'applications'])
            ->assertJsonPath('applications.0.interview_attendance', 'hadir');

        $this->assertDatabaseHas('internship_registrations', [
            'id' => $application->getKey(),
            'interview_attendance' => 'hadir',
        ]);

        $this->actingAs($hrd)
            ->getJson(route('hrd.application.tracking', ['internship', $application->getKey()]))
            ->assertOk()
            ->assertJsonPath('interview_attendance', 'hadir')
            ->assertJsonPath('interview_information', 'Wawancara daring melalui tautan yang dikirim HR.')
            ->assertJsonPath('events.1.event_type', 'attendance_confirmed');

        $this->actingAs($applicant)
            ->postJson(route('karir.interview.confirm', ['internship', $application->getKey()]), [
                'attendance' => 'tidak_hadir',
            ])
            ->assertOk()
            ->assertJsonPath('interview_attendance', 'tidak_hadir');
        $this->assertDatabaseHas('internship_registrations', [
            'id' => $application->getKey(),
            'interview_attendance' => 'tidak_hadir',
        ]);
        $this->actingAs($hrd)
            ->getJson(route('hrd.application.tracking', ['internship', $application->getKey()]))
            ->assertOk()
            ->assertJsonPath('interview_attendance', 'tidak_hadir');

        $rescheduledAt = now()->addDays(4)->timezone('Asia/Jakarta')->startOfMinute();
        $this->postJson(route('hrd.application.interview', ['internship', $application->getKey()]), [
            'interview_at' => $rescheduledAt->format('Y-m-d\TH:i'),
            'interview_information' => 'Jadwal wawancara diperbarui HR.',
        ])->assertOk()
            ->assertJsonPath('interview_attendance', null);

        $this->assertDatabaseHas('internship_registrations', [
            'id' => $application->getKey(),
            'interview_attendance' => null,
            'interview_confirmed_at' => null,
        ]);
    }

    public function test_applicant_cannot_confirm_another_users_interview(): void
    {
        $owner = $this->createUser('Applicant');
        $otherApplicant = User::query()->create([
            'name' => 'Pelamar Lain',
            'email' => 'another-applicant@example.test',
            'password' => 'Password123',
            'status' => 'active',
        ]);
        $application = StaffRegistration::query()->create([
            'applicant_id' => $owner->getKey(),
            'full_name' => $owner->name,
            'email' => $owner->email,
            'phone' => '+628123456789',
            'status' => 'in_review',
            'interview_at' => now()->addDay(),
        ]);

        $this->actingAs($otherApplicant)
            ->postJson(route('karir.interview.confirm', ['staff', $application->getKey()]), [
                'attendance' => 'tidak_hadir',
            ])
            ->assertNotFound();

        $this->assertDatabaseHas('staff_registrations', [
            'id' => $application->getKey(),
            'interview_attendance' => null,
        ]);
    }

    public function test_hrd_cannot_schedule_an_interview_in_the_past_in_wib(): void
    {
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Kandidat Staf',
            'email' => 'nadia.past-interview@example.test',
            'phone' => '+628123456789',
            'status' => 'in_review',
        ]);
        $pastInterview = now()->timezone('Asia/Jakarta')->subMinute();

        $this->actingAs($this->createUser('HRD'))
            ->postJson(route('hrd.application.interview', ['staff', $application->getKey()]), [
                'interview_at' => $pastInterview->format('Y-m-d\TH:i'),
                'interview_information' => 'Jadwal yang sudah lewat.',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('interview_at');

        $this->assertDatabaseHas('staff_registrations', [
            'id' => $application->getKey(),
            'interview_at' => null,
        ]);
    }

    public function test_hrd_cannot_set_a_status_outside_the_registration_flow(): void
    {
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Kandidat Staf',
            'email' => 'nadia.invalid-status@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
        ]);

        $this->actingAs($this->createUser('HRD'))
            ->postJson(route('hrd.application.status', ['staff', $application->getKey()]), [
                'status' => 'completed',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');

        $this->assertDatabaseHas('staff_registrations', [
            'id' => $application->getKey(),
            'status' => 'pending',
        ]);

        $internship = InternshipRegistration::query()->create([
            'full_name' => 'Peserta Belum Diterima',
            'email' => 'peserta.pending@example.test',
            'phone' => '+628123456780',
            'status' => 'pending',
        ]);
        $this->postJson(route('hrd.application.status', ['internship', $internship->getKey()]), [
            'status' => 'completed',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('status');
        $this->assertDatabaseHas('internship_registrations', [
            'id' => $internship->getKey(),
            'status' => 'pending',
        ]);
    }

    public function test_non_hrd_cannot_change_an_application_status(): void
    {
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Kandidat Staf',
            'email' => 'nadia.forbidden@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
        ]);

        $this->actingAs($this->createUser('Applicant'))
            ->postJson(route('hrd.application.status', ['staff', $application->getKey()]), [
                'status' => 'accepted',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('staff_registrations', [
            'id' => $application->getKey(),
            'status' => 'pending',
        ]);
    }

    public function test_hrd_can_download_a_csv_of_database_applications(): void
    {
        StaffRegistration::query()->create([
            'full_name' => 'Nadia Export',
            'email' => 'nadia.export@example.test',
            'phone' => '+628123456789',
            'position' => 'Staf Administrasi',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->createUser('HRD'))
            ->get(route('hrd.export', ['section' => 'staff-applicants']));

        $response->assertOk()
            ->assertDownload()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Nadia Export', $response->streamedContent());
    }

    public function test_hrd_can_download_a_private_applicant_cv(): void
    {
        Storage::fake('private');
        $path = 'career-applications/EWF-APP-TEST000002/cv/cv.pdf';
        Storage::disk('private')->put($path, '%PDF-1.4');
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Berkas',
            'email' => 'nadia.berkas@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
            'reference' => 'EWF-APP-TEST000002',
            'cv_path' => $path,
        ]);

        $this->actingAs($this->createUser('HRD'))
            ->get(route('hrd.application.document.download', [
                'type' => 'staff',
                'application' => $application->getKey(),
                'document' => 'cv',
            ]))
            ->assertOk()
            ->assertDownload('cv.pdf');
    }

    public function test_hrd_can_preview_private_pdf_application_documents(): void
    {
        Storage::fake('private');
        $path = 'career-applications/EWF-APP-TEST000003/cv/cv.pdf';
        $content = "%PDF-1.4\nPreview test\n";
        Storage::disk('private')->put($path, $content);
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Pratinjau',
            'email' => 'nadia.preview@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
            'reference' => 'EWF-APP-TEST000003',
            'cv_path' => $path,
        ]);
        $url = route('hrd.application.document.preview', [
            'type' => 'staff',
            'application' => $application->getKey(),
            'document' => 'cv',
        ]);

        $response = $this->actingAs($this->createUser('HRD'))->get($url);

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('x-content-type-options', 'nosniff')
            ->assertHeader('cache-control', 'no-store, private');
        $this->assertStringStartsWith('inline;', $response->headers->get('content-disposition'));
        $this->assertSame($content, $response->streamedContent());
        $this->get(route('hrd.dashboard'))
            ->assertOk()
            ->assertSee('preview_url', false)
            ->assertSee('preview_mime_type', false);

        $this->actingAs($this->createUser('Applicant'))
            ->get($url)
            ->assertForbidden();
    }

    public function test_hrd_preview_does_not_render_unsupported_document_types_inline(): void
    {
        Storage::fake('private');
        $path = 'career-applications/EWF-APP-TEST000004/cv/cv.docx';
        Storage::disk('private')->put($path, 'not a browser-previewable document');
        $application = StaffRegistration::query()->create([
            'full_name' => 'Nadia Dokumen',
            'email' => 'nadia.docx@example.test',
            'phone' => '+628123456789',
            'status' => 'pending',
            'reference' => 'EWF-APP-TEST000004',
            'cv_path' => $path,
        ]);

        $this->actingAs($this->createUser('HRD'))
            ->get(route('hrd.application.document.preview', [
                'type' => 'staff',
                'application' => $application->getKey(),
                'document' => 'cv',
            ]))
            ->assertStatus(415);
    }

    public function test_hrd_can_upload_and_download_a_private_internship_certificate(): void
    {
        Storage::fake('private');
        $hrd = $this->createUser('HRD');
        $application = InternshipRegistration::query()->create([
            'full_name' => 'Peserta Magang Selesai',
            'email' => 'peserta.selesai@example.test',
            'phone' => '+628123456789',
            'status' => 'completed',
            'end_date' => today()->subDay(),
            'reference' => 'EWF-APP-TEST000001',
        ]);

        $this->actingAs($hrd)
            ->post(route('hrd.documents.store'), [
                'internship_registration_id' => $application->getKey(),
                'document_type' => 'certificate',
                'file' => UploadedFile::fake()->create('certificate.pdf', 32, 'application/pdf'),
            ])
            ->assertRedirect(route('hrd.dashboard').'#documents')
            ->assertSessionHas('hrd_document_saved', true);

        $savedApplication = $application->fresh();
        $this->assertNotNull($savedApplication->certificate_path);
        Storage::disk('private')->assertExists($savedApplication->certificate_path);
        $this->assertDatabaseHas('certificates', [
            'internship_registration_id' => $application->getKey(),
            'file_path' => $savedApplication->certificate_path,
            'issued_by' => $hrd->getKey(),
        ]);

        $this->get(route('hrd.issued-document.download', [$application->getKey(), 'certificate']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->post(route('hrd.documents.store'), [
            'internship_registration_id' => $application->getKey(),
            'document_type' => 'loa',
            'file' => UploadedFile::fake()->create('loa.pdf', 24, 'application/pdf'),
        ])->assertRedirect(route('hrd.dashboard').'#documents');

        $savedApplication = $application->fresh();
        $this->assertNotNull($savedApplication->loa_path);
        Storage::disk('private')->assertExists($savedApplication->certificate_path);
        Storage::disk('private')->assertExists($savedApplication->loa_path);
        $this->get(route('hrd.issued-document.download', [$application->getKey(), 'loa']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_hrd_dashboard_requires_authentication(): void
    {
        $this->get(route('hrd.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_applicant_cannot_open_the_hrd_dashboard(): void
    {
        $this->actingAs($this->createUser('Applicant'));

        $this->get(route('hrd.dashboard'))->assertForbidden();
    }

    public function test_hrd_can_visit_the_public_homepage_without_being_redirected(): void
    {
        $this->actingAs($this->createUser('HRD'));

        $this->get(route('home'))
            ->assertOk();
    }

    public function test_hrd_profile_link_stays_on_the_regular_account_profile(): void
    {
        $this->actingAs($this->createUser('HRD'));

        $this->get(route('profile'))
            ->assertOk()
            ->assertSeeText('Informasi akun')
            ->assertDontSee('Dashboard HRD');
    }

    public function test_hrd_logout_redirects_to_the_hrd_login_page(): void
    {
        $this->actingAs($this->createUser('HRD'))
            ->post(route('logout'))
            ->assertRedirect(route('hrd.login'));

        $this->assertGuest();
    }

    public function test_hrd_can_save_profile_changes_to_the_server(): void
    {
        $user = $this->createUser('HRD');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nadia Aulia HRD',
            'email' => $user->email,
            'phone' => '+628123456789',
            'department' => 'Human Resources',
        ])->assertRedirect(route('hrd.dashboard'))
            ->assertSessionHas('profile_updated', true);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'name' => 'Nadia Aulia HRD',
            'email' => $user->email,
            'phone' => '+628123456789',
            'department' => 'Human Resources',
        ]);
    }

    public function test_hrd_can_upload_a_profile_photo_and_see_it_across_the_dashboard(): void
    {
        Storage::fake('public');
        $user = $this->createUser('HRD');
        $this->actingAs($user)
            ->postJson(route('profile.photo.update'), [
                'profile_photo' => UploadedFile::fake()->createWithContent(
                    'hrd-profile.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a0c0AAAAASUVORK5CYII=', true)
                ),
            ])
            ->assertOk()
            ->assertJsonStructure(['photo_url']);

        $user = $user->fresh();
        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
        $photoUrl = $user->fresh()->profilePhotoUrl();

        $response = $this
            ->get(route('hrd.dashboard'))
            ->assertOk()
            ->assertSee('<img src="'.$photoUrl.'" alt="">', false);

        $this->assertSame(3, substr_count($response->getContent(), '<img src="'.$photoUrl.'" alt="">'));
    }

    public function test_hrd_can_upload_and_replace_a_persistent_profile_cover_photo(): void
    {
        Storage::fake('public');
        $user = $this->createUser('HRD');
        $image = static fn (string $name): UploadedFile => UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a0c0AAAAASUVORK5CYII=', true)
        );

        $this->actingAs($user)->postJson(route('profile.cover-photo.update'), [
            'cover_photo' => $image('first-cover.png'),
        ])->assertOk()
            ->assertJsonStructure(['cover_url']);

        $firstCoverPath = $user->fresh()->profile_cover_path;
        $this->assertNotNull($firstCoverPath);
        Storage::disk('public')->assertExists($firstCoverPath);

        $this->postJson(route('profile.cover-photo.update'), [
            'cover_photo' => $image('second-cover.png'),
        ])->assertOk()
            ->assertJsonStructure(['cover_url']);

        $savedUser = $user->fresh();
        $this->assertNotSame($firstCoverPath, $savedUser->profile_cover_path);
        Storage::disk('public')->assertMissing($firstCoverPath);
        Storage::disk('public')->assertExists($savedUser->profile_cover_path);
        $coverUrl = $savedUser->profileCoverUrl();
        $this->assertStringStartsWith('/storage/', $coverUrl);

        $this->get(route('hrd.dashboard'))
            ->assertOk()
            ->assertSee('<img class="hrd-profile-cover-image" src="'.$coverUrl.'" alt="">', false)
            ->assertSee('Edit header')
            ->assertDontSee('Ganti foto header')
            ->assertDontSee('class="hrd-cover-photo-button"', false);
    }

    private function createUser(string $roleName): User
    {
        $role = Role::query()->create(['role_name' => $roleName]);

        return User::query()->create([
            'role_id' => $role->getKey(),
            'name' => 'Nadia Aulia',
            'email' => strtolower($roleName).'@example.test',
            'password' => Hash::make('Password123'),
            'status' => 'active',
        ]);
    }
}
