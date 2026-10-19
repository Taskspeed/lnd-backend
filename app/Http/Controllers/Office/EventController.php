<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pagination\ListEventRequest;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\RSP\vwEmployee;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;
use App\Traits\SortsByScheduleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTrait;
    use FormatsDateRanges;
    use SortsByScheduleStatus;


 public function index(ListEventRequest $request)
{
    $user     = Auth::user();
    $validated = $request->validated();

    $perPage  = $validated['per_page'] ?? 10;
    $search   = trim($validated['search'] ?? '');
    $statuses = $validated['status'] ?? [];
    $today    = now()->toDateString();

    $schedules = EventSchedule::query()
        ->with([
            'event',
            'scheduleDateTime',
            'office' => fn($q) => $q->select('id', 'event_schedule_id', 'office_name')
                                    ->where('office_name', $user->office),
        ])
        ->whereHas('office', fn($q) => $q->where('office_name', $user->office))
        ->when($search !== '', fn($q) => $q->where(function ($w) use ($search) {
            $w->whereHas('event', fn($e) => $e->where('title_name', 'like', "%{$search}%"))
              ->orWhere('conducted_by', 'like', "%{$search}%");
        }))
        ->get();                       // lahat muna

    if (!empty($statuses)) {
        $schedules = $schedules->filter(fn($s) => in_array($s->computedStatus, $statuses));
    }

    $events = $this->paginateByScheduleStatus($schedules, $perPage)
        ->withQueryString();

    $events->through(function ($schedule) {
        $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
        $schedule->unsetRelation('scheduleDateTime');

        return $schedule;
    });

    return $this->successMessage($events, 'Success fetch', 200);
}


    public function show(int $scheduleId)
    {
        $user = Auth::user();

        $event = EventSchedule::with(['event', 'scheduleDateTime', 'eventCore', 'eventTechnical', 'eventLeadership', 'speaker', 'employeeTag' => function ($query) use ($user, $scheduleId) {
            $query->select('id', 'event_schedule_id', 'control_no', 'name', 'office', 'position', 'status')->where('office', $user->office)->where('event_schedule_id', $scheduleId)->get();
        }, 'office' => function ($query) use ($user) {
            $query->select('id', 'event_schedule_id', 'office_name')->where('office_name', $user->office);
        }])->find($scheduleId);


        if (!$event) {
            return $this->errorMessage('Event not found');
        }

        return $this->successMessage($event, 'Success fetch', 200);
    }
}
