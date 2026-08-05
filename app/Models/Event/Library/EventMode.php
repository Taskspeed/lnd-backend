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

    protected $appends = ['modeId'];
    protected $hidden = ['id'];

    public function getModeIdAttribute()
    {
        return $this->id;
    }
}
