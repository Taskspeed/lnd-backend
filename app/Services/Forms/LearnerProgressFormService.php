<?php

namespace App\Services\Forms;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Event\Event;
use App\Models\Forms\LPR\CoreProgress;
use App\Models\Forms\LPR\LeadershipProgress;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Models\Forms\LPR\TechnicalProgress;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class LearnerProgressFormService
{
    private function formName()
    {
        return 'Leaner Progress Report';
    }

    public function create(?array $validated)
    {

        return DB::transaction(function () use ($validated) {

            // check first if employee are already submit
            $employee_submit = LearnerProgressForm::where('event_id', $validated['event_id'])
                ->where('forms_name', $this->formName()) // match sa ginagamit mo sa create()
                ->where('control_no', $validated['control_no'])
                ->first();

            if ($employee_submit) {
                throw new \Exception('You already submitted this form. You can edit or delete it instead.');
            }

            $event = Event::select('id')->where('id', $validated['event_id'])->first();

            if (!$event) {
                throw new \Exception('Event not found.');
            }

            if (!in_array($event->status, ['Verify', 'Approved'])) {
                throw new \Exception('You cannot submit this form yet. The event must be verified or approved first.');
            }

            $learnerForm = LearnerProgressForm::create([

                'event_id' => $validated['event_id'] ?? null,

                'forms_name' => $this->formName(),
                'control_no' => $validated['control_no'] ?? null,
                'learner' => $validated['learner'] ?? null,
                'lnd_attended' => $validated['lnd_attended'] ?? null,
                'date_of_attendance' =>  $validated['date_of_attendance'] ?? null,

                // competency ratings — 1 to 5 scale
                'delivering_service_excellence_competency' => $validated['delivering_service_excellence_competency'] ?? null,
                'exemplifying_integrity_competency' => $validated['exemplifying_integrity_competency'] ?? null,
                'interpersonal_skills_competency' => $validated['interpersonal_skills_competency'] ?? null,
                'planning_organizing_competency' => $validated['planning_organizing_competency'] ?? null,
                'monitoring_evaluation_competency' => $validated['monitoring_evaluation_competency'] ?? null,
                'records_management_competency' => $validated['records_management_competency'] ?? null,
                'partnering_networking_competency' => $validated['partnering_networking_competency'] ?? null,
                'process_management_competency' => $validated['process_management_competency'] ?? null,
                'managing_performance_coaching_results_competency' => $validated['managing_performance_coaching_results_competency'] ?? null,
                'building_collaborative_inclusive_working_relationships_competency' => $validated['building_collaborative_inclusive_working_relationships_competency'] ?? null,
                'thinking_strategically_creatively_competency' => $validated['thinking_strategically_creatively_competency'] ?? null,
                'problem_solving_decision_making_competency' => $validated['problem_solving_decision_making_competency'] ?? null,

            ]);

            $core_progress = CoreProgress::create([

                'learner_progress_report_id' =>  $learnerForm->id ?? null,
                'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
            ]);

            $leadership_progress = LeadershipProgress::create([

                'learner_progress_report_id' =>  $learnerForm->id ?? null,
                'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
            ]);


            $technical_progress = TechnicalProgress::create([

                'learner_progress_report_id' =>  $learnerForm->id ?? null,
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
                $learnerForm,
                $core_progress,
                $leadership_progress,
                $technical_progress,
                $form_submit
            ];
        });
    }

    public function edit(int $LearnerProgressFormId, ?array $validated)
    {
        return DB::transaction(function () use ($LearnerProgressFormId, $validated) {

            $learnerForm = LearnerProgressForm::find($LearnerProgressFormId);

            if (!$learnerForm) {
                throw new ModelNotFoundException('Learner progrees report id not found');
            }

            $learnerForm->update([
                'event_id' => $validated['event_id'],
                'control_no' => $validated['control_no'],
                'form_name' => $this->formName(),
                'learner' => $validated['learner'] ?? null,
                'lnd_attended' => $validated['lnd_attended'] ?? null,
                'date_of_attendance' => $validated['date_of_attendance'] ?? null,

                // competency ratings — 1 to 5 scale
                'delivering_service_excellence_competency' => $validated['delivering_service_excellence_competency'] ?? null,
                'exemplifying_integrity_competency' => $validated['exemplifying_integrity_competency'] ?? null,
                'interpersonal_skills_competency' => $validated['interpersonal_skills_competency'] ?? null,
                'planning_organizing_competency' => $validated['planning_organizing_competency'] ?? null,
                'monitoring_evaluation_competency' => $validated['monitoring_evaluation_competency'] ?? null,
                'records_management_competency' => $validated['records_management_competency'] ?? null,
                'partnering_networking_competency' => $validated['partnering_networking_competency'] ?? null,
                'process_management_competency' => $validated['process_management_competency'] ?? null,
                'managing_performance_coaching_results_competency' => $validated['managing_performance_coaching_results_competency'] ?? null,
                'building_collaborative_inclusive_working_relationships_competency' => $validated['building_collaborative_inclusive_working_relationships_competency'] ?? null,
                'thinking_strategically_creatively_competency' => $validated['thinking_strategically_creatively_competency'] ?? null,
                'problem_solving_decision_making_competency' => $validated['problem_solving_decision_making_competency'] ?? null,

            ]);

            $core_progress = CoreProgress::updateOrCreate(
                ['learner_progress_report_id' => $learnerForm->id],
                [
                    'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                    'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                    'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
                ]
            );

            $leadership_progress = LeadershipProgress::updateOrCreate(
                ['learner_progress_report_id' => $learnerForm->id],
                [
                    'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                    'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                    'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                    'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
                ]
            );

            $technical_progress = TechnicalProgress::updateOrCreate(
                ['learner_progress_report_id' => $learnerForm->id],
                [
                    'planning_organizing' => $validated['planning_organizing'] ?? null,
                    'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? null,
                    'records_management' => $validated['records_management'] ?? null,
                    'partnering_networking' => $validated['partnering_networking'] ?? null,
                    'process_management' => $validated['process_management'] ?? null,
                ]
            );

            $form_submit = EmployeeFormSubmission::where('event_id', $learnerForm->event_id)
                ->where('control_no', $learnerForm->control_no)
                ->first();

            if ($form_submit) {
                $form_submit->update([
                    'status' => 'Pending',
                ]);
            }

            return [
                $learnerForm->fresh(),
                $core_progress,
                $leadership_progress,
                $technical_progress,
                $form_submit
            ];
        });
    }

    public function delete(int $LearnerProgressFormId)
    {
        return DB::transaction(function () use ($LearnerProgressFormId) {

            $learnerForm = LearnerProgressForm::find($LearnerProgressFormId);

            if (!$learnerForm) {
                throw new ModelNotFoundException('Learner progrees report id not found');
            }

            $employee_submit_form = EmployeeFormSubmission::where('event_id', $learnerForm->event_id)
                ->where('form_name', $learnerForm->forms_name)
                ->where('control_no', $learnerForm->control_no)
                ->first();

            if ($employee_submit_form && $employee_submit_form->status === 'Approved') {
                throw new \Exception('Cannot delete an already approved Learner Progress Report.');
            }

            if ($employee_submit_form) {
                $employee_submit_form->delete();
            }

            $learnerForm->delete();

            return $learnerForm;
        });
    }
}
