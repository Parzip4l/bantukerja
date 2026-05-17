<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkedInProfileGeneratorRequest extends FormRequest
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
            'career_level' => ['required', Rule::in(['fresh-graduate', 'junior', 'mid-level', 'senior', 'manager'])],
            'industry' => ['required', 'string', 'max:150'],
            'primary_skill_1' => ['required', 'string', 'max:100'],
            'primary_skill_2' => ['required', 'string', 'max:100'],
            'primary_skill_3' => ['required', 'string', 'max:100'],
            'main_experience' => ['required', 'string', 'max:1500'],
            'career_target' => ['required', 'string', 'max:500'],
            'language_style' => ['required', Rule::in(['profesional', 'friendly', 'singkat', 'fresh-graduate', 'senior'])],
            'language' => ['required', Rule::in(['id', 'en'])],
        ];
    }
}
