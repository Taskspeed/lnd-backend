<?php

namespace App\Models\Forms\LAMR;

use Illuminate\Database\Eloquent\Model;

class LearningApplicationMonitoringForm extends Model
{
    //

    protected $table = 'learning_application_monitoring_forms';

    protected $fillable = [
        'event_id',
        'form_name',
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

    protected $appends = ['learning_application_monitoring_form_id'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'date_of_attendance' => 'date:F d, Y',
        'event_id' => 'integer'
    ];

    public function getLearningApplicationMonitoringFormIdAttribute()
    {
        return $this->id;
    }

    public function coreMonitoring(){

        return $this->hasOne(CoreMonitoring::class);
    }
    
    public function leaderShipMonitoring(){

        return $this->hasOne(LeadershipMonitoring::class);
    }
    
    public function technicalMonitoring(){

        return $this->hasOne(technicalMonitoring::class);
    }
}
