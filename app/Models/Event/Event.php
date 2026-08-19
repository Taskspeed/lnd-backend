<?php

namespace App\Models\Event;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EventForm;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //

    protected $table = 'events';

    protected $fillable = [
        'title_name',
        // 'venue_name',
       'type_name',
        'source_name',
        'qualifications',
        'hours',
        'fee'

    ];
    protected $casts = [
        'created_at' =>'date:F d, Y',
        'updated_at' =>'date:F d, Y',
    ];

    protected $hidden = ['id','updated_at'];
    protected $appends = ['event_id'];

    public function getEventIdAttribute()
    {
        return $this->id;
    }

    public function form()
    {
        return  $this->hasMany(EventForm::class);
    }
 
     public function schedule()
    {
        return  $this->hasMany(EventSchedule::class);
    }

    
}
