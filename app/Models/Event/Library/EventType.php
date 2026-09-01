<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    //
    protected $table = 'event_types';

    protected $fillable = [
        'type_name',
    ];
    
    protected $appends = ['typeId'];
    protected $hidden = ['id'];

    protected $casts = [
        'created_at' => 'date:F d, Y'
    ];

    public function getTypeIdAttribute()
    {
        return $this->id;
    }
}
