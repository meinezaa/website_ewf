<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Reset kata sandi

Fitur **Lupa kata sandi** mengirim tautan reset melalui mailer Laravel. Token reset berlaku 60 menit dan permintaan tautan dibatasi untuk mencegah penyalahgunaan. `MAIL_MAILER=log` hanya mencatat email di `storage/logs/laravel.log`.

Untuk mengirim dari Gmail pribadi, aktifkan verifikasi 2 langkah akun Google, buat App Password, lalu isi konfigurasi berikut di `.env` lokal. Jangan commit atau bagikan App Password:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=alamat-gmail-anda@gmail.com
MAIL_PASSWORD=app-password-google
MAIL_FROM_ADDRESS=alamat-gmail-anda@gmail.com
MAIL_FROM_NAME="Galeri Magang"
```

Alternatif tanpa SMTP adalah Resend API, tetapi memerlukan API key dan domain pengirim yang Anda miliki serta verifikasi DNS. Untuk kedua cara, `APP_URL` harus alamat publik aplikasi yang dapat dibuka penerima; `localhost` dan `127.0.0.1` tidak dapat dibuka dari ponsel. Setelah mengubah `.env`, jalankan `php artisan config:clear`, mulai ulang server, lalu minta tautan reset lagi.

## Akun pelamar

Akun login dan pendaftaran pelamar menggunakan satu baris akun di tabel MySQL `users`. Saat memperbarui aplikasi dari penyimpanan akun JSON lama, jalankan:

```powershell
php artisan config:clear
php artisan migrate --force
php artisan storage:link
php artisan auth:import-file-users --dry-run
php artisan auth:import-file-users
```

Simpan `storage/app/private/auth-users.json` sebagai cadangan sampai proses impor berhasil diverifikasi. Penghapusan pendaftaran di `staff_registrations` atau `internship_registrations` menonaktifkan akun terkait; pemilik harus mendaftar kembali. Penghapusan akun dari halaman Profil juga menghapus lamaran dan dokumen terkait.

Pendaftaran magang berstatus `pending` yang sudah ada saat migrasi 18 November 2026 dijalankan akan ditandai selesai untuk menampilkan halaman dokumen penyelesaian. Pendaftaran magang baru tetap mengikuti status normal. Tombol unduh sertifikat dan LOA baru aktif setelah PDF tersedia.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
