<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Event\EventSchedule;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    use ApiResponseTrait;
    
    public function eventDate(Request $request)
    {
        $userAuth = Auth::user();

        $validated = $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        $year = $validated['year'] ?? now()->year;
        $month = $validated['month'] ?? now()->month;

        $eventSchedule = EventSchedule::select('id', 'venue_name', 'event_id') // add mo dito yung FK column papunta sa office (hal. office_id) kung kailangan
            ->whereHas('office', function ($query) use ($userAuth) {
                $query->where('office_name', $userAuth->office);
            })
            ->with([
                'office',
                'event' => function ($query) {
                    $query->select('id', 'title_name', 'learning_intervention');
                },
                'scheduleDateTime' => function ($query) use ($year, $month) {
                    $query->select('id', 'schedule_date', 'event_schedule_id', 'morning_in', 'morning_out', 'afternoon_in', 'afternoon_out')
                        ->whereYear('schedule_date', $year)
                        ->whereMonth('schedule_date', $month);
                },
            ])
            ->get();

        return $this->successMessage($eventSchedule, 'Event success');
    }
}
