<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceGeneratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:150'],
            'customer_name' => ['required', 'string', 'max:150'],
            'invoice_number' => ['required', 'string', 'max:50'],
            'invoice_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:150'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
