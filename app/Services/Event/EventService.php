<?php

namespace App\Services\Event;

use App\Models\Event\Event;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventForm;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Models\Event\EventSpeaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class EventService
{

    public function listOfEvent()
    {
        $events = Event::with(['schedule' => function ($query) {
            $query->with('scheduleDateTime');
        }])->get();

        $events->each(function ($event) {
            $latestSchedule = null;
            $latestDate = null;

            foreach ($event->schedule as $schedule) {
                $dates = $schedule->scheduleDateTime
                    ->map(fn($item) => Carbon::parse($item->schedule_date))
                    ->sort();

                if ($dates->isEmpty()) {
                    continue;
                }

                $earliest = $dates->first();
                $latest   = $dates->last();

                $schedule->latest_schedule = $earliest->equalTo($latest)
                    ? $earliest->format('F d, Y')
                    : "{$earliest->format('F d, Y')} - {$latest->format('F d, Y')}";

                unset($schedule->scheduleDateTime);

                // ikumpara laban sa kasalukuyang pinaka-latest
                if (is_null($latestDate) || $latest->greaterThan($latestDate)) {
                    $latestDate = $latest;
                    $latestSchedule = $schedule;
                }
            }

            // panatilihin lang yung isang schedule na latest
            $event->setRelation(
                'schedule',
                $latestSchedule ? collect([$latestSchedule]) : collect()
            );
        });

        return $events;
    }

    public function show(int $eventId)
    {
        $event = Event::with([
            'form',
            'schedule' => function ($query) use ($eventId) {
                $query->with(['scheduleDateTime'])
                    ->select('id', 'event_id', 'venue_name', 'mode_name', 'status', 'hours')
                    ->where('event_id', $eventId);
            }
        ])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        // attach computed range per schedule
        $event->schedule->each(function ($schedule) {
            $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
            unset($schedule->scheduleDateTime);
        });

        return $event;
    }

    public function nominatedEmployee(int $eventId, int $eventScheduleId)
    {
        $event = Event::with([
            'form',
            'schedule' => function ($query) use ($eventId, $eventScheduleId) {
                $query->with([
                    'speaker',
                    'scheduleDateTime',
                    'office',
                    'nominatedEmployee'
                ])
                    ->where('event_id', $eventId)
                    ->where('id', $eventScheduleId);
            }
        ])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        // Compute counts per office after loading
        foreach ($event->schedule as $schedule) {
            $counts = $schedule->nominatedEmployee
                ->groupBy('office') // adjust field: office_name, department, etc.
                ->map->count();

            foreach ($schedule->office as $office) {
                $office->employee_nominated = $counts->get($office->office_name, 0);
            }
        }

        return $event;
    }

    // create event
    public function create(?array $validated)
    {

        return DB::transaction(function () use ($validated) {

            // check if the title_name of event are already create

            $event_title_name_exist = Event::where('title_name', $validated['title_name'])->first();

            if ($event_title_name_exist) {
                throw new \Exception('Event name are already exist not allow to create new. please add new schedule');
            }

            $event = Event::create([
                'title_name'   => $validated['title_name'] ?? null,
                'source_name'  => $validated['source_name'] ?? null,
                'type_name'    => $validated['type_name'] ?? null,

            ]);


            if (!$event) {
                throw new \Exception('Failed to create event');
            }

            $schedule = EventSchedule::create([
                'event_id' => $event->id,
                'venue_name'   => $validated['venue_name'] ?? null,
                'mode_name'    => $validated['mode_name'] ?? null,
                'qualifications'  => $validated['qualifications'] ?? null,
                'hours'  => $validated['hours'] ?? null,
                'fee'  => $validated['fee'] ?? null,
                'status'       => 'Created',
            ]);

            foreach ($validated['form'] ?? [] as $form) {
                EventForm::create([
                    'event_id'  => $event->id,
                    'form_name' => $form['form_name'],
                ]);
            }

            foreach ($validated['office'] ?? [] as $office) {
                EventDepartment::create([
                    'event_schedule_id'  => $schedule->id,
                    'office_name' => $office['office_name'],
                ]);
            }


            foreach ($validated['speaker'] ?? [] as $speaker) {
                EventSpeaker::create([
                    'event_schedule_id'  => $schedule->id,
                    'speaker_name' => $speaker['speaker_name'],
                ]);
            }

            foreach ($validated['DateTime'] ?? [] as $dateTime) {
                EventScheduleDateTime::create([
                    'event_schedule_id'  => $schedule->id,
                    'schedule_date' => $dateTime['schedule_date'] ?? null,
                    'morning_in' => $dateTime['morning_in'] ?? null,
                    'morning_out' => $dateTime['morning_out'] ?? null,
                    'afternoon_in' => $dateTime['afternoon_in'] ?? null,
                    'afternoon_out' => $dateTime['afternoon_out'] ?? null,
                ]);
            }

            return $event->load(['form', 'schedule']);
        });
    }


    public function delete(int $eventId)
    {

        $event = Event::find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        $event->delete();

        return $event;
    }

    public function editEvent(?array $validated, int $eventId)
    {
        return DB::transaction(function () use ($validated, $eventId) {

            $event = Event::find($eventId);

            if (!$event) {
                throw new \Exception('Event not found');
            }

            $event->update([
                'title_name'      => $validated['title_name'] ?? null,
                'source_name'     => $validated['source_name'] ?? null,
                'type_name'  => $validated['type_name'] ?? null,
            ]);

            return $event;
        });
    }


    private function formatDateRanges($scheduleDateTimes)
    {
        if ($scheduleDateTimes->isEmpty()) {
            return null;
        }

        $sorted = $scheduleDateTimes
            ->map(fn($item) => Carbon::parse($item->schedule_date)->startOfDay())
            ->sort()
            ->values();

        $ranges = [];
        $rangeStart = $sorted[0];
        $rangeEnd = $sorted[0];

        foreach ($sorted->slice(1) as $current) {
            if ($rangeEnd->copy()->addDay()->isSameDay($current)) {
                // consecutive, extend range
                $rangeEnd = $current;
            } else {
                $ranges[] = $this->formatRange($rangeStart, $rangeEnd);
                $rangeStart = $current;
                $rangeEnd = $current;
            }
        }
        $ranges[] = $this->formatRange($rangeStart, $rangeEnd);

        return implode(', ', $ranges);
    }

    private function formatRange(Carbon $start, Carbon $end)
    {
        if ($start->isSameDay($end)) {
            return $start->format('F j, Y'); // August 25, 2026
        }

        if ($start->isSameMonth($end)) {
            return $start->format('F j') . ' - ' . $end->format('j, Y'); // August 25 - 26, 2026
        }

        if ($start->isSameYear($end)) {
            return $start->format('F j') . ' - ' . $end->format('F j, Y'); // August 30 - September 2, 2026
        }

        return $start->format('F j, Y') . ' - ' . $end->format('F j, Y'); // diff year
    }
}
