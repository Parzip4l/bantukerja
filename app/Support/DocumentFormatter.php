<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DocumentFormatter
{
    public static function sanitizeText(?string $value): string
    {
        $clean = trim(strip_tags((string) $value));

        return preg_replace('/\s+/u', ' ', $clean) ?? '';
    }

    public static function sanitizeMultilineText(?string $value): string
    {
        $lines = preg_split('/\r\n|\r|\n/', strip_tags((string) $value)) ?: [];

        return collect($lines)
            ->map(fn (?string $line): string => trim((string) $line))
            ->filter()
            ->implode("\n");
    }

    public static function lines(?string $value): array
    {
        $lines = preg_split('/\r\n|\r|\n/', strip_tags((string) $value)) ?: [];

        return collect($lines)
            ->map(fn (?string $line): string => trim((string) $line))
            ->filter()
            ->values()
            ->all();
    }

    public static function commaOrLineList(?string $value): array
    {
        $parts = preg_split('/[\r\n,]+/', strip_tags((string) $value)) ?: [];

        return collect($parts)
            ->map(fn (?string $part): string => trim((string) $part))
            ->filter()
            ->values()
            ->all();
    }

    public static function formatCurrencyIdr(float|int $value): string
    {
        return 'Rp '.number_format((float) $value, 0, ',', '.');
    }

    public static function dateLabel(?string $date, string $format = 'd F Y'): ?string
    {
        if (blank($date)) {
            return null;
        }

        return Carbon::parse($date)->translatedFormat($format);
    }

    public static function generateDocumentNumber(string $prefix, ?string $date = null, int $sequence = 1): string
    {
        $dateCode = Carbon::parse($date ?: now())->format('Ymd');

        return Str::upper($prefix).'-'.$dateCode.'-'.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
