<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    //
    protected $table = 'event_types';

    protected $fillable = [
        'type_name',
    ];
    
    protected $appends = ['eventTypeId'];
    protected $hidden = ['id'];

    public function getEventTypeIdAttribute()
    {
        return $this->id;
    }
}
