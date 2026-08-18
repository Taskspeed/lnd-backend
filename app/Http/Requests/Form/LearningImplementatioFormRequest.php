<?php

namespace App\Http\Requests\Form;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LearningImplementationFormRequest extends FormRequest
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
                'event_schedule_id' => 'required|exists:event_schedules,id',
            'control_no' => 'required|string',
            'learner' => 'nullable|string',
            'lnd_attended' => 'nullable|string',
            'date_of_attendance' => 'nullable|string',
            'competency_developed_acquired' => 'nullable|string',
            'learning_strategies_applied' => 'nullable|string',
            'resources_used' => 'nullable|string',
            'beneficiaries_strategies_applied' => 'nullable|string',
            'performance_indicators_behavior_toward_work' => 'nullable|string',
            'financial_aid_training_attended' => 'nullable|string',
            'return_financial_aid' => 'nullable|string',

            // core implementation
            'delivering_service_excellence' => 'nullable|boolean',
            'exemplifying_integrity' => 'nullable|boolean',
            'interpersonal_skills' => 'nullable|boolean',

            //learning implementation
            'managing_performance_coaching_results' => 'nullable|boolean',
            'building_collaborative_inclusive_working_relationships' => 'nullable|boolean',
            'thinking_strategically_creatively' => 'nullable|boolean',
            'problem_solving_decision_making' => 'nullable|boolean',

            // technical implemantation
            'planning_organizing' => 'nullable|boolean',
            'monitoring_evaluation' => 'nullable|boolean',
            'records_management' => 'nullable|boolean',
            'partnering_networking' => 'nullable|boolean',
            'process_management' => 'nullable|boolean',



        ];
    }
}
