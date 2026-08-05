<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventMode extends Model
{
    //

    protected $table = 'event_modes';

    protected $fillable = [
        'mode_name',
    ];

    protected $appends = ['eventModeId'];
    protected $hidden = ['id'];

    public function getEventModeIdAttribute()
    {
        return $this->id;
    }
}
