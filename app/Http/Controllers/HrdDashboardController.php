<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ApplicationTrackingEvent;
use App\Models\Certificate;
use App\Models\CareerOpening;
use App\Models\InternshipRegistration;
use App\Models\StaffRegistration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class HrdDashboardController extends Controller
{
    private const DISPLAY_TIMEZONE = 'Asia/Jakarta';

    private const MAGANG_REGISTRATION_TYPES = ['magang / internship', 'magang', 'internship'];

    private const PKL_REGISTRATION_TYPES = ['praktik kerja lapangan (pkl)', 'praktik kerja lapangan', 'pkl'];

    private const TRACKED_STATUSES = ['pending', 'in_review', 'accepted', 'completed', 'rejected'];

    private const PREVIEWABLE_DOCUMENT_MIME_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];

    private const STATUS_LABELS = [
        'pending' => 'Perlu ditinjau',
        'in_review' => 'Dalam seleksi',
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai Magang',
    ];

    public function index(Request $request): View
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $request->validate([
            'staff_q' => ['nullable', 'string', 'max:100'],
            'staff_status' => ['nullable', Rule::in(['pending', 'in_review', 'accepted', 'rejected'])],
            'internship_q' => ['nullable', 'string', 'max:100'],
            'internship_status' => ['nullable', Rule::in(['pending', 'in_review', 'accepted', 'rejected', 'completed'])],
            'staff_data_q' => ['nullable', 'string', 'max:100'],
            'internship_data_q' => ['nullable', 'string', 'max:100'],
            'internship_data_status' => ['nullable', Rule::in(['accepted', 'completed'])],
        ]);
        $dashboardStatusData = $this->dashboardStatusData($now);
        $staffStatusCounts = $dashboardStatusData['staff_status_counts'];
        $internshipStatusCounts = $dashboardStatusData['internship_status_counts'];
        $statusStats = $dashboardStatusData['stats'];

        $trendData = $this->trendData($now);
        $careerOpenings = CareerOpening::query()->orderByDesc('created_at')->get();
        $staffApplicants = $this->mapPaginator(
            $this->applyApplicationFilters(StaffRegistration::query(), $request, 'staff', 'staff')
                ->latest('created_at')
                ->paginate(10, ['*'], 'staff_page')
                ->withQueryString(),
            'staff'
        );
        $internshipApplicants = $this->mapPaginator(
            $this->applyApplicationFilters(
                InternshipRegistration::query(),
                $request,
                'internship',
                'internship',
                ['pending', 'in_review', 'accepted', 'rejected', 'completed']
            )
                ->latest('created_at')
                ->paginate(10, ['*'], 'internship_page')
                ->withQueryString(),
            'internship'
        );
        $staffMembers = $this->mapPaginator(
            $this->applyApplicationFilters(
                StaffRegistration::query()->where('status', 'accepted'),
                $request,
                'staff_data',
                'staff'
            )
                ->latest('created_at')
                ->paginate(10, ['*'], 'staff_data_page')
                ->withQueryString(),
            'staff'
        );
        $internshipMembers = $this->mapPaginator(
            $this->applyApplicationFilters(
                InternshipRegistration::query()->whereIn('status', ['accepted', 'completed']),
                $request,
                'internship_data',
                'internship',
                ['accepted', 'completed']
            )
                ->latest('created_at')
                ->paginate(10, ['*'], 'internship_data_page')
                ->withQueryString(),
            'internship'
        );

        $staffSummary = [
            'total' => $statusStats['staff_total'],
            'pending' => (int) $staffStatusCounts->get('pending', 0),
            'in_review' => (int) $staffStatusCounts->get('in_review', 0),
            'accepted_month' => StaffRegistration::query()
                ->where('status', 'accepted')
                ->where('created_at', '>=', $monthStart)
                ->count(),
            'accepted' => (int) $staffStatusCounts->get('accepted', 0),
            'positions' => StaffRegistration::query()
                ->where('status', 'accepted')
                ->whereNotNull('position')
                ->distinct()
                ->count('position'),
            'locations' => StaffRegistration::query()
                ->where('status', 'accepted')
                ->whereNotNull('location')
                ->distinct()
                ->count('location'),
        ];
        $internshipSummary = [
            'total' => $statusStats['internship_total'],
            'pending' => (int) $internshipStatusCounts->get('pending', 0),
            'in_review' => (int) $internshipStatusCounts->get('in_review', 0),
            'accepted_month' => InternshipRegistration::query()
                ->where('status', 'accepted')
                ->where('created_at', '>=', $monthStart)
                ->count(),
            'active' => InternshipRegistration::query()
                ->where('status', 'accepted')
                ->where(function (Builder $query) use ($now): void {
                    $query->whereNull('end_date')->orWhereDate('end_date', '>=', $now->toDateString());
                })
                ->count(),
            'fields' => InternshipRegistration::query()
                ->whereIn('status', ['accepted', 'completed'])
                ->whereNotNull('target_position')
                ->distinct()
                ->count('target_position'),
            'ending_soon' => InternshipRegistration::query()
                ->where('status', 'accepted')
                ->whereBetween('end_date', [$now->toDateString(), $now->copy()->addDays(30)->toDateString()])
                ->count(),
            'completed' => (int) $internshipStatusCounts->get('completed', 0),
        ];
        $internshipSummary['completion_rate'] = $this->percentage(
            $internshipSummary['completed'],
            $internshipSummary['completed'] + (int) $internshipStatusCounts->get('accepted', 0)
        );

        $recentApplications = $this->recentApplications();

        $eligibleInternships = InternshipRegistration::query()
            ->with('certificate')
            ->whereIn('status', ['accepted', 'completed'])
            ->latest('created_at')
            ->get();
        $documentCandidates = $eligibleInternships->map(function (InternshipRegistration $application): array {
            $school = trim((string) ($application->university ?: $application->major ?: ''));

            return $this->applicationData($application, 'internship') + [
                'program_completed' => $application->isProgramCompleted(),
                'university' => $school,
                'school_key' => $school !== '' ? mb_strtolower($school) : 'registration:'.$application->getKey(),
                'school_label' => $school !== '' ? $school : 'Sekolah belum tercatat — '.$application->full_name,
                'faculty' => (string) ($application->faculty ?? ''),
                'study_program' => (string) ($application->internship_major ?: $application->major ?: ''),
                'internship_field' => (string) ($application->target_position ?: $application->job_title ?: ''),
                'period_start' => $application->start_date?->toDateString() ?? '',
                'period_end' => $application->end_date?->toDateString() ?? '',
            ];
        });
        $loaSchools = $documentCandidates
            ->map(fn (array $candidate): array => [
                'key' => $candidate['school_key'],
                'name' => $candidate['university'],
                'label' => $candidate['school_label'],
            ])
            ->unique('key')
            ->values();

        $documentRows = [];
        $seenLoaDocuments = [];
        $certificateCount = 0;
        $loaCount = 0;
        $pendingDocumentCount = 0;
        foreach ($eligibleInternships as $application) {
            $hasGeneratedCertificate = $application->isProgramCompleted()
                && is_array($application->certificate_details)
                && $application->certificate_details !== [];
            $certificatePath = $application->isProgramCompleted() && ! $hasGeneratedCertificate
                ? ($application->certificate_path ?: $application->certificate?->file_path)
                : null;
            $loaPath = in_array($application->status, ['accepted', 'completed'], true)
                ? $application->loa_path
                : null;
            $hasGeneratedLoa = in_array($application->status, ['accepted', 'completed'], true)
                && is_array($application->loa_details)
                && $application->loa_details !== [];
            $hasIssuedDocument = false;

            foreach ([
                'certificate' => ['Sertifikat Magang', $certificatePath, $hasGeneratedCertificate],
                'loa' => ['Surat Penerimaan (LoA)', $loaPath, $hasGeneratedLoa],
            ] as $type => [$label, $path, $hasGeneratedDocument]) {
                if (! $this->privateFileExists($path) && ! $hasGeneratedDocument) {
                    continue;
                }

                $loaNumber = $type === 'loa' && $hasGeneratedLoa
                    ? ($application->loa_details['letter_number'] ?? null)
                    : null;
                if ($loaNumber !== null && isset($seenLoaDocuments[$loaNumber])) {
                    $hasIssuedDocument = true;
                    continue;
                }

                $hasIssuedDocument = true;
                $documentApplication = $this->applicationData($application, 'internship');
                if ($type === 'loa' && count($application->loa_details['participants'] ?? []) > 1) {
                    $documentApplication['name'] = collect($application->loa_details['participants'])
                        ->pluck('full_name')
                        ->filter()
                        ->implode(', ');
                }
                $documentRows[] = [
                    'application' => $documentApplication,
                    'type' => $type,
                    'label' => $label,
                    'path' => $path,
                    'generated' => $hasGeneratedDocument,
                    'filename' => $path
                        ? basename($path)
                        : ($type === 'certificate' ? 'Sertifikat digital' : 'LoA digital'),
                    'url' => route('hrd.issued-document.download', [$application->getKey(), $type]),
                ];
                if ($loaNumber !== null) {
                    $seenLoaDocuments[$loaNumber] = true;
                }
                $type === 'certificate' ? $certificateCount++ : $loaCount++;
            }

            if (! $hasIssuedDocument) {
                $pendingDocumentCount++;
            }
        }

        return view('hrd.layout', [
            'hrdUser' => auth()->user(),
            'statusStats' => $statusStats,
            'categoryStatusRows' => $dashboardStatusData['category_status_rows'],
            'trendData' => $trendData,
            'careerOpenings' => $careerOpenings,
            'staffApplicants' => $staffApplicants,
            'internshipApplicants' => $internshipApplicants,
            'staffMembers' => $staffMembers,
            'internshipMembers' => $internshipMembers,
            'recentApplications' => $recentApplications,
            'staffSummary' => $staffSummary,
            'internshipSummary' => $internshipSummary,
            'documentCandidates' => $documentCandidates,
            'loaSchools' => $loaSchools,
            'documentRows' => collect($documentRows),
            'documentSummary' => [
                'total' => $certificateCount + $loaCount,
                'pending' => $pendingDocumentCount,
                'certificates' => $certificateCount,
                'loas' => $loaCount,
            ],
        ]);
    }

    public function dashboardSnapshot(): JsonResponse
    {
        $now = now();
        $statusData = $this->dashboardStatusData($now);

        return response()
            ->json([
                'stats' => $statusData['stats'],
                'category_status_rows' => $statusData['category_status_rows'],
                'trend_data' => $this->trendData($now),
                'recent_applications' => $this->recentApplications(),
            ])
            ->header('Cache-Control', 'no-store, private');
    }

    public function updateStatus(Request $request, string $type, int $application): JsonResponse
    {
        abort_unless(in_array($type, ['staff', 'internship'], true), 404);
        $allowedStatuses = $type === 'staff'
            ? ['pending', 'in_review', 'accepted', 'rejected']
            : ['pending', 'in_review', 'accepted', 'rejected', 'completed'];
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in($allowedStatuses)],
        ]);
        $model = $this->applicationQuery($type)->findOrFail($application);
        $previousStatus = (string) ($model->status ?: 'pending');
        if (
            $type === 'internship'
            && $validated['status'] === 'completed'
            && ! in_array($model->status, ['accepted', 'completed'], true)
        ) {
            throw ValidationException::withMessages([
                'status' => 'Peserta harus berstatus diterima sebelum program dapat ditandai selesai.',
            ]);
        }

        DB::transaction(function () use ($model, $validated, $request, $type, $previousStatus): void {
            $model->status = $validated['status'];
            if (Schema::hasColumn($model->getTable(), 'reviewed_by')) {
                $model->reviewed_by = $request->user()->getKey();
            }
            $model->save();
            if ($previousStatus !== $validated['status']) {
                ApplicationTrackingEvent::query()->create([
                    'application_type' => $type,
                    'application_id' => $model->getKey(),
                    'event_type' => 'status_changed',
                    'from_status' => $previousStatus,
                    'to_status' => $validated['status'],
                    'actor_id' => $request->user()->getKey(),
                ]);
            }
            $this->logActivity(
                $request,
                sprintf('Status pendaftaran %s #%d diperbarui menjadi %s.', $type, $model->getKey(), $validated['status'])
            );
        });

        return response()->json([
            'status' => $validated['status'],
            'label' => self::STATUS_LABELS[$validated['status']],
            'status_class' => $this->statusClass($validated['status']),
            'updated_at' => $model->fresh()->updated_at?->toIso8601String(),
        ]);
    }

    public function tracking(string $type, int $application): JsonResponse
    {
        abort_unless(in_array($type, ['staff', 'internship'], true), 404);
        $model = $this->applicationQuery($type)->findOrFail($application);

        return response()
            ->json($this->trackingPayload($model, $type))
            ->header('Cache-Control', 'no-store, private');
    }

    public function scheduleInterview(Request $request, string $type, int $application): JsonResponse
    {
        abort_unless(in_array($type, ['staff', 'internship'], true), 404);
        $validated = $request->validate([
            'interview_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'interview_information' => ['required', 'string', 'max:2000'],
        ]);
        $model = $this->applicationQuery($type)->findOrFail($application);
        $interviewAt = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $validated['interview_at'],
            self::DISPLAY_TIMEZONE
        );
        if ($interviewAt->lessThanOrEqualTo(now()->timezone(self::DISPLAY_TIMEZONE))) {
            throw ValidationException::withMessages([
                'interview_at' => 'Jadwal wawancara harus ditetapkan pada waktu mendatang.',
            ]);
        }
        $interviewAt->utc();
        $scheduleChanged = ! $model->interview_at
            || $model->interview_at->toDateTimeString() !== $interviewAt->toDateTimeString()
            || $model->interview_information !== $validated['interview_information'];

        DB::transaction(function () use ($model, $validated, $interviewAt, $scheduleChanged, $request, $type): void {
            $model->interview_at = $interviewAt;
            $model->interview_information = $validated['interview_information'];
            if ($scheduleChanged) {
                $model->interview_attendance = null;
                $model->interview_attendance_reason = null;
                $model->interview_confirmed_at = null;
            }
            $model->save();

            ApplicationTrackingEvent::query()->create([
                'application_type' => $type,
                'application_id' => $model->getKey(),
                'event_type' => 'interview_scheduled',
                'interview_at' => $interviewAt,
                'interview_information' => $validated['interview_information'],
                'actor_id' => $request->user()->getKey(),
            ]);

            $this->logActivity(
                $request,
                sprintf('Jadwal wawancara pendaftaran %s #%d diperbarui.', $type, $model->getKey())
            );
        });

        $model->refresh();

        return response()
            ->json($this->trackingPayload($model, $type))
            ->header('Cache-Control', 'no-store, private');
    }

    public function export(Request $request, string $section): StreamedResponse
    {
        abort_unless(in_array($section, [
            'dashboard',
            'staff-applicants',
            'intern-applicants',
            'staff-data',
            'intern-data',
        ], true), 404);
        $request->validate([
            'staff_q' => ['nullable', 'string', 'max:100'],
            'staff_status' => ['nullable', Rule::in(['pending', 'in_review', 'accepted', 'rejected'])],
            'internship_q' => ['nullable', 'string', 'max:100'],
            'internship_status' => ['nullable', Rule::in(['pending', 'in_review', 'accepted', 'rejected', 'completed'])],
            'staff_data_q' => ['nullable', 'string', 'max:100'],
            'internship_data_q' => ['nullable', 'string', 'max:100'],
            'internship_data_status' => ['nullable', Rule::in(['accepted', 'completed'])],
        ]);

        $applications = match ($section) {
            'staff-applicants' => $this->applyApplicationFilters(
                StaffRegistration::query(),
                $request,
                'staff',
                'staff'
            )->latest('created_at')->get(),
            'intern-applicants' => $this->applyApplicationFilters(
                InternshipRegistration::query(),
                $request,
                'internship',
                'internship',
                ['pending', 'in_review', 'accepted', 'rejected', 'completed']
            )->latest('created_at')->get(),
            'staff-data' => $this->applyApplicationFilters(
                StaffRegistration::query()->where('status', 'accepted'),
                $request,
                'staff_data',
                'staff'
            )->latest('created_at')->get(),
            'intern-data' => $this->applyApplicationFilters(
                InternshipRegistration::query()->whereIn('status', ['accepted', 'completed']),
                $request,
                'internship_data',
                'internship',
                ['accepted', 'completed']
            )->latest('created_at')->get(),
            default => StaffRegistration::query()
                ->latest('created_at')
                ->get()
                ->concat(InternshipRegistration::query()->latest('created_at')->get()),
        };
        $type = str_starts_with($section, 'intern') || $section === 'intern-data'
            ? 'internship'
            : 'staff';
        $rows = $section === 'dashboard'
            ? $applications
                ->map(fn (Model $application): array => $this->exportRow(
                    $application,
                    $application instanceof InternshipRegistration ? 'internship' : 'staff'
                ))
                ->sortByDesc('sort')
                ->values()
            : $applications->map(fn (Model $application): array => $this->exportRow($application, $type))->values();

        return response()->streamDownload(function () use ($rows): void {
            $stream = fopen('php://output', 'wb');
            fputcsv($stream, ['Jenis', 'Nama', 'Email', 'Telepon', 'Posisi / Program', 'Pendidikan / Institusi', 'Lokasi', 'Periode', 'Tanggal daftar', 'Status']);
            foreach ($rows as $row) {
                fputcsv($stream, $row['values']);
            }
            fclose($stream);
        }, 'laporan-hrd-'.$section.'-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function downloadApplicationDocument(string $type, int $application, string $document): StreamedResponse
    {
        $path = $this->applicationDocumentPath($type, $application, $document);

        return Storage::disk('private')->download($path, $this->documentFilename($path, $document));
    }

    public function previewApplicationDocument(string $type, int $application, string $document): StreamedResponse
    {
        $path = $this->applicationDocumentPath($type, $application, $document);
        $mimeType = Storage::disk('private')->mimeType($path);
        abort_unless(in_array($mimeType, self::PREVIEWABLE_DOCUMENT_MIME_TYPES, true), 415);

        return Storage::disk('private')->response(
            $path,
            $this->documentFilename($path, $document),
            [
                'Cache-Control' => 'private, no-store',
                'Content-Security-Policy' => "sandbox; default-src 'none'",
                'Content-Type' => $mimeType,
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }

    public function storeIssuedDocument(Request $request): RedirectResponse
    {
        $type = $request->input('document_type');
        $generateCertificate = $type === 'certificate' && ! $request->hasFile('file');
        $validator = Validator::make($request->all(), [
            'internship_registration_id' => [Rule::requiredIf($type === 'certificate'), 'nullable', 'integer', 'exists:internship_registrations,id'],
            'document_type' => ['required', 'string', Rule::in(['certificate', 'loa'])],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'certificate_period_start' => [Rule::requiredIf($generateCertificate), 'nullable', 'date'],
            'certificate_period_end' => [Rule::requiredIf($generateCertificate), 'nullable', 'date', 'after_or_equal:certificate_period_start'],
            'certificate_issued_at' => [Rule::requiredIf($generateCertificate), 'nullable', 'date'],
            'certificate_signer_title' => [Rule::requiredIf($generateCertificate), 'nullable', 'string', 'max:120'],
            'certificate_signer_name' => [Rule::requiredIf($generateCertificate), 'nullable', 'string', 'max:150'],
            'participants' => [Rule::requiredIf($type === 'loa'), 'nullable', 'array', 'min:1', 'max:5'],
            'participants.*.internship_registration_id' => [Rule::requiredIf($type === 'loa'), 'integer', 'distinct', 'exists:internship_registrations,id'],
            'participants.*.student_number' => [Rule::requiredIf($type === 'loa'), 'string', 'max:50'],
            'participants.*.full_name' => [Rule::requiredIf($type === 'loa'), 'string', 'max:180'],
            'participants.*.study_program' => [Rule::requiredIf($type === 'loa'), 'string', 'max:180'],
            'school_name' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:180'],
            'faculty' => ['nullable', 'string', 'max:180'],
            'internship_field' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:180'],
            'company_name' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:180'],
            'company_location' => ['nullable', 'string', 'max:150'],
            'letter_city' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:150'],
            'period_start' => [Rule::requiredIf($type === 'loa'), 'nullable', 'date'],
            'period_end' => [Rule::requiredIf($type === 'loa'), 'nullable', 'date', 'after_or_equal:period_start'],
            'work_days' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:100'],
            'work_hours' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:100'],
            'letter_date' => [Rule::requiredIf($type === 'loa'), 'nullable', 'date'],
            'letter_body' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:700'],
            'signer_title' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:120'],
            'signer_name' => [Rule::requiredIf($type === 'loa'), 'nullable', 'string', 'max:150'],
        ]);
        if ($validator->fails()) {
            return redirect(route('hrd.dashboard').'#documents')
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        $type = $validated['document_type'];

        if ($type === 'loa') {
            $participantRegistrations = InternshipRegistration::query()
                ->whereIn('id', collect($validated['participants'])->pluck('internship_registration_id'))
                ->get()
                ->keyBy('id');
            $participants = collect($validated['participants'])->map(function (array $participant) use ($participantRegistrations): array {
                $registrationId = (int) $participant['internship_registration_id'];
                $registration = $participantRegistrations->get($registrationId);
                if (! $registration) {
                    throw ValidationException::withMessages([
                        'participants' => 'Data peserta yang dipilih tidak lagi tersedia. Silakan pilih ulang.',
                    ]);
                }

                return [
                    'registration_id' => $registrationId,
                    'student_number' => trim($participant['student_number']),
                    'full_name' => $registration->full_name,
                    'study_program' => (string) ($registration->internship_major ?: $registration->major ?: $participant['study_program']),
                ];
            })->values()->all();
            $registeredSchool = trim((string) (
                $participantRegistrations->first()?->university
                ?: $participantRegistrations->first()?->major
                ?: ''
            ));
            if (count($participants) > 1) {
                $participantSchools = $participantRegistrations
                    ->map(fn (InternshipRegistration $participant): string => mb_strtolower(
                        trim((string) ($participant->university ?: $participant->major ?: ''))
                    ))
                    ->unique()
                    ->values();
                $selectedSchool = mb_strtolower(trim($validated['school_name']));

                if (
                    $participantSchools->count() !== 1
                    || $participantSchools->first() === ''
                    || $participantSchools->first() !== $selectedSchool
                ) {
                    return redirect(route('hrd.dashboard').'#documents')
                        ->withErrors([
                            'participants' => 'Peserta dalam satu LoA harus berasal dari sekolah atau perguruan tinggi yang sama.',
                        ])
                        ->withInput();
                }
            }
            $details = [
                'letter_number' => 'EWF/LOA/'.now()->format('Ymd').'/'.Str::upper(Str::random(5)),
                'participants' => $participants,
                'school_name' => count($participants) > 1 ? $registeredSchool : trim($validated['school_name']),
                'faculty' => trim((string) ($validated['faculty'] ?? '')),
                'internship_field' => trim($validated['internship_field']),
                'company_name' => trim($validated['company_name']),
                'company_location' => trim((string) ($validated['company_location'] ?? '')),
                'letter_city' => trim($validated['letter_city']),
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'work_days' => trim($validated['work_days']),
                'work_hours' => trim($validated['work_hours']),
                'letter_date' => $validated['letter_date'],
                'letter_body' => trim($validated['letter_body']),
                'signer_title' => trim($validated['signer_title']),
                'signer_name' => trim($validated['signer_name']),
            ];

            $previousPaths = DB::transaction(function () use ($participants, $details, $request): array {
                $previousPaths = [];
                foreach ($participants as $participant) {
                    $current = InternshipRegistration::query()
                        ->lockForUpdate()
                        ->findOrFail($participant['registration_id']);
                    if (! $this->isEligibleForIssuedDocument($current, 'loa')) {
                        throw new RuntimeException('Semua peserta harus sudah diterima sebelum LoA diterbitkan.');
                    }

                    if ($current->loa_path) {
                        $previousPaths[] = $current->loa_path;
                    }
                    $current->loa_details = $details;
                    $current->loa_path = null;
                    $current->save();
                    $this->logActivity(
                        $request,
                        sprintf('LoA dibuat untuk peserta magang %s.', $participant['full_name'])
                    );
                }

                return array_values(array_unique($previousPaths));
            });

            foreach ($previousPaths as $previousPath) {
                if (Storage::disk('private')->exists($previousPath)) {
                    Storage::disk('private')->delete($previousPath);
                }
            }

            return redirect(route('hrd.dashboard').'#documents')
                ->with('hrd_document_saved', true);
        }

        $application = InternshipRegistration::query()
            ->findOrFail($validated['internship_registration_id']);
        if (! $this->isEligibleForIssuedDocument($application, $type)) {
            return redirect(route('hrd.dashboard').'#documents')
                ->withErrors(['document_type' => 'Sertifikat hanya dapat diterbitkan setelah program peserta selesai.'])
                ->withInput();
        }
        if ($type === 'certificate' && ! isset($validated['file'])) {
            return $this->storeGeneratedCertificate($application, $validated, $request);
        }
        if ($type === 'certificate') {
            $application->load('certificate');
        }

        $reference = $application->reference ?: 'EWF-APP-'.Str::upper(Str::random(10));
        $path = $request->file('file')->storeAs(
            'career-applications/'.$reference.'/issued',
            $type.'-'.Str::lower(Str::random(16)).'.pdf',
            'private'
        );
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Dokumen HRD gagal disimpan ke penyimpanan privat.');
        }

        try {
            $previousPaths = DB::transaction(function () use ($application, $reference, $path, $type, $request): array {
                $current = InternshipRegistration::query()
                    ->lockForUpdate()
                    ->findOrFail($application->getKey());
                if (! $this->isEligibleForIssuedDocument($current, $type)) {
                    throw new RuntimeException(
                        $type === 'loa'
                            ? 'Pendaftaran peserta harus disetujui sebelum LoA diterbitkan.'
                            : 'Program peserta harus selesai sebelum sertifikat diterbitkan.'
                    );
                }
                if ($type === 'certificate') {
                    $current->load('certificate');
                }

                $previous = $type === 'certificate'
                    ? array_filter([$current->certificate_path, $current->certificate?->file_path])
                    : array_filter([$current->loa_path]);
                $current->reference = $reference;
                if ($type === 'certificate') {
                    $current->certificate_path = $path;
                    $current->certificate_details = null;
                    $certificate = Certificate::query()->firstOrNew([
                        'internship_registration_id' => $current->getKey(),
                    ]);
                    $certificate->fill([
                        'certificate_number' => $certificate->certificate_number
                            ?: 'EWF-CERT-'.Str::upper(Str::random(10)),
                        'file_path' => $path,
                        'issued_by' => $request->user()->getKey(),
                        'issued_at' => today(),
                    ]);
                    $certificate->save();
                } else {
                    $current->loa_path = $path;
                }
                $current->save();
                $this->logActivity(
                    $request,
                    sprintf('Dokumen %s diterbitkan untuk pendaftaran magang #%d.', $type, $current->getKey())
                );

                return array_values(array_unique($previous));
            });
        } catch (Throwable $exception) {
            Storage::disk('private')->delete($path);
            throw $exception;
        }

        foreach ($previousPaths as $previousPath) {
            if (
                $previousPath !== $path
                && Storage::disk('private')->exists($previousPath)
                && ! Storage::disk('private')->delete($previousPath)
            ) {
                throw new RuntimeException('Dokumen lama gagal dihapus setelah penggantian dokumen.');
            }
        }

        return redirect(route('hrd.dashboard').'#documents')
            ->with('hrd_document_saved', true);
    }

    private function storeGeneratedCertificate(
        InternshipRegistration $application,
        array $validated,
        Request $request
    ): RedirectResponse {
        $certificateNumber = 'EWF-CERT-'.Str::upper(Str::random(10));
        $previousPaths = DB::transaction(function () use ($application, $validated, $certificateNumber, $request): array {
            $current = InternshipRegistration::query()
                ->lockForUpdate()
                ->findOrFail($application->getKey());
            if (! $this->isEligibleForIssuedDocument($current, 'certificate')) {
                throw new RuntimeException('Program peserta harus selesai sebelum sertifikat diterbitkan.');
            }

            $previousPaths = array_filter([$current->certificate_path]);
            if (Schema::hasTable('certificates')) {
                $current->load('certificate');
                if ($current->certificate) {
                    $previousPaths[] = $current->certificate->file_path;
                    $current->certificate->delete();
                }
            }

            $current->certificate_details = [
                'certificate_number' => $certificateNumber,
                'full_name' => $current->full_name,
                'company_name' => 'PT EQUITYWORLD FUTURES',
                'period_start' => $validated['certificate_period_start'],
                'period_end' => $validated['certificate_period_end'],
                'issued_at' => $validated['certificate_issued_at'],
                'signer_title' => trim($validated['certificate_signer_title']),
                'signer_name' => trim($validated['certificate_signer_name']),
            ];
            $current->certificate_path = null;
            $current->save();
            $this->logActivity(
                $request,
                sprintf('Sertifikat magang dibuat untuk peserta %s.', $current->full_name)
            );

            return array_values(array_unique($previousPaths));
        });

        foreach ($previousPaths as $previousPath) {
            if (
                Storage::disk('private')->exists($previousPath)
                && ! Storage::disk('private')->delete($previousPath)
            ) {
                throw new RuntimeException('Sertifikat lama gagal dihapus setelah penggantian sertifikat.');
            }
        }

        return redirect(route('hrd.dashboard').'#documents')
            ->with('hrd_document_saved', true);
    }

    public function downloadIssuedDocument(int $application, string $document): StreamedResponse|View
    {
        abort_unless(in_array($document, ['certificate', 'loa'], true), 404);
        $record = InternshipRegistration::query()->findOrFail($application);
        abort_unless($this->isEligibleForIssuedDocument($record, $document), 404);
        if (
            $document === 'loa'
            && is_array($record->loa_details)
            && $record->loa_details !== []
        ) {
            return view('documents.loa', [
                'details' => $record->loa_details,
                'backUrl' => route('hrd.dashboard').'#documents',
            ]);
        }
        if (
            $document === 'certificate'
            && is_array($record->certificate_details)
            && $record->certificate_details !== []
        ) {
            return view('documents.certificate', [
                'details' => $record->certificate_details,
                'backUrl' => route('hrd.dashboard').'#documents',
            ]);
        }
        if ($document === 'certificate') {
            $record->load('certificate');
        }
        $path = $document === 'certificate'
            ? ($record->certificate_path ?: $record->certificate?->file_path)
            : $record->loa_path;

        abort_unless($this->privateFileExists($path), 404);

        $filename = $document === 'certificate'
            ? 'Sertifikat-Magang.pdf'
            : 'Letter-of-Acceptance-LOA.pdf';

        return Storage::disk('private')->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function applicationQuery(string $type): Builder
    {
        return $type === 'staff'
            ? StaffRegistration::query()
            : InternshipRegistration::query();
    }

    private function statusCounts(Builder $query): Collection
    {
        $counts = $query
            ->selectRaw("COALESCE(status, 'pending') as status, COUNT(*) as aggregate")
            ->groupByRaw("COALESCE(status, 'pending')")
            ->pluck('aggregate', 'status');
        foreach ($counts->keys() as $status) {
            if (! in_array($status, self::TRACKED_STATUSES, true)) {
                $counts->put('pending', (int) $counts->get('pending', 0) + (int) $counts->get($status, 0));
                $counts->forget($status);
            }
        }

        return $counts;
    }

    private function dashboardStatusData(Carbon $now): array
    {
        $staffStatusCounts = $this->statusCounts(StaffRegistration::query());
        $internshipStatusCounts = collect(array_fill_keys(self::TRACKED_STATUSES, 0));
        $categoryCounts = [
            'magang' => array_fill_keys(self::TRACKED_STATUSES, 0),
            'pkl' => array_fill_keys(self::TRACKED_STATUSES, 0),
            'unclassified' => array_fill_keys(self::TRACKED_STATUSES, 0),
        ];
        $internshipStatusRows = DB::table('internship_registrations')
            ->select('registration_type', 'status', 'end_date')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('registration_type', 'status', 'end_date')
            ->get();

        foreach ($internshipStatusRows as $row) {
            $category = $this->internshipCategoryKey($row->registration_type);
            $status = $row->status ?? 'pending';
            if ($status === 'completed'
                || ($status === 'accepted'
                    && $row->end_date !== null
                    && $row->end_date < $now->toDateString())) {
                $status = 'completed';
            }
            $status = in_array($status, self::TRACKED_STATUSES, true) ? $status : 'pending';
            $aggregate = (int) $row->aggregate;
            $categoryCounts[$category][$status] += $aggregate;
            $internshipStatusCounts->put(
                $status,
                (int) $internshipStatusCounts->get($status, 0) + $aggregate
            );
        }

        $categoryStatusRows = [
            $this->statusRow('staff', 'Staf', $staffStatusCounts),
            $this->statusRow('magang', 'Magang', collect($categoryCounts['magang'])),
            $this->statusRow('pkl', 'PKL', collect($categoryCounts['pkl'])),
            $this->statusRow('unclassified', 'Magang/PKL belum dikategorikan', collect($categoryCounts['unclassified'])),
        ];
        $staffTotal = (int) $staffStatusCounts->sum();
        $magangTotal = array_sum($categoryCounts['magang']);
        $pklTotal = array_sum($categoryCounts['pkl']);
        $unclassifiedTotal = array_sum($categoryCounts['unclassified']);
        $total = $staffTotal + (int) $internshipStatusCounts->sum();
        $staffMonth = StaffRegistration::query()->where('created_at', '>=', $now->copy()->startOfMonth())->count();
        $magangMonth = $this->internshipCategoryQuery('magang')
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();
        $pklMonth = $this->internshipCategoryQuery('pkl')
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();
        $unclassifiedMonth = $this->internshipCategoryQuery('unclassified')
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();
        $statusStats = [
            'staff_pending' => (int) $staffStatusCounts->get('pending', 0),
            'internship_pending' => (int) $internshipStatusCounts->get('pending', 0),
            'pending' => (int) $staffStatusCounts->get('pending', 0) + (int) $internshipStatusCounts->get('pending', 0),
            'in_review' => (int) $staffStatusCounts->get('in_review', 0) + (int) $internshipStatusCounts->get('in_review', 0),
            'accepted' => (int) $staffStatusCounts->get('accepted', 0)
                + (int) $internshipStatusCounts->get('accepted', 0),
            'rejected' => (int) $staffStatusCounts->get('rejected', 0) + (int) $internshipStatusCounts->get('rejected', 0),
            'completed' => (int) $staffStatusCounts->get('completed', 0) + (int) $internshipStatusCounts->get('completed', 0),
            'total' => $total,
            'total_month' => $staffMonth + $magangMonth + $pklMonth + $unclassifiedMonth,
            'staff_total' => $staffTotal,
            'magang_total' => $magangTotal,
            'magang_completed' => (int) $categoryCounts['magang']['completed'],
            'pkl_total' => $pklTotal,
            'pkl_completed' => (int) $categoryCounts['pkl']['completed'],
            'unclassified_internship_total' => $unclassifiedTotal,
            'internship_total' => $magangTotal + $pklTotal + $unclassifiedTotal,
            'staff_month' => $staffMonth,
            'magang_month' => $magangMonth,
            'pkl_month' => $pklMonth,
            'internship_month' => $magangMonth + $pklMonth + $unclassifiedMonth,
            'unclassified_internship_month' => $unclassifiedMonth,
        ];
        $statusStats['pending_percent'] = $this->percentage($statusStats['pending'], $total);
        $statusStats['in_review_percent'] = $this->percentage($statusStats['in_review'], $total);
        $statusStats['accepted_percent'] = $this->percentage($statusStats['accepted'], $total);
        $statusStats['completed_percent'] = $this->percentage($statusStats['completed'], $total);
        $statusStats['rejected_percent'] = $this->percentage($statusStats['rejected'], $total);

        return [
            'staff_status_counts' => $staffStatusCounts,
            'internship_status_counts' => $internshipStatusCounts,
            'stats' => $statusStats,
            'category_status_rows' => $categoryStatusRows,
        ];
    }

    private function statusRow(string $key, string $label, Collection $counts): array
    {
        $row = [
            'key' => $key,
            'label' => $label,
            'total' => (int) $counts->sum(),
        ];
        foreach (self::TRACKED_STATUSES as $status) {
            $row[$status] = (int) $counts->get($status, 0);
        }

        return $row;
    }

    private function internshipCategoryQuery(string $category): Builder
    {
        return match ($category) {
            'magang' => InternshipRegistration::query()->whereRaw(
                'LOWER(TRIM(COALESCE(registration_type, \'\'))) IN ('.implode(',', array_fill(0, count(self::MAGANG_REGISTRATION_TYPES), '?')).')',
                self::MAGANG_REGISTRATION_TYPES
            ),
            'pkl' => InternshipRegistration::query()->whereRaw(
                'LOWER(TRIM(COALESCE(registration_type, \'\'))) IN ('.implode(',', array_fill(0, count(self::PKL_REGISTRATION_TYPES), '?')).')',
                self::PKL_REGISTRATION_TYPES
            ),
            default => InternshipRegistration::query()->where(function (Builder $query): void {
                $knownTypes = array_merge(self::MAGANG_REGISTRATION_TYPES, self::PKL_REGISTRATION_TYPES);
                $query->whereNull('registration_type')
                    ->orWhereRaw("TRIM(registration_type) = ''")
                    ->orWhereRaw(
                        'LOWER(TRIM(registration_type)) NOT IN ('.implode(',', array_fill(0, count($knownTypes), '?')).')',
                        $knownTypes
                    );
            }),
        };
    }

    private function internshipCategoryKey(?string $registrationType): string
    {
        $normalized = mb_strtolower(trim((string) $registrationType));
        if (in_array($normalized, self::MAGANG_REGISTRATION_TYPES, true)) {
            return 'magang';
        }
        if (in_array($normalized, self::PKL_REGISTRATION_TYPES, true)) {
            return 'pkl';
        }

        return 'unclassified';
    }

    private function applyApplicationFilters(
        Builder $query,
        Request $request,
        string $prefix,
        string $type,
        array $allowedStatuses = ['pending', 'in_review', 'accepted', 'rejected']
    ): Builder {
        $status = $request->query($prefix.'_status');
        if (is_string($status) && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        }

        $search = trim((string) $request->query($prefix.'_q', ''));
        if ($search === '') {
            return $query;
        }

        $searchableColumns = $type === 'internship'
            ? ['full_name', 'email', 'target_position', 'job_title', 'university', 'major', 'internship_major', 'location']
            : ['full_name', 'email', 'position', 'job_title', 'education', 'location'];

        return $query->where(function (Builder $searchQuery) use ($searchableColumns, $search): void {
            foreach ($searchableColumns as $column) {
                $searchQuery->orWhere($column, 'like', '%'.$search.'%');
            }
        });
    }

    private function trendData($now): array
    {
        $firstMonth = $now->copy()->startOfMonth()->subMonths(23);
        $staffCounts = StaffRegistration::query()
            ->where('created_at', '>=', $firstMonth)
            ->get(['created_at'])
            ->groupBy(fn (StaffRegistration $application): string => $application->created_at->format('Y-m'))
            ->map(fn (Collection $applications): int => $applications->count());
        $internshipCounts = InternshipRegistration::query()
            ->where('created_at', '>=', $firstMonth)
            ->get(['created_at', 'registration_type'])
            ->groupBy(fn (InternshipRegistration $application): string => $application->created_at->format('Y-m'))
            ->map(function (Collection $applications): array {
                $counts = ['magang' => 0, 'pkl' => 0, 'unclassified' => 0];
                foreach ($applications as $application) {
                    $counts[$this->internshipCategoryKey($application->registration_type)]++;
                }

                return $counts;
            });

        return collect(range(23, 0))
            ->map(function (int $monthsAgo) use ($now, $staffCounts, $internshipCounts): array {
                $month = $now->copy()->startOfMonth()->subMonths($monthsAgo);
                $key = $month->format('Y-m');
                $internshipMonth = $internshipCounts->get($key, []);

                return [
                    'key' => $key,
                    'label' => $month->translatedFormat('M'),
                    'staff' => (int) $staffCounts->get($key, 0),
                    'magang' => (int) ($internshipMonth['magang'] ?? 0),
                    'pkl' => (int) ($internshipMonth['pkl'] ?? 0),
                    'unclassified' => (int) ($internshipMonth['unclassified'] ?? 0),
                ];
            })
            ->all();
    }

    private function recentApplications(): Collection
    {
        return StaffRegistration::query()
            ->with('applicant:id,profile_photo_path')
            ->latest('created_at')
            ->limit(8)
            ->get()
            ->map(fn (StaffRegistration $application): array => $this->applicationData($application, 'staff') + [
                'photo_url' => $application->applicant?->profilePhotoUrl(),
            ])
            ->concat(
                InternshipRegistration::query()
                    ->with('applicant:id,profile_photo_path')
                    ->latest('created_at')
                    ->limit(8)
                    ->get()
                    ->map(fn (InternshipRegistration $application): array => $this->applicationData($application, 'internship') + [
                        'photo_url' => $application->applicant?->profilePhotoUrl(),
                    ])
            )
            ->sortByDesc('created_at_sort')
            ->take(8)
            ->values();
    }

    private function mapPaginator(LengthAwarePaginator $paginator, string $type): LengthAwarePaginator
    {
        $applications = $paginator->getCollection();
        $applications->load('applicant:id,profile_photo_path');
        $paginator->setCollection(
            $applications->map(fn (Model $application): array => $this->applicationData($application, $type) + [
                'photo_url' => $application->applicant?->profilePhotoUrl(),
            ])
        );

        return $paginator;
    }

    private function applicationData(Model $application, string $type, bool $includeDocuments = true): array
    {
        $isInternship = $type === 'internship';
        $status = (string) ($application->status ?: 'pending');
        $createdAt = $application->created_at;
        $startDate = $isInternship ? $application->start_date : null;
        $endDate = $isInternship ? $application->end_date : null;
        $position = $isInternship
            ? ($application->target_position ?: $application->job_title ?: $application->major ?: 'Program Magang')
            : ($application->job_title ?: $application->position ?: 'Posisi Staf');
        $organization = $isInternship
            ? ($application->university ?: $application->major ?: 'Belum dicantumkan')
            : ($application->education ?: 'Belum dicantumkan');
        $location = $application->location ?: $application->address ?: 'Belum dicantumkan';
        $documents = $includeDocuments ? $this->applicationDocuments($application, $type) : [];
        $typeLabel = $isInternship
            ? match ($this->internshipCategoryKey($application->registration_type)) {
                'magang' => 'Magang',
                'pkl' => 'PKL',
                default => 'Magang/PKL belum dikategorikan',
            }
            : 'Staf';

        return [
            'id' => (int) $application->getKey(),
            'type' => $type,
            'type_label' => $typeLabel,
            'name' => (string) $application->full_name,
            'email' => (string) $application->email,
            'phone' => (string) ($application->phone ?: 'Belum dicantumkan'),
            'position' => (string) $position,
            'organization' => (string) $organization,
            'education' => (string) ($application->education ?: 'Belum dicantumkan'),
            'location' => (string) $location,
            'period' => $startDate && $endDate
                ? $startDate->translatedFormat('d M Y').' – '.$endDate->translatedFormat('d M Y')
                : ($startDate ? $startDate->translatedFormat('d M Y') : 'Belum dicantumkan'),
            'joined' => $createdAt ? $createdAt->translatedFormat('d M Y') : 'Belum dicantumkan',
            'created_at_sort' => $createdAt?->timestamp ?? 0,
            'status' => $status,
            'status_label' => self::STATUS_LABELS[$status] ?? Str::headline($status),
            'status_class' => $this->statusClass($status),
            'initials' => collect(preg_split('/\s+/', trim((string) $application->full_name)) ?: [])
                ->filter()
                ->take(2)
                ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode(''),
            'documents' => $documents,
            'status_url' => route('hrd.application.status', [$type, $application->getKey()]),
            'tracking_url' => route('hrd.application.tracking', [$type, $application->getKey()]),
            'interview_url' => route('hrd.application.interview', [$type, $application->getKey()]),
        ];
    }

    private function trackingPayload(Model $application, string $type): array
    {
        $events = ApplicationTrackingEvent::query()
            ->where('application_type', $type)
            ->where('application_id', $application->getKey())
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return [
            'id' => (int) $application->getKey(),
            'type' => $type,
            'status' => (string) ($application->status ?: 'pending'),
            'status_label' => self::STATUS_LABELS[$application->status ?: 'pending'] ?? Str::headline((string) $application->status),
            'created_at' => $application->created_at?->toIso8601String(),
            'updated_at' => $application->updated_at?->toIso8601String(),
            'interview_at' => $application->interview_at?->toIso8601String(),
            'interview_at_local' => $application->interview_at?->timezone(self::DISPLAY_TIMEZONE)->format('Y-m-d\TH:i'),
            'interview_information' => $application->interview_information,
            'interview_attendance' => $application->interview_attendance,
            'interview_attendance_reason' => $application->interview_attendance_reason,
            'interview_confirmed_at' => $application->interview_confirmed_at?->toIso8601String(),
            'events' => $events->map(fn (ApplicationTrackingEvent $event): array => [
                'event_type' => $event->event_type,
                'from_status' => $event->from_status,
                'to_status' => $event->to_status,
                'interview_at' => $event->interview_at?->toIso8601String(),
                'interview_information' => $event->interview_information,
                'attendance' => $event->attendance,
                'attendance_reason' => $event->attendance_reason,
                'created_at' => $event->created_at?->toIso8601String(),
            ])->all(),
        ];
    }

    private function exportRow(Model $application, string $type): array
    {
        $data = $this->applicationData($application, $type, false);

        return [
            'sort' => $data['created_at_sort'],
            'values' => [
                $data['type_label'],
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['position'],
                $data['organization'],
                $data['location'],
                $data['period'],
                $data['joined'],
                $data['status_label'],
            ],
        ];
    }

    private function applicationDocuments(Model $application, string $type): array
    {
        $documents = [];
        $mainPath = $application->cv_path ?: $application->document_path;
        if ($this->privateFileExists($mainPath)) {
            $preview = $this->previewableDocument($mainPath, $type, $application, 'cv');
            $documents[] = [
                'label' => 'CV / Dokumen utama',
                'filename' => basename($mainPath),
                'url' => route('hrd.application.document.download', [$type, $application->getKey(), 'cv']),
                'preview_url' => $preview['url'] ?? null,
                'preview_mime_type' => $preview['mime_type'] ?? null,
            ];
        }

        foreach ((array) $application->documents as $key => $path) {
            if (! in_array($key, ['transcript', 'cover_letter', 'portfolio', 'diploma', 'ktp'], true)) {
                continue;
            }
            if (is_array($path)) {
                $path = $path['path'] ?? null;
            }
            if (! $this->privateFileExists($path)) {
                continue;
            }

            $preview = $this->previewableDocument($path, $type, $application, $key);
            $documents[] = [
                'label' => Str::headline(str_replace('_', ' ', $key)),
                'filename' => basename($path),
                'url' => route('hrd.application.document.download', [$type, $application->getKey(), $key]),
                'preview_url' => $preview['url'] ?? null,
                'preview_mime_type' => $preview['mime_type'] ?? null,
            ];
        }

        return $documents;
    }

    private function previewableDocument(
        string $path,
        string $type,
        Model $application,
        string $document
    ): ?array {
        $mimeType = Storage::disk('private')->mimeType($path);
        if (! in_array($mimeType, self::PREVIEWABLE_DOCUMENT_MIME_TYPES, true)) {
            return null;
        }

        return [
            'url' => route('hrd.application.document.preview', [$type, $application->getKey(), $document]),
            'mime_type' => $mimeType,
        ];
    }

    private function privateFileExists(mixed $path): bool
    {
        return is_string($path)
            && Str::startsWith($path, 'career-applications/')
            && ! str_contains($path, '..')
            && Storage::disk('private')->exists($path);
    }

    private function applicationDocumentPath(string $type, int $application, string $document): string
    {
        abort_unless(in_array($type, ['staff', 'internship'], true), 404);
        abort_unless(in_array($document, ['cv', 'transcript', 'cover_letter', 'portfolio', 'diploma', 'ktp'], true), 404);

        $record = $this->applicationQuery($type)->findOrFail($application);
        $path = $document === 'cv'
            ? ($record->cv_path ?: $record->document_path)
            : data_get($record->documents, $document);

        if (! $this->privateFileExists($path)) {
            abort(404);
        }

        return $path;
    }

    private function isEligibleForIssuedDocument(InternshipRegistration $application, string $document): bool
    {
        return $document === 'loa'
            ? in_array($application->status, ['accepted', 'completed'], true)
            : $application->isProgramCompleted();
    }

    private function documentFilename(string $path, string $document): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return Str::slug($document).($extension !== '' ? '.'.$extension : '');
    }

    private function statusClass(string $status): string
    {
        return match ($status) {
            'in_review' => 'badge-process',
            'accepted', 'completed' => 'badge-accepted',
            'rejected' => 'badge-rejected',
            default => 'badge-review',
        };
    }

    private function percentage(int $value, int $total): float
    {
        return $total > 0 ? round($value / $total * 100, 1) : 0.0;
    }

    private function logActivity(Request $request, string $activity): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        ActivityLog::query()->create([
            'user_id' => $request->user()->getKey(),
            'activity' => $activity,
            'ip_address' => $request->ip(),
        ]);
    }
}
