<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class SpeakerEmployment extends Model
{
    //
    protected $table = 'speaker_employment';

    protected $fillable = [
        'speaker_id',

        'position',
        'office',
        'employment_type',
        'salary_grade',
    ];

    protected $casts = [
        'speaker_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
