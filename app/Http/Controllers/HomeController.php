<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            $roleName = mb_strtolower(trim((string) Auth::user()?->role?->role_name));

            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($roleName === 'hrd') {
                return redirect()->route('hrd.dashboard');
            }
        }

        $careerRegistration = null;

        if (Auth::check()) {
            $applicantId = Auth::id();

            if (Schema::hasTable('internship_registrations')) {
                $careerRegistration = InternshipRegistration::query()
                    ->where('applicant_id', $applicantId)
                    ->latest('id')
                    ->first();
            }

            if (! $careerRegistration && Schema::hasTable('staff_registrations')) {
                $careerRegistration = StaffRegistration::query()
                    ->where('applicant_id', $applicantId)
                    ->latest('id')
                    ->first();
            }
        }

        return view('user.home', compact('careerRegistration'));
    }
}
