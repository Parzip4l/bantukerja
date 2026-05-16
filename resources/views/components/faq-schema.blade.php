@props(['faqs' => []])

@if (count($faqs))
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq->answer),
                ],
            ])->all(),
        ];
    @endphp
    @push('structured-data')
        <script type="application/ld+json">
            {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endif
