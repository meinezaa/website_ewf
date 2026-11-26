<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationTrackingEvent extends Model
{
    protected $table = 'career_application_tracking_events';

    protected $fillable = [
        'application_type',
        'application_id',
        'event_type',
        'from_status',
        'to_status',
        'interview_at',
        'interview_information',
        'attendance',
        'attendance_reason',
        'actor_id',
    ];

    protected function casts(): array
    {
        return [
            'interview_at' => 'datetime',
        ];
    }
}
