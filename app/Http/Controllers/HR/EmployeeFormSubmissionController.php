<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
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
}
