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

    protected $appends = ['venueId'];
    protected $hidden = ['id'];

    protected $casts = [
        'created_at' => 'date:F d, Y'
    ];

    public function getVenueIdAttribute()
    {
        return $this->id;
    }
}
