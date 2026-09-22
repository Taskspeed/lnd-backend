<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\Office\NominatedEmployeeRequest;
use App\Models\Employee\NominatedEmployee;
use App\Services\Office\EmployeeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnArgument;

class EmployeeController extends Controller
{
    //
    use ApiResponseTrait;

    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    // for nomination
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $trainingName = $request->query('training_name');
            $result = $this->employeeService->employeeListForNomination($trainingName, $user);

            return $this->successMessage($result, 'Success fetch employee list for nomination', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function store(NominatedEmployeeRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        try {
            $nominated_employee = $this->employeeService->create($validated, $user);

            return $this->successMessage($nominated_employee, 'success nominate employee', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destory(int $nominatedId)
    {

        try {
            $result = $this->employeeService->remove($nominatedId);

            return $this->successMessage($result, 'success deleted', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }


    // public function update(NominatedEmployeeRequest $request, int $nominatedId)
    // {
    //     $user = Auth::user();

    //     $validated = $request->validated([
    //           'event_id'   => 'required|exists:events,id',
    //           'control_no' => 'required|string',
    //     ]);

    //     try {
    //         $nominated_employee = $this->employeeService->update($nominatedId, $validated, $user);

    //         return $this->successMessage($nominated_employee, 'success update nominee', 200);
    //     } catch (\Throwable $e) {
    //         return $this->errorMessage($e->getMessage(), 500);
    //     }
    // }

    public function employeeNominatedByOffice(int $scheduleId)
    {

        $user = Auth::user();

        $employee  = NominatedEmployee::where('office', $user->office)->where('event_schedule_id', $scheduleId)->get();

        return $this->successMessage($employee, 'list of employee nominated', 200);
    }

    public function editReason(Request $request, int $nominatedEmployeeId)
    {

        $validated = $request->validate([
            'nominate_reason' => 'nullable|string|max:2000',
        ]);

        try {
            $result =  $this->employeeService->editReason($validated, $nominatedEmployeeId);

            return $this->successMessage($result, 'Reason updated successfully.', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
