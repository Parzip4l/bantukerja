<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinutesGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:200'],
            'event_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:150'],
            'content' => ['required', 'string', 'max:3000'],
        ];
    }
}
