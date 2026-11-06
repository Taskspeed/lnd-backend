<?php

namespace App\Http\Controllers\Arms;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeScheduleController extends Controller
{
    //
    use ApiResponseTrait;

    public function employeeScheduleEvent(int $eventScheduleId){

    
    
    $nominateEmployee = NominatedEmployee::select('id','control_no','office','full_name','designation','status','nominate_status')->where('event_schedule_id',$eventScheduleId)->where('nominate_status','Approved')->get();

      return $this->successMessage($nominateEmployee, 'employee list for this event', 200);
    }
}
