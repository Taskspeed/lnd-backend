<?php

namespace App\Models\Forms\LIR;

use Illuminate\Database\Eloquent\Model;

class LearningImplementationForm extends Model
{
    //

    protected $table = 'learning_implementation_forms';

    protected $fillable = [

        'event_id',
        'form_name',
        'control_no',
        'learner',
        'lnd_attended',
        'date_of_attendance',
        'competency_developed_acquired',
        'learning_strategies_applied',
        'resources_used',
        'beneficiaries_strategies_applied',
        'performance_indicators_behavior_toward_work',
        'financial_aid_training_attended',
        'return_financial_aid'
    ];

    public function coreImplementation(){
        return $this->hasOne(CoreImplementation::class);
    }
    public function technicalImplementation(){
        return $this->hasOne(TechinicalImplementation::class);
    }
    public function learderShipImplementation(){
        return $this->hasOne(LeadershipImplementation::class);
    }
}
