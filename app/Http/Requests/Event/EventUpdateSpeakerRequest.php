<?php

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventUpdateSpeakerRequest extends EventAddSpeakerRequest
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
        $rules = collect(parent::rules())
            ->map(fn($rule) => is_string($rule) && str_starts_with($rule, 'required') ? 'sometimes|' . $rule : $rule)
            ->all();

        $rules['remove_photo'] = 'nullable|boolean';

        return $rules;
    }
}
