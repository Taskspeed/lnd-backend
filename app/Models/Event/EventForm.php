<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventForm extends Model
{
    //

    protected $table = 'event_forms';


    protected $fillable = [
        'event_id',
        'form_name'
    ];
    protected $appends = ['formId'];
    protected $hidden = ['id','created_at','updated_at'];
    protected $casts = [ 
           'event_id' => 'integer',
    ];

    public function getFormIdAttribute()
    {
        return $this->id;
    }
}
