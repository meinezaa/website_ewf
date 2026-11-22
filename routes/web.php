
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::view('/tentang-kami', 'tentang_kami')
    ->name('tentang.kami');

Route::view('/edukasi', 'edukasi')
    ->name('edukasi');

Route::view('/faq', 'faq')
    ->name('faq');

Route::get('/karir', [CareerApplicationController::class, 'index'])
    ->name('karir');

Route::middleware('guest')->group(function () {
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

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/foto', [ProfileController::class, 'updatePhoto'])
        ->middleware('throttle:10,1')
        ->name('profile.photo.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/karir/session', [CareerApplicationController::class, 'refreshApplicationSession'])
        ->middleware('throttle:6,1')
        ->name('karir.session.refresh');
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
