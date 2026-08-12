<?php

namespace App\Services\Forms;

use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAP\BeneficiariesStrategieApplied;
use App\Models\Forms\LAP\FoundationCompetencie;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Models\Forms\LAP\LearningStrategiesImplemented;
use App\Models\Forms\LAP\ManagerialCompetencie;
use App\Models\Forms\LAP\PerformanceIndicator;
use App\Models\Forms\LAP\ResourcesUtilized;
use App\Models\Forms\LAP\SupervisoryCompetencie;
use App\Models\Forms\LAP\TargetDateCompletion;
use App\Models\Forms\LAP\TechnicalCompetencie;
use Illuminate\Support\Facades\DB;

class LearningApplicationPlanFormService
{

    private function formName()
    {
        return 'Learning Application Plan';
    }

    public function create(?array $validated)
    {

        // check first if employee are already submit
        $employee_submit = LearningApplicationPlanForm::where('event_id', $validated['event_id'])
            ->where('forms_name', $this->formName()) // match sa ginagamit mo sa create()
            ->where('control_no', $validated['control_no'])
            ->first();

        if ($employee_submit) {
            throw new \Exception('You already submitted this form. You can edit or delete it instead.');
        }
        return DB::transaction(function () use ($validated) {

            $learning_application_plan = LearningApplicationPlanForm::create([
                'event_id' => $validated['event_id'],
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'],
                'office' => $validated['office'] ?? null,
                'learner' => $validated['learner'] ?? null,
                'title_of_intervention' => $validated['title_of_intervention'] ?? null,
                'date_conducted' => $validated['date_conducted'] ?? null,
                'venue' => $validated['venue'] ?? null,

                'foundation' => $validated['foundation'] ?? null,
                'techinal' => $validated['techinal'] ?? null,
                'supervisory' => $validated['supervisory'] ?? null,
                'managerial' => $validated['managerial'] ?? null,
                'significant_learning_insight' => $validated['significant_learning_insight'] ?? null,

            ]);

            $foundation_competency = FoundationCompetencie::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,

            ]);


            $technical_competency = TechnicalCompetencie::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'planning_organizing' => $validated['planning_organizing'] ?? null,
                'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? null,
                'records_management' => $validated['records_management'] ?? null,
                'partnering_networking' => $validated['partnering_networking'] ?? null,
                'process_management' => $validated['process_management'] ?? null,
                'attention_detail' => $validated['attention_detail'] ?? null,

            ]);

            $managerial_competency = ManagerialCompetencie::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,


            ]);

            $supervisory_competency = SupervisoryCompetencie::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'supervisory_managing_performance_coaching_results' => $validated['supervisory_managing_performance_coaching_results'] ?? null,
                'supervisory_building_collaborative_inclusive_working_relationships' => $validated['supervisory_building_collaborative_inclusive_working_relationships'] ?? null,

            ]);

            $learning_strategies = LearningStrategiesImplemented::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'immediate_application_skills' => $validated['immediate_application_skills'] ?? null,
                'knowledge_sharing' => $validated['knowledge_sharing'] ?? null,
                'peer_coaching_collaboration' => $validated['peer_coaching_collaboration'] ?? null,
                'develop_office_policies_guidelines' => $validated['develop_office_policies_guidelines'] ?? null,
                'create_pilot_project' => $validated['create_pilot_project'] ?? null,
                'include_ipcr' => $validated['include_ipcr'] ?? null,

            ]);

            $performance_indicator = PerformanceIndicator::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'strategic_functions' => $validated['strategic_functions'] ?? null,
                'core_functions' => $validated['core_functions'] ?? null,
                'support_functions' => $validated['support_functions'] ?? null,

            ]);

            $beneficaties = BeneficiariesStrategieApplied::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'employees_staff' => $validated['strategic_functions'] ?? null,
                'office_department' => $validated['core_functions'] ?? null,
                'city_government_organization' => $validated['support_functions'] ?? null,
                'clients_stakeholders_general_public' => $validated['support_functions'] ?? null,

            ]);


            $resources = ResourcesUtilized::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'digital_technologies' => $validated['strategic_functions'] ?? null,
                'physical_printed_resources' => $validated['core_functions'] ?? null,
                'human_resources_organizational_support' => $validated['support_functions'] ?? null,
                'financial_logistical_support' => $validated['support_functions'] ?? null,
                'policy_process_resources' => $validated['support_functions'] ?? null,

            ]);

            $target = TargetDateCompletion::create([
                'learning_application_plan_form_id' => $learning_application_plan->id,
                'within_2_weeks_after_training' => $validated['within_2_weeks_after_training'] ?? null,
                'within_1_month_after_training' => $validated['within_1_month_after_training'] ?? null,
                'within_2_months_after_training' => $validated['within_2_months_after_training'] ?? null,
                'financial_logistical_support' => $validated['financial_logistical_support'] ?? null,
                'within_3_months_after_training' => $validated['within_3_months_after_training'] ?? null,

            ]);

            $form_submit = EmployeeFormSubmission::create([

                'event_id' => $validated['event_id'] ?? null,
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'] ?? null,
                'status' => 'Pending',

            ]);

            return [
                $learning_application_plan,
                $foundation_competency,
                $technical_competency,
                $managerial_competency,
                $supervisory_competency,
                $learning_strategies,
                $performance_indicator,
                $beneficaties,
                $resources,
                $target,
                $form_submit,
            ];
        });
    }


    public function edit(int $LearningApplicationPlanFormId, ?array $validated)
    {
        return DB::transaction(function () use ($LearningApplicationPlanFormId, $validated) {

            $learning_application_plan = LearningApplicationPlanForm::find($LearningApplicationPlanFormId);

            if (!$learning_application_plan) {
                throw new \Exception('Learner progrees report id not found');
            }

            $learning_application_plan->update([
                'event_id' => $validated['event_id'],
                'form_name' => $this->formName(),
                'control_no' => $validated['control_no'],
                'office' => $validated['office'] ?? null,
                'learner' => $validated['learner'] ?? null,
                'title_of_intervention' => $validated['title_of_intervention'] ?? null,
                'date_conducted' => $validated['date_conducted'] ?? null,
                'venue' => $validated['venue'] ?? null,

                'foundation' => $validated['foundation'] ?? null,
                'techinal' => $validated['techinal'] ?? null,
                'supervisory' => $validated['supervisory'] ?? null,
                'managerial' => $validated['managerial'] ?? null,
                'significant_learning_insight' => $validated['significant_learning_insight'] ?? null,

            ]);

            $foundation_competency = FoundationCompetencie::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id,],
                [
                    'delivering_service_excellence' => $validated['delivering_service_excellence'] ?? null,
                    'exemplifying_integrity' => $validated['exemplifying_integrity'] ?? null,
                    'interpersonal_skills' => $validated['interpersonal_skills'] ?? null,
                ]

            );


            $technical_competency = TechnicalCompetencie::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id,],
                [
                    'planning_organizing' => $validated['planning_organizing'] ?? null,
                    'monitoring_evaluation' => $validated['monitoring_evaluation'] ?? null,
                    'records_management' => $validated['records_management'] ?? null,
                    'partnering_networking' => $validated['partnering_networking'] ?? null,
                    'process_management' => $validated['process_management'] ?? null,
                    'attention_detail' => $validated['attention_detail'] ?? null,
                ]

            );

            $managerial_competency = ManagerialCompetencie::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],
                [
                    'managing_performance_coaching_results' => $validated['managing_performance_coaching_results'] ?? null,
                    'building_collaborative_inclusive_working_relationships' => $validated['building_collaborative_inclusive_working_relationships'] ?? null,
                    'thinking_strategically_creatively' => $validated['thinking_strategically_creatively'] ?? null,
                    'problem_solving_decision_making' => $validated['problem_solving_decision_making'] ?? null,
                ]

            );

            $supervisory_competency = SupervisoryCompetencie::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],
                [
                    'supervisory_managing_performance_coaching_results' => $validated['supervisory_managing_performance_coaching_results'] ?? null,
                    'supervisory_building_collaborative_inclusive_working_relationships' => $validated['supervisory_building_collaborative_inclusive_working_relationships'] ?? null,
                ]
            );

            $learning_strategies = LearningStrategiesImplemented::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],
                [
                    'immediate_application_skills' => $validated['immediate_application_skills'] ?? null,
                    'knowledge_sharing' => $validated['knowledge_sharing'] ?? null,
                    'peer_coaching_collaboration' => $validated['peer_coaching_collaboration'] ?? null,
                    'develop_office_policies_guidelines' => $validated['develop_office_policies_guidelines'] ?? null,
                    'create_pilot_project' => $validated['create_pilot_project'] ?? null,
                    'include_ipcr' => $validated['include_ipcr'] ?? null,
                ]

            );

            $performance_indicator = PerformanceIndicator::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],
                [
                    'strategic_functions' => $validated['strategic_functions'] ?? null,
                    'core_functions' => $validated['core_functions'] ?? null,
                    'support_functions' => $validated['support_functions'] ?? null,
                ]

            );

            $beneficaties = BeneficiariesStrategieApplied::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],
                [
                    'employees_staff' => $validated['strategic_functions'] ?? null,
                    'office_department' => $validated['core_functions'] ?? null,
                    'city_government_organization' => $validated['support_functions'] ?? null,
                    'clients_stakeholders_general_public' => $validated['support_functions'] ?? null,
                ]

            );


            $resources = ResourcesUtilized::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],
                [
                    'digital_technologies' => $validated['strategic_functions'] ?? null,
                    'physical_printed_resources' => $validated['core_functions'] ?? null,
                    'human_resources_organizational_support' => $validated['support_functions'] ?? null,
                    'financial_logistical_support' => $validated['support_functions'] ?? null,
                    'policy_process_resources' => $validated['support_functions'] ?? null,
                ]

            );

            $target = TargetDateCompletion::updateOrCreate(
                ['learning_application_plan_form_id' => $learning_application_plan->id],

                [
                    'within_2_weeks_after_training' => $validated['within_2_weeks_after_training'] ?? null,
                    'within_1_month_after_training' => $validated['within_1_month_after_training'] ?? null,
                    'within_2_months_after_training' => $validated['within_2_months_after_training'] ?? null,
                    'financial_logistical_support' => $validated['financial_logistical_support'] ?? null,
                    'within_3_months_after_training' => $validated['within_3_months_after_training'] ?? null,
                ]
            );


            $form_submit = EmployeeFormSubmission::where('event_id', $learning_application_plan->event_id)
                ->where('control_no', $learning_application_plan->control_no)
                ->first();

            if ($form_submit) {
                $form_submit->update([
                    'status' => 'Pending',
                ]);
            }

            return [
                $learning_application_plan->fresh(),
                $foundation_competency,
                $technical_competency,
                $managerial_competency,
                $supervisory_competency,
                $learning_strategies,
                $performance_indicator,
                $beneficaties,
                $resources,
                $target,
                $form_submit,
            ];
        });
    }

    public function delete(int $LearningApplicationPlanFormId)
    {
        return DB::transaction(function () use ($LearningApplicationPlanFormId) {

            $learner_application_plan= LearningApplicationPlanForm::find($LearningApplicationPlanFormId);

            if (!$learner_application_plan) {
                throw new \Exception('Learning application plan id not found');
            }

            $employee_submit_form = EmployeeFormSubmission::where('event_id', $learner_application_plan->event_id)
                ->where('form_name', $learner_application_plan->forms_name)
                ->where('control_no', $learner_application_plan->control_no)
                ->first();

            if ($employee_submit_form && $employee_submit_form->status === 'Approved') {
                throw new \Exception('Cannot delete an already approved Learning application plan.');
            }

            if ($employee_submit_form) {
                $employee_submit_form->delete();
            }

            $learner_application_plan->delete();

            return $learner_application_plan;
        });
    }
}
