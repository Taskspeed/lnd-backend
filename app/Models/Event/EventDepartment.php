<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventDepartment extends Model
{
    //
    protected $table = 'departments';

    protected $fillable = [
        'event_schedule_id',
        'office_name'
    ];

    protected $appends = ['departmentId'];
    protected $hidden = ['id','created_at','updated_at'];
       protected $casts = [ 
           'event_schedule_id' => 'integer',
    ];

    public function getdepartmentIdAttribute()
    {
        return $this->id;
    }
}
