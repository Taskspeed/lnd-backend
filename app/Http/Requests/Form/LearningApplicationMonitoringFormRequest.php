<?php

namespace App\Http\Requests\Form;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LearningApplicationMonitoringFormRequest extends FormRequest
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

            'event_id' => 'required|exists:events,id',
            // 'form_name' => 'required|string',  
            'control_no' => 'required|string',
            'learner' => 'nullable|string',
            'lnd_attended' => 'nullable|string',
            'date_of_attendance' => 'nullable|date_format:Y-m-d',
            'competency_developed_acquired' => 'nullable|string',


            'goals' => 'nullable|string',
            'performance_indicator' => 'nullable|string',
            'learning_strategies_applied' => 'nullable|string',
            'required_resources' => 'nullable|string',
            'target_date_completion' => 'nullable|string',
            'status_as_of_v1' => 'nullable|string',
            'status_as_of_v2' => 'nullable|string',


            // technical
            'planning_organizing'=> 'nullable|boolean',
            'monitoring_evaluation'=> 'nullable|boolean',
            'records_management'=> 'nullable|boolean',
            'partnering_networking'=> 'nullable|boolean',
            'process_management'=> 'nullable|boolean',


            // leadership
            'managing_performance_coaching_results'=> 'nullable|boolean',
            'building_collaborative_inclusive_working_relationships'=> 'nullable|boolean',
            'thinking_strategically_creatively'=> 'nullable|boolean',
            'problem_solving_decision_making'=> 'nullable|boolean',

            // core 
            'delivering_service_excellence'=> 'nullable|boolean',
            'exemplifying_integrity'=> 'nullable|boolean',
            'interpersonal_skills'=> 'nullable|boolean',


        ];
    }
}
