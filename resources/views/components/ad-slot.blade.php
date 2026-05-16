@props(['slotKey', 'label' => 'Iklan'])

@inject('adSlotService', 'App\Services\AdSlotService')

@php
    $slot = $adSlotService->getSlot($slotKey);
@endphp

@if ($slot && filled($slot->code))
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
        {!! $slot->code !!}
    </div>
@elseif ($adSlotService->shouldShowPlaceholder($slot))
    <div class="rounded-3xl border border-dashed border-orange-300 bg-orange-50 px-5 py-8 text-center text-sm text-orange-700">
        Placeholder iklan: {{ $label }} ({{ $slotKey }})
    </div>
@endif
