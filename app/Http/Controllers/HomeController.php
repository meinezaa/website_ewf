<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
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
