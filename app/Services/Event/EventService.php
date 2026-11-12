<?php

namespace App\Services\Event;

use App\Models\ARMS\Tblevent;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Compentecy\EventCore;
use App\Models\Event\Compentecy\EventLeadership;
use App\Models\Event\Compentecy\EventTechnical;
use App\Models\Event\Event;
use App\Models\Event\EventAssessment;
use App\Models\Event\EventDepartment;
use App\Models\Event\EventEmployeeTag;
use App\Models\Event\EventForm;
use App\Models\Event\EventFormSchedule;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Models\Event\EventSpeaker;
use App\Traits\FormatsDateRanges;
use App\Traits\ResolvesOfficeAbbr;
use App\Traits\SortsByScheduleStatus;
use App\Traits\TimeFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Concerns\FormatsMessages;

class EventService
{
    use FormatsDateRanges;
    use SortsByScheduleStatus;
    use TimeFormat;
    use ResolvesOfficeAbbr;


    // public function listOfEvent(array $filters = [])
    // {
    //     $search   = trim($filters['search'] ?? '');
    //     $month = $filters['month'] ?? [];
    //     $year = $filters['year'] ?? [];
    //     $statuses = $filters['status'] ?? [];
    //     $perPage  = $filters['per_page'] ?? 10;
    //     $today    = now()->toDateString();

    //     // Kinukuha ang table at key names mula sa relationships, kaya hindi na hulaan
    //     $eventRel      = (new Event)->schedule();
    //     $scheduleModel = $eventRel->getRelated();
    //     $dateRel       = $scheduleModel->scheduleDateTime();
    //     $dateModel     = $dateRel->getRelated();

    //     $scheduleTable   = $scheduleModel->getTable();
    //     $dateTable       = $dateModel->getTable();
    //     $scheduleKey     = $scheduleModel->getQualifiedKeyName();      // schedules.id
    //     $scheduleEventFk = $eventRel->getQualifiedForeignKeyName();    // schedules.event_id
    //     $eventKey        = $eventRel->getQualifiedParentKeyName();     // events.id
    //     $dateFk          = $dateRel->getQualifiedForeignKeyName();     // dates.schedule_id
    //     $dateCol         = DB::getQueryGrammar()->wrap($dateModel->qualifyColumn('schedule_date'));

    //     // ID ng LATEST schedule ng event (pinakabagong schedule_date; tie-breaker: pinakabagong id)
    //     $latestScheduleId = DB::table($scheduleTable)
    //         ->join($dateTable, $dateFk, '=', $scheduleKey)
    //         ->whereColumn($scheduleEventFk, $eventKey)
    //         ->groupBy($scheduleKey)
    //         ->orderByRaw("MAX($dateCol) DESC")
    //         ->orderByDesc($scheduleKey)
    //         ->limit(1)
    //         ->select($scheduleKey);

    //     // Pinakabagong schedule date ng event, para sa pag-order ng listahan
    //     $latestDate = DB::table($scheduleTable)
    //         ->join($dateTable, $dateFk, '=', $scheduleKey)
    //         ->whereColumn($scheduleEventFk, $eventKey)
    //         ->selectRaw("MAX($dateCol)");

    //     $events = Event::query()
    //         ->with(['schedule.scheduleDateTime'])

    //         // SEARCH (title)
    //         ->when($search !== '', fn($q) => $q->where('title', 'like', "%{$search}%"))

    //         // STATUS: base lang sa LATEST schedule ng event
    //         ->when(!empty($statuses), function ($q) use ($statuses, $today, $latestScheduleId) {
    //             $q->whereHas('schedule', function ($schedule) use ($statuses, $today, $latestScheduleId) {

    //                 // 👇 ito ang bago: schedule na latest lang ang tinitingnan
    //                 $schedule->whereIn($schedule->getModel()->getQualifiedKeyName(), $latestScheduleId);

    //                 $schedule->where(function ($sq) use ($statuses, $today) {
    //                     foreach ($statuses as $status) {
    //                         match ($status) {
    //                             // lahat ng dates ay nasa future
    //                             'up-coming' => $sq->orWhere(function ($c) use ($today) {
    //                                 $c->whereHas('scheduleDateTime')
    //                                     ->whereDoesntHave(
    //                                         'scheduleDateTime',
    //                                         fn($d) => $d->whereDate('schedule_date', '<=', $today)
    //                                     );
    //                             }),

    //                             // may date na <= today AT may date na >= today
    //                             'ongoing' => $sq->orWhere(function ($c) use ($today) {
    //                                 $c->whereHas(
    //                                     'scheduleDateTime',
    //                                     fn($d) => $d->whereDate('schedule_date', '<=', $today)
    //                                 )
    //                                     ->whereHas(
    //                                         'scheduleDateTime',
    //                                         fn($d) => $d->whereDate('schedule_date', '>=', $today)
    //                                     );
    //                             }),

    //                             // lahat ng dates ay nasa past
    //                             'complete' => $sq->orWhere(function ($c) use ($today) {
    //                                 $c->whereHas('scheduleDateTime')
    //                                     ->whereDoesntHave(
    //                                         'scheduleDateTime',
    //                                         fn($d) => $d->whereDate('schedule_date', '>=', $today)
    //                                     );
    //                             }),
    //                         };
    //                     }
    //                 });
    //             });
    //         })

    //         // ORDER: event na may pinakabagong schedule date ang mauna
    //         ->orderByDesc($latestDate)
    //         ->orderByDesc('events.id')

    //         ->paginate($perPage)
    //         ->withQueryString();

    //     // Format per schedule, tapos itago yung raw scheduleDateTime
    //     $events->through(function ($event) {
    //         // sa loob ng event, pinakabagong schedule muna (Nov bago Oct)
    //         $sorted = $event->schedule
    //             ->sortByDesc(fn($s) => $s->scheduleDateTime->max('schedule_date') ?? '')
    //             ->values();

    //         $event->setRelation('schedule', $sorted);

    //         foreach ($event->schedule as $schedule) {
    //             $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
    //             $schedule->unsetRelation('scheduleDateTime');
    //         }

    //         return $event;
    //     });

    //     return $events;
    // }
    public function listOfEvent(array $filters = [])
    {
        $search   = trim($filters['search'] ?? '');
        $year     = !empty($filters['year'])  ? (int) $filters['year']  : null;
        $month    = !empty($filters['month']) ? (int) $filters['month'] : null;
        $statuses = $filters['status'] ?? [];
        $perPage  = $filters['per_page'] ?? 10;
        $today    = now()->toDateString();

        // Get table and key names from the relationships, so nothing is hardcoded
        $eventRel      = (new Event)->schedule();
        $scheduleModel = $eventRel->getRelated();
        $dateRel       = $scheduleModel->scheduleDateTime();
        $dateModel     = $dateRel->getRelated();

        $scheduleTable   = $scheduleModel->getTable();
        $dateTable       = $dateModel->getTable();
        $scheduleKey     = $scheduleModel->getQualifiedKeyName();      // schedules.id
        $scheduleEventFk = $eventRel->getQualifiedForeignKeyName();    // schedules.event_id
        $eventKey        = $eventRel->getQualifiedParentKeyName();     // events.id
        $dateFk          = $dateRel->getQualifiedForeignKeyName();     // dates.schedule_id
        $dateCol         = DB::getQueryGrammar()->wrap($dateModel->qualifyColumn('schedule_date'));

        // ID of the event's LATEST schedule (latest schedule_date; tie-breaker: highest id)
        $latestScheduleId = DB::table($scheduleTable)
            ->join($dateTable, $dateFk, '=', $scheduleKey)
            ->whereColumn($scheduleEventFk, $eventKey)
            ->groupBy($scheduleKey)
            ->orderByRaw("MAX($dateCol) DESC")
            ->orderByDesc($scheduleKey)
            ->limit(1)
            ->select($scheduleKey);

        // Latest schedule date of the event, used for ordering the list
        $latestDate = DB::table($scheduleTable)
            ->join($dateTable, $dateFk, '=', $scheduleKey)
            ->whereColumn($scheduleEventFk, $eventKey)
            ->selectRaw("MAX($dateCol)");

        $events = Event::query()
            ->with(['schedule.scheduleDateTime'])

            // SEARCH (title)
            ->when($search !== '', fn($q) => $q->where('title', 'like', "%{$search}%"))

            // YEAR / MONTH: both must match the SAME date row
            ->when($year || $month, function ($q) use ($year, $month) {
                $q->whereHas('schedule.scheduleDateTime', function ($d) use ($year, $month) {
                    $d->when($year,  fn($x) => $x->whereYear('schedule_date', $year))
                        ->when($month, fn($x) => $x->whereMonth('schedule_date', $month));
                });
            })

            // STATUS: based only on the event's LATEST schedule
            ->when(!empty($statuses), function ($q) use ($statuses, $today, $latestScheduleId) {
                $q->whereHas('schedule', function ($schedule) use ($statuses, $today, $latestScheduleId) {

                    $schedule->whereIn($schedule->getModel()->getQualifiedKeyName(), $latestScheduleId);

                    $schedule->where(function ($sq) use ($statuses, $today) {
                        foreach ($statuses as $status) {
                            match ($status) {
                                // all dates are in the future
                                'up-coming' => $sq->orWhere(function ($c) use ($today) {
                                    $c->whereHas('scheduleDateTime')
                                        ->whereDoesntHave(
                                            'scheduleDateTime',
                                            fn($d) => $d->whereDate('schedule_date', '<=', $today)
                                        );
                                }),

                                // has a date <= today AND a date >= today
                                'ongoing' => $sq->orWhere(function ($c) use ($today) {
                                    $c->whereHas(
                                        'scheduleDateTime',
                                        fn($d) => $d->whereDate('schedule_date', '<=', $today)
                                    )
                                        ->whereHas(
                                            'scheduleDateTime',
                                            fn($d) => $d->whereDate('schedule_date', '>=', $today)
                                        );
                                }),

                                // all dates are in the past
                                'complete' => $sq->orWhere(function ($c) use ($today) {
                                    $c->whereHas('scheduleDateTime')
                                        ->whereDoesntHave(
                                            'scheduleDateTime',
                                            fn($d) => $d->whereDate('schedule_date', '>=', $today)
                                        );
                                }),
                            };
                        }
                    });
                });
            })

            // ORDER: event with the latest schedule date comes first
            ->orderByDesc($latestDate)
            ->orderByDesc('events.id')

            ->paginate($perPage)
            ->withQueryString();

        // Format per schedule, then hide the raw scheduleDateTime
        $events->through(function ($event) {
            // within each event, latest schedule first (Nov before Oct)
            $sorted = $event->schedule
                ->sortByDesc(fn($s) => $s->scheduleDateTime->max('schedule_date') ?? '')
                ->values();

            $event->setRelation('schedule', $sorted);

            foreach ($event->schedule as $schedule) {
                $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
                $schedule->unsetRelation('scheduleDateTime');
            }

            return $event;
        });

        return $events;
    }

    public function show(int $eventId, int $perPage = 10)
    {
        $event = Event::find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        $schedules = $this->paginateByScheduleStatus(
            $event->schedule()
                ->select('id', 'event_id', 'venue_name', 'mode_name', 'status', 'hours')
                ->with('scheduleDateTime')
                ->withCount([
                    // 'nominatedEmployee as participants_count',
                    'nominatedEmployee as approved_count' => fn($q) => $q->where('nominate_status', 'Approved'),
                    'nominatedEmployee as pending_count' => fn($q) => $q->where('nominate_status', 'Pending'),
                ])
                ->get(),
            $perPage
        )->through(function ($schedule) {
            $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
            unset($schedule->scheduleDateTime);

            return $schedule;
        });

        $event->setRelation('schedule', $schedules);

        return $event;
    }

    public function nominatedEmployee(int $eventId, int $eventScheduleId, int $perPage = 10, ?string $office = null, ?string $status = null)
    {
        $event = Event::with([
      
            'schedule' => function ($query) use ($eventId, $eventScheduleId) {
                $query->with([

                    'office',
                    'nominatedEmployee', // buo pa rin, para sa count per office
                    'eventCore',
                    'eventTechnical',
                    'eventLeadership',
                    'eventAssessment',
                    'scheduleDateTime' => function ($query) {
                        $query->with('speakers');
                    },
                ])
                    ->where('event_id', $eventId)
                    ->where('id', $eventScheduleId);
            }
        ])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        foreach ($event->schedule as $schedule) {
            $counts = $schedule->nominatedEmployee
                ->groupBy('office')
                ->map->count();

            foreach ($schedule->office as $office_row) {
                $office_row->employee_nominated = $counts->get($office_row->office_name, 0);
                $office_row->office_abbr = $this->officeAbbr($office_row->office_name);
            }

            // Base query: office filter lang
            $base = NominatedEmployee::where('event_schedule_id', $schedule->id)
                ->when($office, fn($q) => $q->where('office', $office));

            // Stats: isang query, hindi apektado ng status filter
            $statusCounts = (clone $base)
                ->selectRaw('nominate_status, COUNT(*) as aggregate')
                ->groupBy('nominate_status')
                ->pluck('aggregate', 'nominate_status');

            $schedule->nomination_stats = [
                'total'       => (int) $statusCounts->sum(),
                'approved'    => (int) ($statusCounts['Approved'] ?? 0),
                'pending'     => (int) ($statusCounts['Pending'] ?? 0),
                'disapproved' => (int) ($statusCounts['Disapproved'] ?? 0),
            ];

            // Table: office + status filter
            $paginator = (clone $base)
                ->when($status, fn($q) => $q->where('nominate_status', $status))
                ->paginate($perPage)
                ->withQueryString();

            $paginator->getCollection()->each(function ($row) {
                $row->office_abbr = $this->officeAbbr($row->office);
            });

            $schedule->setRelation('nominatedEmployee', $paginator);
        }

        return $event;
    }
    // create event
    public function create(?array $validated)
    {
   
        return DB::transaction(function () use ($validated) {

            // check if the event title already exists
            $event_title_name_exist = Event::where('title_name', $validated['title_name'])->first();

            if ($event_title_name_exist) {
                throw new \Exception('Event name already exists, not allowed to create a new one. Please add a new schedule.');
            }

            $event = Event::create([
                'title_name' => $validated['title_name'] ?? null,
            ]);

            if (!$event) {
                throw new \Exception('Failed to create event');
            }

            $schedule = EventSchedule::create([
                'event_id'              => $event->id,
                'venue_name'            => $validated['venue_name'] ?? null,
                'mode_name'             => $validated['mode_name'] ?? null,
                'qualifications'        => $validated['qualifications'] ?? null,
                'hours'                 => $validated['hours'] ?? null,
                'fee'                   => $validated['fee'] ?? null,
                'source_name'           => $validated['source_name'] ?? null,
                'type_name'             => $validated['type_name'] ?? null,
                'category_name'         => $validated['category_name'] ?? null,
                'conducted_by'          => $validated['conducted_by'] ?? null,
                'learning_intervention' => $validated['learning_intervention'] ?? null,
                'status'                => 'Created',
                'sync_to_arms'          => $validated['sync_to_arms'] ?? false,
                'form_open_date'          => $validated['form_open_date'] ?? null,
                'form_due_date'          => $validated['form_due_date'] ?? null,
                'sg_from'   => $validated['sg_from'] ?? null,
                'sg_to'  => $validated['sg_to'] ?? null,
            ]);

            // ---------- save into ARMS (Tblevent) ----------
            if (!empty($validated['sync_to_arms'])) {
                Log::info('ARMS sync started', ['title' => $validated['title_name']]);

                $savedCount = 0;

                foreach ($validated['DateTime'] ?? [] as $dateTime) {

                    // one row for morning, one row for afternoon (only if it has a value)
                    foreach ([['morning_in', 'morning_out'], ['afternoon_in', 'afternoon_out']] as [$in, $out]) {

                        if (empty($dateTime[$in]) && empty($dateTime[$out])) {
                            continue;
                        }

                        $slot = $in === 'morning_in' ? 'morning' : 'afternoon';

                        try {
                            $armsEvent = Tblevent::create([
                                'Event_name'   => $validated['title_name'],
                                'Event_date'   => $dateTime['schedule_date'] ?? null,
                                'Event_from'   => $this->toTime24($dateTime[$in] ?? null),
                                'Event_to'     => $this->toTime24($dateTime[$out] ?? null),
                                'Event_venue'  => $validated['venue_name'] ?? null,
                                'is_lnd_event'  => true,
                                'userid'       => 0,
                                'date_created' => now()->format('Y-m-d H:i:s'),
                                'event_schedule_id' =>   $schedule->id,
                            ]);

                            if ($armsEvent->exists) {
                                $savedCount++;
                                // Log::info('ARMS event saved', [
                                //     'id'   => $armsEvent->getKey(),
                                //     'date' => $dateTime['schedule_date'] ?? null,
                                //     'slot' => $slot,
                                // ]);
                            } else {
                                // Log::warning('ARMS event NOT saved', [
                                //     'date' => $dateTime['schedule_date'] ?? null,
                                //     'slot' => $slot,
                                // ]);
                            }
                        } catch (\Throwable $e) {
                            // Log::error('ARMS event failed', [
                            //     'error' => $e->getMessage(),
                            //     'date'  => $dateTime['schedule_date'] ?? null,
                            //     'slot'  => $slot,
                            // ]);
                            throw $e; // rollback the whole transaction
                        }
                    }
                }

                // Log::info('ARMS sync finished', ['rows_saved' => $savedCount]);
            }
            // ---------- end ARMS ----------

            EventCore::create([
                'event_schedule_id'             => $schedule->id,
                'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? false,
                'exemplifying_integrity'        => $validated['exemplifying_integrity'] ?? false,
                'interpersonal_skills'          => $validated['interpersonal_skills'] ?? false,
            ]);

            EventTechnical::create([
                'event_schedule_id'     => $schedule->id,
                'planning_organizing'   => $validated['planning_organizing'] ?? false,
                'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? false,
                'records_management'    => $validated['records_management'] ?? false,
                'partnering_networking' => $validated['partnering_networking'] ?? false,
                'process_management'    => $validated['process_management'] ?? false,
                'attention_details'     => $validated['attention_details'] ?? false,
            ]);

            EventLeadership::create([
                'event_schedule_id'                                      => $schedule->id,
                'managing_performance_coaching_results'                  => $validated['managing_performance_coaching_results'] ?? false,
                'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? false,
                'thinking_strategically_creatively'                      => $validated['thinking_strategically_creatively'] ?? false,
                'problem_solving_decision_making'                        => $validated['problem_solving_decision_making'] ?? false,
            ]);


            EventAssessment::create([
                'event_schedule_id'                                      => $schedule->id,
                'pre_test_assessment'                  => $validated['pre_test_assessment'] ?? Null,
                'post_test_assessment' => $validated['post_test_assessment'] ?? Null,
                'is_assessment_open' => $form['is_assessment_open'] ?? false,

            ]);
            foreach ($validated['employee'] ?? [] as $emp) {
                EventEmployeeTag::create([
                    'event_schedule_id' => $schedule->id,
                    'name'              => $emp['name'] ?? null,
                    'control_no'        => $emp['control_no'] ?? null,
                    'office'            => $emp['office'] ?? null,
                    'position'          => $emp['position'] ?? null,
                    'status'            => $emp['status'] ?? null,
                ]);
            }

            foreach ($validated['form'] ?? [] as $form) {
                EventForm::create([
                    'event_schedule_id'  => $schedule->id,
                    'form_name' => $form['form_name'] ?? null,
                ]);
            }


            foreach ($validated['office'] ?? [] as $office) {
                EventDepartment::create([
                    'event_schedule_id'   => $schedule->id,
                    'office_name'         => $office['office_name'] ?? null,
                    'number_of_attendees' => $office['number_of_attendees'] ?? 0,
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

            // Notify office admins of each included office
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
                    Log::info('No office admins found for offices: ' . $officeNames->implode(', '));
                }
            }



            return $event->load(['schedule']);
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
          
            'schedule' => function ($query) use ($eventId, $eventScheduleId) {
                $query->with([
                    'speaker',
                    'scheduleDateTime',
                    'office',
                    'form',
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
