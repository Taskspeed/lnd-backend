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
        'office'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
