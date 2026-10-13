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

        $eventSchedule = EventSchedule::select('id', 'venue_name', 'event_id')
            ->with(['event' => function ($query) {
                $query->select('id', 'title_name', 'learning_intervention');
            }, 'scheduleDateTime' => function ($query) use ($year, $month) {
                $query->select('id', 'schedule_date', 'event_schedule_id', 'morning_in', 'morning_out', 'afternoon_in', 'afternoon_out')
                    ->whereYear('schedule_date', $year)
                    ->whereMonth('schedule_date', $month);
            }])


            ->get();

        return $eventSchedule;
    }


    public function eventStatus(int $year, int $month)
    {

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $schedules = EventSchedule::select('id', 'event_id')
            // i-filter ang mga schedule na may date sa piling month
            ->whereHas('scheduleDateTime', function ($query) use ($start, $end) {
                $query->whereBetween('schedule_date', [$start->toDateString(), $end->toDateString()]);
            })
            // i-load ang LAHAT ng dates para tama ang computedStatus
            ->with('scheduleDateTime:id,schedule_date,event_schedule_id,morning_in,morning_out,afternoon_in,afternoon_out')
            ->get();

        $data = [
            'year'      => (int) $year,
            'month'     => (int) $month,
            'ongoing'   => $schedules->where('computedStatus', 'ongoing')->count(),
            'upcoming'  => $schedules->where('computedStatus', 'up-coming')->count(),
            'completed' => $schedules->where('computedStatus', 'complete')->count(),
            'total'     => $schedules->count(),
        ];

        return $data;
    }
}
