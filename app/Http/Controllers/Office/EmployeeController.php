<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\Office\NominatedEmployeeRequest;
use App\Services\Office\EmployeeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    //
    use ApiResponseTrait;

    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
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
            $result = $this->employeeService->delete($nominatedId);

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
}
