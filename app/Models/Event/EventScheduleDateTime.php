<?php

namespace App\Models\Event;

use App\Casts\TimeFormatCast;
use Illuminate\Database\Eloquent\Model;

class EventScheduleDateTime extends Model
{
    //

    protected $table = 'schedule_date_times';

    protected $fillable = [
        'time_in',
        'time_out',
        'event_schedule_id',
        'schedule_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'time_in'   => TimeFormatCast::class,
        'time_out'  => TimeFormatCast::class,
        'event_schedule_id' => 'integer',
        'schedule_date' =>  'date:F d, Y'
    ];

    public function eventSchedule(){
        return $this->belongsTo(EventSchedule::class);
    }
}
