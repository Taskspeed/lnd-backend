<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class SpeakerProfessionalInfo extends Model
{
    //
    protected $table = 'speaker_professional_info';

    protected $fillable = [
        'speaker_id',
        'educational_attainment',
        'professional_title',
        'specialization',
        'biography',
        'years_of_experience',
        'resume_path',
    ];

    protected $casts = [
        'speaker_id' => 'integer'
    ];
      protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
