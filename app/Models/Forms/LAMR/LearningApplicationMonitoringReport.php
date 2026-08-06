<?php

namespace App\Models\Forms\LAMR;

use Illuminate\Database\Eloquent\Model;

class LearningApplicationMonitoringReport extends Model
{
    //

    protected $table = 'learning_application_monitoring_reports';

    protected $fillable = [
        'event_id',
        'forms_name',
        'control_no',
        'learner',
        'lnd_attended',
        'date_of_attendance',
        'competency_developed_acquired',
        'goals',
        'performance_indicator',
        'learning_strategies_applied',
        'required_resources',
        'target_date_completion',
        'status_as_of_v1',
        'status_as_of_v2',
        'remarks'
    ];
}
