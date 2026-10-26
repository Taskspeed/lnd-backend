<?php

namespace App\Models\ARMS;

use Illuminate\Database\Eloquent\Model;

class Tblevent extends Model
{
    //

     protected $connection  = 'ARMS_DB';
     protected $table = 'tblevents';
    public $timestamps = false;

     protected $fillable = [
        'Event_name',
        'Event_date',
        'Event_from',
        'Event_to',
        'Event_venue',
        'userid',
        'date_created',
        'event_schedule_id'
     ]; 

}
