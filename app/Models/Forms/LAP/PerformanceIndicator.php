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
          protected $hidden = [ 'created_at', 'updated_at'];

          protected $casts = [
                'learning_application_plan_form_id' => 'integer',
        'strategic_functions' => 'boolean',
        'core_functions'=> 'boolean',
        'support_functions'=> 'boolean',
          ];
}
