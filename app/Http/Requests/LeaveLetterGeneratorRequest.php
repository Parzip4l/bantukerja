<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveLetterGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'company' => ['required', 'string', 'max:150'],
            'leave_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
