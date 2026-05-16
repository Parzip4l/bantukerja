@props(['items' => []])

@php
    $schemaItems = collect($items)->values()->map(function ($item, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['label'],
            'item' => $item['url'],
        ];
    })->all();
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $schemaItems,
    ];
@endphp

<nav aria-label="Breadcrumb" class="mb-8">
    <ol class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if (! $loop->first)
                    <span>/</span>
                @endif
                @if ($loop->last)
                    <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="hover:text-blue-700">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

@push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
