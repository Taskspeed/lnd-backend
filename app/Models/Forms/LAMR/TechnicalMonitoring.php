<?php

namespace App\Models\Forms\LAMR;

use Illuminate\Database\Eloquent\Model;

class TechnicalMonitoring extends Model
{
    //
    protected $table = 'technical_monitoring';

    protected $fillable = [
        'learning_application_monitoring_report_id',
        'planning_organizing',
        'monitoring_evaluation',
        'records_management',
        'partnering_networking',
        'process_management',
    ];

    protected $appends = ['technical_monitoring_id'];
    protected $hidden = ['id','created_at','updated_at'];

    public function getTechnicalMonitoringIdAttribute()
    {
        return $this->id;
    }

    protected $casts = [
        
       'learning_application_monitoring_report_id' => 'integer',
        'planning_organizing'=> 'boolean',
        'monitoring_evaluation'=> 'boolean',
        'records_management'=> 'boolean',
        'partnering_networking'=> 'boolean',
        'process_management'=> 'boolean',
    ];
}
