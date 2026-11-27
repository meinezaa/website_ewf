<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerOpening extends Model
{
    protected $fillable = [
        'opening_key',
        'type',
        'title',
        'category',
        'major',
        'location',
        'work_system',
        'education',
        'qualifications',
        'description',
        'benefits',
        'duration',
        'status',
    ];
}
