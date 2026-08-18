<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventSpeaker extends Model
{
    //

    protected $table = 'event_speakers';

    protected $fillable = [
        'event_schedule_id',
        'speaker_name'
    ];

    protected $appends = ['speakerId'];
    protected $hidden = ['id' ,'created_at','updated_at'];
    protected $casts = [ 
           'event_schedule_id' => 'integer',
    ];

    public function getSpeakerIdAttribute()
    {
        return $this->id;
    }
}
