<?php

namespace Tests\Feature;

use App\Models\InternshipRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_separate_registration_counts(): void
    {
        $this->withoutVite();

        $role = Role::create(['role_name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $role->getKey()]);

        foreach ([
            ['magang-one@example.test', 'Magang / Internship'],
            ['magang-two@example.test', 'Internship'],
            ['pkl-one@example.test', 'Praktik Kerja Lapangan (PKL)'],
        ] as [$email, $registrationType]) {
            InternshipRegistration::create([
                'full_name' => 'Pendaftar '.$registrationType,
                'email' => $email,
                'phone' => '08123456789',
                'registration_type' => $registrationType,
            ]);
        }

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSeeText('Pendaftar Magang')
            ->assertSeeText('Pendaftar PKL')
            ->assertDontSeeText('Pendaftar Magang/PKL')
            ->assertViewHas('stats', function ($stats): bool {
                $statsByLabel = collect($stats)->keyBy('label');

                return $statsByLabel->count() === 6
                    && $statsByLabel->get('Pendaftar Magang')['value'] === 2
                    && $statsByLabel->get('Pendaftar PKL')['value'] === 1;
            });
    }

    public function test_admin_can_render_dashboard_and_open_admin_navigation_routes(): void
    {
        $this->withoutVite();

        $role = Role::create(['role_name' => 'Admin']);
        $admin = User::factory()->create([
            'role_id' => $role->getKey(),
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertViewIs('admin.dashboard')
            ->assertSee($admin->name)
            ->assertSee('<b>GALERI</b><strong>MAGANG</strong>', false)
            ->assertSee('PT EQUITYWORLD SURABAYA');

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertViewIs('admin.kelola_hrd.index');
        $this->get(route('admin.users.create'))
            ->assertOk()
            ->assertViewIs('admin.kelola_hrd.create');
        $this->get(route('admin.logs'))->assertOk();
        $this->get(route('admin.profile'))
            ->assertOk()
            ->assertViewIs('admin.profile');
    }

    public function test_admin_can_filter_users_by_applicant_or_hrd_role(): void
    {
        $adminRole = Role::create(['role_name' => 'Admin']);
        $applicantRole = Role::create(['role_name' => 'Applicant']);
        $hrdRole = Role::create(['role_name' => 'HRD']);
        $admin = User::factory()->create(['role_id' => $adminRole->getKey()]);
        $applicant = User::factory()->create([
            'role_id' => $applicantRole->getKey(),
            'name' => 'Applicant Example',
        ]);
        $hrd = User::factory()->create([
            'role_id' => $hrdRole->getKey(),
            'name' => 'HRD Example',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['role' => 'applicant']))
            ->assertOk()
            ->assertSeeText($applicant->name)
            ->assertDontSeeText($hrd->name)
            ->assertViewHas('users', fn ($users) => $users->getCollection()->modelKeys() === [$applicant->getKey()]);

        $this->get(route('admin.users.index', ['role' => 'hrd']))
            ->assertOk()
            ->assertSeeText($hrd->name)
            ->assertDontSeeText($applicant->name)
            ->assertViewHas('users', fn ($users) => $users->getCollection()->modelKeys() === [$hrd->getKey()]);
    }

    public function test_admin_can_log_out_from_the_profile_page(): void
    {
        $role = Role::create(['role_name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $role->getKey()]);

        $response = $this->actingAs($admin)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('id="adminProfileLogoutForm"', false)
            ->assertSee('Keluar dari akun');

        $document = new \DOMDocument();
        $previousLibxmlErrorSetting = libxml_use_internal_errors(true);
        $document->loadHTML($response->getContent());
        libxml_clear_errors();
        libxml_use_internal_errors($previousLibxmlErrorSetting);
        $profileForm = $document->getElementById('adminProfileUpdateForm');
        $logoutForm = $document->getElementById('adminProfileLogoutForm');
        $ancestor = $logoutForm?->parentNode;
        while ($ancestor !== null && $ancestor !== $profileForm) {
            $ancestor = $ancestor->parentNode;
        }

        $this->assertNotNull($profileForm);
        $this->assertNotNull($logoutForm);
        $this->assertNull($ancestor, 'The logout form must not be nested inside the profile update form.');

        $this->post(route('logout'))
            ->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_routes_require_login_and_admin_role(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));

        $role = Role::create(['role_name' => 'Applicant']);
        $applicant = User::factory()->create([
            'role_id' => $role->getKey(),
            'status' => 'active',
        ]);

        $this->actingAs($applicant)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_create_an_hrd_account(): void
    {
        $adminRole = Role::create(['role_name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->getKey()]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'HRD Tester',
                'email' => 'hrd@example.test',
                'phone' => '08123456789',
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $hrdRole = Role::where('role_name', 'HRD')->firstOrFail();
        $this->assertDatabaseHas('users', [
            'name' => 'HRD Tester',
            'email' => 'hrd@example.test',
            'role_id' => $hrdRole->getKey(),
        ]);
        $hrd = User::where('email', 'hrd@example.test')->firstOrFail();
        $this->get(route('admin.users.edit', $hrd))
            ->assertOk()
            ->assertViewIs('admin.kelola_hrd.edit');
    }
}
