<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\RSP\vwEmployee;
use App\Services\HR\Training\EmployeeTrainingService;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;

use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Request;

class OfficeEmployeeController extends Controller
{

    use ApiResponseTrait;
    use FormatsDateRanges;

    protected EmployeeTrainingService $employeeTrainingService;

    public function __construct(EmployeeTrainingService $employeeTrainingService)
    {
        $this->employeeTrainingService = $employeeTrainingService;
    }


    public function show(string $office, Request $request)
    {
        $titleName = $request->query('title');

        $employee =  $this->employeeTrainingService->employeeTrainings($office,$titleName,$request);

        return $this->successMessage($employee, 'Success fetch list of employee', 200);
    }

   
}
