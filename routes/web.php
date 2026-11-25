<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HrdDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/kegiatan', 'user.kegiatan')->name('kegiatan');
Route::view('/tentang-kami', 'user.tentang_kami')->name('tentang.kami');
Route::redirect('/edukasi', '/tentang-kami#pengembangan-kompetensi')->name('edukasi');
Route::view('/faq', 'user.faq')->name('faq');
Route::get('/karir', [CareerApplicationController::class, 'index'])->name('karir');
Route::get('/karir/lowongan/jumlah-pelamar', [CareerApplicationController::class, 'applicantCounts'])
    ->middleware('throttle:60,1')
    ->name('karir.applicant-counts');

Route::get('/hrd/login', [AuthController::class, 'showHrdLogin'])->name('hrd.login');
Route::post('/hrd/login', [AuthController::class, 'loginHrd'])
    ->middleware('throttle:6,1')
    ->name('hrd.login.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/lupa-password', [AuthController::class, 'showPasswordRequest'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendPasswordResetLink'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showPasswordReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('password.update');
    Route::get('/daftar', [AuthController::class, 'showRegistration'])->name('daftar');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('daftar.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/foto', [ProfileController::class, 'updatePhoto'])
        ->middleware('throttle:10,1')
        ->name('profile.photo.update');
    Route::post('/profil/foto-header', [ProfileController::class, 'updateCoverPhoto'])
        ->middleware('throttle:10,1')
        ->name('profile.cover-photo.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/karir/session', [CareerApplicationController::class, 'refreshApplicationSession'])
        ->middleware('throttle:30,1')
        ->name('karir.session.refresh');
    Route::get('/karir/pelacakan', [CareerApplicationController::class, 'trackingUpdates'])
        ->middleware('throttle:60,1')
        ->name('karir.tracking.updates');
    Route::post('/karir/lamaran/{type}/{application}/wawancara', [CareerApplicationController::class, 'confirmInterviewAttendance'])
        ->whereIn('type', ['staff', 'internship'])
        ->whereNumber('application')
        ->middleware('throttle:10,1')
        ->name('karir.interview.confirm');
    Route::get('/karir/dokumen-penyelesaian', [CareerApplicationController::class, 'documents'])
        ->name('karir.documents');
    Route::get('/karir/lamaran/{applicationId}/dokumen/{document}', [CareerApplicationController::class, 'downloadInternshipDocument'])
        ->whereNumber('applicationId')
        ->name('karir.application.document.download');
    Route::post('/karir/lamar/{selectedJobId?}', [CareerApplicationController::class, 'store'])
        ->where('selectedJobId', '[a-z]+-\d+')
        ->middleware('throttle:5,1')
        ->name('karir.apply.store');
});

Route::middleware(['auth', 'hrd'])->group(function (): void {
    Route::get('/hrd', [HrdDashboardController::class, 'index'])->name('hrd.dashboard');
    Route::get('/hrd/dashboard/snapshot', [HrdDashboardController::class, 'dashboardSnapshot'])
        ->middleware('throttle:60,1')
        ->name('hrd.dashboard.snapshot');
    Route::get('/hrd/export/{section}', [HrdDashboardController::class, 'export'])
        ->whereIn('section', ['dashboard', 'staff-applicants', 'intern-applicants', 'staff-data', 'intern-data'])
        ->name('hrd.export');
    Route::post('/hrd/applications/{type}/{application}/status', [HrdDashboardController::class, 'updateStatus'])
        ->whereIn('type', ['staff', 'internship'])
        ->whereNumber('application')
        ->name('hrd.application.status');
    Route::get('/hrd/applications/{type}/{application}/tracking', [HrdDashboardController::class, 'tracking'])
        ->whereIn('type', ['staff', 'internship'])
        ->whereNumber('application')
        ->name('hrd.application.tracking');
    Route::post('/hrd/applications/{type}/{application}/interview', [HrdDashboardController::class, 'scheduleInterview'])
        ->whereIn('type', ['staff', 'internship'])
        ->whereNumber('application')
        ->name('hrd.application.interview');
    Route::get('/hrd/applications/{type}/{application}/documents/{document}', [HrdDashboardController::class, 'downloadApplicationDocument'])
        ->whereIn('type', ['staff', 'internship'])
        ->whereNumber('application')
        ->whereIn('document', ['cv', 'transcript', 'cover_letter', 'portfolio', 'diploma', 'ktp'])
        ->name('hrd.application.document.download');
    Route::get('/hrd/applications/{type}/{application}/documents/{document}/preview', [HrdDashboardController::class, 'previewApplicationDocument'])
        ->whereIn('type', ['staff', 'internship'])
        ->whereNumber('application')
        ->whereIn('document', ['cv', 'transcript', 'cover_letter', 'portfolio', 'diploma', 'ktp'])
        ->name('hrd.application.document.preview');
    Route::post('/hrd/documents', [HrdDashboardController::class, 'storeIssuedDocument'])
        ->name('hrd.documents.store');
    Route::get('/hrd/internships/{application}/documents/{document}', [HrdDashboardController::class, 'downloadIssuedDocument'])
        ->whereNumber('application')
        ->whereIn('document', ['certificate', 'loa'])
        ->name('hrd.issued-document.download');
});