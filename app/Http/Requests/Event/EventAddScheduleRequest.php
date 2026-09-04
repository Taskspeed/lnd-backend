<?php

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventAddScheduleRequest extends FormRequest
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
            'venue_name'=> 'nullable|string',
            'type_name'=> 'nullable|string',
            'mode_name'=> 'nullable|string',
            'source_name'=> 'nullable|string',
            'qualifications'=> 'nullable|string',
            'fee'=> 'nullable|string',
            'hours'=> 'nullable|integer',
            'category_name'=> 'nullable|string',

            // form for event
            'form' => 'nullable|array',
            'form.*.form_name' => 'nullable|string',

             //schedule Date and time 
            'DateTime' => 'nullable|array',
            'DateTime.*.schedule_date' => 'nullable|date_format:Y-m-d',
            // 'DateTime.*.time_in' => 'nullable|date_format:h:i A',
            // 'DateTime.*.time_out' => 'nullable|date_format:h:i A',
            'DateTime.*.morning_in' => 'nullable|date_format:h:i A',
            'DateTime.*.morning_out' => 'nullable|date_format:h:i A',
            'DateTime.*.afternoon_in' => 'nullable|date_format:h:i A',
            'DateTime.*.afternoon_out' => 'nullable|date_format:h:i A',


   

            // office 
            'office' => 'nullable|array',
            'office.*.office_name' => 'nullable|string',

            // speaker 
            'speaker' => 'nullable|array',
            'speaker.*.speaker_name' => 'nullable|string',
            'speaker.*.position' => 'nullable|string',
            'speaker.*.agency' => 'nullable|string',


            // competency 

            // technical
            'planning_organizing'=> 'nullable|boolean',
            'monitoring_evaluation'=> 'nullable|boolean',
            'records_management'=> 'nullable|boolean',
            'partnering_networking'=> 'nullable|boolean',
            'process_management'=> 'nullable|boolean',
            'attention_details'=> 'nullable|boolean',


            // leadership
            'managing_performance_coaching_results'=> 'nullable|boolean',
            'building_collaborative_inclusive_working_relationships'=> 'nullable|boolean',
            'thinking_strategically_creatively'=> 'nullable|boolean',
            'problem_solving_decision_making'=> 'nullable|boolean',

            // core 
            'delivering_service_excellence'=> 'nullable|boolean',
            'exemplifying_integrity'=> 'nullable|boolean',
            'interpersonal_skills'=> 'nullable|boolean',

            'employee' => 'nullable|array',
            'employee.*.control_no' => 'nullable|string',
            'employee.*.position' => 'nullable|string',
            'employee.*.office' => 'nullable|string',
            'employee.*.name' => 'nullable|string',
            'employee.*.status' => 'nullable|string',
        ];
    }
}
