<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventSpeaker extends Model
{
    //

    protected $table = 'event_speakers';

    protected $fillable = [
        'schedule_date_time_id',
        'speaker_name',
        'agency',
        'position'
    ];

    protected $appends = ['speakerId'];
    protected $hidden = ['id' ,'created_at','updated_at'];
    protected $casts = [ 
           'schedule_date_time_id' => 'integer',
    ];

    public function getSpeakerIdAttribute()
    {
        return $this->id;
    }
    // EventSpeaker
public function dateTime()
{
    return $this->belongsTo(EventScheduleDateTime::class, 'schedule_date_time_id');
}
}
