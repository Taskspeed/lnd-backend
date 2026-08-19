<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EmployeeFormSubmission extends Model
{
    //

    protected $table = 'employee_form_submissions';

    protected $fillable = [
        'event_id',
        'form_name',
        'control_no',
        'status',
        'submitted_at',
        'remarks',
        'event_schedule_id'
    ];

    protected $hidden = ['id','created_at','updated_at'];
    protected $appends = ['employee_form_submission_id'];
    protected $casts = [
        'event_id' => 'integer',
        'submitted_at' => 'date:F d, Y',
        'event_schedule_id' => 'integer'
    ];

    public function getEmployeeFormSubmissionIdAttribute()
    {
        return $this->id;
    }
}
