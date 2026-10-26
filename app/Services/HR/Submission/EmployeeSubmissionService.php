<?php

namespace App\Services\HR\Submission;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Models\Forms\LES\EvaluationInstrument;
use App\Models\Forms\LIR\LearningImplementationForm;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Models\Forms\RSE\TrainingEvaluationSpeakers;
use App\Models\RSP\vwEmployee;
use App\Notifications\SubmissionStatusUpdated;
use App\Traits\ResolvesOfficeAbbr;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmployeeSubmissionService
{
     use ResolvesOfficeAbbr;

    public function updateSubmission(?array $validated, int $employeeFormSubmissionId)
    {
        $submission = DB::transaction(function () use ($validated, $employeeFormSubmissionId) {
            $submission = EmployeeFormSubmission::find($employeeFormSubmissionId);

            if (!$submission) {
                throw new \Exception("Employee form not found");
            }

            $originalControlNo = $submission->control_no;

            $submission->update([
                'status'  => $validated['status'],
                'remarks' => $validated['remarks'] ?? null,
            ]);

            return $submission;
        });

        $this->notifyEmployee($submission, $submission->control_no);

        return $submission;
    }

    private function notifyEmployee(EmployeeFormSubmission $submission): void
    {
        try {
            $notification = new SubmissionStatusUpdated(
                submissionId: $submission->id,
                formName: $submission->form_name,
                status: $submission->status,
                controlNo: $submission->control_no,
                eventId: $submission->event_id,
                eventScheduleId: $submission->event_schedule_id,
                remarks: $submission->remarks,

            );

            DatabaseNotification::create([
                'id'              => (string) Str::uuid(),
                'type'            => SubmissionStatusUpdated::class,
                'notifiable_type' => NominatedEmployee::class, // dapat pareho sa gagamitin sa pagbasa
                'notifiable_id'   => (string) $submission->control_no,
                'data'            => $notification->toArray(new \stdClass),
            ]);

            Log::info('notification written', [
                'notifiable_id' => (string) $submission->control_no,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to send submission notification', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);
        }
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
            'Learning Application Plan' => LearningApplicationPlanForm::with(['foundation', 'technical', 'managerial', 'supervisory', 'learningStrategies', 'performanceIndicator', 'beneficiaries', 'resources', 'targetCompletion'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Learning Implementation Report' => LearningImplementationForm::with(['coreImplementation', 'technicalImplementation', 'learderShipImplementation'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Learning Application Monitoring Report' => LearningApplicationMonitoringForm::with(['coreMonitoring', 'leaderShipMonitoring', 'technicalMonitoring'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Learner Progress Report' => LearnerProgressForm::with(['coreProgress', 'leaderShipProgress', 'technicalProgress'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Level 1 Evaluation Instrument' => EvaluationInstrument::with(['facilitator', 'topic', 'feedback', 'venue_evaluation'])->where(
                'employee_form_submission_id',
                $employeeFormSubmission->id
            )->first(),

            'Resources Speaker Evaluation Form' => TrainingEvaluationSpeakers::with(['speakerEvaluation'])->where(
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

    private const REQUIRED_APPROVALS = 6;

  public function submitted(int $perPage, Request $request, int $scheduleId, ?string $office = null, ?string $formStatus = null, ?string $certificateStatus= null)
{
    $required = self::REQUIRED_APPROVALS;

    // reusable na subquery para sa bilang ng approved forms
    $approvedSub = fn ($q) => $q
        ->whereColumn('employee_form_submissions.event_id', 'nominated_employees.event_id')
        ->whereColumn('employee_form_submissions.event_schedule_id', 'nominated_employees.event_schedule_id')
        ->where('status', 'Approved');


    $list = NominatedEmployee::query()
        // base filters: laging susundin
        ->where('nominate_status', 'Approved')
        ->where('event_schedule_id', $scheduleId)

        // optional filters
        ->when($office, fn ($q) => $q->where('office', $office))
        ->when($request->filled('search'), function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%');
        })

        // form status: complete = may at least $required na approved forms
        ->when($formStatus === 'complete', function ($q) use ($approvedSub, $required) {
            $q->whereHas('formSubmissions', $approvedSub, '>=', $required);
        })
        ->when($formStatus === 'incomplete', function ($q) use ($approvedSub, $required) {
            $q->whereHas('formSubmissions', $approvedSub, '<', $required);
        })

        // certificate: issued / pending
        ->when($certificateStatus === 'issued', fn ($q) => $q->where('certificate_issued', true))
        ->when($certificateStatus === 'pending', fn ($q) => $q->where('certificate_issued', false))

        ->withCount([
            'formSubmissions as pending_count' => fn ($q) => $q
                ->whereColumn('employee_form_submissions.event_id', 'nominated_employees.event_id')
                ->whereColumn('employee_form_submissions.event_schedule_id', 'nominated_employees.event_schedule_id')
                ->where('status', 'Pending'),
            'formSubmissions as approved_count' => $approvedSub,
            'formSubmissions as returned_count' => fn ($q) => $q
                ->whereColumn('employee_form_submissions.event_id', 'nominated_employees.event_id')
                ->whereColumn('employee_form_submissions.event_schedule_id', 'nominated_employees.event_schedule_id')
                ->where('status', 'Returned'),
        ])
        ->orderBy('control_no')
        ->paginate($perPage)
        ->withQueryString()
        ->through(function ($nominee) use ($required) {
            $approved = (int) $nominee->approved_count;

            return [
                'control_no'            => $nominee->control_no,
                'full_name'             => $nominee->full_name,
                'designation'             => $nominee->designation,
                'status'             => $nominee->status,
                'office'                => $nominee->office,
                'office_abbr'           => $this->officeAbbr($nominee->office),
                'event_id'              => $nominee->event_id,
                'nominated_employee_id' => $nominee->id,
                'event_schedule_id'     => (int) $nominee->event_schedule_id,
                'pending'               => (int) $nominee->pending_count,
                'approved'              => $approved,
                'returned'              => (int) $nominee->returned_count,
                'required_approvals'    => $required,
                'form_status'           => $approved >= $required ? 'complete' : 'incomplete',
                'certificate_issued'    => $nominee->certificate_issued ? 'issued' : 'pending',
            ];
        });

    return $list;
}
}
