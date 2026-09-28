<?php

namespace App\Http\Requests\Form;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EvaluationInstrumentRequest extends FormRequest
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
        $rules = [
            'event'                => ['required', 'string'],
            'date'                 => ['required', 'date'],
            'venue'                => ['required', 'string'],
            'name_of_participant'  => ['required', 'string'],
            'comments_suggestions' => ['nullable', 'string'],

            'feedback'                      => ['required', 'array'],
                'feedback.*.excellent'          => ['nullable', 'boolean'],
                'feedback.*.high_average'       => ['nullable', 'boolean'],
                'feedback.*.good'               => ['nullable', 'boolean'],
                'feedback.*.fair'               => ['nullable', 'boolean'],
                'feedback.*.poor'               => ['nullable', 'boolean'],
                'feedback.*.highly_recommended' => ['nullable', 'boolean'],
                'feedback.*.not_a_priority'     => ['nullable', 'boolean'],
        ];

        // parehong structure ang 3 sections, kaya i-loop
        foreach (['facilitator', 'topic', 'venue_feedback'] as $section) {
            $rules[$section]                        = ['required', 'array'];
            $rules["$section.*.question"]           = ['required', 'string'];
            $rules["$section.*.strongly_agree"]     = ['nullable', 'boolean'];
            $rules["$section.*.agree"]              = ['nullable', 'boolean'];
            $rules["$section.*.disagree"]           = ['nullable', 'boolean'];
            $rules["$section.*.strongly_disagree"]  = ['nullable', 'boolean'];
        }

        return $rules;
    }
}
