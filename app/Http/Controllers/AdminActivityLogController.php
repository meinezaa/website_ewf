<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'role' => ['nullable', 'string', 'max:50'],
            'activity' => ['nullable', 'in:login,logout,registrasi,pendaftaran,perubahan,sertifikat,pengguna'],
            'q' => ['nullable', 'string', 'max:120'],
        ]);
        $todayStartUtc = now('Asia/Jakarta')->startOfDay()->utc();
        $tomorrowStartUtc = $todayStartUtc->copy()->addDay();

        $query = ActivityLog::query()->with('user.role');
        if (filled($filters['role'] ?? null)) {
            $query->whereHas('user.role', fn (Builder $roleQuery) => $roleQuery
                ->whereRaw('LOWER(role_name) = ?', [mb_strtolower($filters['role'])]));
        }
        if (filled($filters['activity'] ?? null)) {
            $this->filterActivity($query, $filters['activity']);
        }
        if (filled($filters['q'] ?? null)) {
            $search = '%'.addcslashes($filters['q'], '%_\\').'%';
            $query->where(function (Builder $searchQuery) use ($search): void {
                $searchQuery->where('activity', 'like', $search)
                    ->orWhereHas('user', fn (Builder $userQuery) => $userQuery
                        ->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search));
            });
        }

        return view('admin.logs', [
            'logs' => $query
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(20)
                ->withQueryString(),
            'totalActivities' => ActivityLog::count(),
            'todayActivities' => ActivityLog::where('created_at', '>=', $todayStartUtc)
                ->where('created_at', '<', $tomorrowStartUtc)
                ->count(),
            'activeUsers' => ActivityLog::where('created_at', '>=', $todayStartUtc)
                ->where('created_at', '<', $tomorrowStartUtc)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id'),
            'roles' => Role::query()->orderBy('role_name')->pluck('role_name'),
            'filters' => $filters,
        ]);
    }

    private function filterActivity(Builder $query, string $type): void
    {
        $terms = match ($type) {
            'login' => ['Login berhasil', 'berhasil login'],
            'logout' => ['Logout'],
            'registrasi' => ['Membuat akun baru', 'Registrasi'],
            'pendaftaran' => ['lamaran', 'pendaftaran'],
            'perubahan' => ['Memperbarui', 'Mengubah', 'Menonaktifkan', 'Mengaktifkan'],
            'sertifikat' => ['sertifikat', 'certificate'],
            'pengguna' => ['akun HRD', 'akun Applicant', 'akun baru'],
        };

        $query->where(function (Builder $activityQuery) use ($terms): void {
            foreach ($terms as $term) {
                $activityQuery->orWhere('activity', 'like', '%'.$term.'%');
            }
        });
    }
}
