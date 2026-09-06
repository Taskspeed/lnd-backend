<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventEmployeeTag extends Model
{
    //

    protected $table = 'employee_tags'; 

    protected $fillable = [
        'event_schedule_id',
        'control_no',
        'name',
        'position',
        'office',
        'status'
    ];
      protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
