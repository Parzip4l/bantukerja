<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceGeneratorRequest extends FormRequest
{
    use NormalizesCurrencyInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $items = collect($this->input('items', []))
            ->map(function (array $item): array {
                $item['price'] = $this->normalizeCurrencyValue($item['price'] ?? null);

                return $item;
            })
            ->all();

        $this->merge([
            'items' => $items,
        ]);
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'business_logo' => ['nullable', 'image', 'max:2048'],
            'business_logo_path' => ['nullable', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:150'],
            'business_address' => ['required', 'string', 'max:500'],
            'business_phone' => ['required', 'string', 'max:50'],
            'business_email' => ['nullable', 'email', 'max:150'],
            'business_website' => ['nullable', 'url', 'max:255'],
            'business_npwp' => ['nullable', 'string', 'max:100'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_company' => ['nullable', 'string', 'max:150'],
            'customer_address' => ['required', 'string', 'max:500'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'invoice_number' => ['required', 'string', 'max:50'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'po_number' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:10'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.name' => ['required', 'string', 'max:150'],
            'items.*.description' => ['nullable', 'string', 'max:500'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'bank_account_name' => ['nullable', 'string', 'max:150'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
