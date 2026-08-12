<?php

namespace App\Models\Event;

use App\Casts\TimeFormatCast;
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
    protected $hidden = ['id','created_at','updated_at'];

    protected $casts = [
        'morning_in'   => TimeFormatCast::class,
        'morning_out'  => TimeFormatCast::class,
        'afternoon_in' => TimeFormatCast::class,
        'afternoon_out' => TimeFormatCast::class,
        'event_id' => 'integer',
        'schedule_date' =>  'date: F d, Y'
    ];

    public function getScheduleIdAttribute()
    {
        return $this->id;
    }
}
