<?php

namespace App\Models\Forms\LPR;

use Illuminate\Database\Eloquent\Model;

class CoreProgress extends Model
{
    //

    protected $table = 'core_progress';

    protected $fillable = [
        'learner_progress_report_id',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills'
    ];

    protected $appends = ['core_progress_id'];
    protected $hidden = ['id','created_at','updated_at'];

    public function getCoreProgressIdAttribute()
    {
        return $this->id;
    }


    protected $casts = [
        'learner_progress_report_id' => 'integer',
        'delivering_service_excellence' => 'boolean',
        'exemplifying_integrity' => 'boolean',
        'interpersonal_skills' => 'boolean',
    ];
}
