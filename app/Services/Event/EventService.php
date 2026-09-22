<?php

namespace App\Services\Event;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Compentecy\EventCore;
use App\Models\Event\Compentecy\EventLeadership;
use App\Models\Event\Compentecy\EventTechnical;
use App\Models\Event\Event;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventEmployeeTag;
use App\Models\Event\EventForm;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Models\Event\EventSpeaker;
use App\Traits\FormatsDateRanges;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Concerns\FormatsMessages;

class EventService
{
    use FormatsDateRanges;

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

    public function nominatedEmployee(int $eventId, int $eventScheduleId, int $perPage = 10)
    {
        $event = Event::with([
            'form',
            'schedule' => function ($query) use ($eventId, $eventScheduleId) {
                $query->with([
                    'speaker',
                    'scheduleDateTime',
                    'office',
                    'nominatedEmployee' // kailangan pa rin ito, buo, para sa count
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
                ->groupBy('office')
                ->map->count();

            foreach ($schedule->office as $office) {
                $office->employee_nominated = $counts->get($office->office_name, 0);
            }

            // palitan ang buong collection ng paginated version para sa display
            $schedule->setRelation(
                'nominatedEmployee',
                NominatedEmployee::where('event_schedule_id', $schedule->id)
                    ->paginate($perPage)
            );
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
                // 'intervention'   => $validated['intervention'] ?? null,
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
                'source_name'  => $validated['source_name'] ?? null,
                'type_name'    => $validated['type_name'] ?? null,
                'category_name'    => $validated['category_name'] ?? null,
                'conducted_by'    => $validated['conducted_by'] ?? null,
                'status'       => 'Created',
            ]);


            EventCore::create([
                'event_schedule_id' => $schedule->id,
                'delivering_service_excellence'   => $validated['delivering_service_excellence'] ?? false,
                'exemplifying_integrity'    => $validated['exemplifying_integrity'] ?? false,
                'interpersonal_skills'  => $validated['interpersonal_skills'] ?? false,
            ]);

            EventTechnical::create([
                'event_schedule_id' => $schedule->id,
                'planning_organizing'   => $validated['planning_organizing'] ?? false,
                'monitoring_evaluation'    => $validated['monitoring_evaluation'] ?? false,
                'records_management'  => $validated['records_management'] ?? false,
                'partnering_networking'  => $validated['partnering_networking'] ?? false,
                'process_management'  => $validated['process_management'] ?? false,
                'attention_details'  => $validated['attention_details'] ?? false,


            ]);
            EventLeadership::create([
                'event_schedule_id' => $schedule->id,
                'managing_performance_coaching_results'   => $validated['managing_performance_coaching_results'] ?? false,
                'building_collaborative_inclusive_working_relationships'    => $validated['building_collaborative_inclusive_working_relationships'] ?? false,
                'thinking_strategically_creatively'  => $validated['thinking_strategically_creatively'] ?? false,
                'problem_solving_decision_making'  => $validated['problem_solving_decision_making'] ?? false,

            ]);


            foreach ($validated['employee'] as $emp) {
                EventEmployeeTag::create([
                    'event_schedule_id' => $schedule->id,
                    'name'       => $emp['name'] ?? null,
                    'control_no' => $emp['control_no'] ?? null,
                    'office'     => $emp['office'] ?? null,
                    'position'   => $emp['position'] ?? null,
                    'status'     => $emp['status'] ?? null,
                ]);
            }

            foreach ($validated['form'] ?? [] as $form) {
                EventForm::create([
                    'event_id'  => $event->id,
                    'form_name' => $form['form_name'] ?? null,
                ]);
            }

            foreach ($validated['office'] ?? [] as $office) {
                EventDepartment::create([
                    'event_schedule_id'  => $schedule->id,
                    'office_name' => $office['office_name'] ?? null,
                ]);
            }

        // Notify office admins ng bawat kasamang office
            $officeNames = collect($validated['office'] ?? [])->pluck('office_name')->filter();
            
            if ($officeNames->isNotEmpty()) {
                $officeAdmins = \App\Models\User::whereIn('office', $officeNames)
                    ->role('office_admin')
                    ->get();

                if ($officeAdmins->isNotEmpty()) {
                    foreach ($officeAdmins as $admin) {
                        $admin->notify(new \App\Notifications\EventCreated($event, $schedule));
                    }
                } else {
                    Log::info("No office admins found for offices: " . $officeNames->implode(', '));
                }
            }

            foreach ($validated['speaker'] ?? [] as $speaker) {
                EventSpeaker::create([
                    'event_schedule_id'  => $schedule->id,
                    'speaker_name' => $speaker['speaker_name'] ?? null,
                    'position' => $speaker['position'] ?? null,
                    'agency' => $speaker['agency'] ?? null,
                ]);
            }

            foreach ($validated['DateTime'] ?? [] as $dateTime) {
                EventScheduleDateTime::create([
                    'event_schedule_id'  => $schedule->id,
                    'schedule_date' => $dateTime['schedule_date'] ?? null,
                    // 'time_in' => $dateTime['time_in'] ?? null,
                    // 'time_out' => $dateTime['time_out'] ?? null,
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
                // 'source_name'     => $validated['source_name'] ?? null,
                // 'type_name'  => $validated['type_name'] ?? null,
                // 'category_name'  => $validated['category_name'] ?? null,
            ]);

            return $event;
        });
    }

    public function details(int $eventId, int $eventScheduleId)
    {
        $event = Event::with([
            'form',
            'schedule' => function ($query) use ($eventId, $eventScheduleId) {
                $query->with([
                    'speaker',
                    'scheduleDateTime',
                    'office',
                ])
                    ->where('event_id', $eventId)
                    ->where('id', $eventScheduleId);
            }
        ])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        return $event;
    }
}
