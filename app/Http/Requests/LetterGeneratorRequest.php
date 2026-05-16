<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LetterGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'date' => $this->input('date', $this->input('leave_date')),
        ]);
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:150'],
            'position' => ['nullable', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'recipient' => ['nullable', 'string', 'max:150'],
        ];
    }
}
