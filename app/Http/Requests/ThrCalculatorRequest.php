<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThrCalculatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monthly_salary' => ['required', 'numeric', 'min:0'],
            'months_worked' => ['required', 'integer', 'min:0', 'max:600'],
            'employee_status' => ['nullable', 'string', 'max:100'],
        ];
    }
}
