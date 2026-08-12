<?php

namespace App\Services\Forms;

use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAMR\CoreMonitoring;
use App\Models\Forms\LAMR\LeadershipMonitoring;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAMR\TechnicalMonitoring;
use Illuminate\Support\Facades\DB;

class LearningApplicationMonitoringFormService
{

    private function formName()
    {
        return 'Learning Application Monitoring Report';
    }

    public function create(?array $validated)
    {

        return DB::transaction(function () use ($validated) {


            // check first if employee are already submit
            $employee_submit = LearningApplicationMonitoringForm::where('event_id', $validated['event_id'])
                ->where('form_name', $this->formName()) // match sa ginagamit mo sa create()
                ->where('control_no', $validated['control_no'])
                ->first();

            if ($employee_submit) {
                throw new \Exception('You already submitted this form. You can edit or delete it instead.');
            }


            $learning_application_form = LearningApplicationMonitoringForm::create([
                'event_id' => $validated['event_id'],
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'],

                'learner' => $validated['learner'] ?? null,
                'lnd_attended' => $validated['lnd_attended'] ?? null,
                'date_of_attendance' => $validated['date_of_attendance'] ?? null,
                'competency_developed_acquired' => $validated['competency_developed_acquired'] ?? null,

                'goals' => $validated['goals'] ?? null,
                'performance_indicator' => $validated['performance_indicator'] ?? null,
                'learning_strategies_applied' => $validated['learning_strategies_applied'] ?? null,
                'required_resources' => $validated['required_resources'] ?? null,

                'target_date_completion' => $validated['target_date_completion'] ?? null,
                'status_as_of_v1' => $validated['status_as_of_v1'] ?? null,
                'status_as_of_v2' => $validated['status_as_of_v2'] ?? null,
            ]);


            $core_monitoring = CoreMonitoring::create([

                'learning_application_monitoring_report_id' =>  $learning_application_form->id ?? null,
                'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
            ]);

            $leadership_monitoring = LeadershipMonitoring::create([

                'learning_application_monitoring_report_id' =>  $learning_application_form->id ?? null,
                'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
            ]);


            $technical_monitoring = TechnicalMonitoring::create([

                'learning_application_monitoring_report_id' =>  $learning_application_form->id ?? null,
                'planning_organizing' => $validated['planning_organizing'] ?? null,
                'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? null,
                'records_management' => $validated['records_management'] ?? null,
                'partnering_networking' => $validated['partnering_networking'] ?? null,
                'process_management' => $validated['process_management'] ?? null,
            ]);


            $form_submit = EmployeeFormSubmission::create([

                'event_id' => $validated['event_id'] ?? null,
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'] ?? null,
                'status' => 'Pending',

            ]);

            return [
                $learning_application_form,
                $core_monitoring,
                $leadership_monitoring,
                $technical_monitoring,
                $form_submit
            ];
        });
    }


    public function edit(int $learningApplicationMonitoringFormId, ?array $validated)
    {
        return DB::transaction(function () use ($learningApplicationMonitoringFormId, $validated) {

            $learning_application_form = LearningApplicationMonitoringForm::find($learningApplicationMonitoringFormId);

            if (!$learning_application_form) {
                throw new \Exception('Learner progrees report id not found');
            }

            $learning_application_form->update([
                'event_id' => $validated['event_id'],
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'],

                'learner' => $validated['learner'] ?? null,
                'lnd_attended' => $validated['lnd_attended'] ?? null,
                'date_of_attendance' => $validated['date_of_attendance'] ?? null,
                'competency_developed_acquired' => $validated['competency_developed_acquired'] ?? null,

                'goals' => $validated['goals'] ?? null,
                'performance_indicator' => $validated['performance_indicator'] ?? null,
                'learning_strategies_applied' => $validated['learning_strategies_applied'] ?? null,
                'required_resources' => $validated['required_resources'] ?? null,

                'target_date_completion' => $validated['target_date_completion'] ?? null,
                'status_as_of_v1' => $validated['status_as_of_v1'] ?? null,
                'status_as_of_v2' => $validated['status_as_of_v2'] ?? null,

            ]);

            $core_monitoring = CoreMonitoring::updateOrCreate(
                ['learning_application_monitoring_report_id' => $learning_application_form->id],
                [
                    'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                    'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                    'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
                ]
            );

            $leadership_monitoring  = LeadershipMonitoring::updateOrCreate(
                ['learning_application_monitoring_report_id' => $learning_application_form->id],
                [
                    'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                    'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                    'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                    'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
                ]
            );

            $technical_monitoring  = TechnicalMonitoring::updateOrCreate(
                ['learning_application_monitoring_report_id' => $learning_application_form->id],
                [
                    'planning_organizing' => $validated['planning_organizing'] ?? null,
                    'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? null,
                    'records_management' => $validated['records_management'] ?? null,
                    'partnering_networking' => $validated['partnering_networking'] ?? null,
                    'process_management' => $validated['process_management'] ?? null,
                ]
            );

            $form_submit = EmployeeFormSubmission::where('event_id', $learning_application_form->event_id)
                ->where('control_no', $learning_application_form->control_no)
                ->first();

            if ($form_submit) {
                $form_submit->update([
                    'status' => 'Pending',
                ]);
            }

            return [
                $learning_application_form->fresh(),
                $core_monitoring,
                $leadership_monitoring,
                $technical_monitoring,
                $form_submit
            ];
        });
    }

    public function delete(int $learningApplicationMonitoringFormId)
    {
        return DB::transaction(function () use ($learningApplicationMonitoringFormId) {

            $learner_application_monitoring = LearningApplicationMonitoringForm::find($learningApplicationMonitoringFormId);

            if (!$learner_application_monitoring) {
                throw new \Exception('Learning application monitoring id not found');
            }

            $employee_submit_form = EmployeeFormSubmission::where('event_id', $learner_application_monitoring->event_id)
                ->where('form_name', $learner_application_monitoring->forms_name)
                ->where('control_no', $learner_application_monitoring->control_no)
                ->first();

            if ($employee_submit_form && $employee_submit_form->status === 'Approved') {
                throw new \Exception('Cannot delete an already approved Learning application monitoring.');
            }

            if ($employee_submit_form) {
                $employee_submit_form->delete();
            }

            $learner_application_monitoring->delete();

            return $learner_application_monitoring;
        });
    }
}
