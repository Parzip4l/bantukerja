<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterviewSimulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'position_applied' => ['required', 'string', 'max:150'],
            'position_category' => ['required', Rule::in(['admin', 'customer-service', 'sales', 'finance', 'hr', 'it-support', 'digital-marketing', 'backend-developer', 'frontend-developer', 'project-manager', 'operations', 'fresh-graduate', 'lainnya'])],
            'experience_level' => ['required', Rule::in(['fresh-graduate', '0-1-tahun', '1-3-tahun', '3-5-tahun', '5-plus-tahun'])],
            'interview_type' => ['required', Rule::in(['hr-interview', 'user-interview', 'technical-interview', 'final-interview', 'campuran'])],
            'question_count' => ['required', Rule::in(['5', '10', '15', '20'])],
            'include_tips' => ['required', Rule::in(['ya', 'tidak'])],
        ];
    }
}
