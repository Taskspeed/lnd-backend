<?php

namespace App\Models\Forms\LPR;

use Illuminate\Database\Eloquent\Model;

class TechnicalProgress extends Model
{
    //

    protected $table = 'technical_progress';

    protected $fillable = [
        'learner_progress_report_id',
        'planning_organizing',
        'monitoring_evaluation',
        'records_management',
        'partnering_networking',
        'process_management'
    ];

    protected $appends = ['technical_progress_id'];
    protected $hidden = ['id','created_at','updated_at'];

    public function getTechnicalProgressIdAttribute()
    {
        return $this->id;
    }

    protected $casts = [
        
       'learner_progress_report_id' => 'integer',
        'planning_organizing'=> 'boolean',
        'monitoring_evaluation'=> 'boolean',
        'records_management'=> 'boolean',
        'partnering_networking'=> 'boolean',
        'process_management'=> 'boolean',
    ];
}
