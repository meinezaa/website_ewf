<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertSee($admin->name);

        $this->get(route('admin.users.index'))->assertOk();
        $this->get(route('admin.users.create'))->assertOk();
        $this->get(route('admin.logs'))->assertOk();
        $this->get(route('admin.profile'))->assertRedirect(route('profile'));
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
    }
}
