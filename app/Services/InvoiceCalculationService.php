<?php

namespace App\Services;

use App\Support\DocumentFormatter;

class InvoiceCalculationService
{
    public function calculate(array $items, array $options = []): array
    {
        $normalizedItems = collect($items)
            ->filter(fn (array $item): bool => filled($item['name'] ?? null))
            ->take(30)
            ->map(function (array $item): array {
                $qty = (float) ($item['qty'] ?? 0);
                $price = (float) ($item['price'] ?? 0);

                return [
                    'name' => trim((string) ($item['name'] ?? '')),
                    'description' => trim((string) ($item['description'] ?? '')),
                    'unit' => trim((string) ($item['unit'] ?? '')),
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => round($qty * $price, 2),
                ];
            })
            ->values();

        $subtotal = (float) $normalizedItems->sum('subtotal');
        $tax = (float) ($options['tax'] ?? 0);
        $discount = (float) ($options['discount'] ?? 0);
        $taxAmount = $subtotal * ($tax / 100);
        $discountAmount = $subtotal * ($discount / 100);
        $grandTotal = $subtotal + $taxAmount - $discountAmount;

        return [
            'items' => $normalizedItems->all(),
            'subtotal' => round($subtotal, 2),
            'tax_percentage' => $tax,
            'tax_amount' => round($taxAmount, 2),
            'discount_percentage' => $discount,
            'discount_amount' => round($discountAmount, 2),
            'grand_total' => round($grandTotal, 2),
            'formatted_subtotal' => $this->formatRupiah($subtotal),
            'formatted_tax_amount' => $this->formatRupiah($taxAmount),
            'formatted_discount_amount' => $this->formatRupiah($discountAmount),
            'formatted_grand_total' => $this->formatRupiah($grandTotal),
        ];
    }

    public function formatRupiah(float $value): string
    {
        return DocumentFormatter::formatCurrencyIdr($value);
    }
}
