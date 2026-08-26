<?php

namespace App\Services\HR\Dashboard;

use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Traits\FormatsDateRanges;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardService
{
    use FormatsDateRanges;

    public function upComingEvent(Carbon $today, int $perPage)
    {
        $schedules = EventSchedule::select('id', 'event_id', 'venue_name', 'hours', 'status')
            ->with([
                'event' => function ($query) {
                    $query->select('id', 'title_name');
                },
                'scheduleDateTime',
            ])
            ->whereHas('scheduleDateTime', function ($query) use ($today) {
                $query->whereDate('schedule_date', '>=', $today);
            })
            ->paginate($perPage);

        $schedules->getCollection()->each(function ($schedule) {
            $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
            unset($schedule->scheduleDateTime);
        });

        return $schedules;
    }

    public function eventDate(int $year, int $month)
    {

        $eventSchedule = EventScheduleDateTime::select('id', 'schedule_date', 'event_schedule_id')
            ->with(['eventSchedule' => function ($query) {
                $query->select('id', 'status');
            }])
            ->whereYear('schedule_date', $year)
            ->whereMonth('schedule_date', $month)
            ->get();

        return $eventSchedule;
    }
}
