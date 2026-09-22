<?php

namespace App\Models\Forms\LIR;

use Illuminate\Database\Eloquent\Model;

class TechinicalImplementation extends Model
{
    //
    protected $table = 'technical_implementation';

    protected $fillable = [
        'learning_implementation_form_id',
        'planning_organizing',
        'monitoring_evaluation',
        'records_management',
        'partnering_networking',
        'process_management'
    ];
}
