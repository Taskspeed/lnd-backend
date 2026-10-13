<?php

namespace App\Http\Requests\Document;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentStoreRequest extends FormRequest
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

            'control_no' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'event_schedule_id' => 'required|exists:event_schedules,id',
            'document' => 'required|array|min:1',
            'document.*.file_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'document.*.title' => 'required|string|max:100',

        ];
    }
}
