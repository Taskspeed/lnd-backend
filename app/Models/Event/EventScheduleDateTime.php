<?php

namespace App\Models\Event;

use App\Casts\TimeFormatCast;
use Illuminate\Database\Eloquent\Model;

class EventScheduleDateTime extends Model
{
    //

    protected $table = 'schedule_date_times';

    protected $fillable = [
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'event_schedule_id',
        'schedule_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'morning_in'   => TimeFormatCast::class,
        'morning_out'  => TimeFormatCast::class,
        'afternoon_in' => TimeFormatCast::class,
        'afternoon_out' => TimeFormatCast::class,
        'event_schedule_id' => 'integer',
        'schedule_date' =>  'date: F d, Y'
    ];
}
