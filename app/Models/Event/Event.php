<?php

namespace App\Models\Event;

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
}
