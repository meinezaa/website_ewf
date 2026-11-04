<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'internship_registration_id',
        'certificate_number',
        'file_path',
        'issued_by',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
        ];
    }

    public function internshipRegistration(): BelongsTo
    {
        return $this->belongsTo(
            InternshipRegistration::class,
            'internship_registration_id'
        );
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
