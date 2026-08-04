<?php

namespace App\Services\Event\Library;

use App\Models\Event\Library\EventTitle;
use Illuminate\Support\Facades\DB;

class EventTitleService
{
    public function index()
    {
        $title = EventTitle::all();
        return $title;
    }

    public function create(array $validateData)
    {
        return DB::transaction(function () use ($validateData) {
            $title = EventTitle::create($validateData);

            if (!$title) {
                throw new \Exception('Failed to create event type');
            }

            return $title;
        });
    }

    public function update(int $titleId, array $validateData)
    {
        return DB::transaction(function () use ($titleId, $validateData) {
            $title = EventTitle::find($titleId);

            if (!$title) {
                throw new \Exception('Event type not found');
            }

            $title->update($validateData);

            return $title;
        });
    }

    public function destroy(int $titleId)
    {
        $title = EventTitle::find($titleId);

        if (!$title) {
            throw new \Exception('Event type not found');
        }

        $title->delete();

        return $title;
    }
}
