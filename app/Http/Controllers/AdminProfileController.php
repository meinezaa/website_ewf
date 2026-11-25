<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class AdminProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('admin.profile', [
            'profilePhotoUrl' => $request->user()->profilePhotoUrl(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        $user = $request->user();
        $user->update($validated);
        ActivityLog::create([
            'user_id' => $user->getKey(),
            'activity' => 'Memperbarui profil admin.',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $oldPhotoPath = $user->profile_photo_path;
        $newPhotoPath = $validated['profile_photo']->store('profile-photos/'.$user->getKey(), 'public');
        if (! is_string($newPhotoPath)) {
            throw new RuntimeException('Foto profil gagal disimpan.');
        }

        $user->update(['profile_photo_path' => $newPhotoPath]);
        ActivityLog::create([
            'user_id' => $user->getKey(),
            'activity' => 'Mengubah foto profil admin.',
            'ip_address' => $request->ip(),
        ]);
        if ($oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
            $disk = Storage::disk('public');
            if ($disk->exists($oldPhotoPath) && ! $disk->delete($oldPhotoPath)) {
                throw new RuntimeException('Foto profil sebelumnya gagal dihapus.');
            }
        }

        return response()->json(['photo_url' => $user->profilePhotoUrl()]);
    }
}
