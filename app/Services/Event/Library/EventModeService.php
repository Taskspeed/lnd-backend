<?php

namespace App\Services\Event\Library;

use App\Models\Event\Library\EventMode;
use Illuminate\Support\Facades\DB;

class EventModeService
{
    
    public function index()
    {
        $mode = EventMode::all();
        return $mode;
    }

    public function create(array $validateData)
    {
        return DB::transaction(function () use ($validateData) {
            $mode = EventMode::create($validateData);

            if (!$mode) {
                throw new \Exception('Failed to create event mode');
            }

            return $mode;
        });
    }

    public function update(int $modeId, array $validateData)
    {
        return DB::transaction(function () use ($modeId, $validateData) {
            $mode = EventMode::find($modeId);

            if (!$mode) {
                throw new \Exception('Event mode not found');
            }

            $mode->update($validateData);

            return $mode;
        });
    }

    public function destroy(int $modeId)
    {
        $mode = EventMode::find($modeId);

        if (!$mode) {
            throw new \Exception('Event mode not found');
        }

        $mode->delete();

        return $mode;
    }
}
