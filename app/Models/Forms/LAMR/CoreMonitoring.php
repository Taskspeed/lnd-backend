<?php

namespace App\Models\Forms\LAMR;

use Illuminate\Database\Eloquent\Model;

class CoreMonitoring extends Model
{
    //

    protected $table = 'core_monitoring';

    protected $fillable = [
        'learning_application_monitoring_report_id',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills'
    ];

    
    protected $appends = ['core_monitoring_id'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function getCoreMonitoringIdAttribute()
    {
        return $this->id;
    }

    protected $casts = [
        'learning_application_monitoring_report_id' => 'integer',
        
        'delivering_service_excellence' => 'boolean',
        'exemplifying_integrity' => 'boolean',
        'thinking_strategically_creatively' => 'boolean',
        'interpersonal_skills'  => 'boolean',
    ];
}
