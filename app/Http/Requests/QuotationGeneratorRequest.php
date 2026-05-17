<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuotationGeneratorRequest extends FormRequest
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
                $item['discount'] = $this->normalizeCurrencyValue($item['discount'] ?? null);

                return $item;
            })
            ->all();

        $this->merge([
            'additional_fee' => $this->normalizeCurrencyValue($this->input('additional_fee')),
            'items' => $items,
        ]);
    }

    public function rules(): array
    {
        return [
            'template_slug' => ['nullable', 'string', 'max:150'],
            'vendor_logo' => ['nullable', 'image', 'max:2048'],
            'vendor_logo_path' => ['nullable', 'string', 'max:255'],
            'vendor_name' => ['required', 'string', 'max:150'],
            'vendor_address' => ['required', 'string', 'max:500'],
            'vendor_email' => ['nullable', 'email', 'max:150'],
            'vendor_phone' => ['required', 'string', 'max:50'],
            'client_name' => ['required', 'string', 'max:150'],
            'client_address' => ['nullable', 'string', 'max:500'],
            'quotation_number' => ['required', 'string', 'max:50'],
            'quotation_date' => ['required', 'date'],
            'validity_days' => ['required', 'integer', 'min:1', 'max:365'],
            'quotation_title' => ['required', 'string', 'max:200'],
            'project_description' => ['required', 'string', 'max:3000'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.name' => ['required', 'string', 'max:150'],
            'items.*.description' => ['nullable', 'string', 'max:500'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'additional_fee' => ['nullable', 'numeric', 'min:0'],
            'payment_terms' => ['required', Rule::in(['100-awal', 'dp50-pelunasan50', 'dp30-pelunasan70', 'custom'])],
            'payment_terms_custom' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
            'terms_and_conditions' => ['nullable', 'string', 'max:3000'],
            'person_in_charge' => ['required', 'string', 'max:150'],
            'person_in_charge_title' => ['required', 'string', 'max:150'],
        ];
    }
}
