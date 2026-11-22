<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        return Auth::check()
            ? redirect()->route('profile')
            : view('register.masuk');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email', '')))]);
        $credentials = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', 'string'],
            'next' => ['nullable', 'string', 'max:255'],
        ]);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'status' => 'active',
        ])) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])
                ->withInput($request->only('email', 'next'));
        }

        $request->session()->regenerate();

        return redirect()->to($this->safeNext($credentials['next'] ?? null));
    }

    public function showPasswordRequest(): View|RedirectResponse
    {
        return Auth::check()
            ? redirect()->route('profile')
            : view('register.lupa-password');
    }

    public function sendPasswordResetLink(Request $request): RedirectResponse
    {
        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email', '')))]);
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $status = PasswordBroker::sendResetLink(['email' => $validated['email']]);
        $message = config('mail.default') === 'log'
            ? 'Mode email saat ini hanya mencatat pesan di log aplikasi dan tidak mengirim email. Jika email terdaftar, periksa log aplikasi untuk tautan reset.'
            : 'Jika alamat email terdaftar, tautan untuk mengatur ulang kata sandi akan dikirimkan.';

        $message = match ($status) {
            PasswordBroker::RESET_LINK_SENT,
            PasswordBroker::INVALID_USER,
            PasswordBroker::RESET_THROTTLED => $message,
            default => throw new \LogicException('Unexpected password reset link status.'),
        };

        return back()->with('status', $message);
    }

    public function showPasswordReset(Request $request, string $token): View|RedirectResponse
    {
        $email = $request->query('email', '');

        return Auth::check()
            ? redirect()->route('profile')
            : view('register.reset-password', [
                'token' => $token,
                'email' => is_string($email) ? $email : '',
            ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email', '')))]);
        $credentials = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $status = PasswordBroker::reset($credentials, function (User $user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        });

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('login')->with(
                'status',
                'Kata sandi berhasil diubah. Silakan masuk menggunakan kata sandi baru.'
            );
        }

        $message = match ($status) {
            PasswordBroker::INVALID_USER => 'Akun dengan alamat email tersebut tidak ditemukan.',
            PasswordBroker::INVALID_TOKEN => 'Tautan reset kata sandi tidak valid atau sudah kedaluwarsa. Minta tautan baru.',
            PasswordBroker::RESET_THROTTLED => 'Silakan tunggu sebelum mencoba kembali.',
            default => throw new \LogicException('Unexpected password reset status.'),
        };

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }

    public function showRegistration(): View|RedirectResponse
    {
        return Auth::check()
            ? redirect()->route('profile')
            : view('register.daftar');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email', '')))]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique(User::class, 'email')->whereNull('deleted_at'),
            ],
            'phone' => ['required', 'string', 'max:40'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'next' => ['nullable', 'string', 'max:255'],
        ]);

        $email = mb_strtolower($validated['email']);
        try {
            $user = DB::transaction(function () use ($validated, $email): User {
                $user = User::withTrashed()
                    ->where('email', $email)
                    ->lockForUpdate()
                    ->first();
                $applicantRole = Role::query()->firstOrCreate(['role_name' => 'Applicant']);
                $accountAttributes = [
                    'role_id' => $applicantRole->getKey(),
                    'name' => $validated['name'],
                    'email' => $email,
                    'phone' => $validated['phone'],
                    'password' => $validated['password'],
                    'status' => 'active',
                ];

                if ($user?->trashed()) {
                    $user->restore();
                    $user->fill($accountAttributes)->save();

                    return $user;
                }

                return User::create($accountAttributes);
            });
        } catch (QueryException $exception) {
            if (($exception->errorInfo[0] ?? null) !== '23000' && ($exception->errorInfo[1] ?? null) !== 1062) {
                throw $exception;
            }

            return back()
                ->withErrors(['email' => 'Email ini sudah terdaftar.'])
                ->withInput($request->only('name', 'email', 'phone', 'next'));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to($this->safeNext($validated['next'] ?? null));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function safeNext(?string $next): string
    {
        $parts = $next !== null ? parse_url($next) : false;
        $path = is_array($parts) ? ($parts['path'] ?? '') : '';
        $fragment = is_array($parts) ? ($parts['fragment'] ?? '') : '';
        $allowedPaths = ['/', '/tentang-kami', '/edukasi', '/faq', '/karir'];
        $allowedFragment = match ($path) {
            '/' => $fragment === '' || in_array($fragment, ['berita', 'produk', 'karir', 'edukasi'], true),
            '/tentang-kami' => $fragment === '' || in_array($fragment, ['profil', 'legalitas', 'regulasi', 'fasilitas'], true),
            '/edukasi' => $fragment === '' || in_array($fragment, ['mekanisme', 'indeks', 'lgd', 'jadwal'], true),
            '/faq' => $fragment === '',
            '/karir' => $this->isAllowedCareerFragment($fragment),
            default => false,
        };

        if (
            is_array($parts)
            && ! isset($parts['scheme'])
            && ! isset($parts['host'])
            && ! isset($parts['user'])
            && ! isset($parts['pass'])
            && in_array($path, $allowedPaths, true)
            && $allowedFragment
            && ! str_contains($next, '\\')
            && ! preg_match('/[\x00-\x1F\x7F]/', $next)
        ) {
            return url($next);
        }

        return route('profile');
    }

    private function isAllowedCareerFragment(string $fragment): bool
    {
        if (preg_match('#^apply/(?:professional-[0-2]|internship-[0-4])$#D', $fragment) === 1) {
            return true;
        }

        if (preg_match('#^apply/(internship-[0-4])\?(.+)$#D', $fragment, $matches) === 1) {
            parse_str($matches[2], $parameters);
            if (
                count($parameters) !== 2
                || array_diff(array_keys($parameters), ['major', 'field']) !== []
                || ! is_string($parameters['major'] ?? null)
                || ! is_string($parameters['field'] ?? null)
            ) {
                return false;
            }

            foreach (CareerApplicationController::internshipPrograms()[$parameters['major']] ?? [] as $program) {
                if ($program['job_id'] === $matches[1] && $program['label'] === $parameters['field']) {
                    return true;
                }
            }

            return false;
        }

        return $fragment === '' || preg_match(
            '#^(?:jobs(?:\?(?:category=[a-z-]+|type=(?:magang|profesional))(?:&(?:category=[a-z-]+|type=(?:magang|profesional)))?)?|job/(?:professional-[0-2]|internship-[0-4])|saved|lowongan|magang)$#D',
            $fragment
        ) === 1;
    }
}
