<?php

namespace App\Models\Forms\RSE;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class TrainingEvaluationSpeakers extends Model
{
    //

    protected $table = 'training_evaluation_speakers';

    protected $fillable = [
        'employee_form_submission_id',
        'event',
        'date',
        'venue',
        'learning_service_provider',
        'form_name',
        'control_no',
              'image'
    ];

    protected $casts = [
        'employee_form_submission_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

      protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $value
                ? url("/api/erms/resource-speaker-evaluation/image/{$attributes['id']}")
                : null,
        );
    }

    public function speakerEvaluation()
    {
        return $this->hasMany(SpeakerEvaluation::class, 'training_evaluation_speaker_id');
    }
}
