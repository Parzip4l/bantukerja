@props(['slotKey', 'label' => 'Iklan'])

@inject('adSlotService', 'App\Services\AdSlotService')

@php
    $adSlot = filled($slotKey) ? $adSlotService->getSlot($slotKey) : null;
@endphp

@if ($adSlot && filled($adSlot->code))
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
        {!! $adSlot->code !!}
    </div>
@elseif ($adSlotService->shouldShowPlaceholder($adSlot))
    <div class="rounded-3xl border border-dashed border-orange-300 bg-orange-50 px-5 py-8 text-center text-sm text-orange-700">
        Placeholder iklan: {{ $label }} ({{ $slotKey }})
    </div>
@endif
