<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">LinkedIn profile helper</div>
    <div style="margin-top:8px;font-size:24px;font-weight:700;">Headline & About LinkedIn</div>
    <div style="margin-top:18px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">3 opsi headline</div>
        <ol style="margin:10px 0 0;padding-left:18px;font-size:12px;line-height:1.9;">
            @foreach ($document['headlines'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ol>
    </div>
    <div style="display:table;width:100%;margin-top:18px;">
        <div style="display:table-cell;width:50%;vertical-align:top;padding-right:10px;">
            <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">About singkat</div>
                <div style="margin-top:8px;font-size:12px;line-height:1.9;">{{ $document['about_short'] }}</div>
            </div>
        </div>
        <div style="display:table-cell;width:50%;vertical-align:top;padding-left:10px;">
            <div style="border:1px solid #e2e8f0;border-radius:18px;padding:16px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">About profesional</div>
                <div style="margin-top:8px;font-size:12px;line-height:1.9;">{{ $document['about_professional'] }}</div>
            </div>
        </div>
    </div>
    <div style="margin-top:18px;border-top:1px solid #e2e8f0;padding-top:16px;font-size:12px;line-height:1.9;color:#475569;">
        <strong>Checklist profil:</strong> {{ implode(', ', $document['checklist']) }}
    </div>
</div>
