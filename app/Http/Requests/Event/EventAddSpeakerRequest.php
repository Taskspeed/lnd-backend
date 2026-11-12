<?php

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventAddSpeakerRequest extends FormRequest
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
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'gender' => 'required|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'email' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // 'is_active' => 'nullable|boolean',


            'position' => 'nullable|string',
            'office' => 'nullable|string',
            'employment_type' => 'nullable|string',
            'salary_grade' => 'nullable|integer',


            //   'educational_attainment',
            'professional_title' => 'nullable|string',
            'specialization' => 'nullable|string',
            'biography' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            // 'resume_path',

        ];
    }
}
