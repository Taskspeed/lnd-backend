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
           

             //schedule Date and time 
            'DateTime' => 'nullable|array',
            'DateTime.*.schedule_date' => 'nullable|date_format:Y-m-d',
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
        ];
    }
}
