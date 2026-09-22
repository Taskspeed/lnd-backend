<?php

namespace App\Services\HR\Submission;

use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Models\Forms\LIR\LearningImplementationForm;
use App\Models\Forms\LPR\LearnerProgressForm;
use Illuminate\Support\Facades\DB;

class EmployeeSubmissionService
{

    // for approval
    public function updateSubmission(?array $validated, int $employeeFormSubmissionId)
    {

        return DB::transaction(function ()  use ($validated, $employeeFormSubmissionId) {

            $submission = EmployeeFormSubmission::find($employeeFormSubmissionId);

            if (!$submission) {
                throw new \Exception("Employee form not found");
            }

            $submission->update([
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null
            ]);

            return $submission;
        });
    }


    // show the submission of the employee form
    public function showEmployeeFormSubmitted(string $controlNo, string $formName, int $eventScheduleId)
    {
        $employeeFormSubmission = EmployeeFormSubmission::where('control_no', $controlNo)
            ->where('form_name', $formName)
            ->where('event_schedule_id', $eventScheduleId)
            ->first();

        if (!$employeeFormSubmission) {
            throw new \Exception("No form submission found");
        }

        $formData = match ($employeeFormSubmission->form_name) {
            'Learning Application Plan' => LearningApplicationPlanForm::with(['foundations','technical','managerials','supervisorys','learningStrategies','performanceIndicator','beneficiaries','resources','targetCompletion'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Learning Implementation Report' => LearningImplementationForm::with(['coreImplementation','technicalImplementation','learderShipImplementation'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Learning Application Monitoring Report' => LearningApplicationMonitoringForm::with(['coreMonitoring','leaderShipMonitoring','technicalMonitoring'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Learner Progress Report' => LearnerProgressForm::with(['coreProgress','leaderShipProgress','technicalProgress'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            default => null,
        };

        if (!$formData) {
            throw new \Exception("No matching form data found for: " . $employeeFormSubmission->form_name);
        }

        return [
            'submission' => $employeeFormSubmission,
            'form_data'  => $formData,
        ];
    }
}
