<?php

namespace App\Models\Employee;

use App\Models\Event\EmployeeFormSubmission;
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
        'event_schedule_id',
        'nominate_reason',
        'nominate_status',
        'certificate_issued'

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];


    protected $appends = ['nominated_employee_id'];
    protected $casts = [
        'event_id' => 'integer',
        'event_schedule_id' => 'integer',
        'is_attended' => 'boolean',
        'certificate_issued' => 'boolean'
    ];

    public function getNominatedEmployeeIdAttribute()
    {
        return $this->id;
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'control_no', 'control_no');
    }

    public function employeeAttendances()
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    public function formSubmissions()
    {
        return $this->hasMany(EmployeeFormSubmission::class, 'control_no', 'control_no');
    }
}
