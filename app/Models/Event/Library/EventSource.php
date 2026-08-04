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
}
