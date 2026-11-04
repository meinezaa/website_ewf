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
        'gender',
        'address',
        'phone',
        'university',
        'major',
        'semester',
        'start_date',
        'end_date',
        'document_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
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
}
