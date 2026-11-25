<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\InternshipRegistration;
use App\Models\Role;
use App\Models\StaffRegistration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardAdminController extends Controller
{
    public function __invoke()
    {
        /* ---- Akun ---- */
        $total    = User::count();
        $aktif    = User::where('status', 'active')->count();
        $nonaktif = $total - $aktif;
        $pct      = $total ? round($aktif / $total * 100) : 0;
        $totalApplicants = User::whereHas('role', fn ($query) => $query->whereRaw('LOWER(role_name) = ?', ['applicant']))->count();
        $newApplicantsThisMonth = User::whereHas('role', fn ($query) => $query->whereRaw('LOWER(role_name) = ?', ['applicant']))
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        /* ---- Komposisi role ---- */
        $perRole = User::select('role_id', DB::raw('COUNT(*) as total'))
            ->groupBy('role_id')->pluck('total', 'role_id');

        $roles = Role::orderBy('id')->get()->map(fn ($r) => (object) [
            'label' => $r->role_name,
            'class' => Str::slug($r->role_name),
            'total' => (int) ($perRole[$r->id] ?? 0),
        ]);
        $byRole = fn ($name) => $roles->firstWhere('class', $name)?->total ?? 0;

        $staff = StaffRegistration::count();
        $magang = InternshipRegistration::count();

        $stats = [
            ['label' => 'Total User',           'value' => $totalApplicants, 'note' => "+$newApplicantsThisMonth bulan ini", 'icon' => 'users'],
            ['label' => 'Total Admin',          'value' => $byRole('admin'), 'note' => 'Pengelola sistem',        'icon' => 'shield'],
            ['label' => 'Total HRD',            'value' => $byRole('hrd'),   'note' => 'Pengelola seleksi',       'icon' => 'briefcase'],
            ['label' => 'Pendaftar Staff',      'value' => $staff,           'note' => 'Jalur karyawan',          'icon' => 'card'],
            ['label' => 'Pendaftar Magang/PKL', 'value' => $magang,          'note' => 'Jalur magang',            'icon' => 'cap'],
        ];

        /* ---- Aktivitas 7 hari terakhir (sumber data sama dengan Activity Log) ---- */
        $counts = ActivityLog::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')->pluck('c', 'd');

        $week = collect(range(6, 0))->map(function ($i) use ($counts) {
            $day = now()->subDays($i);
            return [
                'label' => $day->locale('id')->isoFormat('ddd'),
                'count' => (int) ($counts[$day->toDateString()] ?? 0),
                'today' => $i === 0,
            ];
        });
        $weekMax   = max(1, $week->max('count'));
        $weekTotal = $week->sum('count');

        /* ---- Daftar terbaru ---- */
        $recent = ActivityLog::with('user.role')
            ->orderByDesc('created_at')->orderByDesc('id')
            ->take(6)->get();

        $newUsers = User::with('role')->latest()->take(5)->get();
        $hour = now()->hour;
        $sapa = match (true) {
            $hour < 11 => 'Selamat pagi',
            $hour < 15 => 'Selamat siang',
            $hour < 19 => 'Selamat sore',
            default => 'Selamat malam',
        };
        $nama = auth()->user()->name;
        $tanggal = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');

        return view('admin.dashboard', compact(
            'stats', 'total', 'aktif', 'nonaktif', 'pct',
            'roles', 'week', 'weekMax', 'weekTotal', 'recent', 'newUsers',
            'sapa', 'nama', 'tanggal'
        ));
    }
}
