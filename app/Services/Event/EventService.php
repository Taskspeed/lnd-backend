<?php

namespace App\Services\Event;

use App\Models\Event\Event;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventForm;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventSpeaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService
{

    public function listOfEvent(){

        $event = Event::all();
        return $event;
    }

    public function show( int $eventId){

        $event = Event::with(['form','office','speaker','schedule'])->find($eventId);
        return $event;
    }

    // create event
    public function create(?array $validated)
    {
        $user = Auth::user();

        return DB::transaction(function () use ($validated, $user) {
            $event = Event::create([
                'title_name'   => $validated['title_name'],
                'venue_name'   => $validated['venue_name'],
                'type_name'    => $validated['type_name'],
                'source_name'  => $validated['source_name'],
                'qualifications'  => $validated['qualifications'],
                'hours'  => $validated['hours'],
                'fee'  => $validated['fee'],
                'created_by' => $user->name ?? null,
                'status'       => 'Pending',
            ]);

            if (!$event) {
                throw new \Exception('Failed to create event');
            }

            foreach ($validated['form'] ?? [] as $form) {
                EventForm::create([
                    'event_id'  => $event->id,
                    'form_name' => $form['form_name'],
                ]);
            }

            foreach ($validated['office'] ?? [] as $office) {
                EventDepartment::create([
                    'event_id'  => $event->id,
                    'office_name' => $office['office_name'],
                ]);
            }


            foreach ($validated['speaker'] ?? [] as $speaker) {
                EventSpeaker::create([
                    'event_id'  => $event->id,
                    'speaker_name' => $speaker['speaker_name'],
                ]);
            }

            foreach ($validated['schedule'] ?? [] as $schedule) {
                EventSchedule::create([
                    'event_id'  => $event->id,
                    'schedule_date' => $schedule['schedule_date'] ?? null,
                    'morning_in' => $schedule['morning_in'] ?? null,
                    'morning_out' => $schedule['morning_out'] ?? null,
                    'afternoon_in' => $schedule['afternoon_in'] ?? null,
                    'afternoon_out' => $schedule['afternoon_out'] ?? null,
                ]);
            }

            return $event->load(['form', 'office', 'schedule', 'speaker']);
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
}
