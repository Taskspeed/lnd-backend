<?php

namespace App\Models\Employee;

use App\Casts\TimeFormatCast;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    //

    protected $table = 'employee_attendances';



    protected $fillable = [

        'control_no',
        'name',
        'scan_date',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'nominated_employee_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    protected $casts = [

        'morning_in'   => TimeFormatCast::class,
        'morning_out'  => TimeFormatCast::class,
        'afternoon_in'  => TimeFormatCast::class,
        'afternoon_out'  => TimeFormatCast::class,
        //   'event_schedule_id' => 'integer',
        //   'scan_date' =>  'date:F d, Y'
    ];
}
