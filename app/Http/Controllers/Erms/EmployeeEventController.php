<?php

namespace App\Http\Controllers\Erms;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeEventController extends Controller
{

    use ApiResponseTrait;

    // need to optimize later on


    // get the list of event of employee
    public function index(string $controlNo)
    {
        //
        $employee = NominatedEmployee::with(['event' => function ($query) {
            $query->with(['schedule',]);
        }])->select('event_id', 'control_no', 'office')->where('control_no', $controlNo)->get();

        return $this->successMessage($employee, 'Employeee list of event', 200);
    }

    public function show(int $eventId, string $controlNo)
    {
        $employee = NominatedEmployee::with(['event' => function ($query) {
            $query->with(['schedule', 'speaker']);
        }])
            ->select('event_id', 'control_no', 'office')
            ->where('control_no', $controlNo)
            ->where('event_id', $eventId)
            ->first(); // isang event lang naman ang hahanapin, so first() na lang instead of get()

        if (!$employee) {
            return $this->errorMessage('Employee event not found', 404);
        }

        $submissions = EmployeeFormSubmission::where('control_no', $controlNo)
            ->where('event_id', $eventId)
            ->get();

        $totalRequired = $employee->event->form->count();
        $totalSubmitted = $submissions->count();

        $employee->forms_summary = [
            'total_required' => $totalRequired,
            'total_submitted' => $totalSubmitted,
            'remaining_form_need_to_submit'    => max($totalRequired - $totalSubmitted, 0),
            'approved'        => $submissions->where('status', 'Approved')->count(),
            'pending'         => $submissions->where('status', 'Pending')->count(),
            'returned'        => $submissions->where('status', 'Returned')->count(),
        ];

        $employee->submitted_forms = $submissions->map(function ($sub) {
            return [
                'form_name'    => $sub->form_name,
                'status'       => $sub->status,
                'submitted_at' => $sub->submitted_at,
                'remarks'      => $sub->remarks,
            ];
        })->values();

        return $this->successMessage($employee, 'Employee list of event', 200);
    }



    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
