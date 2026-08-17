<?php

namespace App\Services\Forms;

use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LIR\CoreImplementation;
use App\Models\Forms\LIR\LeadershipImplementation;
use App\Models\Forms\LIR\LearningImplementationForm;
use App\Models\Forms\LIR\TechinicalImplementation;
use Illuminate\Support\Facades\DB;

class LearningImplementationFormService
{
    private function formName()
    {

        return 'Learning Implementation Report';
    }


    public function create(?array $validated)
    {

        return DB::transaction(function () use ($validated) {

            // check first if employee are already submit
            $employee_submit = LearningImplementationForm::where('event_id', $validated['event_id'])
                ->where('form_name', $this->formName()) // match sa ginagamit mo sa create()
                ->where('control_no', $validated['control_no'])
                ->first();

            if ($employee_submit) {
                throw new \Exception('You already submitted this form. You can edit or delete it instead.');
            }

            $event = LearningImplementationForm::select('id')->where('id', $validated['event_id'])->first();

            if (!$event) {
                throw new \Exception('Event not found.');
            }

            if (!in_array($event->status, ['Verify', 'Approved'])) {
                throw new \Exception('You cannot submit this form yet. The event must be verified or approved first.');
            }

            $learning_implementation = LearningImplementationForm::create([


                'event_id' => $validated['event_id'],
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'],
                'learner' => $validated['learner'] ?? null,
                'lnd_attended' => $validated['lnd_attended'] ?? null,
                'date_of_attendance' => $validated['date_of_attendance'] ?? null,
                'competency_developed_acquired' => $validated['competency_developed_acquired'] ?? null,
                'learning_strategies_applied' => $validated['learning_strategies_applied'] ?? null,
                'resources_used' => $validated['resources_used'] ?? null,
                'beneficiaries_strategies_applied' => $validated['beneficiaries_strategies_applied'] ?? null,
                'performance_indicators_behavior_toward_work' => $validated['performance_indicators_behavior_toward_work'] ?? null,
                'financial_aid_training_attended' => $validated['financial_aid_training_attended'] ?? null,
                'return_financial_aid' => $validated['return_financial_aid'] ?? null,

            ]);

            $core_implementation = CoreImplementation::create([

                'learning_implementation_report_id' =>  $learning_implementation->id ?? null,
                'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
            ]);

            $leadership_implementation = LeadershipImplementation::create([

                'learning_implementation_report_id' =>  $learning_implementation->id ?? null,
                'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
            ]);


            $technical_implementation  = TechinicalImplementation::create([

                'learning_implementation_report_id' =>  $learning_implementation->id ?? null,
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
                'submitted_at' => now(),

            ]);

            return [
                $learning_implementation,
                $core_implementation,
                $leadership_implementation,
                $technical_implementation,
                $form_submit
            ];
        });
    }


    public function edit(int $learningImplementationFormId, ?array $validated)
    {

        return DB::transaction(function () use ($learningImplementationFormId, $validated) {

            // check first if employee are already submit
            $learning_implementation = LeadershipImplementation::find($learningImplementationFormId);

            if (!$learning_implementation) {
                throw new \Exception('Learner progrees report id not found');
            }

            $learning_implementation->update([

                'event_id' => $validated['event_id'],
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'],
                'learner' => $validated['learner'] ?? null,
                'lnd_attended' => $validated['lnd_attended'] ?? null,
                'date_of_attendance' => $validated['date_of_attendance'] ?? null,
                'competency_developed_acquired' => $validated['competency_developed_acquired'] ?? null,
                'learning_strategies_applied' => $validated['learning_strategies_applied'] ?? null,
                'resources_used' => $validated['resources_used'] ?? null,
                'beneficiaries_strategies_applied' => $validated['beneficiaries_strategies_applied'] ?? null,
                'performance_indicators_behavior_toward_work' => $validated['performance_indicators_behavior_toward_work'] ?? null,
                'financial_aid_training_attended' => $validated['financial_aid_training_attended'] ?? null,
                'return_financial_aid' => $validated['return_financial_aid'] ?? null,

            ]);

            $core_implementation = CoreImplementation::updateOrCreate(
                ['learning_implementation_report_id' =>  $learning_implementation->id],
                [


                    'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                    'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                    'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
                ]
            );

            $leadership_implementation = LeadershipImplementation::updateOrCreate(['learning_implementation_report_id' =>  $learning_implementation->id], [


                'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
            ]);


            $technical_implementation  = TechinicalImplementation::updateOrCreate(['learning_implementation_report_id' =>  $learning_implementation->id], [


                'planning_organizing' => $validated['planning_organizing'] ?? null,
                'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? null,
                'records_management' => $validated['records_management'] ?? null,
                'partnering_networking' => $validated['partnering_networking'] ?? null,
                'process_management' => $validated['process_management'] ?? null,
            ]);


            $form_submit = EmployeeFormSubmission::where('event_id', $learning_implementation->event_id)
                ->where('control_no', $learning_implementation->control_no)
                ->first();

            if ($form_submit) {
                $form_submit->update([
                    'status' => 'Pending',
                ]);
            }


            return [
                $learning_implementation,
                $core_implementation,
                $leadership_implementation,
                $technical_implementation,
                $form_submit
            ];
        });
    }

    public function delete(int $learningImplementationFormId)
    {
        return DB::transaction(function () use ($learningImplementationFormId) {

            $learning_implementation = LearningImplementationForm::find($learningImplementationFormId);

            if (!$learning_implementation) {
                throw new \Exception('Learning implementation id not found');
            }

            $employee_submit_form = EmployeeFormSubmission::where('event_id', $learning_implementation->event_id)
                ->where('form_name', $learning_implementation->form_name)
                ->where('control_no', $learning_implementation->control_no)
                ->first();

            if ($employee_submit_form && $employee_submit_form->status === 'Approved') {
                throw new \Exception('Cannot delete an already approved Learning implementation.');
            }

            if ($employee_submit_form) {
                $employee_submit_form->delete();
            }

            $learning_implementation->delete();

            return $learning_implementation;
        });
    }
}
