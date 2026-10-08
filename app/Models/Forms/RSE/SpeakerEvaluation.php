<?php

namespace App\Models\Forms\RSE;

use Illuminate\Database\Eloquent\Model;

class SpeakerEvaluation extends Model
{
    //

    protected $table = 'speaker_evaluations';

    protected $fillable = [

        'training_evaluation_speaker_id',
        'mastery_of_subject',
        'ability_to_involve',
        'session_interest',
        'handling_questions',
        'voice_projection',
        'communication',
        'practical_relevance',
        'over_all_rating',

    ];

    protected $casts = [
        'training_evaluation_speaker_id' => 'integer',
        'mastery_of_subject' => 'integer',
        'ability_to_involve' => 'integer',
        'session_interest' => 'integer',
        'handling_questions' => 'integer',
        'voice_projection' => 'integer',
        'communication' => 'integer',
        'practical_relevance' => 'integer',
        'over_all_rating' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function trainingEvaluationSpeaker()
    {
        return $this->belongsTo(TrainingEvaluationSpeakers::class, 'training_evaluation_speaker_id');
    }
}
