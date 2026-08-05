<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventTitle extends Model
{
    //
    protected $table = 'event_titles';

    protected $fillable = [
        'title_name',
    ];
     protected $appends = ['titleId'];
    protected $hidden = ['id'];

    public function getTitleIdAttribute()
    {
        return $this->id;
    }
}
