<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;

class SalaryCalculatorRequest extends FormRequest
{
    use NormalizesCurrencyInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'base_salary' => $this->normalizeCurrencyValue($this->input('base_salary')),
            'allowance' => $this->normalizeCurrencyValue($this->input('allowance')),
            'deduction' => $this->normalizeCurrencyValue($this->input('deduction')),
            'bpjs' => $this->normalizeCurrencyValue($this->input('bpjs')),
            'tax' => $this->normalizeCurrencyValue($this->input('tax')),
        ]);
    }

    public function rules(): array
    {
        return [
            'base_salary' => ['required', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'deduction' => ['nullable', 'numeric', 'min:0'],
            'bpjs' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
