<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use App\Models\ActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CareerApplicationController extends Controller
{
    private const JOBS = [
        'professional-0' => ['OB', 'PT Equityworld Futures'],
        'professional-1' => ['Resepsionis', 'PT Equityworld Futures'],
        'professional-2' => ['Asisten WPB', 'PT Equityworld Futures'],
        'professional-3' => ['Driver', 'PT Equityworld Futures'],
        'professional-4' => ['Staf', 'PT Equityworld Futures'],
        'internship-0' => ['Magang Administrasi', 'PT Equityworld Futures'],
        'internship-1' => ['Magang Marketing', 'PT Equityworld Futures'],
        'internship-2' => ['Magang IT (Teknologi Informasi)', 'PT Equityworld Futures'],
        'internship-3' => ['Magang Desain Grafis & Kreatif', 'PT Equityworld Futures'],
        'internship-4' => ['Magang Operasional', 'PT Equityworld Futures'],
    ];

    public static function internshipPrograms(): array
    {
        return [
            'Sistem Informasi' => [
                ['label' => 'Business Analyst', 'job_id' => 'internship-2'],
                ['label' => 'System Analyst', 'job_id' => 'internship-2'],
                ['label' => 'Fullstack Developer', 'job_id' => 'internship-2'],
                ['label' => 'UI/UX Designer', 'job_id' => 'internship-3'],
            ],
            'Administrasi Bisnis' => [
                ['label' => 'Admin & Operasional', 'job_id' => 'internship-0'],
                ['label' => 'HR Support', 'job_id' => 'internship-4'],
                ['label' => 'Business Development', 'job_id' => 'internship-1'],
            ],
            'Manajemen' => [
                ['label' => 'Manajemen Proyek', 'job_id' => 'internship-4'],
                ['label' => 'HRD', 'job_id' => 'internship-4'],
                ['label' => 'Operasional', 'job_id' => 'internship-4'],
            ],
            'Hubungan Internasional' => [
                ['label' => 'Hubungan Masyarakat', 'job_id' => 'internship-1'],
                ['label' => 'Riset Kebijakan', 'job_id' => 'internship-2'],
                ['label' => 'Kerja Sama Lembaga', 'job_id' => 'internship-1'],
            ],
            'Ilmu Komunikasi' => [
                ['label' => 'Public Relations', 'job_id' => 'internship-1'],
                ['label' => 'Social Media', 'job_id' => 'internship-3'],
                ['label' => 'Content Creator', 'job_id' => 'internship-3'],
            ],
            'Bisnis Digital' => [
                ['label' => 'Digital Marketing', 'job_id' => 'internship-1'],
                ['label' => 'E-Commerce', 'job_id' => 'internship-1'],
                ['label' => 'Growth Analyst', 'job_id' => 'internship-2'],
            ],
            'Informatika' => [
                ['label' => 'Backend Developer', 'job_id' => 'internship-2'],
                ['label' => 'Frontend Developer', 'job_id' => 'internship-2'],
                ['label' => 'Data Analyst', 'job_id' => 'internship-2'],
            ],
            'Akuntansi' => [
                ['label' => 'Staff Akuntansi', 'job_id' => 'internship-0'],
                ['label' => 'Pajak', 'job_id' => 'internship-0'],
                ['label' => 'Audit Internal', 'job_id' => 'internship-0'],
            ],
            'Pemasaran' => [
                ['label' => 'Marketing Communication', 'job_id' => 'internship-1'],
                ['label' => 'Sales & Partnership', 'job_id' => 'internship-1'],
                ['label' => 'Brand Strategy', 'job_id' => 'internship-3'],
            ],
        ];
    }

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $applications = $user ? $this->applicationsForEmail($user->email) : collect();

        if ($user && $this->completedInternshipApplications($user->email)->isNotEmpty()) {
            return redirect()->route('karir.documents');
        }

        $downloadableDocuments = $this->downloadableDocumentsFor($applications);

        $internshipPrograms = self::internshipPrograms();

        return view('karir', compact('applications', 'downloadableDocuments', 'internshipPrograms'));
    }

    public function documents(Request $request): View|RedirectResponse
    {
        $applications = $this->completedInternshipApplications($request->user()->email);
        if ($applications->isEmpty()) {
            return redirect()->route('karir');
        }

        $downloadableDocuments = $this->downloadableDocumentsFor($applications);

        $internshipPrograms = self::internshipPrograms();

        return view('karir', compact('applications', 'downloadableDocuments', 'internshipPrograms'));
    }

    public function refreshApplicationSession(Request $request): Response
    {
        $request->session()->put('_career_application_activity', now()->timestamp);

        return response()->noContent()->header('Cache-Control', 'no-store');
    }

    public function downloadInternshipDocument(Request $request, int $applicationId, string $document): StreamedResponse
    {
        abort_unless(in_array($document, ['certificate', 'loa'], true), 404);

        $application = InternshipRegistration::query()
            ->whereKey($applicationId)
            ->where('email', $request->user()->email)
            ->firstOrFail();
        abort_unless($application->isProgramCompleted(), 404);

        $isCertificate = $document === 'certificate';
        $path = $isCertificate ? $application->certificate_path : $application->loa_path;
        abort_unless($this->issuedPdfExists($path, $application->reference), 404);

        $filename = $isCertificate ? 'Sertifikat-Magang.pdf' : 'Letter-of-Acceptance-LOA.pdf';

        return Storage::disk('private')->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public static function announcementOpenings(): array
    {
        $openings = ['professional' => [], 'internship' => []];

        foreach (self::JOBS as $jobId => $job) {
            if (str_starts_with($jobId, 'professional-')) {
                $openings['professional'][] = $job[0];
            } elseif (str_starts_with($jobId, 'internship-')) {
                $openings['internship'][] = preg_replace('/^Magang\s+/i', '', $job[0]);
            }
        }

        return $openings;
    }

    public function store(Request $request, ?string $selectedJobId = null): RedirectResponse
    {
        $user = $request->user();
        if ($this->hasApplicationForEmail($user->email)) {
            return redirect()
                ->route('karir')
                ->with('application_already_submitted', true);
        }

        $jobId = $request->input('job_id');
        if (! in_array($jobId, array_keys(self::JOBS), true)) {
            $fallbackJobId = $selectedJobId ?? $request->query('selected_job_id');
            if (is_string($fallbackJobId) && array_key_exists($fallbackJobId, self::JOBS)) {
                $request->merge(['job_id' => $fallbackJobId]);
                $jobId = $fallbackJobId;
            }
        }
        $isInternship = is_string($jobId) && str_starts_with($jobId, 'internship-');
        $validator = Validator::make($request->all(), [
            'job_id' => ['required', 'string', 'in:'.implode(',', array_keys(self::JOBS))],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'location' => ['required', 'string', 'max:150'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['nullable', 'in:perempuan,laki-laki'],
            'headline' => ['nullable', 'string', 'max:180'],
            'expected_salary' => ['nullable', 'string', 'max:40'],
            'availability' => ['nullable', 'string', 'max:100'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'summary' => ['nullable', 'string', 'max:3000'],
            'experiences' => ['nullable', 'array', 'max:5'],
            'experiences.*.type' => ['nullable', 'in:Pengalaman magang/PKL,Organisasi,Kepanitiaan,Freelance,Projek akademik,Lainnya'],
            'experiences.*.company' => ['nullable', 'string', 'max:150'],
            'experiences.*.position' => ['nullable', 'string', 'max:150'],
            'experiences.*.period' => ['nullable', 'string', 'max:100'],
            'experiences.*.start_period' => ['nullable', 'date_format:Y-m'],
            'experiences.*.end_period' => ['nullable', 'date_format:Y-m'],
            'experiences.*.currently_working' => ['nullable', 'boolean'],
            'experiences.*.description' => ['nullable', 'string', 'max:1000'],
            'education' => $isInternship ? ['required', 'array', 'min:1', 'max:5'] : ['nullable', 'array', 'max:5'],
            'education.*.level' => $isInternship
                ? ['required', 'in:SMA,SMK,D1,D2,D3,D4,S1,S2,Lainnya']
                : ['nullable', 'in:SMA,SMK,D1,D2,D3,D4,S1,S2,Lainnya'],
            'education.*.institution' => $isInternship ? ['required', 'string', 'max:180'] : ['nullable', 'string', 'max:180'],
            'education.*.major' => $isInternship ? ['required', 'string', 'max:150'] : ['nullable', 'string', 'max:150'],
            'education.*.status' => $isInternship ? ['required', 'in:Sedang berlangsung,Sudah lulus'] : ['nullable', 'in:Sedang berlangsung,Sudah lulus'],
            'education.*.start_year' => $isInternship ? ['required', 'integer', 'min:1950', 'max:'.(now()->year + 1)] : ['nullable', 'integer', 'min:1950', 'max:'.(now()->year + 1)],
            'education.*.completion_year' => $isInternship ? ['required', 'integer', 'min:1950', 'max:'.(now()->year + 10)] : ['nullable', 'integer', 'min:1950', 'max:'.(now()->year + 10)],
            'education.*.year' => ['nullable', 'string', 'max:20'],
            'education.*.gpa' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'registration_type' => $isInternship
                ? ['required', 'in:Magang / Internship,Praktik Kerja Lapangan (PKL)']
                : ['nullable', 'in:Magang / Internship,Praktik Kerja Lapangan (PKL)'],
            'internship_major' => $isInternship ? ['required', 'string', 'max:150'] : ['nullable', 'string', 'max:150'],
            'target_position' => $isInternship ? ['required', 'string', 'max:180'] : ['nullable', 'string', 'max:180'],
            'internship_start_period' => $isInternship ? ['required', 'date_format:Y-m-d'] : ['nullable', 'date_format:Y-m-d'],
            'internship_end_period' => $isInternship ? ['required', 'date_format:Y-m-d'] : ['nullable', 'date_format:Y-m-d'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'cv' => $isInternship ? ['required', 'file', 'mimes:pdf', 'max:5120'] : ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.transcript' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'documents.cover_letter' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'documents.portfolio' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'documents.diploma' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'documents.ktp' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'documents.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'privacy_consent' => ['accepted'],
        ]);
        $validator->after(function ($validator) use ($request, $isInternship, $jobId): void {
            $start = $request->input('internship_start_period');
            $end = $request->input('internship_end_period');

            if ($isInternship && is_string($start) && is_string($end) && $end < $start) {
                $validator->errors()->add('internship_end_period', 'Periode selesai harus sama atau setelah periode awal.');
            }

            if ($isInternship) {
                $selectedMajor = $request->input('internship_major');
                $selectedField = $request->input('target_position');
                $selectedProgram = is_string($selectedMajor)
                    ? (self::internshipPrograms()[$selectedMajor] ?? [])
                    : [];
                $validField = collect($selectedProgram)->first(
                    fn (array $field): bool => is_string($selectedField)
                        && $field['label'] === $selectedField
                        && $field['job_id'] === $jobId
                );

                if ($validField === null) {
                    $validator->errors()->add('target_position', 'Pilih jurusan dan bidang magang yang tersedia.');
                }
            }
        });
        if ($validator->fails()) {
            $jobId = in_array($request->input('job_id'), array_keys(self::JOBS), true)
                ? $request->input('job_id')
                : 'professional-0';
            $applicationFragment = '#apply/'.$jobId;
            if ($isInternship && filled($request->input('internship_major')) && filled($request->input('target_position'))) {
                $applicationFragment .= '?'.http_build_query([
                    'major' => $request->input('internship_major'),
                    'field' => $request->input('target_position'),
                ]);
            }

            return redirect()
                ->to(route('karir').$applicationFragment)
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();

        $validated['email'] = $user->email;
        [$jobTitle, $company] = self::JOBS[$validated['job_id']];
        if ($isInternship) {
            $jobTitle = $validated['target_position'];
        }
        $reference = 'EWF-APP-'.Str::upper(Str::random(10));
        $storedPaths = [];

        try {
            $application = $this->withEmailLock($validated['email'], function () use (
                $request,
                $validated,
                $user,
                $jobTitle,
                $company,
                $reference,
                $isInternship,
                &$storedPaths
            ): StaffRegistration|InternshipRegistration|null {
                if ($this->hasApplicationForEmail($validated['email'])) {
                    return null;
                }

                return DB::transaction(function () use (
                    $request,
                    $validated,
                    $user,
                    $jobTitle,
                    $company,
                    $reference,
                    $isInternship,
                    &$storedPaths
                ): StaffRegistration|InternshipRegistration {
                    $education = $this->filledRows($validated['education'] ?? [], 'institution', 'major');
                    $experiences = $this->filledRows($validated['experiences'] ?? [], 'company', 'position', 'institution');
                    $firstEducation = $education[0] ?? [];
                    $databaseUserId = $user->exists
                        && DB::table('users')->where('id', $user->getKey())->exists()
                        ? $user->getKey()
                        : null;
                    $attributes = [
                        'applicant_id' => $databaseUserId,
                        'reviewed_by' => null,
                        'full_name' => $validated['full_name'],
                        'email' => $validated['email'],
                        'birth_date' => $validated['date_of_birth'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'gender' => $validated['gender'] ?? null,
                        'address' => $validated['location'],
                        'location' => $validated['location'],
                        'phone' => $validated['phone'],
                        'education' => $firstEducation['institution'] ?? null,
                        'position' => $isInternship ? $validated['target_position'] : $jobTitle,
                        'document_path' => 'pending',
                        'status' => 'pending',
                        'reference' => $reference,
                        'job_id' => $validated['job_id'],
                        'job_title' => $jobTitle,
                        'company' => $company,
                        'headline' => $validated['headline'] ?? ($isInternship ? 'Pelamar magang' : ''),
                        'expected_salary' => $validated['expected_salary'] ?? null,
                        'availability' => $validated['availability'] ?? null,
                        'portfolio_url' => $validated['portfolio_url'] ?? null,
                        'summary' => $validated['summary'] ?? ($isInternship
                            ? sprintf('%s untuk jurusan %s pada bidang %s, periode %s sampai %s.',
                                $validated['registration_type'],
                                $validated['internship_major'],
                                $validated['target_position'],
                                $validated['internship_start_period'],
                                $validated['internship_end_period'])
                            : ''),
                        'privacy_consent_at' => now(),
                        'experiences' => $experiences,
                        'education_details' => $education,
                        'skills' => array_values(array_filter(array_map('trim', explode(',', $validated['skills'] ?? '')))),
                        'cv_path' => 'pending',
                        'documents' => [],
                    ];

                    if ($isInternship) {
                        $attributes['university'] = $firstEducation['institution'];
                        $attributes['major'] = $firstEducation['major'];
                        $attributes['semester'] = null;
                        $attributes['start_date'] = $validated['internship_start_period'];
                        $attributes['end_date'] = $validated['internship_end_period'];
                        $attributes['registration_type'] = $validated['registration_type'];
                        $attributes['internship_major'] = $validated['internship_major'];
                        $attributes['target_position'] = $validated['target_position'];
                        $application = InternshipRegistration::create($attributes);
                    } else {
                        $application = StaffRegistration::create($attributes);
                    }

                    $cvPath = $request->file('cv')->store('career-applications/'.$reference.'/cv', 'private');
                    if (! $cvPath) {
                        throw new RuntimeException('CV could not be stored.');
                    }
                    $storedPaths[] = $cvPath;

                    $documentPaths = [];
                    foreach ($request->file('documents', []) as $documentType => $document) {
                        if ($document === null) {
                            continue;
                        }
                        $path = $document->store('career-applications/'.$reference.'/documents', 'private');
                        if (! $path) {
                            throw new RuntimeException('An application document could not be stored.');
                        }
                        $storedPaths[] = $path;
                        $documentPaths[$documentType] = $path;
                    }

                    $application->update([
                        'cv_path' => $cvPath,
                        'document_path' => $cvPath,
                        'documents' => $documentPaths,
                    ]);

                    return $application;
                });
            });

            if ($application === null) {
                return redirect()
                    ->route('karir')
                    ->with('application_already_submitted', true);
            }
        } catch (QueryException $exception) {
            if ($storedPaths !== []) {
                Storage::disk('private')->delete($storedPaths);
            }

            if (($exception->errorInfo[1] ?? null) === 1062) {
                return redirect()
                    ->route('karir')
                    ->with('application_already_submitted', true);
            }

            throw $exception;
        } catch (Throwable $exception) {
            if ($storedPaths !== []) {
                Storage::disk('private')->delete($storedPaths);
            }

            throw $exception;
        }

        ActivityLog::create([
            'user_id' => $request->user()->getKey(),
            'activity' => 'Mengirim lamaran untuk posisi '.$application->job_title.'.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('karir')
            ->with('application_submitted', true)
            ->with('application_reference', $application->reference);
    }

    private function filledRows(array $rows, string ...$requiredFields): array
    {
        return array_values(array_filter($rows, function ($row) use ($requiredFields): bool {
            if (! is_array($row)) {
                return false;
            }

            foreach ($requiredFields as $field) {
                if (filled($row[$field] ?? null)) {
                    return true;
                }
            }

            return false;
        }));
    }

    private function applicationsForEmail(string $email): Collection
    {
        return StaffRegistration::query()
            ->where('email', $email)
            ->get()
            ->concat(InternshipRegistration::query()->where('email', $email)->get())
            ->sortByDesc('created_at')
            ->values();
    }

    private function completedInternshipApplications(string $email): Collection
    {
        return $this->applicationsForEmail($email)
            ->filter(fn ($application) => $application instanceof InternshipRegistration && $application->isProgramCompleted())
            ->values();
    }

    private function downloadableDocumentsFor(Collection $applications): array
    {
        $downloadableDocuments = [];

        foreach ($applications as $application) {
            if ($application instanceof InternshipRegistration) {
                $downloadableDocuments[$application->getKey()] = [
                    'certificate' => $this->issuedPdfExists($application->certificate_path, $application->reference),
                    'loa' => $this->issuedPdfExists($application->loa_path, $application->reference),
                ];
            }
        }

        return $downloadableDocuments;
    }

    private function issuedPdfExists(?string $path, string $reference): bool
    {
        $issuedDirectory = 'career-applications/'.$reference.'/issued/';

        return is_string($path)
            && dirname($path) === rtrim($issuedDirectory, '/')
            && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf'
            && Storage::disk('private')->exists($path);
    }

    private function hasApplicationForEmail(string $email): bool
    {
        return StaffRegistration::query()->where('email', $email)->exists()
            || InternshipRegistration::query()->where('email', $email)->exists();
    }

    private function withEmailLock(string $email, callable $callback): mixed
    {
        $connection = DB::connection();
        if ($connection->getDriverName() !== 'mysql') {
            return $callback();
        }

        $lockName = 'ewf-career-'.substr(hash('sha256', mb_strtolower($email)), 0, 48);
        $lock = $connection->selectOne('SELECT GET_LOCK(?, 10) AS acquired', [$lockName]);
        if ((int) ($lock->acquired ?? 0) !== 1) {
            throw new RuntimeException('Could not lock this applicant email for submission.');
        }

        try {
            return $callback();
        } finally {
            $connection->selectOne('SELECT RELEASE_LOCK(?) AS released', [$lockName]);
        }
    }
}
