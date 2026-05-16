<div style="margin: 0 auto; max-width: 760px; border: 1px solid #dbeafe; border-radius: 20px; overflow: hidden; background: #ffffff; color: #0f172a; font-family: DejaVu Sans, sans-serif;">
    <div style="background: #eff6ff; padding: 22px 28px;">
        <div style="font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: #1d4ed8;">Surat Resmi</div>
        <div style="margin-top: 8px; font-size: 24px; font-weight: 700;">{{ $document['subject'] }}</div>
    </div>
    <div style="padding: 28px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; font-size: 12px; color: #475569;">
            <div>{{ $document['recipient'] ? 'Kepada Yth. '.$document['recipient'] : '' }}</div>
            <div>{{ $document['city'] ? $document['city'].', ' : '' }}{{ $document['date_label'] }}</div>
        </div>

        <div style="margin-top: 26px; font-size: 12px; line-height: 2.05; text-align: justify; color: #334155;">
            {{ $document['body_text'] }}
        </div>

        @include('generators.shared.partials.signature-block', [
            'name' => $document['name'],
            'position' => $document['position'] ?? null,
            'company' => $document['company'] ?? null,
        ])
    </div>
</div>
