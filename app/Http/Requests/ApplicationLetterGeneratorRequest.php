<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationLetterGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'position_applied' => ['required', 'string', 'max:150'],
            'company_name' => ['required', 'string', 'max:150'],
            'recruiter_name' => ['nullable', 'string', 'max:150'],
            'job_source' => ['nullable', 'string', 'max:150'],
            'education_level' => ['required', 'string', 'max:100'],
            'major' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['required', Rule::in(['fresh-graduate', '1-2-tahun', '3-5-tahun', '5-plus-tahun'])],
            'experience_summary' => ['required', 'string', 'min:40', 'max:2000'],
            'main_skills' => ['required', 'string', 'max:1000'],
            'letter_type' => ['required', Rule::in(['formal', 'singkat-profesional', 'fresh-graduate', 'berpengalaman', 'magang', 'email-lamaran'])],
            'language_style' => ['required', Rule::in(['indonesia-formal', 'indonesia-santai-profesional'])],
            'date' => ['required', 'date'],
        ];
    }
}
