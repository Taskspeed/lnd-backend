<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class NominatedEmployee extends Model
{
    //
    protected $table = 'nominated_employees';

    protected $fillable = [
        'event_id',
        'control_no',
        'office'
    ];
}
