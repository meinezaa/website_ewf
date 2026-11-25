<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUsersController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::with('role')
                ->whereHas('role', fn ($query) => $query->whereRaw('LOWER(role_name) != ?', ['admin']))
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function edit(User $user): View
    {
        $this->ensureHrd($user);

        return view('admin.users.edit', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique(User::class, 'email')->whereNull('deleted_at'),
            ],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $role = Role::query()->firstOrCreate(['role_name' => 'HRD']);

        $user = User::create([
            'role_id' => $role->getKey(),
            'name' => $validated['name'],
            'email' => mb_strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'status' => 'active',
        ]);
        $this->recordActivity($request, 'Menambahkan akun HRD: '.$user->name);

        return redirect()->route('admin.users.index')->with('success', 'Akun HRD berhasil dibuat.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureHrd($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($user->getKey())->whereNull('deleted_at'),
            ],
            'phone' => ['nullable', 'string', 'max:40'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $previousName = $user->name;
        $previousStatus = $user->status;
        $validated['email'] = mb_strtolower($validated['email']);
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);
        $activity = $previousStatus === 'active' && $user->status !== 'active'
            ? 'Menonaktifkan akun HRD: '.$previousName
            : 'Memperbarui akun HRD: '.$previousName;
        if ($previousName !== $user->name) {
            $activity .= ' menjadi '.$user->name;
        }
        if ($previousStatus !== $user->status) {
            $activity .= ' (status: '.($user->status === 'active' ? 'aktif' : 'nonaktif').')';
        }
        $this->recordActivity($request, $activity);

        return redirect()->route('admin.users.index')->with('success', 'Data akun HRD berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureHrd($user);
        $this->recordActivity($request, 'Menghapus akun HRD: '.$user->name);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun HRD berhasil dihapus.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        abort_unless($this->roleName($user) === 'applicant', 403, 'Status hanya dapat diubah dari sini untuk akun Applicant.');

        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);
        $this->recordActivity(
            $request,
            ($user->status === 'active' ? 'Mengaktifkan akun Applicant: ' : 'Menonaktifkan akun Applicant: ').$user->name
        );

        return redirect()->route('admin.users.index')->with(
            'success',
            'Status akun Applicant berhasil diubah menjadi '.($user->status === 'active' ? 'aktif.' : 'nonaktif.')
        );
    }

    private function ensureHrd(User $user): void
    {
        abort_unless($this->roleName($user) === 'hrd', 403, 'Aksi ini hanya tersedia untuk akun HRD.');
    }

    private function roleName(User $user): string
    {
        return mb_strtolower((string) $user->role?->role_name);
    }

    private function recordActivity(Request $request, string $activity): void
    {
        ActivityLog::create([
            'user_id' => $request->user()->getKey(),
            'activity' => $activity,
            'ip_address' => $request->ip(),
        ]);
    }
}
