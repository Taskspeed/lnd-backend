<?php

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssessmentAddRequest extends FormRequest
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
        'event_schedule_id' => ['required', 'integer', 'exists:event_schedules,id'],
        'event_name'        => ['nullable', 'string', 'max:255'],
        'title'             => ['nullable', 'string', 'max:255'],
        'passing_score'     => ['nullable', 'integer', 'min:0'],

        'questions'                          => ['required', 'array', 'min:1'],
        'questions.*.question'               => ['required', 'string'],
        'questions.*.question_type'          => ['required', 'string'],
        'questions.*.points'                 => ['required', 'integer', 'min:0'],
        'questions.*.options'                => ['nullable', 'array'],
        'questions.*.options.*.option_text'  => ['required', 'string'],
        'questions.*.options.*.is_correct'   => ['required', 'boolean'],
    ];
    }
}
