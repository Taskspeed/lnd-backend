<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\RSP\xPersonal;
use App\Models\RSP\xPersonalAddt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NominationFormController extends Controller
{
   
    public function inHouseNomination(int $eventId, int $eventSchduleId)
    {
        $userAuth = Auth::user();
        $data = $this->employeeNominated($eventId,$eventSchduleId,$userAuth);

        $pdf = Pdf::loadView('forms.inhouse-nomination', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('inhouse-nomination-form.pdf');
    }

    
   
private function employeeNominated(int $eventId, int $eventScheduleId, Authenticatable $userAuth): array
{
    $event = Event::with(['schedule' => function ($query) use ($eventScheduleId) {
        $query->where('id', $eventScheduleId)->with('scheduleDateTime');
    }])->findOrFail($eventId);

    $schedule = $event->schedule->first();

    $nominees = NominatedEmployee::where('event_id', $eventId)
        ->where('event_schedule_id', $eventScheduleId)
        ->where('office', $userAuth->office)
        ->get();

    $controlNos = $nominees->pluck('control_no');

    $personals = xPersonal::select('ControlNo', 'Surname', 'Firstname', 'MIddlename', 'Sex')
        ->whereIn('ControlNo', $controlNos)
        ->get()
        ->keyBy('ControlNo');

    $contacts = xPersonalAddt::select('ControlNo', 'CellphoneNo')
        ->whereIn('ControlNo', $controlNos)
        ->get()
        ->keyBy('ControlNo');

   $participants = $nominees->map(function ($nominee) use ($personals, $contacts) {
    $personal = $personals->get($nominee->control_no);
    $contact  = $contacts->get($nominee->control_no);

    return [
        'surname'    => $personal->Surname ?? '',
        'first_name' => $personal->Firstname ?? '',
        'mi'         => Str::upper(Str::substr($personal->MIddlename ?? '', 0, 1)),
        'sex'        => Str::upper(Str::substr($personal->Sex ?? '', 0, 1)), // M/F
        'position'   => $nominee->designation,
        'relevance'  => $nominee->nominate_reason,
        'status'     => $nominee->status,
        'contact'    => $contact->CellphoneNo ?? '',
    ];
})->values()->all();

return [
    'date'          => now()->format('F d, Y'),
    'officeName'    => $userAuth->office,
    'office'        => $userAuth->office,
    'training_title'=> $event->title_name,
    'training_date' => $this->formatTrainingDates($schedule?->scheduleDateTime ?? []),
    'office_head'   => null, // lagyan mo kung saan mo kukunin ang pangalan ng office head
    'participants'  => $participants,
];
}

private function formatTrainingDates($scheduleDates): string
{
    $dates = collect($scheduleDates)
        ->map(fn ($s) => Carbon::parse($s->getRawOriginal('schedule_date'))->startOfDay())
        ->unique(fn ($d) => $d->toDateString())
        ->sort()
        ->values();

    if ($dates->isEmpty()) {
        return '';
    }

    // I-grupo ang magkakasunod na araw
    $groups = [];
    $start = $prev = $dates->first();

    foreach ($dates->slice(1) as $date) {
        if ($prev->copy()->addDay()->isSameDay($date)) {
            $prev = $date;
            continue;
        }
        $groups[] = [$start, $prev];
        $start = $prev = $date;
    }
    $groups[] = [$start, $prev];

    return collect($groups)->map(function ($g) {
        [$s, $e] = $g;

        if ($s->isSameDay($e)) {
            return $s->format('F j, Y');                             // October 5, 2026
        }
        if ($s->format('Y-m') === $e->format('Y-m')) {
            return $s->format('F j') . '-' . $e->format('j, Y');     // October 5-7, 2026
        }
        if ($s->year === $e->year) {
            return $s->format('F j') . ' - ' . $e->format('F j, Y'); // September 30 - October 2, 2026
        }
        return $s->format('F j, Y') . ' - ' . $e->format('F j, Y');  // December 30, 2026 - January 2, 2027
    })->implode(', ');
}

 
}
