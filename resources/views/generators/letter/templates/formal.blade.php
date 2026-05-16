<div style="margin: 0 auto; max-width: 760px; min-height: 980px; padding: 34px; border: 1px solid #e2e8f0; background: #ffffff; color: #0f172a; font-family: DejaVu Sans, sans-serif;">
    <div style="text-align: center; font-size: 20px; font-weight: 700; letter-spacing: 0.04em;">{{ $document['subject'] }}</div>

    <div style="margin-top: 34px; text-align: right; font-size: 12px; color: #475569;">
        {{ $document['city'] ? $document['city'].', ' : '' }}{{ $document['date_label'] }}
    </div>

    @if (! empty($document['recipient']))
        <div style="margin-top: 22px; font-size: 12px; line-height: 1.8;">
            Kepada Yth.<br>
            {{ $document['recipient'] }}
        </div>
    @endif

    <div style="margin-top: 22px; font-size: 12px; line-height: 2;">
        Dengan hormat,
    </div>

    <div style="margin-top: 14px; font-size: 12px; line-height: 2.05; text-align: justify;">
        {{ $document['body_text'] }}
    </div>

    @include('generators.shared.partials.signature-block', [
        'name' => $document['name'],
        'position' => $document['position'] ?? null,
        'company' => $document['company'] ?? null,
    ])
</div>
