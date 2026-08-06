<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class ManagerialCompetencie extends Model
{
    //

    //
    protected $table = 'managerial_competencies';

    protected $fillable = [
        'learning_application_plan_form_id',
        'managing_performance_coaching_results',
        'building_collaborative_inclusive_working_relationships',
        'thinking_strategically_creatively',
        'partnering_networking',
        'problem_solving_decision_making'

    ];
}
