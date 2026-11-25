<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'department',
        'gender',
        'location',
        'profile_photo_path',
        'profile_cover_path',
        'headline',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function profilePhotoUrl(): ?string
    {
        if (! $this->profile_photo_path) {
            return null;
        }

        $encodedPath = array_map('rawurlencode', explode('/', $this->profile_photo_path));

        return '/storage/'.implode('/', $encodedPath);
    }

    public function profileCoverUrl(): ?string
    {
        if (! $this->profile_cover_path) {
            return null;
        }

        $encodedPath = array_map('rawurlencode', explode('/', $this->profile_cover_path));

        return '/storage/'.implode('/', $encodedPath);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function staffRegistrations(): HasMany
    {
        return $this->hasMany(StaffRegistration::class, 'applicant_id');
    }

    public function internshipRegistrations(): HasMany
    {
        return $this->hasMany(InternshipRegistration::class, 'applicant_id');
    }
}
