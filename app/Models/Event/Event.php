<?php

namespace App\Models\Event;

use App\Models\Event\EventForm;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //

    protected $table = 'events';

    protected $fillable = [
        'title_name',
        'venue_name',
        'type_name',
        'source_name',
        'speaker_name',
        'status'
    ];

    protected $hidden = ['id'];
    protected $appends = ['eventId'];

    public function getEventIdAttribute()
    {
        return $this->id;
    }

    public function form()
    {
        return  $this->hasMany(EventForm::class);
    }
    public function office()
    {
        return  $this->hasMany(EventDepartment::class);
    }

     public function speaker()
    {
        return  $this->hasMany(EventSpeaker::class);
    }

     public function schedule()
    {
        return  $this->hasMany(EventSchedule::class);
    }
}
