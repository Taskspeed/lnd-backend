<?php

namespace App\Models\Forms\LPR;

use Illuminate\Database\Eloquent\Model;

class TechnicalProgress extends Model
{
    //

    protected $table = 'technical_progress';

    protected $fillable = [
        'planning_organizing',
        'monitoring_evaluation',
        'records_management',
        'partnering_networking',
        'process_management'
    ];
}
