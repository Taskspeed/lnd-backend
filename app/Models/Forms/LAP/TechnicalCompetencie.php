<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class TechnicalCompetencie extends Model
{
    //
    protected $table = 'technical_competencies';

    protected $fillable = [
        'learning_application_plan_form_id',
        'planning_organizing',
        'monitoring_evaluation',
        'records_management',
        'partnering_networking',
        'process_management',
        'attention_detail'
    ];
}
