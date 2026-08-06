<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class PerformanceIndicator extends Model
{
    //


    protected $table = 'performance_indicators';

    protected $fillable = [
        'learning_application_plan_form_id',
        'strategic_functions',
        'core_functions',
        'support_functions'

    ];
}
