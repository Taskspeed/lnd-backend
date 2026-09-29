<?php

namespace App\Models\Forms\RSE;

use Illuminate\Database\Eloquent\Model;

class TrainingEvaluationSpeakers extends Model
{
    //

    protected $table = 'training_evaluation_speakers';

    protected $fillable = [
        'event_speaker_id',
        'event',
        'date',
        'venue',
        'learning_service_provider',
    ];

    public function speakerEvaluation(){
        return $this->hasMany(SpeakerEvaluation::class);
    }
}
