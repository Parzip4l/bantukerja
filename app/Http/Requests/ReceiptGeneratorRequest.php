<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;

class ReceiptGeneratorRequest extends FormRequest
{
    use NormalizesCurrencyInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount' => $this->normalizeCurrencyValue($this->input('amount')),
        ]);
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'payer_name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0'],
            'receipt_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1000'],
        ];
    }
}
