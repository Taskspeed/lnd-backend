<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventSource extends Model
{
    //

    protected $table = 'event_sources';

    protected $fillable = [
        'source_name',
    ];
    protected $appends = ['eventSourceId'];
    protected $hidden = ['id'];

    public function getEventSourceIdAttribute()
    {
        return $this->id;
    }
}
