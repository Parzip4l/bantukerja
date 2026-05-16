<?php

namespace App\Http\Requests\Concerns;

trait NormalizesCurrencyInput
{
    protected function normalizeCurrencyValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = preg_replace('/[^\d]/', '', (string) $value);

        return $normalized === '' ? null : $normalized;
    }
}
