<?php

namespace App\Http\Requests\Form;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TrainingEvaluationRequest extends FormRequest
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


            'event'                     => ['required', 'string'],
            'date'                      => ['required', 'date'],
            'venue'                     => ['nullable', 'string'],
            'learning_service_provider' => ['nullable', 'string'],


            'speaker' => ['required', 'array'],
            'speaker.*.event_speaker_id' => ['required', 'exists:event_speakers,id'],
            'speaker.*.mastery_of_subject' => ['required', 'integer', 'between:1,5'],
            'speaker.*.ability_to_involve' => ['required', 'integer', 'between:1,5'],
            'speaker.*.session_interest'   => ['required', 'integer', 'between:1,5'],
            'speaker.*.handling_questions' => ['required', 'integer', 'between:1,5'],
            'speaker.*.voice_projection'   => ['required', 'integer', 'between:1,5'],
            'speaker.*.communication'      => ['required', 'integer', 'between:1,5'],
            'speaker.*.practical_relevance' => ['required', 'integer', 'between:1,5'],
            'speaker.*.overall_rating'     => ['required', 'integer', 'between:1,5'],
            'speaker.*.comments'     => ['nullable','string'],

        ];
    }
}
