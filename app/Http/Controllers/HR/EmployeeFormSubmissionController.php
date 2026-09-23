<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Services\HR\Submission\EmployeeSubmissionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeFormSubmissionController extends Controller
{

    use ApiResponseTrait;

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private const REQUIRED_APPROVALS = 4;


    public function listOfEmployeeSubmitted(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $list = NominatedEmployee::query()
            ->where('nominate_status', 'Approved')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%');
            })
            // only include nominees who actually have at least one form submission
            ->whereHas('formSubmissions', function ($q) use ($request) {
                $q->when(
                    $request->filled('event_schedule_id'),
                    fn($q2) => $q2->where('event_schedule_id', $request->event_schedule_id)
                );
            })
            ->with(['formSubmissions' => function ($q) use ($request) {
                $q->when(
                    $request->filled('event_schedule_id'),
                    fn($q2) => $q2->where('event_schedule_id', $request->event_schedule_id)
                );
            }])
            ->orderBy('control_no')
            ->paginate($perPage)
            ->through(function ($nominee) {
                $submissions = $nominee->formSubmissions;

                $pending  = $submissions->where('status', 'Pending')->count();
                $approved = $submissions->where('status', 'Approved')->count();
                $returned = $submissions->where('status', 'Returned')->count();

                // control_no / event_schedule_id dapat magkapareho sa lahat ng
                // submissions ng isang nominee, kaya kunin na lang natin sa una
                $first = $submissions->first();

                return [
                    'control_no'            => $first->control_no,
                    'full_name'             => $nominee->full_name,
                    'event_id'              => $nominee->event_id,
                    'nominated_employee_id' => $nominee->id,
                    'event_schedule_id'     => (int) $first->event_schedule_id,
                    'pending'               => $pending,
                    'approved'              => $approved,
                    'returned'              => $returned,
                    'required_approvals'    => self::REQUIRED_APPROVALS,
                    'certificate_status'    => $approved >= self::REQUIRED_APPROVALS
                        ? 'Complete'
                        : 'Incomplete',
                ];
            });

        return $this->successMessage($list, 'list of employee form submitted', 200);
    }
}
