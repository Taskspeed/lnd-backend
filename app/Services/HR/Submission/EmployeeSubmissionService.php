<?php

namespace App\Services\HR\Submission;

use App\Models\Event\EmployeeFormSubmission;
use Illuminate\Support\Facades\DB;

class EmployeeSubmissionService
{


    public function updateSubmission(?array $validated, int $employeeFormSubmissionId)
    {

        return DB::transaction(function ()  use ($validated,$employeeFormSubmissionId){

            $submission = EmployeeFormSubmission::find($employeeFormSubmissionId);

            if(!$submission){
                 throw new \Exception("Employee form not found");
            }

            $submission->update([
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null
            ]);

            return $submission;
        });
    }
}
