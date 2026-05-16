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
            'document_number' => ['nullable', 'string', 'max:100'],
            'opening' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string', 'max:3000'],
            'closing' => ['nullable', 'string', 'max:1000'],
            'first_party_name' => ['nullable', 'string', 'max:150'],
            'first_party_role' => ['nullable', 'string', 'max:150'],
            'second_party_name' => ['nullable', 'string', 'max:150'],
            'second_party_role' => ['nullable', 'string', 'max:150'],
        ];
    }
}
