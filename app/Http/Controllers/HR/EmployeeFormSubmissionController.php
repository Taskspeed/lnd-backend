<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Services\HR\Submission\EmployeeSubmissionService;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;
use Illuminate\Http\Request;

class EmployeeFormSubmissionController extends Controller
{

    use ApiResponseTrait;
    use FormatsDateRanges;

    protected EmployeeSubmissionService $employeeSubmissionService;

    public function __construct(EmployeeSubmissionService $employeeSubmissionService)
    {
        $this->employeeSubmissionService = $employeeSubmissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $controlNo = $request->input('control_no');
        $eventId = $request->input('event_id');
        $eventScheduleId = $request->input('event_schedule_id');

        $list_of_submission = EmployeeFormSubmission::where('control_no', $controlNo)->where('event_id', $eventId)->where('event_schedule_id', $eventScheduleId)->get();
        return $this->successMessage($list_of_submission, 'Fetch success list of employee submission', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $controlNo = $request->input('control_no');
        $formName = $request->input('form_name');
        $eventScheduleId = $request->input('event_schedule_id');

        try {
            $result = $this->employeeSubmissionService->showEmployeeFormSubmitted(
                $controlNo,
                $formName,
                $eventScheduleId
            );

            return $this->successMessage($result, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $employeeFormSubmissionId)
    {
        //
        $validated = $request->validate([
            'status' => 'required|in:Returned,Approved',
            'remarks' => 'nullable|string'
        ]);

        try {
            $result = $this->employeeSubmissionService->updateSubmission($validated, $employeeFormSubmissionId);

            return $this->successMessage($result, 'success updated', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 404);
        }
    }


    public function listOfEmployeeSubmitted(Request $request, int $scheduleId)
    {
        $perPage = $request->input('per_page', 10);
        $office  = $request->filled('office') ? trim($request->query('office')) : null; // optional
        $formStatus        = $request->query('form_status');         // complete | incomplete
        $certificateStatus = $request->query('certificate_issued');  // issued | pending
        $data = $this->employeeSubmissionService->submitted($perPage, $request, $scheduleId, $office, $formStatus, $certificateStatus);

        return $this->successMessage($data, 'list of employee form submitted', 200);
    }


    public function listOfEventParticipants(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 10), 100);
        $search  = trim((string) $request->input('search', ''));

        $schedules = EventSchedule::select('id', 'event_id')
            ->with(['event', 'scheduleDateTime'])
            ->withCount([
                'nominatedEmployee as participants_count' => fn($q) => $q->where('nominate_status', 'Approved'),
                'nominatedEmployee as certificates_issued_count' => fn($q) => $q->where('certificate_issued', true),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('event', function ($q) use ($search) {
                    $q->where('title_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString() // keeps per_page and search in the page links
            ->through(function ($schedule) {
                $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
                $schedule->unsetRelation('scheduleDateTime');
                return $schedule;
            });

        return $this->successMessage($schedules, 'success fetch', 200);
    }

    // get the event information for submission
    public function eventDetails(int $eventId, int $eventScheduleId)
    {
        $event = Event::with([
            'form',
            'schedule' => function ($query) use ($eventId, $eventScheduleId) {
                $query->with([
                    'speaker',
                    'scheduleDateTime',
                    'office',
                    'eventCore',
                    'eventTechnical',
                    'eventLeadership',
                ])
                    ->where('event_id', $eventId)
                    ->where('id', $eventScheduleId);
            },
        ])->find($eventId);

        if (!$event) {
            throw new \Exception('Event not found');
        }

        foreach ($event->schedule as $schedule) {
            // Office counts: query lang ang counts, hindi na isasama ang buong nominated employees sa response
            $counts = $schedule->nominatedEmployee()
                ->where('nominate_status','Approved')
                ->selectRaw('office, COUNT(*) as total')
                ->groupBy('office')
                ->pluck('total', 'office');

            foreach ($schedule->office as $office_row) {
                $office_row->employee_nominated = (int) $counts->get($office_row->office_name, 0);
            }
        }
        return $this->successMessage($event, 'Fetch Event schedule', 200);
    }
}
