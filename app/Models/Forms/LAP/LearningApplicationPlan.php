<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class LearningApplicationPlan extends Model
{
    //

    protected $table = 'learning_application_plan_forms';

    protected $fillable = [
        'event_id',
        'form_name',
        'control_no',
        'office',
        'learner',
        'title_of_intervention',
        'date_conducted',
        'venue',
        'foundation',
        'techinal',
        'supervisory',
        'managerial',
        'significant_learning_insight'
    ];
}
