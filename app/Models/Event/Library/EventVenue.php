<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;

class EventVenue extends Model
{
    //
    protected $table = 'event_venues';

    protected $fillable = [
        'venue_name',
    ];

    protected $appends = ['eventVenueId'];
    protected $hidden = ['id'];

    public function getEventVenueIdAttribute()
    {
        return $this->id;
    }
}
