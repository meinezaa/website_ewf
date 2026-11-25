<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InternshipRegistration extends Model
{
    protected $table = 'internship_registrations';

    protected $fillable = [
        'applicant_id',
        'reviewed_by',
        'full_name',
        'email',
        'birth_date',
        'date_of_birth',
        'gender',
        'address',
        'location',
        'phone',
        'university',
        'major',
        'semester',
        'start_date',
        'end_date',
        'document_path',
        'status',
        'reference',
        'job_id',
        'job_title',
        'company',
        'headline',
        'expected_salary',
        'availability',
        'portfolio_url',
        'summary',
        'privacy_consent_at',
        'experiences',
        'education_details',
        'skills',
        'cv_path',
        'documents',
        'registration_type',
        'internship_major',
        'target_position',
        'certificate_path',
        'loa_path',
        'interview_at',
        'interview_information',
        'interview_attendance',
        'interview_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'date_of_birth' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'privacy_consent_at' => 'datetime',
            'interview_at' => 'datetime',
            'interview_confirmed_at' => 'datetime',
            'experiences' => 'array',
            'education_details' => 'array',
            'skills' => 'array',
            'documents' => 'array',
        ];
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class, 'internship_registration_id');
    }

    public function isProgramCompleted(): bool
    {
        return $this->status === 'completed'
            || ($this->status === 'accepted'
                && $this->end_date !== null
                && $this->end_date->isBefore(today()));
    }
}
