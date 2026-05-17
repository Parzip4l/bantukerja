<?php

namespace App\Services;

use App\Support\DocumentFormatter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PdfTextExtractorService
{
    public function extractFromUpload(UploadedFile $file): string
    {
        $path = $file->getRealPath();

        if (! $path || ! is_file($path)) {
            return '';
        }

        $binary = file_get_contents($path);

        if (! is_string($binary) || $binary === '') {
            return '';
        }

        return $this->extractFromBinary($binary);
    }

    public function extractFromBinary(string $binary): string
    {
        $segments = [];

        preg_match_all('/<<(.*?)>>\s*stream[\r\n]+(.*?)[\r\n]+endstream/si', $binary, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $dictionary = $match[1] ?? '';
            $stream = $match[2] ?? '';

            $segments[] = $this->decodeStream($stream, $dictionary);
        }

        $segments[] = $binary;

        $text = collect($segments)
            ->filter(fn (?string $segment): bool => is_string($segment) && $segment !== '')
            ->flatMap(fn (string $segment): array => $this->extractTextBlocks($segment))
            ->map(fn (string $line): string => DocumentFormatter::sanitizeMultilineText($line))
            ->filter(fn (string $line): bool => $line !== '')
            ->unique()
            ->implode("\n");

        return trim(preg_replace("/\n{3,}/", "\n\n", $text) ?? $text);
    }

    protected function decodeStream(string $stream, string $dictionary): string
    {
        $raw = trim($stream, "\r\n");

        if (! str_contains($dictionary, 'FlateDecode')) {
            return $raw;
        }

        $candidates = [
            @gzuncompress($raw),
            @gzinflate($raw),
            @gzdecode($raw),
            @gzinflate(substr($raw, 2)),
        ];

        foreach ($candidates as $decoded) {
            if (is_string($decoded) && $decoded !== '') {
                return $decoded;
            }
        }

        return $raw;
    }

    protected function extractTextBlocks(string $content): array
    {
        preg_match_all('/BT(.*?)ET/s', $content, $matches);
        $blocks = $matches[1] ?? [];

        if ($blocks === []) {
            return $this->extractFallbackStrings($content);
        }

        return collect($blocks)
            ->flatMap(fn (string $block): array => $this->extractCommandsFromBlock($block))
            ->filter()
            ->values()
            ->all();
    }

    protected function extractCommandsFromBlock(string $block): array
    {
        $fragments = [];

        preg_match_all('/\((.*?)\)\s*Tj/si', $block, $textMatches);
        foreach (($textMatches[1] ?? []) as $text) {
            $fragments[] = $this->decodeLiteralString($text);
        }

        preg_match_all('/<([0-9A-Fa-f\s]+)>\s*Tj/si', $block, $hexMatches);
        foreach (($hexMatches[1] ?? []) as $hex) {
            $fragments[] = $this->decodeHexString($hex);
        }

        preg_match_all('/\[(.*?)\]\s*TJ/si', $block, $arrayMatches);
        foreach (($arrayMatches[1] ?? []) as $arrayText) {
            preg_match_all('/\((.*?)\)|<([0-9A-Fa-f\s]+)>/si', $arrayText, $items, PREG_SET_ORDER);

            foreach ($items as $item) {
                if (! empty($item[1])) {
                    $fragments[] = $this->decodeLiteralString($item[1]);
                } elseif (! empty($item[2])) {
                    $fragments[] = $this->decodeHexString($item[2]);
                }
            }
        }

        return collect($fragments)
            ->map(function (string $text): string {
                $normalized = trim(preg_replace('/\s+/', ' ', $text) ?? $text);

                return Str::ascii($normalized);
            })
            ->filter(fn (string $text): bool => mb_strlen($text) >= 2)
            ->values()
            ->all();
    }

    protected function extractFallbackStrings(string $content): array
    {
        preg_match_all('/\(([^()]*)\)/s', $content, $matches);

        return collect($matches[1] ?? [])
            ->map(fn (string $text): string => Str::ascii(trim(preg_replace('/\s+/', ' ', $this->decodeLiteralString($text)) ?? '')))
            ->filter(fn (string $text): bool => mb_strlen($text) >= 4)
            ->take(120)
            ->values()
            ->all();
    }

    protected function decodeLiteralString(string $value): string
    {
        $text = preg_replace_callback('/\\\\([0-7]{1,3})/', function (array $matches): string {
            return chr(octdec($matches[1]));
        }, $value) ?? $value;

        return strtr($text, [
            '\\n' => "\n",
            '\\r' => "\r",
            '\\t' => "\t",
            '\\b' => "\x08",
            '\\f' => "\f",
            '\\(' => '(',
            '\\)' => ')',
            '\\\\' => '\\',
        ]);
    }

    protected function decodeHexString(string $hex): string
    {
        $normalized = preg_replace('/\s+/', '', $hex) ?? $hex;

        if ($normalized === '') {
            return '';
        }

        if (strlen($normalized) % 2 !== 0) {
            $normalized .= '0';
        }

        $decoded = @hex2bin($normalized);

        return is_string($decoded) ? $decoded : '';
    }
}
