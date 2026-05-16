<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CvAtsGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'professional_title' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'portfolio' => ['nullable', 'url', 'max:255'],
            'summary' => ['required', 'string', 'min:50', 'max:2000'],
            'skills' => ['required', 'string', 'max:2000'],
            'languages' => ['nullable', 'string', 'max:1000'],
            'certifications' => ['nullable', 'string', 'max:2000'],
            'achievements' => ['nullable', 'string', 'max:2000'],
            'work_experiences' => ['required', 'array', 'min:1'],
            'work_experiences.*.job_title' => ['required', 'string', 'max:150'],
            'work_experiences.*.company' => ['required', 'string', 'max:150'],
            'work_experiences.*.location' => ['nullable', 'string', 'max:150'],
            'work_experiences.*.start_date' => ['required', 'date'],
            'work_experiences.*.end_date' => ['nullable', 'date'],
            'work_experiences.*.is_current' => ['nullable', 'boolean'],
            'work_experiences.*.description' => ['required', 'string', 'max:2000'],
            'educations' => ['required', 'array', 'min:1'],
            'educations.*.degree' => ['required', 'string', 'max:150'],
            'educations.*.institution' => ['required', 'string', 'max:150'],
            'educations.*.location' => ['nullable', 'string', 'max:150'],
            'educations.*.start_year' => ['required', 'integer', 'min:1950', 'max:2100'],
            'educations.*.end_year' => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'educations.*.description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
