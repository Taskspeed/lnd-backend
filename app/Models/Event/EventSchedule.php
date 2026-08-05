<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    //

    protected $table = 'event_schedules';

    protected $fillable = [
        'event_id',
        'schedule_date',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
    ];

    protected $appends = ['scheduleId'];
    protected $hidden = ['id'];

    public function getScheduleIdAttribute()
    {
        return $this->id;
    }
}
