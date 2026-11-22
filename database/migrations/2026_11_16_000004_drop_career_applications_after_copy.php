<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('career_applications')) {
            return;
        }

        foreach (DB::table('career_applications')->orderBy('id')->get() as $application) {
            $isInternship = str_starts_with($application->job_id, 'internship-');
            $table = $isInternship ? 'internship_registrations' : 'staff_registrations';
            $target = DB::table($table)
                ->whereRaw('BINARY reference = BINARY ?', [$application->reference])
                ->first();

            if ($target === null || ! $this->matches($application, $target, $isInternship)) {
                throw new RuntimeException(
                    'career_applications was kept because an application copy could not be verified.'
                );
            }
        }

        Schema::drop('career_applications');
    }

    public function down(): void
    {
        if (Schema::hasTable('career_applications')) {
            return;
        }

        Schema::create('career_applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference', 24)->nullable()->unique();
            $table->string('job_id', 64)->nullable()->index();
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 40)->nullable();
            $table->string('location')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 32)->nullable();
            $table->string('headline', 180)->nullable();
            $table->string('expected_salary', 40)->nullable();
            $table->string('availability', 100)->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('summary')->nullable();
            $table->timestamp('privacy_consent_at')->nullable();
            $table->string('registration_type', 80)->nullable();
            $table->string('target_position', 180)->nullable();
            $table->string('internship_start_period', 10)->nullable();
            $table->string('internship_duration', 40)->nullable();
            $table->string('school_internship_period', 100)->nullable();
            $table->boolean('cover_letter_required')->nullable();
            $table->string('internship_end_period', 10)->nullable();
            $table->json('experiences')->nullable();
            $table->json('education')->nullable();
            $table->json('skills')->nullable();
            $table->string('cv_path')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
        });

        foreach (['staff_registrations', 'internship_registrations'] as $table) {
            foreach (DB::table($table)->whereNotNull('reference')->orderBy('id')->get() as $application) {
                DB::table('career_applications')->insert($this->restoreApplication($application, $table));
            }
        }
    }

    private function matches(object $source, object $target, bool $isInternship): bool
    {
        $pairs = [
            ['user_id', 'applicant_id'],
            ['full_name', 'full_name'],
            ['email', 'email'],
            ['phone', 'phone'],
            ['location', 'location'],
            ['location', 'address'],
            ['date_of_birth', 'birth_date'],
            ['date_of_birth', 'date_of_birth'],
            ['gender', 'gender'],
            ['job_id', 'job_id'],
            ['job_title', 'job_title'],
            ['company', 'company'],
            ['headline', 'headline'],
            ['expected_salary', 'expected_salary'],
            ['availability', 'availability'],
            ['portfolio_url', 'portfolio_url'],
            ['summary', 'summary'],
            ['privacy_consent_at', 'privacy_consent_at'],
            ['cv_path', 'cv_path'],
            ['cv_path', 'document_path'],
            ['created_at', 'created_at'],
            ['updated_at', 'updated_at'],
        ];

        foreach ($pairs as [$sourceField, $targetField]) {
            if ((string) ($source->{$sourceField} ?? '') !== (string) ($target->{$targetField} ?? '')) {
                return false;
            }
        }

        foreach ([
            'experiences' => 'experiences',
            'education' => 'education_details',
            'skills' => 'skills',
            'documents' => 'documents',
        ] as $sourceField => $targetField) {
            if ($this->decodeArray($source->{$sourceField} ?? null) != $this->decodeArray($target->{$targetField} ?? null)) {
                return false;
            }
        }

        foreach (['internship_duration', 'school_internship_period', 'cover_letter_required'] as $unusedField) {
            if ($source->{$unusedField} !== null) {
                return false;
            }
        }

        if (! $isInternship) {
            $education = $this->decodeArray($source->education ?? null);
            $institution = $education[0]['institution'] ?? null;

            return ($target->position ?? null) === $source->job_title
                && ($target->education ?? null) === $institution;
        }

        $education = $this->decodeArray($source->education ?? null);

        return (string) ($source->registration_type ?? '') === (string) ($target->registration_type ?? '')
            && (string) ($source->target_position ?? '') === (string) ($target->target_position ?? '')
            && (string) ($source->internship_start_period ?? '') === (string) ($target->start_date ?? '')
            && (string) ($source->internship_end_period ?? '') === (string) ($target->end_date ?? '')
            && ($target->university ?? null) === ($education[0]['institution'] ?? null)
            && ($target->major ?? null) === ($education[0]['major'] ?? null);
    }

    private function restoreApplication(object $application, string $table): array
    {
        $isInternship = $table === 'internship_registrations';

        return [
            'user_id' => $application->applicant_id,
            'reference' => $application->reference,
            'job_id' => $application->job_id,
            'job_title' => $application->job_title,
            'company' => $application->company,
            'full_name' => $application->full_name,
            'email' => $application->email,
            'phone' => $application->phone,
            'location' => $application->location ?? $application->address,
            'date_of_birth' => $application->date_of_birth ?? $application->birth_date,
            'gender' => $application->gender,
            'headline' => $application->headline,
            'expected_salary' => $application->expected_salary,
            'availability' => $application->availability,
            'portfolio_url' => $application->portfolio_url,
            'summary' => $application->summary,
            'privacy_consent_at' => $application->privacy_consent_at,
            'registration_type' => $isInternship ? $application->registration_type : null,
            'target_position' => $isInternship ? $application->target_position : null,
            'internship_start_period' => $isInternship ? $application->start_date : null,
            'internship_duration' => null,
            'school_internship_period' => null,
            'cover_letter_required' => null,
            'internship_end_period' => $isInternship ? $application->end_date : null,
            'experiences' => $this->encodeArray($application->experiences),
            'education' => $this->encodeArray($application->education_details),
            'skills' => $this->encodeArray($application->skills),
            'cv_path' => $application->cv_path ?? $application->document_path,
            'documents' => $this->encodeArray($application->documents),
            'created_at' => $application->created_at,
            'updated_at' => $application->updated_at,
        ];
    }

    private function decodeArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function encodeArray(mixed $value): string
    {
        return json_encode($this->decodeArray($value), JSON_THROW_ON_ERROR);
    }
};
