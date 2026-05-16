<?php

namespace App\Services;

use App\Models\AdSlot;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AdSlotService
{
    public function getSlot(string $key): ?AdSlot
    {
        $payload = Cache::remember("ad-slot:{$key}", now()->addMinutes(10), function () use ($key): ?array {
            return AdSlot::query()
                ->where('key', $key)
                ->active()
                ->first()?->toArray();
        });

        if (! is_array($payload)) {
            return null;
        }

        return (new AdSlot)->forceFill($payload)->syncOriginal();
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
