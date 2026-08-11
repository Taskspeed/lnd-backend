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

    public function foundation()
    {
        return $this->hasOne(FoundationCompetencie::class);
    }
    public function technical()
    {
        return $this->hasOne(TechnicalCompetencie::class);
    }
    public function managerial()
    {
        return $this->hasOne(ManagerialCompetencie::class);
    }
    public function supervisory()
    {
        return $this->hasOne(SupervisoryCompetencie::class);
    }
    public function learningStrategies()
    {
        return $this->hasOne(LearningStrategiesImplemented::class);
    }
    public function performanceIndicator()
    {
        return $this->hasOne(PerformanceIndicator::class);
    }
    public function beneficiaries()
    {
        return $this->hasOne(BeneficiariesStrategieApplied::class);
    }
    public function resources()
    {
        return $this->hasOne(ResourcesUtilized::class);
    }

    public function targetCompletion()
    {
        return $this->hasOne(TargetDateCompletion::class);
    }
}
