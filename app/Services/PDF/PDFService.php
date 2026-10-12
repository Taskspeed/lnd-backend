<?php

namespace App\Services\PDF;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\RSP\xPersonal;
use App\Models\RSP\yOffice;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
   
    public function attendance(int $eventId, int $eventSchduleId, int $scheduleDateTimeId)
    {

        $data = $this->nominatedEmployee($eventId, $eventSchduleId, $scheduleDateTimeId);

        return $data ;
    }


    private function nominatedEmployee(int $eventId, int $eventScheduleId, int $scheduleDateTimeId): array
    {
        $event = Event::with(['schedule' => function ($query) use ($eventScheduleId, $scheduleDateTimeId) {
            $query->where('id', $eventScheduleId)
                ->with(['scheduleDateTime' => function ($q) use ($scheduleDateTimeId) {
                    $q->where('id', $scheduleDateTimeId);
                }]);
        }])->findOrFail($eventId);

        $schedule = $event->schedule->first();

        // gumagana kahit hasOne o hasMany ang relation
        $dateTime = $schedule?->scheduleDateTime;
        $dateTime = $dateTime instanceof \Illuminate\Support\Collection ? $dateTime->first() : $dateTime;

        $nominees = NominatedEmployee::where('event_id', $eventId)
            ->where('event_schedule_id', $eventScheduleId)
            ->where('nominate_status', 'Approved')
            ->get();

        // Sex galing xPersonal (isang query)
        $controlNos = $nominees->pluck('control_no')->filter()->unique()->values();

        $personals = xPersonal::select('ControlNo', 'Sex')
            ->whereIn('ControlNo', $controlNos)
            ->get()
            ->keyBy(fn($p) => trim((string) $p->ControlNo));

        // Office abbreviation (isang query)
        $officeNames = $nominees->pluck('office')->filter()->unique()->values();

        $abbrByOffice = yOffice::select('Descriptions', 'Abbr')
            ->whereIn('Descriptions', $officeNames)
            ->get()
            ->keyBy(fn($o) => strtoupper(trim($o->Descriptions)))
            ->map(fn($o) => $o->Abbr);

        $nominees->each(function ($nominee) use ($abbrByOffice, $personals) {
            // office
            $key = strtoupper(trim((string) $nominee->office));
            $nominee->office_abbr = $abbrByOffice[$key] ?? $nominee->office;

            // sex -> "M" o "F" (kahit "Male"/"Female" ang laman ng database)
            $personal = $personals->get(trim((string) $nominee->control_no));
            $sex = strtoupper(trim((string) ($personal->Sex ?? '')));
            $nominee->sex = $sex !== '' ? $sex[0] : '';
        });

        return [
            'training_title'    => $event->title_name,
            'training_activity' => $schedule?->type_name,
            'training_venue'    => $schedule?->venue_name,
            'morning_in'        => $dateTime?->morning_in,
            'morning_out'       => $dateTime?->morning_out,
            'afternoon_in'      => $dateTime?->afternoon_in,
            'afternoon_out'     => $dateTime?->afternoon_out,
            'training_date'     => $dateTime
                ? \Carbon\Carbon::parse($dateTime->getRawOriginal('schedule_date'))->format('F d, Y')
                : '',
            'participants'      => $nominees,
        ];
    }
}
