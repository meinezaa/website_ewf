<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffRegistration extends Model
{
    protected $table = 'staff_registrations';

    protected $fillable = [
        'applicant_id',
        'reviewed_by',
        'full_name',
        'email',
        'birth_date',
        'gender',
        'address',
        'phone',
        'education',
        'position',
        'document_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
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
}
