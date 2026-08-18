<?php

namespace App\Http\Requests\Form;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LearningApplicationPlanFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // learning applicant plan
            'event_id' => 'required|exists:events,id',
                'event_schedule_id' => 'required|exists:event_schedules,id',
            'control_no' => 'required|string',
            'office' =>'nullable|string',
            'learner' =>'nullable|string',
            'title_of_intervention' =>'nullable|string',
            'date_conducted' =>'nullable|string',
            'venue' =>'nullable|string',

            'foundation' =>'nullable|boolean',
            'techinal'=>'nullable|boolean',
            'supervisory'=>'nullable|boolean',
            'managerial'=>'nullable|boolean',
            'significant_learning_insight'=>'nullable|string',




            // foundation competency
            'delivering_service_excellence'=>'nullable|boolean',
            'exemplifying_integrity'=>'nullable|boolean',
            'interpersonal_skills'=>'nullable|boolean',


            //technical cpmpentency

            'planning_organizing'=>'nullable|boolean',
            'monitoring_evaluation'=>'nullable|boolean',
            'records_management'=>'nullable|boolean',
            'partnering_networking'=>'nullable|boolean',
            'process_management'=>'nullable|boolean',
            'attention_detail'=>'nullable|boolean',

        

            // managerial

            'managing_performance_coaching_results'=>'nullable|boolean',
            'building_collaborative_inclusive_working_relationships'=>'nullable|boolean',
            'thinking_strategically_creatively'=>'nullable|boolean',
            'problem_solving_decision_making'=>'nullable|boolean',



            // supervisory
            'supervisory_managing_performance_coaching_results'=>'nullable|boolean',
            'supervisory_building_collaborative_inclusive_working_relationships'=>'nullable|boolean',



            // learning strategies
            'immediate_application_skills'=>'nullable|boolean',
            'knowledge_sharing'=>'nullable|boolean',
            'peer_coaching_collaboration'=>'nullable|boolean',
            'develop_office_policies_guidelines'=>'nullable|boolean',
            'create_pilot_project'=>'nullable|boolean',
            'include_ipcr'=>'nullable|boolean',



            // performance indicator
            'strategic_functions'=>'nullable|boolean',
            'core_functions'=>'nullable|boolean',
            'support_functions'=>'nullable|boolean',


            // beneficaries
            'employees_staff'=>'nullable|boolean',
            'office_department'=>'nullable|boolean',
            'city_government_organization'=>'nullable|boolean',
            'clients_stakeholders_general_public'=>'nullable|boolean',

        
            // resources

            'digital_technologies'=>'nullable|boolean',
            'physical_printed_resources'=>'nullable|boolean',
            'human_resources_organizational_support'=>'nullable|boolean',
            'financial_logistical_support'=>'nullable|boolean',
            'policy_process_resources'=>'nullable|boolean',


            // target
            'within_2_weeks_after_training'=>'nullable|boolean',
            'within_1_month_after_training'=>'nullable|boolean',
            'within_2_months_after_training'=>'nullable|boolean',
            'within_3_months_after_training'=>'nullable|boolean',

        ];
    }
}
