<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobDescriptionGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'output_type' => ['required', Rule::in(['internal-hr', 'job-posting'])],
            'position_name' => ['required', 'string', 'max:150'],
            'department' => ['required', 'string', 'max:150'],
            'job_level' => ['required', Rule::in(['intern', 'staff', 'senior-staff', 'supervisor', 'manager', 'head', 'director'])],
            'employment_type' => ['required', Rule::in(['full-time', 'part-time', 'internship', 'contract', 'freelance', 'remote', 'hybrid', 'on-site'])],
            'work_location' => ['required', 'string', 'max:150'],
            'position_summary' => ['required', 'string', 'max:2000'],
            'position_objective' => ['required', 'string', 'max:2000'],
            'reports_to' => ['required', 'string', 'max:150'],
            'direct_reports' => ['nullable', 'string', 'max:150'],
            'responsibilities' => ['required', 'array', 'min:1', 'max:20'],
            'responsibilities.*.text' => ['required', 'string', 'max:500'],
            'education_qualification' => ['required', 'string', 'max:500'],
            'minimum_experience' => ['required', 'string', 'max:300'],
            'technical_skills' => ['required', 'string', 'max:1500'],
            'soft_skills' => ['required', 'string', 'max:1500'],
            'tools_software' => ['nullable', 'string', 'max:1500'],
            'kpis' => ['required', 'array', 'min:1', 'max:15'],
            'kpis.*.text' => ['required', 'string', 'max:300'],
            'benefits' => ['nullable', 'string', 'max:1500'],
            'salary_range' => ['nullable', 'string', 'max:150'],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
            'output_style' => ['required', Rule::in(['formal-hr', 'friendly-startup', 'singkat', 'detail-profesional'])],
            'language' => ['required', Rule::in(['id', 'en'])],
        ];
    }
}
