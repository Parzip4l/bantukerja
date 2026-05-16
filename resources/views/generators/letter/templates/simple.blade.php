<div style="margin: 0 auto; max-width: 740px; padding: 28px; border: 1px solid #e5e7eb; background: #ffffff; color: #111827; font-family: DejaVu Sans, sans-serif;">
    <div style="font-size: 24px; font-weight: 700;">{{ $document['subject'] }}</div>
    <div style="margin-top: 10px; font-size: 12px; color: #4b5563;">{{ $document['city'] ? $document['city'].', ' : '' }}{{ $document['date_label'] }}</div>
    @if (! empty($document['recipient']))
        <div style="margin-top: 20px; font-size: 12px; color: #111827;">Kepada Yth. {{ $document['recipient'] }}</div>
    @endif
    <div style="margin-top: 24px; font-size: 12px; line-height: 1.95; color: #374151;">{{ $document['body_text'] }}</div>
    @include('generators.shared.partials.signature-block', [
        'name' => $document['name'],
        'position' => $document['position'] ?? null,
        'company' => $document['company'] ?? null,
    ])
</div>
