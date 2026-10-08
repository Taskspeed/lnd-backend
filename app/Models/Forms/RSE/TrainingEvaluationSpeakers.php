<?php

namespace App\Models\Forms\RSE;

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
    ];

    protected $casts = [
        'employee_form_submission_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function speakerEvaluation()
    {
        return $this->hasMany(SpeakerEvaluation::class, 'training_evaluation_speaker_id');
    }
}
