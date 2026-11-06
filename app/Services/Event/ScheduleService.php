<?php

namespace App\Services\Event;

use App\Models\ARMS\Tblevent;
use App\Models\Event\Compentecy\EventCore;
use App\Models\Event\Compentecy\EventLeadership;
use App\Models\Event\Compentecy\EventTechnical;
use App\Models\Event\Event;
use App\Models\Event\EventAssessment;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventEmployeeTag;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Models\Event\EventSpeaker;
use App\Traits\TimeFormat;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleService
{
    use TimeFormat;

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
                'conducted_by'    => $validated['conducted_by'] ?? null,
                'status'       => 'Created',
                'learning_intervention'    => $validated['learning_intervention'] ?? null,
                'sync_to_arms'    => $validated['sync_to_arms'] ??  false,
            ]);

            $event  = Event::find($validated['event_id']);

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

            EventAssessment::create([
                'event_schedule_id' => $schedule->id,
                'pre_test_assessment'   => $validated['pre_test_assessment'] ?? Null,
                'post_test_assessment'    => $validated['post_test_assessment'] ?? Null,


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
                    'number_of_attendees'       => $office['number_of_attendees'] ?? 0,
                ]);
            }

            

            foreach ($validated['DateTime'] ?? [] as $dt) {
                $dateTime = EventScheduleDateTime::create([
                    'event_schedule_id' => $schedule->id,
                    'schedule_date'     => $dt['schedule_date'] ?? null,
                    'morning_in'        => $dt['morning_in'] ?? null,
                    'morning_out'       => $dt['morning_out'] ?? null,
                    'afternoon_in'      => $dt['afternoon_in'] ?? null,
                    'afternoon_out'     => $dt['afternoon_out'] ?? null,
                ]);

                foreach ($dt['speakers'] ?? [] as $speaker) {
                    $dateTime->speakers()->create([
                        'speaker_name' => $speaker['speaker_name'] ?? null,
                        'position'     => $speaker['position'] ?? null,
                        'agency'       => $speaker['agency'] ?? null,
                    ]);
                }
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


           


            // ---------- ARMS sync (last, after everything else succeeded) ----------
            if (!empty($validated['sync_to_arms'])) {
                // Log::info('ARMS sync started', ['title' => $event->title_name]);

                $savedCount = 0;

                foreach ($validated['DateTime'] ?? [] as $dateTime) {
                    foreach ([['morning_in', 'morning_out'], ['afternoon_in', 'afternoon_out']] as [$in, $out]) {

                        if (empty($dateTime[$in]) && empty($dateTime[$out])) {
                            continue;
                        }

                        $slot = $in === 'morning_in' ? 'morning' : 'afternoon';

                        try {
                            $armsEvent = Tblevent::create([
                                'Event_name'   => $event->title_name,
                                'Event_date'   => $dateTime['schedule_date'] ?? null,
                                'Event_from'   => $this->toTime24($dateTime[$in] ?? null),
                                'Event_to'     => $this->toTime24($dateTime[$out] ?? null),
                                'Event_venue'  => $validated['venue_name'] ?? null,
                                'userid'       => 0,
                                'is_lnd_event'  => true,
                                'date_created' => now()->format('Y-m-d H:i:s'),
                                'event_schedule_id' =>   $schedule->id,
                            ]);

                            $savedCount++;
                            // Log::info('ARMS event saved', [
                            //     'id'   => $armsEvent->getKey(),
                            //     'date' => $dateTime['schedule_date'] ?? null,
                            //     'slot' => $slot,
                            // ]);
                        } catch (\Throwable $e) {
                            // Log::error('ARMS event failed', [
                            //     'error' => $e->getMessage(),
                            //     'date'  => $dateTime['schedule_date'] ?? null,
                            //     'slot'  => $slot,
                            // ]);
                            throw $e; // rollback
                        }
                    }
                }

                Log::info('ARMS sync finished', ['rows_saved' => $savedCount]);
            }
            return $schedule->load(['office',  'scheduleDateTime']);
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
                'conducted_by'    => $validated['conducted_by'] ?? null,
                'learning_intervention'    => $validated['learning_intervention'] ?? null,
                'sync_to_arms'    => $validated['sync_to_arms'] ??  false,
            ]);

            $schedule->employeeTag()->delete();

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
            $schedule->eventAssessment()->delete();
            EventAssessment::create([
                'event_schedule_id' => $schedule->id,
                'pre_test_assessment'   => $validated['pre_test_assessment'] ?? Null,
                'post_test_assessment'   => $validated['post_test_assessment'] ?? Null,
            ]);

            $schedule->office()->delete();

            foreach ($validated['office'] ?? [] as $office) {
                EventDepartment::create([
                    'event_schedule_id' => $schedule->id,
                    'office_name'       => $office['office_name'],
                    'number_of_attendees'       => $office['number_of_attendees'] ?? 0,
                ]);
            }

            $dateTimeIds = $schedule->scheduleDateTime()->pluck('id');
            EventSpeaker::whereIn('schedule_date_time_id', $dateTimeIds)->delete();
            $schedule->scheduleDateTime()->delete();


            foreach ($validated['DateTime'] ?? [] as $dt) {
                $dateTime = EventScheduleDateTime::create([
                    'event_schedule_id' => $schedule->id,
                    'schedule_date'     => $dt['schedule_date'] ?? null,
                    'morning_in'        => $dt['morning_in'] ?? null,
                    'morning_out'       => $dt['morning_out'] ?? null,
                    'afternoon_in'      => $dt['afternoon_in'] ?? null,
                    'afternoon_out'     => $dt['afternoon_out'] ?? null,
                ]);

                foreach ($dt['speakers'] ?? [] as $speaker) {
                    $dateTime->speakers()->create([
                        'speaker_name' => $speaker['speaker_name'] ?? null,
                        'position'     => $speaker['position'] ?? null,
                        'agency'       => $speaker['agency'] ?? null,
                    ]);
                }
            }

            // ---------- ARMS sync (huling hakbang) ----------
            $event = Event::find($schedule->event_id);

            Tblevent::query()->getConnection()->transaction(function () use ($schedule, $event, $validated) {

                // Burahin muna ang lumang ARMS rows ng schedule na ito
                Tblevent::where('event_schedule_id', $schedule->id)->delete();

                // Kung naka-on ang sync, gumawa ng bago mula sa bagong dates/times
                if (!empty($validated['sync_to_arms'])) {
                    foreach ($validated['DateTime'] ?? [] as $dt) {
                        foreach ([['morning_in', 'morning_out'], ['afternoon_in', 'afternoon_out']] as [$in, $out]) {

                            if (empty($dt[$in]) && empty($dt[$out])) {
                                continue;
                            }

                            Tblevent::create([
                                'Event_name'        => $event->title_name,
                                'Event_date'        => $dt['schedule_date'] ?? null,
                                'Event_from'        => $this->toTime24($dt[$in] ?? null),
                                'Event_to'          => $this->toTime24($dt[$out] ?? null),
                                'Event_venue'       => $validated['venue_name'] ?? null,
                                'userid'            => 0,
                                'is_lnd_event'      => true,
                                'date_created'      => now()->format('Y-m-d H:i:s'),
                                'event_schedule_id' => $schedule->id,
                            ]);
                        }
                    }
                }
            });

            return $schedule->load(['office', 'scheduleDateTime.speakers']);
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
            // $event->speaker()->delete();
            $event->scheduleDateTime()->delete();
            $event->nominatedEmployee()->delete();

            $event->delete();

            return $event;
        });
    }

    public function eventViewSchedule(int $scheduleId)
    {

        $schedule  = EventSchedule::with(['eventCore', 'eventTechnical', 'eventLeaderShip', 'office', 'eventAssessment', 'employeeTag', 'scheduleDateTime' => function ($query) {
            $query->with('speakers');
        },])->find($scheduleId);

        if (!$scheduleId) {
            throw new \Exception('Schedule are not found');
        }

        return $schedule;
    }
}
