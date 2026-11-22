<?php

namespace Tests\Feature;

use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_registration_page_renders_with_external_stylesheet(): void
    {
        $this->get(route('daftar'))
            ->assertOk()
            ->assertSeeText('Daftar Akun Baru')
            ->assertSee('rel="stylesheet"', false)
            ->assertSee('auth-transitions', false)
            ->assertDontSee('<style', false)
            ->assertSee('name="email"', false);
    }

    public function test_login_page_renders_with_external_stylesheet(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText('Masuk ke Akun')
            ->assertSee('rel="stylesheet"', false)
            ->assertSee('auth-transitions', false)
            ->assertDontSee('<style', false)
            ->assertSee('name="password"', false);
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        config(['cache.default' => 'array']);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');
        Artisan::call('migrate', ['--force' => true]);
        Auth::forgetGuards();
    }

    public function test_registration_creates_a_hashed_account_and_continues_to_an_apply_page(): void
    {
        $response = $this->post(route('daftar.store'), [
            'name' => 'Indira Prastika',
            'email' => 'INDIRA@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'next' => '/karir#apply/professional-2',
        ]);

        $user = User::query()->where('email', 'indira@example.test')->firstOrFail();
        $response->assertRedirect(route('karir').'#apply/professional-2');
        $this->assertAuthenticatedAs($user);
        $this->assertSame('indira@example.test', $user->email);
        $this->assertSame('Applicant', $user->role->role_name);
        $this->assertTrue(Hash::check('Password123', $user->password));
    }

    public function test_registration_rejects_duplicate_email_and_mismatched_password_confirmation(): void
    {
        $this->createUser([
            'name' => 'Existing Candidate',
            'email' => 'candidate@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->post(route('daftar.store'), [
            'name' => 'Another Candidate',
            'email' => 'candidate@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Different123',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_authenticates_registered_user_and_rejects_invalid_credentials(): void
    {
        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'not-the-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
        ])->assertRedirect(route('profile'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'));
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
            'next' => '/karir#apply/internship-4',
        ])->assertRedirect(route('karir').'#apply/internship-4');
    }

    public function test_password_reset_sends_a_token_link_and_allows_login_with_the_new_password(): void
    {
        Notification::fake();
        config(['mail.default' => 'log']);
        $statusMessage = 'Mode email saat ini hanya mencatat pesan di log aplikasi dan tidak mengirim email. Jika email terdaftar, periksa log aplikasi untuk tautan reset.';

        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText('Lupa kata sandi?')
            ->assertSee('href="'.route('password.request').'"', false);

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSeeText('Lupa kata sandi?')
            ->assertSee('rel="stylesheet"', false)
            ->assertDontSee('css/password-reset.css', false)
            ->assertDontSee('<style', false)
            ->assertSee(asset('image/'.rawurlencode('logo galeri magang.png')), false)
            ->assertSee('<span class="password-brand-galeri">GALERI</span>', false)
            ->assertSee('<span class="password-brand-magang">MAGANG</span>', false)
            ->assertSee('class="password-visual-curve"', false)
            ->assertSeeText('Kembali ke halaman login')
            ->assertSeeText('Kirim tautan reset')
            ->assertDontSee('←');
        if (app()->environment('local') && config('mail.default') === 'log') {
            $this->get(route('password.request'))
                ->assertSeeText('Mode email lokal hanya mencatat tautan reset di log aplikasi.');
        }

        $this->from(route('password.request'))->post(route('password.email'), [
            'email' => 'unknown@example.test',
        ])->assertRedirect(route('password.request'))
            ->assertSessionHas('status', $statusMessage);
        Notification::assertNothingSent();

        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->from(route('password.request'))->post(route('password.email'), [
            'email' => 'INDIRA@example.test',
        ])->assertRedirect(route('password.request'))
            ->assertSessionHas('status', $statusMessage);

        $token = null;
        Notification::assertSentTo($user, ResetPasswordNotification::class, function (ResetPasswordNotification $notification) use (&$token): bool {
            $token = $notification->token;

            return true;
        });
        $this->assertIsString($token);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertOk()
            ->assertSeeText('Buat kata sandi baru')
            ->assertSee('rel="stylesheet"', false)
            ->assertDontSee('css/password-reset.css', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee(asset('image/'.rawurlencode('logo galeri magang.png')), false)
            ->assertSee('<span class="password-brand-galeri">GALERI</span>', false)
            ->assertSee('<span class="password-brand-magang">MAGANG</span>', false)
            ->assertSee('class="password-visual-curve"', false)
            ->assertSeeText('Kembali ke halaman login')
            ->assertDontSee('←');

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123',
            'password_confirmation' => 'DifferentPassword123',
        ])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('Password123', $user->fresh()->password));

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertRedirect(route('login'))
            ->assertSessionHas('status');
        $this->assertTrue(Hash::check('NewPassword123', $user->fresh()->password));
        $this->get(route('login'))
            ->assertSeeText('Kata sandi berhasil diubah. Silakan masuk menggunakan kata sandi baru.');

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'NewPassword123',
        ])->assertRedirect(route('profile'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_reset_token_does_not_authorize_a_password_reset(): void
    {
        Notification::fake();
        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->post(route('password.update'), [
            'token' => 'not-a-valid-token',
            'email' => $user->email,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('Password123', $user->fresh()->password));
    }

    public function test_guest_auth_links_return_to_the_page_they_opened(): void
    {
        $this->get(route('tentang.kami'))
            ->assertOk()
            ->assertSee('href="'.route('login', ['next' => '/tentang-kami']).'"', false)
            ->assertSee('href="'.route('daftar', ['next' => '/tentang-kami']).'"', false);

        $this->get(route('karir'))
            ->assertOk()
            ->assertSee('href="'.route('login', ['next' => '/karir']).'"', false)
            ->assertSee('href="'.route('daftar', ['next' => '/karir']).'"', false)
            ->assertSee('data-return-current', false);
    }

    public function test_login_returns_user_to_an_allowed_previous_page(): void
    {
        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
            'next' => '/tentang-kami#profil',
        ])->assertRedirect(route('tentang.kami').'#profil');

        $this->post(route('logout'));
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
            'next' => '/edukasi#jadwal',
        ])->assertRedirect(route('edukasi').'#jadwal');

        $this->post(route('logout'));
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
            'next' => '/faq',
        ])->assertRedirect(route('faq'));

        $this->post(route('logout'));
        $selectedProgramNext = '/karir#apply/internship-2?major=Sistem+Informasi&field=Business+Analyst';
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
            'next' => $selectedProgramNext,
        ])->assertRedirect(route('karir').'#apply/internship-2?major=Sistem+Informasi&field=Business+Analyst');

        $this->post(route('logout'));
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
            'next' => '/karir#apply/internship-2?major=Manajemen&field=Business+Analyst',
        ])->assertRedirect(route('profile'));
    }

    public function test_registration_returns_user_to_an_allowed_previous_page(): void
    {
        $this->post(route('daftar.store'), [
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'next' => '/tentang-kami#profil',
        ])->assertRedirect(route('tentang.kami').'#profil');
    }

    public function test_registration_preserves_selected_internship_program_when_returning_to_apply_page(): void
    {
        $this->post(route('daftar.store'), [
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'next' => '/karir#apply/internship-2?major=Sistem+Informasi&field=Business+Analyst',
        ])->assertRedirect(route('karir').'#apply/internship-2?major=Sistem+Informasi&field=Business+Analyst');
    }

    public function test_profile_can_be_updated_and_user_can_sign_out(): void
    {
        $this->useSqliteCareerDatabase();
        Storage::fake('public');

        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->actingAs($user)->get(route('profile'))
            ->assertOk()
            ->assertSeeText('Halo, Indira Prastika')
            ->assertSeeText('Informasi akun')
            ->assertSeeText('Riwayat lamaran')
            ->assertSee('aria-label="Pilih foto profil"', false)
            ->assertSee('id="profilePhoto" name="profile_photo" type="file"', false)
            ->assertDontSee('data-profile-photo-preview', false)
            ->assertDontSeeText('Pratinjau foto siap.')
            ->assertDontSeeText('maksimal 2 MB')
            ->assertSeeText('Nomor WhatsApp')
            ->assertSeeText('Jenis kelamin')
            ->assertDontSeeText('Pilih jenis kelamin')
            ->assertSeeText('Kota domisili')
            ->assertSeeText('Jelajahi karir')
            ->assertSee('href="'.route('karir').'#jobs"', false)
            ->assertSee('id="logoutAccountCard"', false)
            ->assertSee('id="deleteAccountCard"', false)
            ->assertDontSeeText('Headline profesional')
            ->assertSeeText('Keluar akun')
            ->assertDontSeeText('Profil Pelamar')
            ->assertDontSeeText('Sign out');

        $this->postJson(route('profile.photo.update'), [
            'profile_photo' => UploadedFile::fake()->create('profile.pdf', 20, 'application/pdf'),
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('profile_photo');

        $this->put(route('profile.update'), [
            'name' => 'Indira P.',
            'phone' => '+628123456789',
            'gender' => 'perempuan',
            'location' => 'Jakarta',
        ])->assertRedirect(route('profile'))
            ->assertSessionHas('profile_updated', true);

        $savedUser = $user->fresh();
        $this->assertSame('Indira P.', $savedUser->name);
        $this->assertSame('perempuan', $savedUser->gender);
        $this->assertSame('Jakarta', $savedUser->location);
        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('id="profilePhoto" name="profile_photo" type="file"', false)
            ->assertDontSee('data-profile-photo-preview', false)
            ->assertDontSeeText('Pratinjau foto siap.')
            ->assertDontSeeText('maksimal 2 MB');

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    public function test_profile_photo_upload_is_saved_immediately_and_shown_in_the_header(): void
    {
        Storage::fake('public');

        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);
        $image = static fn (): UploadedFile => UploadedFile::fake()->createWithContent(
            'profile.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a0c0AAAAASUVORK5CYII=', true)
        );

        $firstResponse = $this->actingAs($user)->postJson(route('profile.photo.update'), [
            'profile_photo' => $image(),
        ])->assertOk()
            ->assertJsonStructure(['photo_url']);

        $firstPhotoPath = $user->fresh()->profile_photo_path;
        $this->assertNotNull($firstPhotoPath);
        Storage::disk('public')->assertExists($firstPhotoPath);
        $firstPhotoUrl = $user->fresh()->profilePhotoUrl();
        $this->assertStringStartsWith('/storage/', $firstPhotoUrl);
        $firstResponse->assertJsonPath('photo_url', $firstPhotoUrl);

        $secondResponse = $this->postJson(route('profile.photo.update'), [
            'profile_photo' => $image(),
        ])->assertOk()
            ->assertJsonStructure(['photo_url']);

        $savedUser = $user->fresh();
        $this->assertNotSame($firstPhotoPath, $savedUser->profile_photo_path);
        Storage::disk('public')->assertMissing($firstPhotoPath);
        Storage::disk('public')->assertExists($savedUser->profile_photo_path);
        $photoUrl = $savedUser->profilePhotoUrl();
        $secondResponse->assertJsonPath('photo_url', $photoUrl);

        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('<img src="'.$photoUrl.'" alt="Foto profil Indira Prastika">', false)
            ->assertSee('<img class="account-nav-avatar" src="'.$photoUrl.'" alt="">', false)
            ->assertDontSeeText('Pratinjau foto siap.')
            ->assertDontSeeText('maksimal 2 MB');

        $this->get(route('tentang.kami'))
            ->assertOk()
            ->assertSee('<img class="account-nav-avatar" src="'.$photoUrl.'" alt="">', false);
    }

    public function test_signed_in_applicant_does_not_see_registration_cta(): void
    {
        $this->useSqliteCareerDatabase();

        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->actingAs($user)->get(route('karir'))
            ->assertOk()
            ->assertDontSeeText('Daftar Sebagai Pelamar');
    }

    public function test_database_applicant_can_submit_and_track_an_application(): void
    {
        $this->useSqliteCareerDatabase();
        Storage::fake('private');
        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->actingAs($user)->post(route('karir.apply.store'), [
            'job_id' => 'professional-0',
            'full_name' => 'Indira Prastika',
            'email' => $user->email,
            'phone' => '+628123456789',
            'location' => 'Jakarta',
            'date_of_birth' => '2002-05-10',
            'cv' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            'privacy_consent' => '1',
        ])->assertRedirect(route('karir'))
            ->assertSessionHas('application_submitted', true);

        $application = StaffRegistration::firstOrFail();
        $this->assertSame($user->id, $application->applicant_id);
        $this->get(route('karir'))
            ->assertOk()
            ->assertSeeText('Status pelacakan lamaran')
            ->assertSeeText('Pelamar: Indira Prastika')
            ->assertSeeText('OB')
            ->assertSee('id="status-pendaftaran"', false)
            ->assertDontSee('id="careerApplicationForm"', false);

        $this->get(route('profile'))
            ->assertOk()
            ->assertSeeText('Tanggal submit:')
            ->assertDontSeeText('Nomor pendaftaran:')
            ->assertDontSeeText($application->reference)
            ->assertSeeText('Lacak status pendaftaran')
            ->assertSee('href="'.route('karir').'#status-pendaftaran"', false)
            ->assertDontSeeText('Jelajahi karir');
    }

    public function test_signed_in_user_sees_profile_icon_on_about_page(): void
    {
        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);

        $this->actingAs($user)->get(route('tentang.kami'))
            ->assertOk()
            ->assertSee('bi-person-circle', false)
            ->assertSee('rel="stylesheet"', false)
            ->assertSee('href="'.route('profile').'"', false);
    }

    public function test_untrusted_next_url_is_not_used_for_redirect(): void
    {
        $this->post(route('daftar.store'), [
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'next' => 'https://attacker.example',
        ])->assertRedirect(route('profile'));
    }

    public function test_deleting_either_registration_type_revokes_login_and_allows_reregistration(): void
    {
        $staffUser = $this->createUser([
            'name' => 'Staff Candidate',
            'email' => 'staff@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);
        $staffApplication = StaffRegistration::create([
            'applicant_id' => $staffUser->id,
            'full_name' => $staffUser->name,
            'email' => $staffUser->email,
            'phone' => $staffUser->phone,
        ]);

        Auth::login($staffUser);
        $staffApplication->delete();

        $this->assertSoftDeleted('users', ['id' => $staffUser->id]);
        Auth::forgetGuards();
        $this->get(route('profile'))->assertRedirect(route('login'));
        $this->post(route('login.store'), [
            'email' => $staffUser->email,
            'password' => 'Password123',
        ])->assertSessionHasErrors('email');

        $internshipUser = $this->createUser([
            'name' => 'Intern Candidate',
            'email' => 'intern@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);
        $internshipApplication = InternshipRegistration::create([
            'applicant_id' => $internshipUser->id,
            'full_name' => $internshipUser->name,
            'email' => $internshipUser->email,
            'phone' => $internshipUser->phone,
        ]);

        $internshipApplication->delete();

        $this->assertSoftDeleted('users', ['id' => $internshipUser->id]);
        $this->post(route('login.store'), [
            'email' => $internshipUser->email,
            'password' => 'Password123',
        ])->assertSessionHasErrors('email');

        $this->post(route('daftar.store'), [
            'name' => 'Staff Candidate',
            'email' => 'staff@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ])->assertRedirect(route('profile'));
        $this->assertDatabaseHas('users', [
            'email' => 'staff@example.test',
            'deleted_at' => null,
            'status' => 'active',
        ]);
    }

    public function test_legacy_file_accounts_can_be_imported_and_linked_to_existing_applications(): void
    {
        $legacyFile = storage_path('framework/testing/auth-import-'.bin2hex(random_bytes(6)).'.json');
        file_put_contents($legacyFile, json_encode([
            '14' => [
                'id' => '14',
                'name' => 'Legacy Candidate',
                'email' => 'legacy@example.test',
                'phone' => '+628123456789',
                'gender' => 'perempuan',
                'location' => 'Jakarta',
                'password' => Hash::make('Password123'),
                'status' => 'active',
            ],
        ], JSON_THROW_ON_ERROR));
        config(['auth.legacy_users_file' => $legacyFile]);

        try {
            $application = StaffRegistration::create([
                'full_name' => 'Legacy Candidate',
                'email' => 'legacy@example.test',
                'phone' => '+628123456789',
            ]);

            $this->artisan('auth:import-file-users', ['--dry-run' => true])
                ->expectsOutput('Accounts would be imported: 1. Already present: 0.')
                ->assertExitCode(0);
            $this->assertDatabaseCount('users', 0);

            $this->artisan('auth:import-file-users')
                ->expectsOutput('Accounts imported: 1. Already present: 0.')
                ->assertExitCode(0);

            $user = User::query()->where('email', 'legacy@example.test')->firstOrFail();
            $this->assertTrue(Hash::check('Password123', $user->password));
            $this->assertSame('Applicant', $user->role->role_name);
            $this->assertSame($user->id, $application->fresh()->applicant_id);
            $this->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'Password123',
            ])->assertRedirect(route('profile'));
        } finally {
            if (is_file($legacyFile)) {
                unlink($legacyFile);
            }
        }
    }

    public function test_account_deletion_removes_applications_and_documents_after_password_confirmation(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $user = $this->createUser([
            'name' => 'Indira Prastika',
            'email' => 'indira@example.test',
            'phone' => '+628123456789',
            'password' => 'Password123',
        ]);
        $profilePhotoPath = 'profile-photos/'.$user->getKey().'/avatar.png';
        Storage::disk('public')->put($profilePhotoPath, 'profile photo');
        $user->update(['profile_photo_path' => $profilePhotoPath]);
        $cvPath = 'career-applications/EWF-APP-TEST/cv/cv.pdf';
        $ktpPath = 'career-applications/EWF-APP-TEST/documents/ktp.pdf';
        Storage::disk('private')->put($cvPath, 'cv');
        Storage::disk('private')->put($ktpPath, 'ktp');
        $application = StaffRegistration::create([
            'applicant_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'cv_path' => $cvPath,
            'document_path' => $cvPath,
            'documents' => ['ktp' => $ktpPath],
        ]);

        $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'WrongPassword123',
        ])->assertSessionHasErrors('password');
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->delete(route('profile.destroy'), [
            'password' => 'Password123',
        ])->assertRedirect(route('home'))
            ->assertSessionHas('account_deleted', true);

        $this->get(route('home'))->assertSeeText('Akun Anda berhasil dihapus.');
        $this->assertGuest();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('staff_registrations', ['id' => $application->id]);
        Storage::disk('private')->assertMissing($cvPath);
        Storage::disk('private')->assertMissing($ktpPath);
        Storage::disk('public')->assertMissing($profilePhotoPath);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123',
        ])->assertSessionHasErrors('email');
        $this->post(route('daftar.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ])->assertRedirect(route('profile'));
    }

    private function createUser(array $attributes): User
    {
        return User::create([
            'name' => $attributes['name'],
            'email' => mb_strtolower($attributes['email']),
            'phone' => $attributes['phone'] ?? null,
            'password' => Hash::make($attributes['password']),
            'status' => 'active',
        ]);
    }

    private function useSqliteCareerDatabase(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');
        Artisan::call('migrate', ['--force' => true]);
    }
}
