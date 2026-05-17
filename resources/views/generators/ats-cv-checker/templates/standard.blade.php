<div style="margin:0 auto;max-width:820px;padding:28px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font-family:DejaVu Sans,sans-serif;">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:#64748b;">ATS CV Checker</div>
    <div style="margin-top:8px;font-size:24px;font-weight:700;">Skor CV: {{ $document['score'] }}/100</div>
    <div style="margin-top:10px;font-size:12px;color:#475569;">{{ $document['label'] }} • Posisi target: {{ $document['target_position'] }}</div>
    <div style="margin-top:20px;">
        @foreach ($document['breakdown'] as $item)
            <div style="margin-bottom:12px;border:1px solid #e2e8f0;border-radius:18px;padding:14px;">
                <div style="display:table;width:100%;">
                    <div style="display:table-cell;width:72%;font-size:12px;font-weight:700;color:#0f172a;">{{ $item['label'] }}</div>
                    <div style="display:table-cell;width:28%;text-align:right;font-size:12px;color:#0f172a;">{{ $item['score'] }}/{{ $item['max'] }}</div>
                </div>
                <div style="margin-top:8px;font-size:12px;line-height:1.8;color:#475569;">{{ $item['note'] }}</div>
            </div>
        @endforeach
    </div>
    <div style="display:table;width:100%;margin-top:16px;">
        <div style="display:table-cell;width:50%;vertical-align:top;padding-right:10px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Yang sudah baik</div>
            <ul style="margin:8px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                @foreach ($document['good_points'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        <div style="display:table-cell;width:50%;vertical-align:top;padding-left:10px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#64748b;">Yang perlu diperbaiki</div>
            <ul style="margin:8px 0 0;padding-left:18px;font-size:12px;line-height:1.9;color:#475569;">
                @foreach ($document['improvements'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
