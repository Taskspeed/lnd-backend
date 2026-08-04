<?php

namespace App\Services\Event\Library;

use App\Models\Event\Library\EventSource;
use Illuminate\Support\Facades\DB;

class EventSourceService
{
  
    public function index()
    {
        $source = EventSource::all();
        return $source;
    }

    public function create(array $validateData)
    {
        return DB::transaction(function () use ($validateData) {
            $source = EventSource::create($validateData);

            if (!$source) {
                throw new \Exception('Failed to create event type');
            }

            return $source;
        });
    }

    public function update(int $sourceId, array $validateData)
    {
        return DB::transaction(function () use ($sourceId, $validateData) {
            $source = EventSource::find($sourceId);

            if (!$source) {
                throw new \Exception('Event type not found');
            }

            $source->update($validateData);

            return $source;
        });
    }

    public function destroy(int $sourceId)
    {
        $source = EventSource::find($sourceId);

        if (!$source) {
            throw new \Exception('Event type not found');
        }

        $source->delete();

        return $source;
    }
}
