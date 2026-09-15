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
          protected $hidden = [ 'created_at', 'updated_at'];

          protected $casts = [
                  'learning_application_plan_form_id' => 'integer',
        'managing_performance_coaching_results'=> 'boolean',
        'building_collaborative_inclusive_working_relationships'=> 'boolean',
        'thinking_strategically_creatively'=> 'boolean',
        'partnering_networking'=> 'boolean',
        'problem_solving_decision_making'
          ];
}
