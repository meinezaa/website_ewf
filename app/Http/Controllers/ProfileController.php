<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $email = $user->email;
        $applications = StaffRegistration::query()
            ->where('email', $email)
            ->get()
            ->concat(InternshipRegistration::query()->where('email', $email)->get())
            ->sortByDesc('created_at')
            ->values();

        return view('profile', [
            'applications' => $applications,
            'profilePhotoUrl' => $user->profilePhotoUrl(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'gender' => ['nullable', 'in:perempuan,laki-laki'],
            'location' => ['nullable', 'string', 'max:150'],
        ]);

        $user = $request->user();
        $user->update($validated);

        return back()->with('profile_updated', true);
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_photo.required' => 'Pilih foto profil terlebih dahulu.',
            'profile_photo.image' => 'File yang dipilih harus berupa gambar.',
            'profile_photo.mimes' => 'Pilih foto dengan format JPG, PNG, atau WebP.',
            'profile_photo.max' => 'Ukuran foto terlalu besar.',
        ]);

        $user = $request->user();
        $oldPhotoPath = $user->profile_photo_path;
        $newPhotoPath = $validated['profile_photo']->store('profile-photos/'.$user->getKey(), 'public');
        if (! is_string($newPhotoPath)) {
            throw new RuntimeException('Foto profil gagal disimpan.');
        }

        $user->update(['profile_photo_path' => $newPhotoPath]);
        if ($oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
            $publicDisk = Storage::disk('public');
            if ($publicDisk->exists($oldPhotoPath) && ! $publicDisk->delete($oldPhotoPath)) {
                throw new RuntimeException('Foto profil sebelumnya gagal dihapus.');
            }
        }

        return response()->json([
            'photo_url' => $user->profilePhotoUrl(),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $email = $user->email;
        $profilePhotoPath = $user->profile_photo_path;
        $applications = StaffRegistration::query()
            ->where('applicant_id', $user->getKey())
            ->orWhere('email', $email)
            ->get()
            ->concat(
                InternshipRegistration::query()
                    ->where('applicant_id', $user->getKey())
                    ->orWhere('email', $email)
                    ->get()
            );
        $paths = $applications
            ->flatMap(fn ($application): array => array_merge(
                [
                    $application->cv_path,
                    $application->document_path,
                    $application->certificate_path ?? null,
                    $application->loa_path ?? null,
                ],
                array_values($application->documents ?? [])
            ))
            ->filter(fn ($path): bool => is_string($path) && str_starts_with($path, 'career-applications/'))
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($user, $email): void {
            StaffRegistration::query()
                ->where('applicant_id', $user->getKey())
                ->orWhere('email', $email)
                ->delete();
            InternshipRegistration::query()
                ->where('applicant_id', $user->getKey())
                ->orWhere('email', $email)
                ->delete();
            $user->delete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Storage::disk('private')->delete($paths);
        if ($profilePhotoPath) {
            $publicDisk = Storage::disk('public');
            if ($publicDisk->exists($profilePhotoPath) && ! $publicDisk->delete($profilePhotoPath)) {
                throw new RuntimeException('Foto profil gagal dihapus.');
            }
        }

        return redirect()->route('home')->with('account_deleted', true);
    }
}
