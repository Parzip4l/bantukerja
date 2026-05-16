<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;

class AuditFeeRequest extends FormRequest
{
    use NormalizesCurrencyInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'total_assets' => $this->normalizeCurrencyValue($this->input('total_assets')),
            'company_revenue' => $this->normalizeCurrencyValue($this->input('company_revenue')),
        ]);
    }

    public function rules(): array
    {
        return [
            'total_assets' => ['required', 'numeric', 'min:0'],
            'company_revenue' => ['required', 'numeric', 'min:0'],
        ];
    }
}
