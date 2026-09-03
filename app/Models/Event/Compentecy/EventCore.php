<?php

namespace App\Models\Event\Compentecy;

use Illuminate\Database\Eloquent\Model;

class EventCore extends Model
{
    //

        protected $table = 'event_core_competencies';

    protected $fillable = [
        'event_schedule_id',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills'
    ];
}
