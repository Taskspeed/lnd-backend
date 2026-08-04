<?php

namespace App\Services\Event\Library;

use App\Models\Event\Library\EventVenue;
use Illuminate\Support\Facades\DB;

class EventVenueService
{

  public function index()
    {
        $venue = EventVenue::all();
        return $venue;
    }

    public function create(array $validateData)
    {
        return DB::transaction(function () use ($validateData) {
            $venue = EventVenue::create($validateData);

            if (!$venue) {
                throw new \Exception('Failed to create event venue');
            }

            return $venue;
        });
    }

    public function update(int $venueId, array $validateData)
    {
        return DB::transaction(function () use ($venueId, $validateData) {
            $venue = EventVenue::find($venueId);

            if (!$venue) {
                throw new \Exception('Event venue not found');
            }

            $venue->update($validateData);

            return $venue;
        });
    }

    public function destroy(int $venueId)
    {
        $venue = EventVenue::find($venueId);

        if (!$venue) {
            throw new \Exception('Event venue not found');
        }

        $venue->delete();

        return $venue;
    }
}
