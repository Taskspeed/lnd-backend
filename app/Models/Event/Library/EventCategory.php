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

      protected $casts = [
        'created_at' => 'date:F d, Y'
    ];

      protected $appends = ['categoryId'];
    protected $hidden = ['id'];

    public function getCategoryIdAttribute()
    {
        return $this->id;
    }

    
}
