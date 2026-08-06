<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class TargetDateCompletion extends Model
{
    //


    protected $table = 'target_date_completions';

    protected $fillable = [
        'learning_application_plan_form_id',
        'within_2_weeks_after_training',
        'within_1_month_after_training',
        'within_2_months_after_training',
        'within_3_months_after_training'

    ];
}
