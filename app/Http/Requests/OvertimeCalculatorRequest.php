<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;

class OvertimeCalculatorRequest extends FormRequest
{
    use NormalizesCurrencyInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'monthly_wage' => $this->normalizeCurrencyValue($this->input('monthly_wage')),
        ]);
    }

    public function rules(): array
    {
        return [
            'monthly_wage' => ['required', 'numeric', 'min:0'],
            'hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'day_type' => ['required', 'in:kerja,libur'],
        ];
    }
}
