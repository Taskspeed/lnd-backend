<?php

namespace App\Http\Controllers\Erms;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Event\Event;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Models\Forms\LES\EvaluationInstrument;
use App\Models\Forms\LIR\LearningImplementationForm;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Models\Forms\RSE\TrainingEvaluationSpeakers;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeEventController extends Controller
{

    use ApiResponseTrait;
    use FormatsDateRanges;

    // need to optimize later on


    // get the list of event of employee
    public function index(Request $request, string $controlNo)
    {
        $perPage = (int) $request->input('per_page', 10);

        $employee = NominatedEmployee::where('nominate_status', 'Approved')
            ->where('control_no', $controlNo)
            ->with('event.schedule.scheduleDateTime')
            ->select('event_id', 'control_no', 'office')
            ->orderByDesc('event_id') // stable ordering, palitan kung iba ang gusto mong sort
            ->paginate($perPage);

        // attach computed date range per schedule
        $employee->getCollection()->each(function ($nominated) {
            $nominated->event?->schedule->each(function ($schedule) {
                $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
                $schedule->unsetRelation('scheduleDateTime');
            });
        });

        return $this->successMessage($employee, 'Employeee list of event', 200);
    }



    public function show(int $eventId, int $eventScheduleId, string $controlNo)
    {
        $employee = NominatedEmployee::with(['event' => function ($query) use ($eventScheduleId) {

            $query->with(['schedule' => function ($query) use ($eventScheduleId) {

                $query->where('id', $eventScheduleId)->with(['scheduleDateTime']);
            }]);
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
            ->where('event_schedule_id', $eventScheduleId)
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
                'employee_form_submission_id'    => $sub->id,
                'form_name'    => $sub->form_name,
                'status'       => $sub->status,
                'submitted_at' => $sub->submitted_at,
                'remarks'      => $sub->remarks,
            ];
        })->values();

        return $this->successMessage($employee, 'Employee list of event', 200);
    }


    private const FORM_MODELS = [
        'Learning Application Plan' => [
            'model' => LearningApplicationPlanForm::class,
            'with'  => [
                'foundations',
                'technical',
                'managerials',
                'supervisorys',
                'learningStrategies',
                'performanceIndicator',
                'beneficiaries',
                'resources',
                'targetCompletion',
            ],
        ],
        'Learning Application Monitoring Report' => [
            'model' => LearningApplicationMonitoringForm::class,
            'with'  => ['coreMonitoring', 'leaderShipMonitoring', 'technicalMonitoring'],
        ],

        'Learning Implementation Report' => [
            'model' => LearningImplementationForm::class,
            'with'  => ['coreImplementation', 'technicalImplementation', 'learderShipImplementation'],
        ],
    ];

    public function getForm(int $eventId, int $eventScheduleId, string $controlNo, string $formName)
    {
        $config = self::FORM_MODELS[$formName] ?? null;

        if (!$config) {
            return response()->json(['message' => "Unknown form: {$formName}"], 422);
        }

        $submission = EmployeeFormSubmission::where('event_id', $eventId)
            ->where('event_schedule_id', $eventScheduleId)
            ->where('control_no', $controlNo)
            ->where('form_name', $formName)
            ->first();

        if (!$submission) {
            return response()->json(['message' => 'No submission found'], 404);
        }

        Log::info('getForm debug', [
            'submission_id'   => $submission->id,
            'submission_key'  => $submission->getKey(),
            'key_name'        => $submission->getKeyName(),
            'submission_attrs' => $submission->getAttributes(),
            'model'           => $config['model'],
            'model_table'     => (new $config['model'])->getTable(),
            'model_connection' => (new $config['model'])->getConnectionName(),
            'total_rows'      => $config['model']::count(),
            'matching_rows'   => $config['model']::where('employee_form_submission_id', $submission->id)->count(),
            'sql'             => $config['model']::where('employee_form_submission_id', $submission->id)->toSql(),
        ]);

        $form = $config['model']::with($config['with'])
            ->where('employee_form_submission_id', $submission->id)
            ->first();

        if (!$form) {
            return response()->json(['message' => 'Submission exists but form details are missing'], 404);
        }

        return $this->successMessage(['submission ' => $submission, 'form' => $form], 'Employee list of event', 200);
    }


    public function competencyEvent(int $eventId, int $eventScheduleId, string $controlNo)
    {
        $employee_nominated = NominatedEmployee::select('id', 'office', 'full_name')
            ->where('event_id', $eventId)
            ->where('event_schedule_id', $eventScheduleId)
            ->where('control_no', $controlNo)
            ->first();

        $event = Event::where('id', $eventId)
            ->with(['schedule' => function ($query) use ($eventScheduleId) {
                $query->select('id', 'event_id', 'venue_name', 'conducted_by')
                    ->where('id', $eventScheduleId)
                    ->with(['eventCore', 'eventLeaderShip', 'eventTechnical', 'scheduleDateTime', 'speaker']);
            }])
            ->first();

        $event?->schedule->each(function ($schedule) {
            $schedule->schedule_date_range = $this->formatDateRanges($schedule->scheduleDateTime);
            $schedule->unsetRelation('scheduleDateTime');
        });

        return $this->successMessage(
            ['employee' => $employee_nominated, 'event' => $event],
            'Success fetch',
            200
        );
    }

    public function viewLearnerProgress(int $employeeFormSubmissionId)
    {
        $learnerProgressForm = LearnerProgressForm::with(['coreProgress', 'leaderShipProgress', 'technicalProgress'])
            ->where('employee_form_submission_id', $employeeFormSubmissionId)->first();

        if (!$learnerProgressForm) {
            return $this->errorMessage('Learner progress report form  not found', 400);
        }

        // $status = EmployeeFormSubmission::find($learnerProgressForm->employee_form_submission_id);

        return $this->successMessage([
            'form' => $learnerProgressForm,
            // 'status' => $status,
        ], 'Success fetch', 200);
    }

    public function viewLearnerApplicationMonitoring(int $employeeFormSubmissionId)
    {

        $learnera_application_monitoring = LearningApplicationMonitoringForm::with(['coreMonitoring', 'leaderShipMonitoring', 'technicalMonitoring'])
            ->where('employee_form_submission_id', $employeeFormSubmissionId)->first();

        if (!$learnera_application_monitoring) {
            return $this->errorMessage('Learning application monitoring report id not found', 400);
        }

        //  $status = EmployeeFormSubmission::find($learnera_application_monitoring->employee_form_submission_id);

        return $this->successMessage([
            'form' => $learnera_application_monitoring,
            // 'status' => $status,
        ], 'Success fetch', 200);
    }

    public function viewLearningApplicationPlan(int $employeeFormSubmissionId)
    {
        $learning_application_plan = LearningApplicationPlanForm::with([
            'foundation',
            'technical',
            'managerial',
            'supervisory',
            'learningStrategies',
            'performanceIndicator',
            'beneficiaries',
            'resources',
            'targetCompletion',
        ])->where('employee_form_submission_id', $employeeFormSubmissionId)->first();

        if (!$learning_application_plan) {
            return $this->errorMessage('Learning application plan id not found', 400);
        }

        //  $status = EmployeeFormSubmission::find($learning_application_plan->employee_form_submission_id);


        return $this->successMessage([
            'form' => $learning_application_plan,
            // 'status' => $status,
        ], 'Success fetch', 200);
    }

    public function viewLearningImplementation(int $employeeFormSubmissionId)
    {
        $learning_implementation = LearningImplementationForm::with([
            'coreImplementation',
            'learderShipImplementation',
            'technicalImplementation'
        ])->where('employee_form_submission_id', $employeeFormSubmissionId)->first();

        if (!$learning_implementation) {
            return $this->errorMessage('Learning implementation id not found', 400);
        }

        // $status = EmployeeFormSubmission::find($learning_implementation->employee_form_submission_id);


        return $this->successMessage([
            'form' => $learning_implementation,
            // 'status' => $status,
        ], 'Success fetch', 200);
    }

    public function viewResourcesSpeakerEvaluation(int $employeeFormSubmissionId)
    {

        $data = TrainingEvaluationSpeakers::with('speakerEvaluation')->where('employee_form_submission_id', $employeeFormSubmissionId)->first();
        return $this->successMessage($data, 'Submitted successfully', 200);
    }


    public function viewEvaluationInstrument(int $employeeFormSubmissionId)
    {

        $data = EvaluationInstrument::with(['facilitator', 'topic', 'feedback', 'venue_evaluation'])->where('employee_form_submission_id', $employeeFormSubmissionId)->first();
        return $this->successMessage($data, 'Submitted successfully', 200);
    }
}
