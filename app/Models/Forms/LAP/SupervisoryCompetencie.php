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
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
           'supervisory_learning_application_plan_form_id' => 'integer',
        'supervisory_managing_performance_coaching_results'=> 'boolean',
        'supervisory_building_collaborative_inclusive_working_relationships' => 'boolean',
    ];
}
