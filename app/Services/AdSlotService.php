<?php

namespace App\Services;

use App\Models\AdSlot;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AdSlotService
{
    private const CACHE_VERSION = 'v3';

    public function getSlot(string $key): ?AdSlot
    {
        $payload = Cache::remember($this->cacheKey($key), now()->addMinutes(10), function () use ($key): ?array {
            $slot = AdSlot::query()
                ->where('key', $key)
                ->active()
                ->first();

            if (! $slot) {
                return null;
            }

            // Use raw attributes so broken timestamp strings in production data
            // do not trigger Carbon parsing during array serialization.
            return $slot->getAttributes();
        });

        if (! is_array($payload)) {
            return null;
        }

        $payload = $this->sanitizePayload($payload);

        $adSlot = new AdSlot;
        $adSlot->setRawAttributes($payload, true);

        return $adSlot;
    }

    protected function cacheKey(string $key): string
    {
        return 'ad-slot:'.self::CACHE_VERSION.':'.$key;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function sanitizePayload(array $payload): array
    {
        foreach (['created_at', 'updated_at'] as $column) {
            if (! array_key_exists($column, $payload)) {
                continue;
            }

            if (! is_string($payload[$column]) || ! preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $payload[$column])) {
                $payload[$column] = null;
            }
        }

        return $payload;
    }

    public function globalScript(): ?string
    {
        return Setting::valueOf('adsense_global_script');
    }

    public function clientId(): ?string
    {
        return Setting::valueOf('adsense_client_id');
    }

    public function shouldShowPlaceholder(?AdSlot $slot): bool
    {
        return app()->environment('local') && (! $slot || blank($slot->code));
    }
}
