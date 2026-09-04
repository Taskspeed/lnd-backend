<?php

namespace App\Services\Event;

use App\Models\Event\Compentecy\EventCore;
use App\Models\Event\Compentecy\EventLeadership;
use App\Models\Event\Compentecy\EventTechnical;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventEmployeeTag;
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
                'source_name'  => $validated['source_name'] ?? null,
                'type_name'    => $validated['type_name'] ?? null,
                'category_name'    => $validated['category_name'] ?? null,
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


    public function editSchedule(int $eventScheduleId, ?array $validated)
    {
        return DB::transaction(function () use ($eventScheduleId, $validated) {

            $schedule = EventSchedule::find($eventScheduleId);

            if (!$schedule) {
                throw new \Exception("Event schedule are not found");
            }

            $schedule->update([
                'venue_name'   => $validated['venue_name'] ?? null,
                'mode_name'    => $validated['mode_name'] ?? null,
                'qualifications'  => $validated['qualifications'] ?? null,
                'hours'  => $validated['hours'] ?? null,
                'fee'  => $validated['fee'] ?? null,
                'source_name'  => $validated['source_name'] ?? null,
                'type_name'    => $validated['type_name'] ?? null,
                'category_name'    => $validated['category_name'] ?? null,
                // 'status'       => 'Created',
            ]);


            $schedule->eventCore()->delete();

            EventCore::create([
                'event_schedule_id' => $schedule->id,
                'delivering_service_excellence'   => $validated['delivering_service_excellence'] ?? false,
                'exemplifying_integrity'    => $validated['exemplifying_integrity'] ?? false,
                'interpersonal_skills'  => $validated['interpersonal_skills'] ?? false,
            ]);

             $schedule->eventTechnical()->delete();

            EventTechnical::create([
                'event_schedule_id' => $schedule->id,
                'planning_organizing'   => $validated['planning_organizing'] ?? false,
                'monitoring_evaluation'    => $validated['monitoring_evaluation'] ?? false,
                'records_management'  => $validated['records_management'] ?? false,
                'partnering_networking'  => $validated['partnering_networking'] ?? false,
                'process_management'  => $validated['process_management'] ?? false,
                'attention_details'  => $validated['attention_details'] ?? false,

            ]);

            $schedule->eventLeadership()->delete();

            EventLeadership::create([
                'event_schedule_id' => $schedule->id,
                'managing_performance_coaching_results'   => $validated['managing_performance_coaching_results'] ?? false,
                'building_collaborative_inclusive_working_relationships'    => $validated['building_collaborative_inclusive_working_relationships'] ?? false,
                'thinking_strategically_creatively'  => $validated['thinking_strategically_creatively'] ?? false,
                'problem_solving_decision_making'  => $validated['problem_solving_decision_making'] ?? false,
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
                    'morning_in' => $dateTime['morning_in'] ?? null,
                    'morning_out' => $dateTime['morning_out'] ?? null,
                    'afternoon_in' => $dateTime['afternoon_in'] ?? null,
                    'afternoon_out' => $dateTime['afternoon_out'] ?? null,
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
