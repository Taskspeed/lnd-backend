<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    //

    protected $table = 'event_categories';
    
    
    protected $fillable = [
        'category_name'
    ];

    
}
