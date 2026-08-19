<?php

namespace App\Services\Event;

use App\Models\Event\Event;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventForm;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Models\Event\EventSpeaker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService
{


    public function listOfEvent()
    {

        $event = Event::all();
        return $event;
    }

    public function show(int $eventId)
    {

        $event = Event::with([
            'form',
            'schedule' => function ($query) use ($eventId) {
                $query->with(['scheduleDateTime'])->select('id', 'event_id', 'venue_name', 'type_name', 'status')->where('event_id', $eventId)->get();
            }
        ])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        return $event;
    }

    public function nominatedEmployee(int $eventId,int $eventScheduleId)
    {

        $event = Event::with(['form', 'schedule' => function ($query) use ($eventId,$eventScheduleId) {
            $query->with(['office', 'speaker', 'nominatedEmployee'])
                ->where('event_id', $eventId)
                 ->where('id', $eventScheduleId)->get();
        }])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
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
                'qualifications'  => $validated['qualifications'] ?? null,
                'hours'  => $validated['hours'] ?? null,
                'fee'  => $validated['fee'] ?? null,

            ]);


            if (!$event) {
                throw new \Exception('Failed to create event');
            }

            $schedule = EventSchedule::create([
                'event_id' => $event->id,
                'venue_name'   => $validated['venue_name'] ?? null,
                'mode_name'    => $validated['mode_name'] ?? null,
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

    public function updateEventStatus(array $validated, int $eventScheduleId)
    {

        $event = EventSchedule::find($eventScheduleId);

        if (!$event) {
            throw new \Exception('Event id not found');
        }

        $event->update($validated);

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
            'qualifications'  => $validated['qualifications'] ?? null,
            'hours'           => $validated['hours'] ?? null,
            'fee'             => $validated['fee'] ?? null,
            'type_name'  => $validated['type_name'] ?? null,
        ]);

        // Update the existing schedule IN PLACE instead of delete+recreate,
        // so its id stays the same and NominatedEmployee rows stay valid/intact.
        $schedule = $event->schedule()->first();

        if ($schedule) {
            $schedule->update([
                'venue_name' => $validated['venue_name'] ?? null,
                'mode_name'  => $validated['mode_name'] ?? null,
            ]);
        } else {
            $schedule = EventSchedule::create([
                'event_id'   => $event->id,
                'venue_name' => $validated['venue_name'] ?? null,
                'mode_name'  => $validated['mode_name'] ?? null,
                // 'status'     => 'Created',
            ]);
        }

        // Only replace office/speaker/datetime — NominatedEmployee is untouched
        // since it stays attached to the same $schedule->id.
        EventDepartment::where('event_schedule_id', $schedule->id)->delete();
        EventSpeaker::where('event_schedule_id', $schedule->id)->delete();
        EventScheduleDateTime::where('event_schedule_id', $schedule->id)->delete();

        // form connects directly to event
        $event->form()->delete();

        foreach ($validated['form'] ?? [] as $form) {
            EventForm::create([
                'event_id'  => $event->id,
                'form_name' => $form['form_name'],
            ]);
        }

        foreach ($validated['office'] ?? [] as $office) {
            EventDepartment::create([
                'event_schedule_id' => $schedule->id,
                'office_name'       => $office['office_name'],
            ]);
        }

        foreach ($validated['speaker'] ?? [] as $speaker) {
            EventSpeaker::create([
                'event_schedule_id' => $schedule->id,
                'speaker_name'      => $speaker['speaker_name'],
            ]);
        }

        foreach ($validated['DateTime'] ?? [] as $dateTime) {
            EventScheduleDateTime::create([
                'event_schedule_id' => $schedule->id,
                'schedule_date'     => $dateTime['schedule_date'] ?? null,
                'morning_in'        => $dateTime['morning_in'] ?? null,
                'morning_out'       => $dateTime['morning_out'] ?? null,
                'afternoon_in'      => $dateTime['afternoon_in'] ?? null,
                'afternoon_out'     => $dateTime['afternoon_out'] ?? null,
            ]);
        }

        return $event->load(['form', 'schedule.office', 'schedule.speaker',]);
    });
}

    public function addSchedule(?array $validated)
    {

        return DB::transaction(function () use ($validated) {
            $schedule = EventSchedule::create([
                'event_id' => $validated['event_id'],
                'venue_name'   => $validated['venue_name'] ?? null,
                'mode_name'    => $validated['mode_name'] ?? null,
                'status'       => 'Created',
            ]);


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

            return $schedule->load(['office', 'speaker', 'scheduleDateTime']);
        });
    }
}
