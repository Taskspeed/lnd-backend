<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventDepartment extends Model
{
    //
    protected $table = 'departments';

    protected $fillable = [
        'event_id',
        'office_name'
    ];

    protected $appends = ['departmentId'];
    protected $hidden = ['id'];

    public function getdepartmentIdAttribute()
    {
        return $this->id;
    }
}
