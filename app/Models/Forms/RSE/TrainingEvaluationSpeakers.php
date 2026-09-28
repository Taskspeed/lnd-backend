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
        'comments',
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
