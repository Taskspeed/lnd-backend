<?php

namespace App\Services\Event\Library;

use App\Models\Event\Library\EventType;
use Illuminate\Support\Facades\DB;

class EventTypeService
{


    public function index()
    {
        $type = EventType::all();
        return $type;
    }

    public function create(array $validateData)
    {
        return DB::transaction(function () use ($validateData) {
            $type = EventType::create($validateData);

            if (!$type) {
                throw new \Exception('Failed to create event type');
            }

            return $type;
        });
    }

    public function update(int $typeId, array $validateData)
    {
        return DB::transaction(function () use ($typeId, $validateData) {
            $type = EventType::find($typeId);

            if (!$type) {
                throw new \Exception('Event type not found');
            }

            $type->update($validateData);

            return $type;
        });
    }

    public function destroy(int $typeId)
    {
        $type = EventType::find($typeId);

        if (!$type) {
            throw new \Exception('Event type not found');
        }

        $type->delete();

        return $type;
    }
}
