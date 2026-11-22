<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $staffTableCreated = $this->createStaffRegistrationsTable();
        $internshipTableCreated = $this->createInternshipRegistrationsTable();

        $this->prepareApplicantColumn('staff_registrations', $staffTableCreated);
        $this->prepareApplicantColumn('internship_registrations', $internshipTableCreated);
        $this->addApplicationColumns('staff_registrations', false, $staffTableCreated);
        $this->addApplicationColumns('internship_registrations', true, $internshipTableCreated);

        if (Schema::hasTable('career_applications')) {
            DB::transaction(function (): void {
                foreach (DB::table('career_applications')->orderBy('id')->get() as $application) {
                    $isInternship = str_starts_with($application->job_id, 'internship-');
                    $table = $isInternship ? 'internship_registrations' : 'staff_registrations';

                    if (DB::table($table)->where('email', $application->email)->exists()) {
                        throw new RuntimeException(
                            'Cannot move career application because its email already exists in '.$table.'.'
                        );
                    }

                    $education = $this->decodeArray($application->education);
                    $firstEducation = $education[0] ?? [];
                    $cvPath = $application->cv_path;
                    $values = [
                        'applicant_id' => $application->user_id,
                        'reviewed_by' => null,
                        'full_name' => $application->full_name,
                        'email' => $application->email,
                        'birth_date' => $application->date_of_birth,
                        'date_of_birth' => $application->date_of_birth,
                        'gender' => $application->gender,
                        'address' => $application->location,
                        'location' => $application->location,
                        'phone' => $application->phone,
                        'position' => $application->target_position ?: $application->job_title,
                        'document_path' => $cvPath,
                        'status' => 'pending',
                        'reference' => $application->reference,
                        'job_id' => $application->job_id,
                        'job_title' => $application->job_title,
                        'company' => $application->company,
                        'headline' => $application->headline,
                        'expected_salary' => $application->expected_salary,
                        'availability' => $application->availability,
                        'portfolio_url' => $application->portfolio_url,
                        'summary' => $application->summary,
                        'privacy_consent_at' => $application->privacy_consent_at,
                        'experiences' => $this->encodeArray($application->experiences),
                        'education_details' => $this->encodeArray($application->education),
                        'skills' => $this->encodeArray($application->skills),
                        'cv_path' => $cvPath,
                        'documents' => $this->encodeArray($application->documents),
                        'created_at' => $application->created_at,
                        'updated_at' => $application->updated_at,
                    ];

                    if ($isInternship) {
                        $values['university'] = $this->nullableString($firstEducation['institution'] ?? null);
                        $values['major'] = $this->nullableString($firstEducation['major'] ?? null);
                        $values['semester'] = null;
                        $values['start_date'] = $application->internship_start_period;
                        $values['end_date'] = $application->internship_end_period;
                        $values['registration_type'] = $application->registration_type;
                        $values['target_position'] = $application->target_position;
                    } else {
                        $values['education'] = $this->nullableString($firstEducation['institution'] ?? null);
                    }

                    DB::table($table)->insert($values);
                }
            });
        }
    }

    public function down(): void
    {
        throw new RuntimeException(
            'This migration copies applicant data. Keep the backup table and restore from backup before rolling it back.'
        );
    }

    private function createStaffRegistrationsTable(): bool
    {
        if (Schema::hasTable('staff_registrations')) {
            return false;
        }

        Schema::create('staff_registrations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('applicant_id')->nullable()->unique();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->string('full_name', 150);
            $table->string('email', 255)->unique();
            $table->date('birth_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 32)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 40);
            $table->string('education', 180)->nullable();
            $table->string('position', 180)->nullable();
            $table->string('document_path')->nullable();
            $table->string('status')->default('pending');
            $this->declareApplicationColumns($table, false);
            $table->timestamps();
            $table->foreign('applicant_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        return true;
    }

    private function createInternshipRegistrationsTable(): bool
    {
        if (Schema::hasTable('internship_registrations')) {
            return false;
        }

        Schema::create('internship_registrations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('applicant_id')->nullable()->unique();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->string('full_name', 150);
            $table->string('email', 255)->unique();
            $table->date('birth_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 32)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 40);
            $table->string('university', 180)->nullable();
            $table->string('major', 180)->nullable();
            $table->string('semester', 20)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('document_path')->nullable();
            $table->string('status')->default('pending');
            $this->declareApplicationColumns($table, true);
            $table->timestamps();
            $table->foreign('applicant_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        return true;
    }

    private function prepareApplicantColumn(string $tableName, bool $tableCreated): void
    {
        if (! $tableCreated && DB::connection()->getDriverName() === 'mysql') {
            $applicantForeignKey = $tableName === 'staff_registrations'
                ? 'fk_staff_applicant'
                : 'fk_internship_applicant';

            if ($this->hasForeignKey($tableName, $applicantForeignKey)) {
                Schema::table($tableName, function (Blueprint $table) use ($applicantForeignKey): void {
                    $table->dropForeign($applicantForeignKey);
                });
            }

            try {
                Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                    $table->unsignedBigInteger('applicant_id')->nullable()->change();
                    $table->string('full_name', 150)->change();
                    $table->string('email', 255)->change();
                    $table->string('phone', 40)->change();

                    if (Schema::hasColumn($tableName, 'position')) {
                        $table->string('position', 180)->nullable()->change();
                    }

                    if ($tableName === 'staff_registrations' && Schema::hasColumn($tableName, 'education')) {
                        $table->string('education', 180)->nullable()->change();
                    }

                    if ($tableName === 'internship_registrations') {
                        if (Schema::hasColumn($tableName, 'university')) {
                            $table->string('university', 180)->nullable()->change();
                        }
                        if (Schema::hasColumn($tableName, 'major')) {
                            $table->string('major', 180)->nullable()->change();
                        }
                    }
                });
            } finally {
                if (! $this->hasForeignKey($tableName, $applicantForeignKey)) {
                    Schema::table($tableName, function (Blueprint $table) use ($applicantForeignKey): void {
                        $table->foreign('applicant_id', $applicantForeignKey)
                            ->references('id')
                            ->on('users')
                            ->cascadeOnDelete();
                    });
                }
            }
        }

        if ($tableCreated || $this->hasUniqueEmailIndex($tableName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
            $table->unique('email', $tableName.'_email_unique');
        });
    }

    private function addApplicationColumns(string $tableName, bool $isInternship, bool $tableCreated): void
    {
        if ($tableCreated) {
            return;
        }

        $columns = [
            'date_of_birth' => fn (Blueprint $table) => $table->date('date_of_birth')->nullable(),
            'location' => fn (Blueprint $table) => $table->string('location')->nullable(),
            'reference' => fn (Blueprint $table) => $table->string('reference', 24)->nullable()->unique(),
            'job_id' => fn (Blueprint $table) => $table->string('job_id', 64)->nullable(),
            'job_title' => fn (Blueprint $table) => $table->string('job_title')->nullable(),
            'company' => fn (Blueprint $table) => $table->string('company')->nullable(),
            'headline' => fn (Blueprint $table) => $table->string('headline', 180)->nullable(),
            'expected_salary' => fn (Blueprint $table) => $table->string('expected_salary', 40)->nullable(),
            'availability' => fn (Blueprint $table) => $table->string('availability', 100)->nullable(),
            'portfolio_url' => fn (Blueprint $table) => $table->string('portfolio_url')->nullable(),
            'summary' => fn (Blueprint $table) => $table->text('summary')->nullable(),
            'privacy_consent_at' => fn (Blueprint $table) => $table->timestamp('privacy_consent_at')->nullable(),
            'experiences' => fn (Blueprint $table) => $table->json('experiences')->nullable(),
            'education_details' => fn (Blueprint $table) => $table->json('education_details')->nullable(),
            'skills' => fn (Blueprint $table) => $table->json('skills')->nullable(),
            'cv_path' => fn (Blueprint $table) => $table->string('cv_path')->nullable(),
            'documents' => fn (Blueprint $table) => $table->json('documents')->nullable(),
        ];

        if ($isInternship) {
            $columns['registration_type'] = fn (Blueprint $table) => $table->string('registration_type', 80)->nullable();
            $columns['target_position'] = fn (Blueprint $table) => $table->string('target_position', 180)->nullable();
        }

        $missingColumns = array_filter(
            $columns,
            fn (Closure $column, string $name): bool => ! Schema::hasColumn($tableName, $name),
            ARRAY_FILTER_USE_BOTH
        );

        if ($missingColumns === []) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($missingColumns): void {
            foreach ($missingColumns as $declareColumn) {
                $declareColumn($table);
            }
        });
    }

    private function declareApplicationColumns(Blueprint $table, bool $isInternship): void
    {
        $table->string('reference', 24)->nullable()->unique();
        $table->string('job_id', 64)->nullable();
        $table->string('job_title')->nullable();
        $table->string('company')->nullable();
        $table->string('location')->nullable();
        $table->string('headline', 180)->nullable();
        $table->string('expected_salary', 40)->nullable();
        $table->string('availability', 100)->nullable();
        $table->string('portfolio_url')->nullable();
        $table->text('summary')->nullable();
        $table->timestamp('privacy_consent_at')->nullable();
        $table->json('experiences')->nullable();
        $table->json('education_details')->nullable();
        $table->json('skills')->nullable();
        $table->string('cv_path')->nullable();
        $table->json('documents')->nullable();

        if ($isInternship) {
            $table->string('registration_type', 80)->nullable();
            $table->string('target_position', 180)->nullable();
        }
    }

    private function hasUniqueEmailIndex(string $tableName): bool
    {
        foreach (Schema::getIndexes($tableName) as $index) {
            if (($index['unique'] ?? false) && ($index['columns'] ?? []) === ['email']) {
                return true;
            }
        }

        return false;
    }

    private function hasForeignKey(string $tableName, string $name): bool
    {
        foreach (Schema::getForeignKeys($tableName) as $foreignKey) {
            if (($foreignKey['name'] ?? null) === $name) {
                return true;
            }
        }

        return false;
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

    private function nullableString(mixed $value): ?string
    {
        return is_string($value) && trim($value) !== '' ? $value : null;
    }
};
