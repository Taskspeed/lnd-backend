<?php

namespace App\Models\Employee;

use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Model;

class NominatedEmployee extends Model
{
    //
    protected $table = 'nominated_employees';

    protected $fillable = [
        'event_id',
        'control_no',
        'office',
        'full_name',
        'designation',
        'status',
        'sg',
        'level',
        'event_schedule_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];


    protected $appends = ['nominated_employee_id'];
    protected $casts = [
        'event_id' => 'integer',
        'event_schedule_id'=> 'integer'
    ];

    public function getNominatedEmployeeIdAttribute()
    {
        return $this->id;
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
