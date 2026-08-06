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
}
