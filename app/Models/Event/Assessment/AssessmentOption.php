<?php

namespace App\Models\Event\Assessment;

use Illuminate\Database\Eloquent\Model;

class AssessmentOption extends Model
{
    //

    protected $table = 'assessment_options';

    protected $fillable = [
        'assessment_question_id',
        'option_text',
        'is_correct',

    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'assessment_question_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
