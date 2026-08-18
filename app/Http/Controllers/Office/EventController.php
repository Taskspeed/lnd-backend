<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\RSP\vwEmployee;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTrait;

    public function index()
    {
        $user = Auth::user();

        $event = Event::whereHas('schedule.office', function ($query) use ($user) {
            $query->where('office_name', $user->office);
        })
            ->with(['schedule' => function ($query) use ($user) {
                $query->whereHas('office', function ($q) use ($user) {
                    $q->where('office_name', $user->office);
                })
                    ->with([
                        'scheduleDateTime',
                        'office' => function ($q) use ($user) {
                            $q->where('office_name', $user->office);
                        },
                    ]);
            }])
            ->get();

        if ($event->isEmpty()) {
            return $this->infoMessage('There is no available event for your office', 200);
        }

        return $this->successMessage($event, 'Success fetch', 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $eventId)
    {
        $user = Auth::user();


        $event = Event::with([
            'form',
            'office',
            'speaker',
            'schedule',
            'nominatedEmployee' => function ($query) use ($user) {
                $query->where('office', $user->office);
            },
        ])->find($eventId);

        if (!$event) {
            return $this->errorMessage('Event not found');
        }

        return $this->successMessage($event, 'Success fetch', 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
