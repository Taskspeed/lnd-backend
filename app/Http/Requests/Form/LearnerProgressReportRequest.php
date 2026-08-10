<?php

namespace App\Http\Requests\Form;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LearnerProgressReportRequest extends FormRequest
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
            //

            'event_id' => 'sometimes|required|exists:events,id',
            'forms_name' => 'nullable|string',
            'control_no' => 'nullable|string',
            'office' => 'nullable|string',
            'learner' => 'nullable|string',
            'lnd_attended' => 'nullable|string',
            'date_of_attendance' => 'nullable|date',
 
            // competency ratings — 1 to 5 scale
            'delivering_service_excellence_competency' => 'nullable|integer|min:1|max:5',
            'exemplifying_integrity_competency' => 'nullable|integer|min:1|max:5',
            'interpersonal_skills_competency' => 'nullable|integer|min:1|max:5',
            'planning_organizing_competency' => 'nullable|integer|min:1|max:5',
            'monitoring_evaluation_competency' => 'nullable|integer|min:1|max:5',
            'records_management_competency' => 'nullable|integer|min:1|max:5',
            'partnering_networking_competency' => 'nullable|integer|min:1|max:5',
            'process_management_competency' => 'nullable|integer|min:1|max:5',
            'managing_performance_coaching_results_competency' => 'nullable|integer|min:1|max:5',
            'building_collaborative_inclusive_working_relationships_competency' => 'nullable|integer|min:1|max:5',
            'thinking_strategically_creatively_competency' => 'nullable|integer|min:1|max:5',
            'problem_solving_decision_making_competency' => 'nullable|integer|min:1|max:5',

            'remarks' => 'nullable|string',


            // core progress
            'learner_progress_report_id' => 'nullable|boolean',
            'delivering_service_excellence'=> 'nullable|boolean',
            'exemplifying_integrity'=> 'nullable|boolean',
            'interpersonal_skills'=> 'nullable|boolean',

            // leadership_progress
            'managing_performance_coaching_results' => 'nullable|boolean',
            'building_collaborative_inclusive_working_relationships' => 'nullable|boolean',
            'thinking_strategically_creatively' => 'nullable|boolean',
            'problem_solving_decision_making' => 'nullable|boolean',

            // technical progress
            'planning_organizing' => 'nullable|boolean',
            'monitoring_evaluation' => 'nullable|boolean',
            'records_management' => 'nullable|boolean',
            'partnering_networking' => 'nullable|boolean',
            'process_management' => 'nullable|boolean'


        ];
    }
}
