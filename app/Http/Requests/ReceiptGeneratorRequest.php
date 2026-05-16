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
            'receipt_number' => ['nullable', 'string', 'max:100'],
            'payer_name' => ['required', 'string', 'max:150'],
            'receiver_name' => ['nullable', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0'],
            'receipt_date' => ['required', 'date'],
            'city' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['nullable', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
