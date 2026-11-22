<?php

namespace App\Http\Controllers;

use App\Services\AnnouncementFeed;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use UnexpectedValueException;

class AnnouncementController extends Controller
{
    public function show(string $slug, AnnouncementFeed $announcementFeed): View
    {
        try {
            $announcement = $announcementFeed->findBySlug($slug);
        } catch (ConnectionException|RequestException|UnexpectedValueException $exception) {
            Log::warning('Unable to load Equityworld announcement.', [
                'slug' => $slug,
                'message' => $exception->getMessage(),
            ]);

            return view('pengumuman-detail', ['announcement' => null, 'error' => true]);
        }

        abort_if($announcement === null, 404);

        return view('pengumuman-detail', ['announcement' => $announcement, 'error' => false]);
    }
}
