<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobDescriptionMatcherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'target_position' => ['required', 'string', 'max:150'],
            'job_description' => ['required', 'string', 'max:8000'],
            'profile_summary' => ['required', 'string', 'max:5000'],
            'owned_skills' => ['required', 'string', 'max:2000'],
            'experience_summary' => ['required', 'string', 'max:3000'],
            'education_level' => ['nullable', 'string', 'max:300'],
        ];
    }
}
