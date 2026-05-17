<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterviewStarAnswerRequest extends FormRequest
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
            'interview_question' => ['required', 'string', 'max:500'],
            'experience_level' => ['required', Rule::in(['fresh-graduate', 'berpengalaman'])],
            'experience_type' => ['required', Rule::in(['pengalaman-kerja', 'magang', 'organisasi', 'project-kuliah', 'freelance', 'volunteer'])],
            'situation' => ['required', 'string', 'max:1500'],
            'task' => ['required', 'string', 'max:1500'],
            'action' => ['required', 'string', 'max:2000'],
            'result' => ['nullable', 'string', 'max:1500'],
            'highlight_skills' => ['nullable', 'string', 'max:1000'],
            'answer_style' => ['required', Rule::in(['formal', 'natural-profesional', 'singkat', 'detail'])],
            'output_format' => ['required', Rule::in(['struktur-star', 'paragraf-interview', 'keduanya'])],
        ];
    }
}
