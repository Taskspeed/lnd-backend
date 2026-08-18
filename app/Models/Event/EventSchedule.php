<?php

namespace App\Models\Event;

use App\Casts\TimeFormatCast;
use App\Models\Employee\NominatedEmployee;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    //

    protected $table = 'event_schedules';

    protected $fillable = [
        'event_id',
        'venue_name',
        'type_name',
        'status'
    ];

    protected $casts = [
        'event_id' => 'integer'
    ];

    protected $appends = ['scheduleId'];
    protected $hidden = ['id', 'created_at', 'updated_at'];


    public function getScheduleIdAttribute()
    {
        return $this->id;
    }

    public function scheduleDateTime()
    {
        return $this->hasMany(EventScheduleDateTime::class);
    }

    public function office()
    {
        return  $this->hasMany(EventDepartment::class);
    }
    public function speaker()
    {
        return  $this->hasMany(EventSpeaker::class);
    }
    public function nominatedEmployee()
    {
        return $this->hasMany(NominatedEmployee::class);
    }
}
