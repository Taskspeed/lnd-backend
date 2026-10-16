<?php

namespace App\Services\Excel;

use App\Models\Employee\NominatedEmployee;
use App\Models\RSP\vwEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExcelService
{
    public function generateAgencyLearningDevelopmentMonitoringReport(int $year)
    {

        // 1. Approved nominations, naka-group per employee
        $trainings = NominatedEmployee::select('id', 'control_no', 'event_schedule_id')
            ->with([
                'eventSchedule:id,event_id,learning_intervention',
                'eventSchedule.scheduleDateTime:id,event_schedule_id,schedule_date',
            ])
            ->where('nominate_status', 'Approved')
            ->get()
            ->groupBy('control_no');

        // 2. Training kada buwan (start date ng training ang batayan)
        $countByMonth = function ($controlNo) use ($trainings, $year) {
            $months = ($trainings[$controlNo] ?? collect())
                ->unique('event_schedule_id')
                ->map(function ($n) {
                    $schedule  = $n->eventSchedule;
                    $startDate = $schedule?->scheduleDateTime
                        ->sortBy('schedule_date')
                        ->first()?->schedule_date;

                    if (! $schedule || ! $startDate) {
                        return null;
                    }

                    return [
                        'event_schedule_id'     => $schedule->id,
                        'learning_intervention' => $schedule->learning_intervention,
                        'start_date'            => $startDate, // Carbon
                    ];
                })
                ->filter()
                ->filter(fn($t) => $t['start_date']->year == $year)
                ->groupBy(fn($t) => $t['start_date']->format('n'))
                ->sortKeys();

            return [
                'total'    => $months->flatten(1)->count(),
                'by_month' => $months->mapWithKeys(fn($items, $m) => [
                    Carbon::create(null, (int) $m, 1)->format('F') => [
                        'count'     => $items->count(),
                        'trainings' => $items->map(fn($t) => [
                            'event_schedule_id'     => $t['event_schedule_id'],
                            'learning_intervention' => $t['learning_intervention'],
                            'start_date'            => $t['start_date']->format('F d, Y'),
                        ])->values(),
                    ],
                ]),
            ];
        };

        // 3. Office > Status > Employees
        return vwEmployee::select('ControlNo', 'name', 'position', 'status', 'office')
            ->orderBy('office')
            ->orderBy('status')
            ->orderBy('name')
            ->get()
            ->groupBy('office')
            ->map(
                fn($officeGroup) => $officeGroup
                    ->groupBy('status')
                    ->map(
                        fn($statusGroup) => $statusGroup
                            ->map(function ($emp) use ($countByMonth) {
                                $t = $countByMonth($emp->ControlNo);

                                return [
                                    'ControlNo'          => $emp->ControlNo,
                                    'name'               => $emp->name,
                                    'position'           => $emp->position,
                                    'total_trainings'    => $t['total'],
                                    'trainings_by_month' => $t['by_month'],
                                ];
                            })
                            ->values()
                    )
            );
    }
}
