<?php

namespace Tests\Feature;

use App\Http\Controllers\CareerApplicationController;
use App\Models\InternshipRegistration;
use App\Models\Role;
use App\Models\StaffRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CareerApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_career_applications_table_is_not_present_after_migrations(): void
    {
        $this->assertFalse(Schema::hasTable('career_applications'));
        $this->assertTrue(Schema::hasTable('staff_registrations'));
        $this->assertTrue(Schema::hasTable('internship_registrations'));
    }

    public function test_hrd_approval_updates_career_tracking_and_enables_loa_download(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $hrd = $this->hrd();
        $application = InternshipRegistration::create([
            'applicant_id' => $applicant->id,
            'full_name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => '+628123456789',
            'status' => 'pending',
            'reference' => 'INT-APPROVAL-001',
            'job_id' => 'internship-0',
            'job_title' => 'Magang Administrasi',
            'company' => 'PT Equityworld Futures',
        ]);

        $this->actingAs($hrd)
            ->postJson(route('hrd.application.status', ['internship', $application->getKey()]), [
                'status' => 'in_review',
            ])
            ->assertOk();

        $this->actingAs($applicant)
            ->get(route('karir'))
            ->assertOk()
            ->assertSeeText('Sedang ditinjau HRD')
            ->assertSeeText('Seleksi Wawancara')
            ->assertSeeText('HRD sedang meninjau lamaran dan akan menginformasikan jadwal wawancara bila diperlukan.');

        $this->actingAs($hrd)
            ->postJson(route('hrd.application.status', ['internship', $application->getKey()]), [
                'status' => 'accepted',
            ])
            ->assertOk();

        $this->actingAs($applicant)
            ->get(route('karir'))
            ->assertOk()
            ->assertSeeText('Disetujui HRD')
            ->assertSeeText('Lamaran disetujui HRD')
            ->assertSeeText('Sertifikat tersedia setelah program magang atau PKL selesai.')
            ->assertDontSee(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'loa',
            ]), false);

        $this->actingAs($hrd)
            ->from(route('hrd.dashboard'))
            ->post(route('hrd.documents.store'), [
                'internship_registration_id' => $application->getKey(),
                'document_type' => 'certificate',
                'file' => UploadedFile::fake()->create('certificate.pdf', 32, 'application/pdf'),
            ])
            ->assertRedirect(route('hrd.dashboard').'#documents')
            ->assertSessionHasErrors('document_type');

        $this->post(route('hrd.documents.store'), [
            'internship_registration_id' => $application->getKey(),
            'document_type' => 'loa',
            'file' => UploadedFile::fake()->create('loa.pdf', 24, 'application/pdf'),
        ])->assertRedirect(route('hrd.dashboard').'#documents')
            ->assertSessionHas('hrd_document_saved', true);

        $application->refresh();
        $this->assertSame('accepted', $application->status);
        Storage::disk('private')->assertExists($application->loa_path);

        $this->actingAs($applicant)
            ->get(route('karir'))
            ->assertSee(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'loa',
            ]), false);

        $this->get(route('karir.application.document.download', [
            'applicationId' => $application->getKey(),
            'document' => 'loa',
        ]))->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    public function test_internship_programs_match_the_requested_majors_and_fields(): void
    {
        $programs = CareerApplicationController::internshipPrograms();

        $this->assertSame([
            'Sistem Informasi' => ['Business Analyst', 'System Analyst', 'Fullstack Developer', 'UI/UX Designer'],
            'Administrasi Bisnis' => ['Admin & Operasional', 'HR Support', 'Business Development'],
            'Manajemen' => ['Manajemen Proyek', 'HRD', 'Operasional'],
            'Hubungan Internasional' => ['Hubungan Masyarakat', 'Riset Kebijakan', 'Kerja Sama Lembaga'],
            'Ilmu Komunikasi' => ['Public Relations', 'Social Media', 'Content Creator'],
            'Bisnis Digital' => ['Digital Marketing', 'E-Commerce', 'Growth Analyst'],
            'Informatika' => ['Backend Developer', 'Frontend Developer', 'Data Analyst'],
            'Akuntansi' => ['Staff Akuntansi', 'Pajak', 'Audit Internal'],
            'Pemasaran' => ['Marketing Communication', 'Sales & Partnership', 'Brand Strategy'],
        ], array_map(fn (array $fields) => array_column($fields, 'label'), $programs));

        $internationalRelationsFields = collect($programs['Hubungan Internasional']);
        $this->assertSame(
            'internship-0',
            $internationalRelationsFields->firstWhere('label', 'Riset Kebijakan')['job_id']
        );
        $this->assertFalse($internationalRelationsFields->contains('job_id', 'internship-2'));
    }

    public function test_career_job_applicant_counts_come_from_registration_tables(): void
    {
        StaffRegistration::create([
            'full_name' => 'Staff One',
            'email' => 'staff-one@example.test',
            'phone' => '+628123456789',
            'job_id' => 'professional-0',
        ]);
        StaffRegistration::create([
            'full_name' => 'Staff Two',
            'email' => 'staff-two@example.test',
            'phone' => '+628123456780',
            'job_id' => 'professional-0',
            'status' => 'rejected',
        ]);
        InternshipRegistration::create([
            'full_name' => 'Intern One',
            'email' => 'intern-one@example.test',
            'phone' => '+628123456781',
            'job_id' => 'internship-2',
        ]);

        $this->get(route('karir'))
            ->assertOk()
            ->assertSee('data-career-applicants="2"', false)
            ->assertSee('data-career-applicants="1"', false);

        $this->get(route('karir.applicant-counts'))
            ->assertOk()
            ->assertJsonPath('counts.professional-0', 2)
            ->assertJsonPath('counts.professional-1', 0)
            ->assertJsonPath('counts.internship-2', 1);
    }

    public function test_transition_completes_only_accepted_internships_with_a_past_end_date(): void
    {
        $applicant = $this->applicant();
        $pendingApplication = InternshipRegistration::create([
            'applicant_id' => $applicant->id,
            'full_name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => '+628123456789',
            'status' => 'pending',
            'end_date' => today()->subDay(),
            'reference' => 'INT-TRANSITION-001',
            'job_id' => 'internship-0',
            'job_title' => 'Magang Administrasi',
            'company' => 'PT Equityworld Futures',
        ]);
        $finishedApplication = InternshipRegistration::create([
            'full_name' => 'Peserta Selesai',
            'email' => 'finished@example.test',
            'phone' => '+628123456780',
            'status' => 'accepted',
            'end_date' => today()->subDay(),
            'reference' => 'INT-TRANSITION-002',
            'job_id' => 'internship-0',
            'job_title' => 'Magang Administrasi',
            'company' => 'PT Equityworld Futures',
        ]);

        $migration = require database_path('migrations/2026_11_18_000000_complete_existing_internship_registrations.php');
        $migration->up();

        $this->assertDatabaseHas('internship_registrations', [
            'id' => $pendingApplication->getKey(),
            'status' => 'pending',
        ]);
        $this->assertFalse($pendingApplication->fresh()->isProgramCompleted());
        $this->assertDatabaseHas('internship_registrations', [
            'id' => $finishedApplication->getKey(),
            'status' => 'completed',
        ]);
    }

    public function test_applicant_can_submit_a_valid_career_application_with_private_documents(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $this->actingAs($applicant);

        $response = $this->post(route('karir.apply.store'), [
            'job_id' => 'professional-0',
            'full_name' => 'Indira Prastika',
            'email' => 'spoofed@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'portfolio_url' => 'https://example.test/portfolio',
            'experiences' => [['company' => 'Contoh Co', 'position' => 'Analis', 'description' => 'Menyusun laporan.']],
            'education' => [['institution' => 'Universitas Contoh', 'major' => 'Akuntansi', 'year' => '2024']],
            'skills' => 'Excel, Analisis',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'documents' => [
                'portfolio' => UploadedFile::fake()->create('portfolio.pdf', 100, 'application/pdf'),
                'diploma' => UploadedFile::fake()->create('ijazah.pdf', 100, 'application/pdf'),
                'ktp' => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
            ],
            'privacy_consent' => '1',
        ]);

        $application = StaffRegistration::firstOrFail();

        $response->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true)
            ->assertSessionHas('application_reference', $application->reference);
        $this->assertSame('OB', $application->job_title);
        $this->assertSame('PT Equityworld Futures', $application->company);
        $this->assertSame('2002-05-10', $application->date_of_birth->toDateString());
        $this->assertSame(['Excel', 'Analisis'], $application->skills);
        $this->assertSame('indira@example.test', $application->email);
        $this->assertSame($applicant->id, $application->applicant_id);
        $this->assertNotNull($application->privacy_consent_at);
        $this->assertSame('', $application->headline);
        $this->assertSame('', $application->summary);
        $this->assertDatabaseHas('career_application_tracking_events', [
            'application_type' => 'staff',
            'application_id' => $application->getKey(),
            'event_type' => 'application_submitted',
            'to_status' => 'pending',
        ]);
        Storage::disk('private')->assertExists($application->cv_path);
        Storage::disk('private')->assertExists($application->documents['ktp']);
        Storage::disk('private')->assertExists($application->documents['diploma']);

        $this->get(route('karir'))
            ->assertSeeText('Status pelacakan lamaran')
            ->assertSeeText($application->job_title)
            ->assertSeeText('Pelamar: Indira Prastika')
            ->assertSeeText('Lamaran dikirim')
            ->assertSeeText('Verifikasi berkas')
            ->assertSeeText('Seleksi Wawancara')
            ->assertSeeText('Hasil akhir')
            ->assertSeeText('Perusahaan Terverifikasi')
            ->assertSeeText('Tanggal pengiriman')
            ->assertSeeText($application->created_at->timezone('Asia/Jakarta')->format('d/m/Y, H:i').' WIB')
            ->assertSeeText('Menunggu pembaruan HRD')
            ->assertSeeText('Menunggu verifikasi')
            ->assertSee('id="trackingSearchForm"', false)
            ->assertSee('class="career-heading tracking-heading"', false)
            ->assertDontSee('Kawan Pajak')
            ->assertSeeText('Unduh Berkas PDF')
            ->assertSeeText('Kalender')
            ->assertSeeText('WhatsApp Helpdesk HR')
            ->assertDontSeeText('Referensi:')
            ->assertDontSeeText($application->reference)
            ->assertDontSeeText('Tambahkan wawancara ke kalender')
            ->assertSeeText('Jadwal seleksi berikutnya akan ditampilkan setelah diumumkan.')
            ->assertDontSeeText('Daftar Lowongan & Program Magang')
            ->assertDontSee('id="careerApplicationForm"', false);
    }

    public function test_application_uses_selected_job_id_when_hidden_input_is_missing(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();

        $response = $this->actingAs($applicant)->post(route('karir.apply.store').'?selected_job_id=professional-1', [
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ]);

        $response->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true);
        $this->assertDatabaseHas('staff_registrations', [
            'email' => 'indira@example.test',
            'job_id' => 'professional-1',
            'job_title' => 'Resepsionis',
        ]);
    }

    public function test_application_uses_job_id_from_submission_path_when_form_field_is_missing(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();

        $response = $this->actingAs($applicant)->post(route('karir.apply.store', [
            'selectedJobId' => 'professional-0',
        ]), [
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ]);

        $response->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true);
        $this->assertDatabaseHas('staff_registrations', [
            'email' => 'indira@example.test',
            'job_id' => 'professional-0',
            'job_title' => 'OB',
        ]);
    }

    public function test_applicant_can_only_submit_one_application_across_job_types(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $this->actingAs($applicant)->post(route('karir.apply.store'), [
            'job_id' => 'professional-0',
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ])->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true);

        $this->post(route('karir.apply.store'), [
            'job_id' => 'internship-2',
        ])->assertRedirect(route('karir'))
            ->assertSessionHas('application_already_submitted', true);

        $this->assertDatabaseCount('staff_registrations', 1);
        $this->assertDatabaseCount('internship_registrations', 0);
        $this->assertDatabaseHas('staff_registrations', [
            'email' => 'indira@example.test',
            'job_id' => 'professional-0',
        ]);
    }

    public function test_applicant_can_submit_a_complete_sample_internship_application(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $this->actingAs($applicant);

        $response = $this->post(route('karir.apply.store'), [
            'job_id' => 'internship-2',
            'full_name' => 'Indira Prastika',
            'email' => 'spoofed@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'gender' => 'perempuan',
            'portfolio_url' => 'https://example.test/portfolio',
            'education' => [[
                'level' => 'S1',
                'institution' => 'Universitas Contoh',
                'major' => 'Akuntansi',
                'status' => 'Sedang berlangsung',
                'start_year' => '2022',
                'completion_year' => '2026',
                'gpa' => '3.75',
            ]],
            'registration_type' => 'Magang / Internship',
            'internship_major' => 'Sistem Informasi',
            'target_position' => 'Fullstack Developer',
            'internship_start_period' => '2026-07-01',
            'internship_end_period' => now()->addMonth()->toDateString(),
            'documents' => [
                'transcript' => UploadedFile::fake()->create('transkrip.pdf', 100, 'application/pdf'),
                'cover_letter' => UploadedFile::fake()->create('surat-pengantar.pdf', 100, 'application/pdf'),
                'portfolio' => UploadedFile::fake()->create('portofolio.pdf', 100, 'application/pdf'),
                'diploma' => UploadedFile::fake()->create('ijazah.pdf', 100, 'application/pdf'),
                'ktp' => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
            ],
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ]);

        $application = InternshipRegistration::firstOrFail();

        $response->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true);
        $this->assertSame('Fullstack Developer', $application->job_title);
        $this->assertSame('PT Equityworld Futures', $application->company);
        $this->assertSame('2002-05-10', $application->date_of_birth->toDateString());
        $this->assertSame('perempuan', $application->gender);
        $this->assertSame('Magang / Internship', $application->registration_type);
        $this->assertSame('Sistem Informasi', $application->internship_major);
        $this->assertSame('Fullstack Developer', $application->target_position);
        $this->assertSame('2026-07-01', $application->start_date->toDateString());
        $this->assertSame(now()->addMonth()->toDateString(), $application->end_date->toDateString());
        $this->assertSame([], $application->experiences);
        $this->assertSame('S1', $application->education_details[0]['level']);
        $this->assertArrayHasKey('transcript', $application->documents);
        $this->assertArrayHasKey('cover_letter', $application->documents);
        foreach (['transcript', 'cover_letter', 'portfolio', 'diploma', 'ktp'] as $documentType) {
            $this->assertArrayHasKey($documentType, $application->documents);
            Storage::disk('private')->assertExists($application->documents[$documentType]);
        }

        $this->get(route('karir'))
            ->assertSeeText('Status pelacakan lamaran')
            ->assertSeeText('Fullstack Developer')
            ->assertSeeText('Lamaran dikirim')
            ->assertDontSee('id="careerApplicationForm"', false);

        $application->update(['status' => 'completed']);
        $this->get(route('karir'))
            ->assertRedirect(route('karir.documents'));
        $this->get(route('karir.documents'))
            ->assertOk()
            ->assertSeeText('Unduh dokumen magang')
            ->assertSeeText('Dokumen penyelesaian program')
            ->assertSeeText('Sertifikat Magang / PKL')
            ->assertSeeText('Letter of Acceptance (LOA)')
            ->assertSeeText('Belum tersedia')
            ->assertSeeText('Dokumen yang belum diterbitkan HRD akan muncul di sini setelah tersedia.')
            ->assertDontSee('<section class="tracker"', false);

        $certificatePath = 'career-applications/'.$application->reference.'/issued/certificate.pdf';
        $loaPath = 'career-applications/'.$application->reference.'/issued/loa.pdf';
        Storage::disk('private')->put($certificatePath, '%PDF certificate');
        Storage::disk('private')->put($loaPath, '%PDF loa');
        $application->update([
            'certificate_path' => $certificatePath,
            'loa_path' => $loaPath,
        ]);

        $this->get(route('karir.documents'))
            ->assertSee(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'certificate',
            ]), false)
            ->assertSee(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'loa',
            ]), false);

        $this->get(route('karir.application.document.download', [
            'applicationId' => $application->getKey(),
            'document' => 'certificate',
        ]))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->get(route('karir.application.document.download', [
            'applicationId' => $application->getKey(),
            'document' => 'loa',
        ]))->assertOk()->assertHeader('content-type', 'application/pdf');

        $otherApplicant = User::factory()->create(['email' => 'other@example.test']);
        $this->actingAs($otherApplicant)
            ->get(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'certificate',
            ]))
            ->assertNotFound();
    }

    public function test_internship_with_a_past_end_date_shows_completed_documents(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $reference = 'INT-AUTO-COMPLETE-001';
        $certificatePath = 'career-applications/'.$reference.'/issued/certificate.pdf';
        $loaPath = 'career-applications/'.$reference.'/issued/loa.pdf';
        Storage::disk('private')->put($certificatePath, '%PDF certificate');
        Storage::disk('private')->put($loaPath, '%PDF loa');

        $application = InternshipRegistration::create([
            'applicant_id' => $applicant->id,
            'full_name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => '+628123456789',
            'status' => 'accepted',
            'reference' => $reference,
            'job_id' => 'internship-0',
            'job_title' => 'Magang Administrasi',
            'company' => 'PT Equityworld Futures',
            'start_date' => now()->subMonths(2)->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
            'certificate_path' => $certificatePath,
            'loa_path' => $loaPath,
        ]);

        $this->actingAs($applicant)
            ->get(route('karir'))
            ->assertRedirect(route('karir.documents'));

        $this->get(route('karir.documents'))
            ->assertOk()
            ->assertSeeText('Unduh dokumen magang')
            ->assertSeeText('Program selesai')
            ->assertSeeText('Sertifikat Magang / PKL')
            ->assertSeeText('Letter of Acceptance (LOA)')
            ->assertSee(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'certificate',
            ]), false)
            ->assertSee(route('karir.application.document.download', [
                'applicationId' => $application->getKey(),
                'document' => 'loa',
            ]), false);

        $this->get(route('karir.application.document.download', [
            'applicationId' => $application->getKey(),
            'document' => 'certificate',
        ]))->assertOk()->assertHeader('content-type', 'application/pdf');

        $this->get(route('karir.application.document.download', [
            'applicationId' => $application->getKey(),
            'document' => 'loa',
        ]))->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    public function test_internship_end_period_cannot_be_before_the_start_period(): void
    {
        Storage::fake('private');
        $this->actingAs($this->applicant());

        $this->post(route('karir.apply.store'), [
            'job_id' => 'internship-3',
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'education' => [[
                'level' => 'S1',
                'institution' => 'Universitas Contoh',
                'major' => 'Akuntansi',
                'status' => 'Sedang berlangsung',
                'start_year' => '2022',
                'completion_year' => '2026',
            ]],
            'registration_type' => 'Praktik Kerja Lapangan (PKL)',
            'target_position' => 'Asisten',
            'internship_start_period' => '2026-09-01',
            'internship_end_period' => '2026-07-31',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ])->assertSessionHasErrors('internship_end_period');

        $this->assertDatabaseCount('internship_registrations', 0);
        $this->assertDatabaseCount('staff_registrations', 0);
    }

    public function test_internship_major_and_field_must_match_an_available_program(): void
    {
        Storage::fake('private');
        $this->actingAs($this->applicant());

        $this->post(route('karir.apply.store'), [
            'job_id' => 'internship-3',
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'education' => [[
                'level' => 'S1',
                'institution' => 'Universitas Contoh',
                'major' => 'Sistem Informasi',
                'status' => 'Sedang berlangsung',
                'start_year' => '2022',
                'completion_year' => '2026',
            ]],
            'registration_type' => 'Magang / Internship',
            'internship_major' => 'Manajemen',
            'target_position' => 'Business Analyst',
            'internship_start_period' => '2026-07-01',
            'internship_end_period' => '2026-09-30',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ])->assertSessionHasErrors('target_position');

        $this->assertDatabaseCount('internship_registrations', 0);
    }

    public function test_internship_application_requires_complete_education_but_not_experience(): void
    {
        Storage::fake('private');
        $this->actingAs($this->applicant());

        $response = $this->from('/karir#apply/internship-2')->post(route('karir.apply.store'), [
            'job_id' => 'internship-2',
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'education' => [[
                'level' => 'S1',
                'institution' => 'Universitas Contoh',
                'major' => 'Akuntansi',
                'start_year' => '2022',
                'completion_year' => '2026',
            ]],
            'registration_type' => 'Magang / Internship',
            'target_position' => 'Asisten',
            'internship_start_period' => '2026-07-01',
            'internship_end_period' => '2026-09-30',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ]);

        $response->assertSessionHasErrors([
            'education.0.status',
        ]);
        $this->assertDatabaseCount('internship_registrations', 0);
        $this->assertDatabaseCount('staff_registrations', 0);
    }

    public function test_applicant_can_submit_a_sample_professional_application(): void
    {
        Storage::fake('private');
        $this->actingAs($this->applicant());

        $response = $this->from('/karir#apply/professional-1')->post(route('karir.apply.store'), [
            'job_id' => 'professional-1',
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'headline' => 'Analis keuangan',
            'summary' => 'Ringkasan kandidat.',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ]);

        $application = StaffRegistration::firstOrFail();
        $response->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true);
        $this->assertSame('Resepsionis', $application->job_title);
        $this->assertSame('PT Equityworld Futures', $application->company);
    }

    public function test_cv_must_be_an_allowed_document_type(): void
    {
        Storage::fake('private');
        $this->actingAs($this->applicant());

        $response = $this->from('/karir#apply/professional-0')->post(route('karir.apply.store'), [
            'job_id' => 'professional-0',
            'full_name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'headline' => 'Analis keuangan',
            'summary' => 'Ringkasan kandidat.',
            'cv' => UploadedFile::fake()->create('cv.txt', 10, 'text/plain'),
            'privacy_consent' => '1',
        ]);

        $response->assertSessionHasErrors('cv');
        $this->assertDatabaseCount('staff_registrations', 0);
        $this->assertDatabaseCount('internship_registrations', 0);
    }

    public function test_career_page_includes_profile_and_cv_application_form(): void
    {
        $response = $this->get('/karir');
        $response
            ->assertOk()
            ->assertSeeText('OB')
            ->assertSeeText('Resepsionis')
            ->assertSeeText('Asisten WPB')
            ->assertSeeText('Driver')
            ->assertSeeText('Staf')
            ->assertSeeText('Lowongan kerja dibuka: OB, Resepsionis, Asisten WPB, Driver, Staf.')
            ->assertSeeText('Program Magang & PKL dibuka: Administrasi, Marketing, IT (Teknologi Informasi), Desain Grafis & Kreatif, Operasional.')
            ->assertSee('<h4>Administrasi</h4>', false)
            ->assertSee('<h4>Marketing</h4>', false)
            ->assertSee('<h4>IT (Teknologi Informasi)</h4>', false)
            ->assertSee('<h4>Desain Grafis &amp; Kreatif</h4>', false)
            ->assertSee('<h4>Operasional</h4>', false)
            ->assertSee('Mendukung pembuatan materi promosi untuk kebutuhan pemasaran.', false)
            ->assertSee('Membantu pemeriksaan dan pemeliharaan perangkat komputer.', false)
            ->assertSee('Membantu membuat desain konten untuk media sosial perusahaan.', false)
            ->assertSee('Membantu pelaksanaan kegiatan operasional perusahaan.', false)
            ->assertDontSeeText('Management Trainee')
            ->assertDontSeeText('Software Engineer & UI/UX Intern')
            ->assertDontSeeText('Junior Tax Assistant Intern')
            ->assertDontSeeText('Finance & Accounting Intern')
            ->assertDontSeeText('Relationship Manager & Market Analyst')
            ->assertDontSeeText('Senior Tax Accountant & Corporate Consultant')
            ->assertDontSeeText('Legal Compliance Officer & Notary Liaison Staff')
            ->assertSee('OB mendukung kebersihan, kerapian, dan kenyamanan area kerja', false)
            ->assertSee('Asisten WPB membantu kebutuhan administrasi dan koordinasi kegiatan tim', false)
            ->assertSee('Driver mendukung mobilitas operasional perusahaan', false)
            ->assertSee('Memiliki SIM yang masih berlaku dan sesuai jenis kendaraan.', false)
            ->assertSeeText('Profil & CV')
            ->assertSee('Submit berkas')
            ->assertSeeText('Magang/PKL')
            ->assertSeeText('Pilih Jurusan & Bidang Magang')
            ->assertSeeText('Sistem Informasi')
            ->assertSeeText('Business Analyst')
            ->assertSee('id="internshipField0" data-internship-field', false)
            ->assertSee('<option value="" selected disabled hidden>Pilih bidang</option>', false)
            ->assertSee('<option value="Business Analyst" data-job-id="internship-2">Business Analyst</option>', false)
            ->assertSee('data-internship-apply data-major="Sistem Informasi"', false)
            ->assertSeeText('Administrasi Bisnis')
            ->assertSeeText('Manajemen Proyek')
            ->assertSeeText('Hubungan Internasional')
            ->assertSeeText('Ilmu Komunikasi')
            ->assertSeeText('Bisnis Digital')
            ->assertSeeText('Informatika')
            ->assertSeeText('Staff Akuntansi')
            ->assertSeeText('Sales & Partnership')
            ->assertDontSee('data-internship-more', false)
            ->assertSee('>Lihat Karir</button>', false)
            ->assertSee('id="detailApply"', false)
            ->assertSeeText('Lamar sekarang')
            ->assertSee('id="detailInternshipPicker" hidden', false)
            ->assertSee('id="detailInternshipMajor"', false)
            ->assertSee('id="detailInternshipField" disabled', false)
            ->assertSeeText('Bidang')
            ->assertSeeText('OB')
            ->assertSeeText('Resepsionis')
            ->assertSeeText('Asisten WPB')
            ->assertSeeText('Driver')
            ->assertSeeText('Staf')
            ->assertDontSeeText('Skema Benefit')
            ->assertDontSeeText('Semua Benefit')
            ->assertDontSee('<h4>Magang Administrasi</h4>', false)
            ->assertSee('href="#jobs?type=profesional"', false)
            ->assertSee('href="#jobs?type=magang"', false)
            ->assertSee('href="#job/professional-0"', false)
            ->assertSee('href="#job/internship-0"', false)
            ->assertSeeText('Nama posisi (A–Z)')
            ->assertSeeText('Postingan terbaru')
            ->assertSeeText('Postingan terlama')
            ->assertDontSeeText('Benefit tertinggi')
            ->assertDontSeeText('Semua bidang')
            ->assertDontSeeText('Semua jenjang')
            ->assertDontSee('id="filterEducation"', false)
            ->assertDontSee('id="applyFilters"', false)
            ->assertDontSeeText('Terapkan filter')
            ->assertSeeText('Pilih bidang')
            ->assertSeeText('Pilih jenjang')
            ->assertSeeText('Riwayat pencarian:')
            ->assertSeeText('Hapus riwayat')
            ->assertSee('data-clear-search-history', false)
            ->assertSeeText('Sistem kerja')
            ->assertDontSeeText('Layanan Pelanggan')
            ->assertDontSeeText('Entry level')
            ->assertDontSee('id="filterCity"', false)
            ->assertSee('data-career-work-system="Work From Office (WFO)"', false)
            ->assertSee('value="teknologi"', false)
            ->assertSee('value="marketing"', false)
            ->assertSee('value="desain"', false)
            ->assertSee('value="operasional"', false)
            ->assertSee('value="SMA"', false)
            ->assertSee('value="SMK"', false)
            ->assertSee('value="D3"', false)
            ->assertSee('value="D4"', false)
            ->assertSee('value="S1"', false)
            ->assertSee('value="S2"', false)
            ->assertSee('Program Magang Administrasi memberikan kesempatan kepada peserta', false)
            ->assertDontSeeText('Status pelacakan lamaran')
            ->assertDontSeeText('Headline profesional')
            ->assertDontSeeText('Ekspektasi gaji bulanan')
            ->assertDontSeeText('Ketersediaan mulai bekerja')
            ->assertDontSeeText('Ringkasan profil')
            ->assertDontSee('name="headline"', false)
            ->assertDontSee('name="expected_salary"', false)
            ->assertDontSee('name="availability"', false)
            ->assertDontSee('name="summary"', false)
            ->assertSeeText('Tanggal lahir')
            ->assertSeeText('Jenis kelamin (opsional)')
            ->assertSeeText('Profil')
            ->assertSeeText('Pengalaman')
            ->assertSeeText('Pendidikan')
            ->assertSeeText('Keahlian')
            ->assertSeeText('Persetujuan data')
            ->assertSeeText('Dokumen')
            ->assertSeeText('Pratinjau pendaftaran')
            ->assertSeeText('Periksa sebelum mengirim')
            ->assertSee('id="applicationPreviewJobTitle"', false)
            ->assertSee('class="apply-field full" id="applicationDiplomaField"', false)
            ->assertSee('id="applicationProgressDescription"', false)
            ->assertDontSeeText('Contoh data')
            ->assertSee('id="applicationDocumentCount" aria-live="polite">0/6</span>', false)
            ->assertSee('data-check="applicationSkills"', false)
            ->assertSee('name="documents[transcript]"', false)
            ->assertSee('name="documents[cover_letter]"', false)
            ->assertSee('name="documents[portfolio]"', false)
            ->assertSee('name="documents[diploma]"', false)
            ->assertSee('name="documents[ktp]"', false)
            ->assertSeeText('Fotokopi KTP')
            ->assertSeeText('Fotokopi ijazah terakhir')
            ->assertSee('class="apply-field full" id="applicationDiplomaField"', false)
            ->assertSeeText('Simpan sebagai Draft')
            ->assertSeeText('Surat pengantar magang / PKL')
            ->assertDontSeeText('Surat keterangan aktif sekolah/kuliah')
            ->assertDontSeeText('Pilih jika ingin mengisi')
            ->assertDontSeeText('Kebutuhan surat pengantar')
            ->assertDontSee('class="apply-hint"', false)
            ->assertSee('>Pilih jenis pengalaman</option>', false)
            ->assertDontSee('>Pengalaman kerja</option>', false)
            ->assertSee('>Pilih jenjang</option>', false)
            ->assertSee('>Pilih status</option>', false)
            ->assertSee('>Pilih jenis pendaftaran</option>', false)
            ->assertSee('name="internship_start_period"', false)
            ->assertSee('name="internship_end_period"', false)
            ->assertSee('id="applicationInternshipEnd" type="date"', false)
            ->assertSee('id="applicationInternshipStart" type="date"', false)
            ->assertSee('id="applicationTargetPosition" name="target_position"', false)
            ->assertSee('id="applicationTargetPosition" name="target_position" value="" maxlength="180" readonly', false)
            ->assertSee('id="applicationInternshipMajor" name="internship_major" value="" maxlength="150" readonly', false)
            ->assertSeeText('Jurusan program yang dipilih')
            ->assertSeeText('Bidang magang yang dipilih')
            ->assertSee("field.name === 'job_id'", false)
            ->assertSee("field.name !== 'job_id'", false)
            ->assertSee("document.getElementById('applicationJobId').value = job.id;", false)
            ->assertDontSee('name="internship_duration"', false)
            ->assertSee('placeholder="Contoh: PT Equityworld Futures"', false)
            ->assertSee('placeholder="Contoh: Staf keuangan"', false)
            ->assertSee('placeholder="Contoh: SMA Negeri 1 Jakarta / Universitas Indonesia"', false)
            ->assertSeeText('Nama asal sekolah/perguruan tinggi')
            ->assertSee('placeholder="Contoh: Akuntansi"', false)
            ->assertDontSee('<option value="">Pilih jenjang</option>', false)
            ->assertDontSee('<option value="">Pilih status</option>', false)
            ->assertDontSee('<option value="">Pilih jenis pendaftaran</option>', false)
            ->assertDontSee('<option value="">Pilih durasi</option>', false)
            ->assertSee('class="apply-field full" data-internship-only hidden><label for="applicationRegistrationType"', false)
            ->assertSeeText('Daftar Sebagai Pelamar')
            ->assertDontSeeText('BAPPEBTI No. 988/SI/2006')
            ->assertSee('src="'.asset('image/logo ewf.png').'" alt="Logo EWF"', false)
            ->assertSee('enctype="multipart/form-data"', false);

        $careerCss = file_get_contents(resource_path('css/karir.css'));
        $this->assertNotFalse($careerCss);
        $this->assertStringContainsString('#careerApply[data-is-internship="true"] #applicationDiplomaField{grid-column:1/-1;width:100%}', $careerCss);
        $this->assertStringContainsString('.krr .float .safe{position:absolute;left:-26px;bottom:-22px', $careerCss);
        $this->assertStringContainsString('@keyframes krBob{50%{translate:0 -4px}}', $careerCss);

        $this->assertSame(5, substr_count($response->getContent(), 'data-career-type="profesional"'));
        $this->assertSame(5, substr_count($response->getContent(), 'data-career-type="magang"'));
        $this->assertSame(9, substr_count($response->getContent(), 'class="card internship-major-card"'));
        preg_match_all('/<article\b(?=[^>]*class="card internship-major-card")(?=[^>]*\shidden\b)[^>]*>/', $response->getContent(), $hiddenMajors);
        $this->assertCount(3, $hiddenMajors[0]);
        $this->assertSame(6, substr_count($response->getContent(), 'class="card internship-major-card"') - count($hiddenMajors[0]));
    }

    public function test_home_career_signup_is_guest_only_and_information_link_opens_careers(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Formulir Pendaftaran Karir Online')
            ->assertSeeText('Informasi Detail')
            ->assertSee(route('karir').'#lowongan', false)
            ->assertSee(route('daftar', ['next' => '/#karir']), false);

        $this->actingAs($this->applicant())
            ->get(route('home'))
            ->assertOk()
            ->assertDontSeeText('Formulir Pendaftaran Karir Online')
            ->assertDontSeeText('Informasi Detail')
            ->assertDontSeeText('Daftar Karir Sekarang');
    }

    public function test_activities_signup_callout_is_guest_only(): void
    {
        $this->get(route('kegiatan'))
            ->assertOk()
            ->assertSeeText('Siap Jadi Bagian dari')
            ->assertSee(route('daftar', ['next' => '/karir#lowongan']), false);

        $this->actingAs($this->applicant())
            ->get(route('kegiatan'))
            ->assertOk()
            ->assertDontSeeText('Siap Jadi Bagian dari')
            ->assertDontSeeText('Daftar Sekarang');
    }

    public function test_career_page_prefills_selected_internship_major_and_field(): void
    {
        $this->get(route('karir', [
            'internship_major' => 'Sistem Informasi',
            'target_position' => 'Business Analyst',
        ]))
            ->assertOk()
            ->assertSee('id="applicationInternshipMajor" name="internship_major" value="Sistem Informasi" maxlength="150" readonly', false)
            ->assertSee('id="applicationTargetPosition" name="target_position" value="Business Analyst" maxlength="180" readonly', false);
    }

    public function test_guest_cannot_submit_a_career_application(): void
    {
        $this->post(route('karir.apply.store'), [])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('staff_registrations', 0);
        $this->assertDatabaseCount('internship_registrations', 0);
    }

    public function test_applicant_without_applications_does_not_see_tracking_dashboard(): void
    {
        $this->actingAs($this->applicant())
            ->get(route('karir'))
            ->assertOk()
            ->assertSeeText('Daftar Lowongan & Program Magang')
            ->assertDontSeeText('Status pelacakan lamaran');
    }

    public function test_applicant_without_a_completed_internship_is_redirected_from_documents_page(): void
    {
        $this->actingAs($this->applicant())
            ->get(route('karir.documents'))
            ->assertRedirect(route('karir'));
    }

    public function test_authenticated_applicant_can_refresh_the_application_session(): void
    {
        $response = $this->actingAs($this->applicant())
            ->get(route('karir.session.refresh'));

        $response->assertOk()
            ->assertJsonStructure(['csrf_token'])
            ->assertSessionHas('_career_application_activity');
        $this->assertSame($this->app['session.store']->token(), $response->json('csrf_token'));
    }

    private function applicant(): User
    {
        return User::create([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'password' => 'Password123',
            'status' => 'active',
        ]);
    }

    private function hrd(): User
    {
        $role = Role::query()->create(['role_name' => 'HRD']);

        return User::create([
            'role_id' => $role->getKey(),
            'name' => 'HRD Penguji',
            'email' => 'hrd@example.test',
            'password' => 'Password123',
            'status' => 'active',
        ]);
    }
}
