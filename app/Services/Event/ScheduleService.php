<?php

namespace App\Services\Event;

use App\Models\Event\EventDepartment;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Models\Event\EventSpeaker;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function addSchedule(?array $validated)
    {

        return DB::transaction(function () use ($validated) {
            $schedule = EventSchedule::create([
                'event_id' => $validated['event_id'],
                'venue_name'   => $validated['venue_name'] ?? null,
                'mode_name'    => $validated['mode_name'] ?? null,
                'qualifications'  => $validated['qualifications'] ?? null,
                'hours'  => $validated['hours'] ?? null,
                'fee'  => $validated['fee'] ?? null,
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
                    'time_in' => $dateTime['time_in'] ?? null,
                    'time_out' => $dateTime['time_out'] ?? null,
                ]);
            }

            return $schedule->load(['office', 'speaker', 'scheduleDateTime']);
        });
    }


    public function editSchedule(int $eventScheduleId, ?array $validated)
    {
        return DB::transaction(function () use ($eventScheduleId, $validated) {

            $schedule = EventSchedule::find($eventScheduleId);

            if (!$schedule) {
                throw new \Exception("Event schedule are not found");
            }

            $schedule->update([
                'venue_name' => $validated['venue_name'] ?? null,
                'mode_name'  => $validated['mode_name'] ?? null,
                'qualifications'  => $validated['qualifications'] ?? null,
                'hours'  => $validated['hours'] ?? null,
                'fee'  => $validated['fee'] ?? null,
            ]);

            $schedule->office()->delete();

            foreach ($validated['office'] ?? [] as $office) {
                EventDepartment::create([
                    'event_schedule_id' => $schedule->id,
                    'office_name'       => $office['office_name'],
                ]);
            }

            $schedule->speaker()->delete();

            foreach ($validated['speaker'] ?? [] as $speaker) {
                EventSpeaker::create([
                    'event_schedule_id' => $schedule->id,
                    'speaker_name'      => $speaker['speaker_name'],
                ]);
            }

            $schedule->scheduleDateTime()->delete();

            foreach ($validated['DateTime'] ?? [] as $dateTime) {
                EventScheduleDateTime::create([
                    'event_schedule_id' => $schedule->id,
                    'schedule_date'     => $dateTime['schedule_date'] ?? null,
                    'time_in' => $dateTime['time_in'] ?? null,
                    'time_out' => $dateTime['time_out'] ?? null,
                ]);
            }

            return $schedule->load(['office', 'speaker', 'scheduleDateTime']);
        });
    }

    public function updateEventSchedule(array $validated, int $eventScheduleId)
    {

        $event = EventSchedule::find($eventScheduleId);

        if (!$event) {
            throw new \Exception('Event id not found');
        }

        $event->update($validated);

        return $event;
    }

    public function delete(int $eventScheduleId)
    {
        return DB::transaction(function () use ($eventScheduleId) {
            $event = EventSchedule::find($eventScheduleId);

            if (!$event) {
                throw new \Exception('Event Schedule not found');
            }

            if (in_array($event->status, ['Approved', 'Complete'])) {
                throw new \Exception('Cannot delete an approved event schedule');
            }

            $event->office()->delete();
            $event->speaker()->delete();
            $event->scheduleDateTime()->delete();
            $event->nominatedEmployee()->delete();

            $event->delete();

            return $event;
        });
    }
}
