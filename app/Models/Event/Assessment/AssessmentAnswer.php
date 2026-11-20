<?php

namespace App\Models\Event\Assessment;

use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    //

 protected $table = 'assessment_answers';

    protected $fillable = [
        'assessment_question_id',
        'assessment_option_id',
        'nominated_employee_id',
        'answer_text',
        'is_correct',
        'earned_points',
    ];

    protected $casts = [
        'is_correct'    => 'boolean',
        'earned_points' => 'integer',
    ];

}
