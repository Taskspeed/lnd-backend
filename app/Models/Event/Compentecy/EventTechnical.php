<?php

namespace App\Models\Event\Compentecy;

use Illuminate\Database\Eloquent\Model;

class EventTechnical extends Model
{
    //  
    protected $table = 'event_technical_competencies';

    protected $fillable = [
        'event_schedule_id',
        'planning_organizing',
        'monitoring_evaluation',
        'records_management',
        'partnering_networking',
        'process_management',
        'attention_detail'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'planning_organizing' => 'boolean',
        'monitoring_evaluation' => 'boolean',
        'records_management' => 'boolean',
        'partnering_networking' => 'boolean',
        'process_management' => 'boolean',
        'attention_detail' => 'boolean',
    ];
}
