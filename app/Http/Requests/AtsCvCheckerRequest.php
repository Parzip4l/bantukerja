<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtsCvCheckerRequest extends FormRequest
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
            'cv_text' => ['required', 'string', 'min:120', 'max:12000'],
            'main_skills' => ['nullable', 'string', 'max:2000'],
            'experience_level' => ['required', Rule::in(['fresh-graduate', '0-1-tahun', '1-3-tahun', '3-5-tahun', '5-plus-tahun'])],
            'target_industry' => ['nullable', 'string', 'max:150'],
        ];
    }
}
