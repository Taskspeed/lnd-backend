<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class SupervisoryCompetencie extends Model
{
    //
    protected $table = 'supervisory_competencies';

    protected $fillable = [
        'supervisory_learning_application_plan_form_id',
        'supervisory_managing_performance_coaching_results',
        'supervisory_building_collaborative_inclusive_working_relationships',

    ];
}
