<?php

namespace App\Support;

use Illuminate\Support\Str;

class MediaUrl
{
    public static function resolve(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (is_file(public_path('uploads/'.$normalizedPath))) {
            return self::normalizeUrl(url('uploads/'.$normalizedPath));
        }

        if (is_file(public_path('storage/'.$normalizedPath))) {
            return self::normalizeUrl(url('storage/'.$normalizedPath));
        }

        return self::normalizeUrl(url('uploads/'.$normalizedPath));
    }

    protected static function normalizeUrl(string $url): string
    {
        return str_replace(
            ['httphttps://', 'httpshttp://'],
            ['https://', 'http://'],
            $url,
        );
    }
}
