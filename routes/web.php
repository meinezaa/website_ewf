
<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::view('/kegiatan', 'kegiatan')
    ->name('kegiatan');

Route::view('/produk', 'produk')
    ->name('produk');

Route::view('/live-quotes', 'live-quotes', [
    'products' => config('products'),
    'charts' => [
        [
            'title' => 'Emas (XAU/USD)',
            'symbol' => config('products.gol.quote_symbol'),
            'reference' => 'Harga emas spot',
        ],
        [
            'title' => 'Nikkei 225',
            'symbol' => config('products.jpk50.quote_symbol'),
            'reference' => 'Japan 225 CFD sebagai acuan indeks Nikkei 225',
        ],
        [
            'title' => 'Hang Seng',
            'symbol' => config('products.hkk50.quote_symbol'),
            'reference' => 'Indeks Hang Seng',
        ],
    ],
])->name('live-quotes');

Route::get('/produk/{product}/spesifikasi', function (string $product) {
    $details = config("products.{$product}");

    abort_if($details === null, 404);

    return view('produk-detail', ['product' => $details]);
})->name('produk.spesifikasi');

Route::get('/berita', [NewsController::class, 'index'])
    ->name('berita');

Route::get('/berita/{article}', [NewsController::class, 'show'])
    ->whereNumber('article')
    ->name('berita.detail');

Route::get('/pengumuman/{slug}', [AnnouncementController::class, 'show'])
    ->name('pengumuman.detail');
