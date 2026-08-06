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
        'remarks'
    ];
}
