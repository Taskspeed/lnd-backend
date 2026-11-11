<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //

    protected $table = 'events';

    protected $fillable = [
        'title_name',
  

    ];
    protected $casts = [
        'created_at' =>'date:F d, Y',
        'updated_at' =>'date:F d, Y',
    ];

    protected $hidden = ['id','updated_at','created_at'];
    protected $appends = ['event_id'];

    public function getEventIdAttribute()
    {
        return $this->id;
    }

 
     public function schedule()
    {
        return  $this->hasMany(EventSchedule::class);
    }

    
}
