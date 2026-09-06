<?php

namespace App\Models\Event;

use App\Casts\TimeFormatCast;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Compentecy\EventCore;
use App\Models\Event\Compentecy\EventLeadership;
use App\Models\Event\Compentecy\EventTechnical;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    //

    protected $table = 'event_schedules';

    protected $fillable = [
        'event_id',
        'venue_name',
        'mode_name',
        'status',
        'hours',
        'qualifications',
        'fee',
         'type_name',
        'source_name',
        'category_name',
        'conducted_by'
    ];

    protected $casts = [
        'event_id' => 'integer',
       'hours'  => 'integer',
    ];

    protected $appends = ['scheduleId','computedStatus'];
    protected $hidden = ['id', 'created_at', 'updated_at','status'];

    public function getComputedStatusAttribute()
    {
        $dates = $this->scheduleDateTime()
            ->orderBy('schedule_date') // palitan kung ibang column name
            ->pluck('schedule_date');

        if ($dates->isEmpty()) {
            return $this->status; // fallback sa manual status kung wala pang date
        }

        $start = Carbon::parse($dates->first())->startOfDay();
        $end   = Carbon::parse($dates->last())->endOfDay();
        $today = Carbon::now();

        if ($today->lt($start)) {
            return 'up-coming';
        } elseif ($today->between($start, $end)) {
            return 'ongoing';
        } else {
            return 'complete';
        }
    }


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

     public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // competency
    public function eventCore(){

        return $this->hasMany(EventCore::class);
    }

    public function eventTechnical(){

        return $this->hasMany(EventTechnical::class);
    }

    public function eventLeadership(){
        
        return $this->hasMany(EventLeadership::class);
    }

      public function employeeTag(){
        
        return $this->hasMany(EventEmployeeTag::class);
    }
}
