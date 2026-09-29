<?php

namespace App\Models\Forms\RSE;

use Illuminate\Database\Eloquent\Model;

class SpeakerEvaluation extends Model
{
    //

    protected $table = 'speaker_evaluations';

    protected $fillable = [
        'event_speaker_id',
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
}
